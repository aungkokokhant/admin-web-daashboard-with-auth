<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\GiftVoucher;
use App\Models\Promotion;
use App\Enums\VoucherStatus;
use Illuminate\Http\Request;

class ResellVoucherController extends Controller
{
    /**
     * Display resellable vouchers
     */
    public function index(Request $request)
    {
        $query = GiftVoucher::where('re_sellable', true);

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
            ->withQueryString();

        $promotions = Promotion::orderBy('title')->get();

        return view('reseller.resell-vouchers.index', compact('vouchers', 'promotions'));
    }

    /**
     * Validate voucher (activate it for usage)
     */
    public function validate(GiftVoucher $voucher)
    {
        if ($voucher->status !== VoucherStatus::DEACTIVATE) {
            return redirect()
                ->route('reseller.resell-vouchers.index')
                ->with('error', 'Only deactivated vouchers can be activated.');
        }

        $voucher->update([
            'status' => VoucherStatus::UNUSED,
            'activated_at' => now(),
            'activated_by_reseller_id' => auth('reseller')->id(),
            'expires_at' => now()->addDays($voucher->available_days),
        ]);

        return redirect()
            ->route('reseller.resell-vouchers.index')
            ->with('success', 'Voucher has been Activated successfully.');
    }
}
