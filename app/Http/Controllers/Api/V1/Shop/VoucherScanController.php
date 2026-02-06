<?php

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Controller;
use App\Support\VoucherCrypto;
use App\Support\ApiResponse;
use App\Models\GiftVoucher;
use App\Enums\VoucherStatus;
use Illuminate\Http\Request;

class VoucherScanController extends Controller
{
    public function validateVoucher(Request $request)
    {
        $request->validate([
            'payload' => 'required|string'
        ]);

        try {
            $data = VoucherCrypto::decrypt($request->payload);

            $voucherId   = $data['vid'] ?? null;
            $voucherCode = $data['code'] ?? null;

            if (!$voucherId || !$voucherCode) {
                return ApiResponse::error('Invalid voucher payload structure', 422);
            }

            $voucher = GiftVoucher::with([
                'promotion',
                'assignedShop',
                'redemption',
                'redemption.shop',
            ])->where('id', $voucherId)
                ->where('voucher_code', $voucherCode)
                ->first();

            if (!$voucher) {
                return ApiResponse::error('Voucher not found', 404);
            }

            switch ($voucher->status) {
                case VoucherStatus::UNUSED:
                    $message = 'Voucher is valid and ready to use';
                    break;

                case VoucherStatus::EXPIRED:
                    $message = 'Voucher is expired';
                    break;

                case VoucherStatus::REDEEMED:
                    $message = 'Voucher has already been redeemed';
                    break;

                case VoucherStatus::REVOKED:
                    $message = 'Voucher has been revoked';
                    break;

                default:
                    $message = 'Voucher cannot be used';
            }

            $canRedeem = $voucher->status === VoucherStatus::UNUSED;

            return ApiResponse::success(
                $message,
                [
                    'voucher' => [
                        'id' => $voucher->id,
                        'voucher_code' => $voucher->voucher_code,
                        'voucher_type' => $voucher->voucher_type->value,
                        'voucher_value' => $voucher->voucher_value,
                        'max_discount_amount' => $voucher->max_discount_amount,
                        'status' => $voucher->status->value,
                        'can_redeem' => $canRedeem,
                        'expires_at' => $voucher->expires_at?->toDateTimeString(),

                        'promotion' => $voucher->promotion ? [
                            'id' => $voucher->promotion->id,
                            'title' => $voucher->promotion->title,
                            'description' => $voucher->promotion->description,
                        ] : null,

                        'assigned_shop' => $voucher->assignedShop ? [
                            'id' => $voucher->assignedShop->id,
                            'name' => $voucher->assignedShop->shop_name,
                            'code' => $voucher->assignedShop->shop_code,
                        ] : null,

                        'redemption' => $voucher->redemption ? [
                            'redeemed_at' => $voucher->redemption->redeemed_at?->toDateTimeString(),
                            'original_amount' => $voucher->redemption->original_amount,
                            'discount_amount' => $voucher->redemption->discount_amount,
                            'final_amount' => $voucher->redemption->final_amount,
                            'transaction_ref' => $voucher->redemption->transaction_ref,

                            'shop' => $voucher->redemption->shop ? [
                                'id'   => $voucher->redemption->shop->id,
                                'name' => $voucher->redemption->shop->shop_name,
                                'code' => $voucher->redemption->shop->shop_code,
                            ] : null,
                        ] : null,
                    ]
                ]
            );
        } catch (\RuntimeException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }
}
