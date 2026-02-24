<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reseller\ResellerAuthController;
use App\Http\Controllers\Reseller\ResellVoucherController;

/*
|--------------------------------------------------------------------------
| Reseller Authentication (Guest)
|--------------------------------------------------------------------------
*/

Route::middleware('guest:reseller')->group(function () {

    Route::get('/login', [ResellerAuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [ResellerAuthController::class, 'login'])
        ->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Reseller Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:reseller')->group(function () {

    Route::post('/logout', [ResellerAuthController::class, 'logout'])
        ->name('logout');

    Route::prefix('resell-vouchers')->name('resell-vouchers.')->group(function () {

        Route::get('/', [ResellVoucherController::class, 'index'])
            ->name('index');

        Route::post('/{voucher}/validate', [ResellVoucherController::class, 'validate'])
            ->name('validate');
    });
});
