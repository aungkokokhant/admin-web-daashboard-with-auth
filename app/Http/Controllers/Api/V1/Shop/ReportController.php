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
    public function voucherRedemptionReport(Request $request)
    {
        try {
            $shop = $request->user('shop');

            if (!$shop) {
                return ApiResponse::error('Unauthenticated', 401);
            }

            // Month filter (YYYY-MM) or default to current month
            $month = $request->query('month');
            $date  = $month
                ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
                : now()->startOfMonth();

            $startDate = $date->copy()->startOfMonth();
            $endDate   = $date->copy()->endOfMonth();

            // ================= Summary =================
            $summary = VoucherRedemption::where('shop_id', $shop->id)
                ->whereBetween('redeemed_at', [$startDate, $endDate])
                ->selectRaw('
                    COALESCE(SUM(original_amount), 0) as total_original_amount,
                    COALESCE(SUM(discount_amount), 0) as total_discount_amount,
                    COALESCE(SUM(final_amount), 0) as total_final_amount
                ')
                ->first();

            // ================= List (History) =================
            $redemptions = VoucherRedemption::with(['voucher'])
                ->where('shop_id', $shop->id)
                ->whereBetween('redeemed_at', [$startDate, $endDate])
                ->orderByDesc('redeemed_at')
                ->get();

            return ApiResponse::success(
                'Voucher redemption report retrieved successfully',
                [
                    'filter' => [
                        'month'      => $startDate->format('Y-m'),
                        'start_date' => $startDate->toDateString(),
                        'end_date'   => $endDate->toDateString(),
                    ],
                    'summary' => [
                        'total_original_amount' => (float) $summary->total_original_amount,
                        'total_discount_amount' => (float) $summary->total_discount_amount,
                        'total_final_amount'    => (float) $summary->total_final_amount,
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
