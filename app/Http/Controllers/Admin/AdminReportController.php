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
        // Month filter (YYYY-MM)
        $month = $request->get('report_month')
            ? Carbon::createFromFormat('Y-m', $request->report_month)
            : Carbon::now();

        $start = $month->copy()->startOfMonth();
        $end   = $month->copy()->endOfMonth();

        // Get all redemptions of selected month
        $redemptions = VoucherRedemption::with('shop')
            ->whereBetween('redeemed_at', [$start, $end])
            ->get();

        // Monthly totals
        $monthlySummary = [
            'total_count'      => $redemptions->count(),
            'original_total'   => $redemptions->sum('original_amount'),
            'discount_total'   => $redemptions->sum('discount_amount'),
            'final_total'      => $redemptions->sum('final_amount'),
        ];

        // Group by shop
        $shops = $redemptions
            ->groupBy('shop_id')
            ->map(function ($items) {
                return [
                    'shop'            => $items->first()->shop,
                    'total_count'     => $items->count(),
                    'original_total'  => $items->sum('original_amount'),
                    'discount_total'  => $items->sum('discount_amount'),
                    'final_total'     => $items->sum('final_amount'),
                ];
            });

        return view('admin.reports.voucher-redemptions', [
            'month'           => $month,
            'monthlySummary'  => $monthlySummary,
            'shops'           => $shops,
        ]);
    }

    public function shopVoucherRedemptions(Request $request, Shop $shop)
    {
        $month = $request->get('report_month')
            ? Carbon::createFromFormat('Y-m', $request->report_month)
            : Carbon::now();

        $start = $month->copy()->startOfMonth();
        $end   = $month->copy()->endOfMonth();

        // Get shop voucher redemptions
        $redemptions = VoucherRedemption::with('voucher')
            ->where('shop_id', $shop->id)
            ->whereBetween('redeemed_at', [$start, $end])
            ->orderByDesc('redeemed_at')
            ->get();

        // Summary
        $summary = [
            'total_count'     => $redemptions->count(),
            'original_total'  => $redemptions->sum('original_amount'),
            'discount_total'  => $redemptions->sum('discount_amount'),
            'final_total'     => $redemptions->sum('final_amount'),
        ];

        return view('admin.reports.shop-voucher-redemptions', [
            'shop'        => $shop,
            'month'       => $month,
            'summary'     => $summary,
            'redemptions' => $redemptions,
        ]);
    }
}
