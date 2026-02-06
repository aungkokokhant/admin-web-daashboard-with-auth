<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Shop\AuthController;
use App\Http\Controllers\Api\V1\Shop\ReportController;
use App\Http\Controllers\Api\V1\Shop\VoucherRedemptionController;
use App\Http\Controllers\Api\V1\Shop\VoucherScanController;

/*
|--------------------------------------------------------------------------
| Shop API Auth (Public)
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

/*
|--------------------------------------------------------------------------
| Shop API Protected
|--------------------------------------------------------------------------
*/
Route::middleware('auth:shop')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/me', [AuthController::class, 'me'])
        ->name('me');

    Route::post('change-password', [AuthController::class, 'changePassword'])
        ->name('changePassword');

    /*
    |--------------------------------------------------------------------------
    | Voucher Scan (Validation Only)
    |--------------------------------------------------------------------------
    */
    Route::post('/vouchers/validateVoucher', [VoucherScanController::class, 'validateVoucher'])
        ->name('vouchers.validateVoucher');

    Route::post('/vouchers/redeem', [VoucherRedemptionController::class, 'redeem'])
        ->name('vouchers.redeem');

    Route::get(
        '/reports/voucher-redemptions',
        [ReportController::class, 'voucherRedemptionReport']
    )->name('reports.voucherRedemptions');
});
