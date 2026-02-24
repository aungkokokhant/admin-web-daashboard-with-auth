<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoucherRedemption;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{

    public function voucherRedemptions(Request $request)
    {
        // Validate request
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // If no dates provided → default to current month
        if ($request->start_date && $request->end_date) {

            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();
        } else {

            $start = Carbon::now()->startOfMonth();
            $end   = Carbon::now()->endOfMonth();
        }


        // Get redemptions between date range
        $redemptions = VoucherRedemption::with(['shop', 'voucher'])
            ->whereBetween('redeemed_at', [$start, $end])
            ->get();

        $totalDiscount = $redemptions->sum('discount_amount');

        $totalGiftDiscount = $redemptions
            ->filter(fn($r) => $r->voucher && !$r->voucher->re_sellable)
            ->sum('discount_amount');

        $totalResellDiscount = $redemptions
            ->filter(fn($r) => $r->voucher && $r->voucher->re_sellable)
            ->sum('discount_amount');

        $totalPayoutAmount = $redemptions
            ->filter(fn($r) => $r->payout_status)
            ->sum('discount_amount');

        $remainingPayoutAmount = $totalDiscount - $totalPayoutAmount;

        $monthlySummary = [
            'total_count'            => $redemptions->count(),
            'original_total'         => $redemptions->sum('original_amount'),
            'discount_total'         => $totalDiscount,
            'final_total'            => $redemptions->sum('final_amount'),

            'gift_discount_total'    => $totalGiftDiscount,
            'resell_discount_total'  => $totalResellDiscount,
            'payout_total'           => $totalPayoutAmount,
            'remaining_payout_total' => $remainingPayoutAmount,
        ];

        $shops = $redemptions
            ->groupBy('shop_id')
            ->map(function ($items) {

                $totalDiscount = $items->sum('discount_amount');

                $giftDiscount = $items
                    ->filter(fn($r) => $r->voucher && !$r->voucher->re_sellable)
                    ->sum('discount_amount');

                $resellDiscount = $items
                    ->filter(fn($r) => $r->voucher && $r->voucher->re_sellable)
                    ->sum('discount_amount');

                $payoutTotal = $items
                    ->filter(fn($r) => $r->payout_status)
                    ->sum('discount_amount');

                return [
                    'shop'                   => $items->first()->shop,
                    'total_count'            => $items->count(),
                    'original_total'         => $items->sum('original_amount'),
                    'discount_total'         => $totalDiscount,
                    'final_total'            => $items->sum('final_amount'),

                    'gift_discount_total'    => $giftDiscount,
                    'resell_discount_total'  => $resellDiscount,
                    'payout_total'           => $payoutTotal,
                    'remaining_payout_total' => $totalDiscount - $payoutTotal,
                ];
            });

        return view('admin.reports.voucher-redemptions', [
            'start'          => $start,
            'end'            => $end,
            'monthlySummary' => $monthlySummary,
            'shops'          => $shops,
        ]);
    }

    public function shopVoucherRedemptions(Request $request, Shop $shop)
    {
        // Validate
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // Date handling
        if ($request->start_date && $request->end_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end   = Carbon::now()->endOfMonth();
        }

        // Get redemptions
        $redemptions = VoucherRedemption::with('voucher')
            ->where('shop_id', $shop->id)
            ->whereBetween('redeemed_at', [$start, $end])
            ->orderByDesc('redeemed_at')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Financial Calculations
    |--------------------------------------------------------------------------
    */

        $totalDiscount = $redemptions->sum('discount_amount');

        $giftDiscount = $redemptions
            ->filter(fn($r) => $r->voucher && !$r->voucher->re_sellable)
            ->sum('discount_amount');

        $resellDiscount = $redemptions
            ->filter(fn($r) => $r->voucher && $r->voucher->re_sellable)
            ->sum('discount_amount');

        $payoutTotal = $redemptions
            ->filter(fn($r) => $r->payout_status)
            ->sum('discount_amount');

        $summary = [
            'total_count'            => $redemptions->count(),
            'original_total'         => $redemptions->sum('original_amount'),
            'discount_total'         => $totalDiscount,
            'final_total'            => $redemptions->sum('final_amount'),

            'gift_discount_total'    => $giftDiscount,
            'resell_discount_total'  => $resellDiscount,
            'payout_total'           => $payoutTotal,
            'remaining_payout_total' => $totalDiscount - $payoutTotal,
        ];

        return view('admin.reports.shop-voucher-redemptions', [
            'shop'        => $shop,
            'start'       => $start,
            'end'         => $end,
            'summary'     => $summary,
            'redemptions' => $redemptions,
        ]);
    }
}
