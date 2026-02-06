<?php

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Controller;
use App\Models\GiftVoucher;
use App\Models\VoucherRedemption;
use App\Enums\VoucherStatus;
use App\Enums\VoucherType;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class VoucherRedemptionController extends Controller
{
    public function redeem(Request $request)
    {
        $request->validate([
            'voucher_id'      => ['required', 'integer'],
            'voucher_code'    => ['required', 'string'],
            'original_amount' => ['required', 'numeric', 'min:1'],
        ]);

        try {
            $shop = $request->user(); // authenticated shop

            return DB::transaction(function () use ($request, $shop) {

                /** @var GiftVoucher|null $voucher */
                $voucher = GiftVoucher::lockForUpdate()
                    ->where('id', $request->voucher_id)
                    ->where('voucher_code', $request->voucher_code)
                    ->first();

                if (!$voucher) {
                    return ApiResponse::error('Voucher not found', 404);
                }

                // ❌ Status check
                if ($voucher->status !== VoucherStatus::UNUSED) {
                    return ApiResponse::error(
                        'Voucher cannot be redeemed',
                        422
                    );
                }

                // ❌ Expiry check
                if ($voucher->expires_at && $voucher->expires_at->isPast()) {
                    $voucher->update([
                        'status' => VoucherStatus::EXPIRED,
                    ]);

                    return ApiResponse::error(
                        'Voucher has expired',
                        422
                    );
                }

                // ❌ Assigned shop validation
                if (
                    $voucher->assigned_shop_id &&
                    $voucher->assigned_shop_id !== $shop->id
                ) {
                    return ApiResponse::error(
                        'Voucher not assigned to this shop',
                        403
                    );
                }

                $originalAmount = $request->original_amount;
                $discountAmount = 0;

                // 💰 Discount calculation
                if ($voucher->voucher_type === VoucherType::FIXED) {
                    $discountAmount = min(
                        $voucher->voucher_value,
                        $originalAmount
                    );
                }

                if ($voucher->voucher_type === VoucherType::PERCENTAGE) {
                    $discountAmount = ($originalAmount * $voucher->voucher_value) / 100;

                    $discountAmount = min(
                        $discountAmount,
                        $voucher->max_discount_amount
                    );
                }

                $finalAmount = max(
                    $originalAmount - $discountAmount,
                    0
                );

                // 🧾 Create redemption record
                $redemption = VoucherRedemption::create([
                    'voucher_id'      => $voucher->id,
                    'shop_id'         => $shop->id,
                    'original_amount' => $originalAmount,
                    'discount_amount' => $discountAmount,
                    'final_amount'    => $finalAmount,
                    'redeemed_at'     => now(),
                    'transaction_ref' => strtoupper(uniqid('TXN-')),
                ]);

                // 🔄 Update voucher status
                $voucher->update([
                    'status' => VoucherStatus::REDEEMED,
                ]);

                return ApiResponse::success(
                    'Voucher redeemed successfully',
                    [
                        'voucher' => [
                            'id'           => $voucher->id,
                            'voucher_code' => $voucher->voucher_code,
                            'voucher_type' => $voucher->voucher_type->value,
                        ],
                        'redemption' => [
                            'original_amount' => $originalAmount,
                            'discount_amount' => $discountAmount,
                            'final_amount'    => $finalAmount,
                            'redeemed_at'     => $redemption->redeemed_at?->toDateTimeString(),
                            'transaction_ref' => $redemption->transaction_ref,
                        ],
                    ]
                );
            });
        } catch (ValidationException $e) {
            return ApiResponse::error(
                $e->getMessage(),
                422,
                $e->errors()
            );
        } catch (Throwable $e) {
            report($e);

            return ApiResponse::error(
                'Something went wrong while redeeming voucher',
                500
            );
        }
    }
}
