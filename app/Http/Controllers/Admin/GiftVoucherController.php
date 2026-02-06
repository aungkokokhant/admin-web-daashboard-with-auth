<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftVoucher;
use App\Models\Promotion;
use App\Enums\VoucherType;
use App\Enums\VoucherStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Support\VoucherCrypto;
use App\Services\VoucherQrService;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Throwable;

class GiftVoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = GiftVoucher::query();

        // 🔍 Search by voucher code
        if ($request->filled('code')) {
            $query->where('voucher_code', 'like', '%' . $request->code . '%');
        }

        // 🎟 Filter by voucher type
        if ($request->filled('type')) {
            $query->where('voucher_type', $request->type);
        }

        // 🚦 Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 📣 Promotion filter
        if ($request->filled('promotion')) {
            if ($request->promotion === 'standalone') {
                $query->whereNull('promotion_id');
            } else {
                $query->where('promotion_id', $request->promotion);
            }
        }

        $vouchers = $query
            ->latest()
            ->paginate(15)
            ->withQueryString(); // keep filters during pagination

        // Needed for filter dropdown
        $promotions = Promotion::orderBy('title')->get();

        return view('admin.vouchers.index', compact('vouchers', 'promotions'));
    }

    public function create()
    {
        $today = Carbon::today();

        $promotions = Promotion::whereDate('end_date', '>=', $today)
            ->orderBy('start_date')
            ->get();

        return view('admin.vouchers.create', compact('promotions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'voucher_type' => ['required', 'in:' . implode(',', VoucherType::values())],
            'voucher_value' => ['required', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'promotion_id' => ['nullable', 'exists:promotions,id'],
            'expires_at' => ['required', 'date', 'after:today'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $qrFiles = [];

        for ($i = 0; $i < $data['quantity']; $i++) {

            $maxDiscount = $data['voucher_type'] === 'fixed'
                ? $data['voucher_value']
                : $data['max_discount_amount'];

            $voucherCode = $this->generateVoucherCode();


            $voucher = GiftVoucher::create([
                'voucher_code' => $voucherCode,
                'voucher_type' => $data['voucher_type'],
                'voucher_value' => $data['voucher_value'],
                'max_discount_amount' => $maxDiscount,
                'promotion_id' => $data['promotion_id'],
                'expires_at' => $data['expires_at'],
                'status' => VoucherStatus::UNUSED,
                'created_by_admin_id' => auth('admin')->id(),
                'qr_payload' => $voucherCode, // QR payload generation can be handled separately

            ]);

            // 🔐 Encrypt payload
            $payload = VoucherCrypto::encrypt(
                $voucher->id,
                $voucher->voucher_code
            );

            // 📦 Generate QR
            $qrFiles[] = VoucherQrService::generate(
                $payload,
                $voucher->voucher_code
            );
        }

        // 🔽 SINGLE DOWNLOAD
        if (count($qrFiles) === 1) {
            return response()->download($qrFiles[0])->deleteFileAfterSend(true);
        }

        // 📦 ZIP DOWNLOAD (BULK)
        $zipName = 'gift_vouchers_' . now()->format('Ymd_His') . '.zip';
        $zipPath = storage_path('app/public/' . $zipName);

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($qrFiles as $file) {
            $zip->addFile($file, basename($file));
        }

        $zip->close();

        // Clean up PNGs
        foreach ($qrFiles as $file) {
            @unlink($file);
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function edit(GiftVoucher $voucher)
    {
        if ($voucher->status !== VoucherStatus::UNUSED) {
            return redirect()
                ->route('admin.vouchers.index')
                ->with('error', 'Only unused vouchers can be edited.');
        }

        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, GiftVoucher $voucher)
    {
        if ($voucher->status !== VoucherStatus::UNUSED) {
            return redirect()
                ->route('admin.vouchers.index')
                ->with('error', 'This voucher can no longer be modified.');
        }

        $data = $request->validate([
            'voucher_type' => ['required', 'in:' . implode(',', VoucherType::values())],
            'voucher_value' => ['required', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'expires_at' => ['required', 'date', 'after:today'],
        ]);

        // Enforce business rule again
        $data['max_discount_amount'] =
            $data['voucher_type'] === 'fixed'
            ? $data['voucher_value']
            : $data['max_discount_amount'];

        $voucher->update($data);

        return redirect()
            ->route('admin.vouchers.index')
            ->with('success', 'Voucher updated successfully.');
    }

    public function revoke(GiftVoucher $voucher)
    {
        if ($voucher->status !== VoucherStatus::UNUSED) {
            return redirect()
                ->route('admin.vouchers.index')
                ->with('error', 'Only unused vouchers can be revoked.');
        }

        $voucher->update([
            'status' => VoucherStatus::REVOKED,
        ]);

        return redirect()
            ->route('admin.vouchers.index')
            ->with('success', 'Voucher has been revoked.');
    }


    public function downloadQr(GiftVoucher $voucher)
    {
        try {

            // 1️⃣ Basic sanity check (should never fail, but safe)
            if (!$voucher->id || !$voucher->voucher_code) {
                abort(404, 'Invalid voucher');
            }

            // 2️⃣ Encrypt payload (always regenerate safely)
            $payload = VoucherCrypto::encrypt(
                $voucher->id,
                $voucher->voucher_code
            );

            // 3️⃣ Generate QR file
            $qrPath = VoucherQrService::generate(
                $payload,
                $voucher->voucher_code
            );

            // 4️⃣ Ensure file really exists
            if (!file_exists($qrPath)) {
                Log::error('QR generation failed', [
                    'voucher_id' => $voucher->id,
                    'path' => $qrPath,
                ]);

                abort(500, 'QR file could not be generated');
            }

            // 5️⃣ Download & auto-delete
            return response()
                ->download($qrPath)
                ->deleteFileAfterSend(true);
        } catch (Throwable $e) {

            // 6️⃣ Log full error for developers
            Log::error('Voucher QR download failed', [
                'voucher_id' => $voucher->id ?? null,
                'error' => $e->getMessage(),
            ]);

            // 7️⃣ User-friendly error
            return redirect()
                ->route('admin.vouchers.index')
                ->with('error', 'Failed to generate QR code. Please try again.');
        }
    }

    private function generateVoucherCode(): string
    {
        do {
            $code = 'AKM-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (GiftVoucher::where('voucher_code', $code)->exists());

        return $code;
    }
}
