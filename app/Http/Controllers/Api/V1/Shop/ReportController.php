<?php

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Controller;
use App\Models\VoucherRedemption;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    // public function voucherRedemptionReport(Request $request)
    // {
    //     try {
    //         $shop = $request->user('shop');

    //         if (!$shop) {
    //             return ApiResponse::error('Unauthenticated', 401);
    //         }

    //         // Month filter (YYYY-MM) or default to current month
    //         $month = $request->query('month');
    //         $date  = $month
    //             ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
    //             : now()->startOfMonth();

    //         $startDate = $date->copy()->startOfMonth();
    //         $endDate   = $date->copy()->endOfMonth();

    //         // ================= Summary =================
    //         $summary = VoucherRedemption::where('shop_id', $shop->id)
    //             ->whereBetween('redeemed_at', [$startDate, $endDate])
    //             ->selectRaw('
    //                 COALESCE(SUM(original_amount), 0) as total_original_amount,
    //                 COALESCE(SUM(discount_amount), 0) as total_discount_amount,
    //                 COALESCE(SUM(final_amount), 0) as total_final_amount
    //             ')
    //             ->first();

    //         // ================= List (History) =================
    //         $redemptions = VoucherRedemption::with(['voucher'])
    //             ->where('shop_id', $shop->id)
    //             ->whereBetween('redeemed_at', [$startDate, $endDate])
    //             ->orderByDesc('redeemed_at')
    //             ->get();

    //         return ApiResponse::success(
    //             'Voucher redemption report retrieved successfully',
    //             [
    //                 'filter' => [
    //                     'month'      => $startDate->format('Y-m'),
    //                     'start_date' => $startDate->toDateString(),
    //                     'end_date'   => $endDate->toDateString(),
    //                 ],
    //                 'summary' => [
    //                     'total_original_amount' => (float) $summary->total_original_amount,
    //                     'total_discount_amount' => (float) $summary->total_discount_amount,
    //                     'total_final_amount'    => (float) $summary->total_final_amount,
    //                 ],
    //                 'redemptions' => $redemptions,
    //             ]
    //         );
    //     } catch (\Throwable $e) {
    //         return ApiResponse::error(
    //             'Failed to retrieve voucher redemption report',
    //             500,
    //             [
    //                 'error' => $e->getMessage(),
    //             ]
    //         );
    //     }
    // }

    public function voucherRedemptionReport(Request $request)
    {
        try {
            $shop = $request->user('shop');

            if (!$shop) {
                return ApiResponse::error('Unauthenticated', 401);
            }

            // Validate
            $request->validate([
                'start_date' => ['nullable', 'date'],
                'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
            ]);

            // Date Handling
            if ($request->start_date && $request->end_date) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate   = Carbon::parse($request->end_date)->endOfDay();
            } else {
                $startDate = now()->startOfMonth();
                $endDate   = now()->endOfMonth();
            }

            /*
        |--------------------------------------------------------------------------
        | SUMMARY (Optimized SQL)
        |--------------------------------------------------------------------------
        */

            $summary = VoucherRedemption::leftJoin('gift_vouchers', 'voucher_redemptions.voucher_id', '=', 'gift_vouchers.id')
                ->where('voucher_redemptions.shop_id', $shop->id)
                ->whereBetween('voucher_redemptions.redeemed_at', [$startDate, $endDate])
                ->selectRaw('
                COUNT(voucher_redemptions.id) as total_count,
                COALESCE(SUM(original_amount), 0) as total_original_amount,
                COALESCE(SUM(discount_amount), 0) as total_discount_amount,
                COALESCE(SUM(final_amount), 0) as total_final_amount,

                COALESCE(SUM(
                    CASE WHEN gift_vouchers.re_sellable = 0
                    THEN discount_amount ELSE 0 END
                ), 0) as gift_discount_total,

                COALESCE(SUM(
                    CASE WHEN gift_vouchers.re_sellable = 1
                    THEN discount_amount ELSE 0 END
                ), 0) as resell_discount_total,

                COALESCE(SUM(
                    CASE WHEN voucher_redemptions.payout_status = 1
                    THEN discount_amount ELSE 0 END
                ), 0) as payout_total
            ')
                ->first();

            $remainingPayout = $summary->total_discount_amount - $summary->payout_total;

            /*
        |--------------------------------------------------------------------------
        | LIST (History)
        |--------------------------------------------------------------------------
        */

            $redemptions = VoucherRedemption::with(['voucher'])
                ->where('shop_id', $shop->id)
                ->whereBetween('redeemed_at', [$startDate, $endDate])
                ->orderByDesc('redeemed_at')
                ->get();

            return ApiResponse::success(
                'Voucher redemption report retrieved successfully',
                [
                    'filter' => [
                        'start_date' => $startDate->toDateString(),
                        'end_date'   => $endDate->toDateString(),
                    ],
                    'summary' => [
                        'total_count'            => (int) $summary->total_count,
                        'total_original_amount'  => (float) $summary->total_original_amount,
                        'total_discount_amount'  => (float) $summary->total_discount_amount,
                        'total_final_amount'     => (float) $summary->total_final_amount,

                        'gift_discount_total'    => (float) 0,
                        'resell_discount_total'  => (float) 0,
                        'payout_total'           => (float) $summary->payout_total,
                        'remaining_payout_total' => (float) $remainingPayout,
                    ],
                    'redemptions' => $redemptions,
                ]
            );
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'Failed to retrieve voucher redemption report',
                500,
                [
                    'error' => $e->getMessage(),
                ]
            );
        }
    }
}
