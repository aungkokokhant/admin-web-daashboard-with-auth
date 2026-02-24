<?php

use App\Enums\PromotionStatus;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\GiftVoucherController;
use App\Http\Controllers\Admin\ResellerController;
use App\Http\Controllers\Admin\ResellVoucherController;
use App\Models\Promotion;
use App\Models\Shop;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Admin Authentication (Guests Only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest:admin')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:admin')->group(function () {

    Route::get('/dashboard', function () {

        $today = Carbon::today();

        $activeTodayPromotions = Promotion::where('status', PromotionStatus::ACTIVE)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('end_date')
            ->get();

        return view('admin.dashboard', [
            'totalShops' => Shop::count(),
            'activePromotionsCount' => $activeTodayPromotions->count(),
            'activeTodayPromotions' => $activeTodayPromotions,
        ]);
    })->name('dashboard');

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout');

    Route::prefix('promotions')->name('promotions.')->group(function () {

        Route::get('/', [PromotionController::class, 'index'])
            ->name('index');

        Route::get('/create', [PromotionController::class, 'create'])
            ->name('create');

        Route::post('/', [PromotionController::class, 'store'])
            ->name('store');

        Route::get('/{promotion}/edit', [PromotionController::class, 'edit'])
            ->name('edit');

        Route::put('/{promotion}', [PromotionController::class, 'update'])
            ->name('update');

        Route::delete('/{promotion}', [PromotionController::class, 'destroy'])
            ->name('destroy');
    });


    Route::prefix('shops')->name('shops.')->group(function () {

        Route::get('/', [ShopController::class, 'index'])
            ->name('index');

        Route::get('/create', [ShopController::class, 'create'])
            ->name('create');

        Route::post('/', [ShopController::class, 'store'])
            ->name('store');

        Route::get('/{shop}/edit', [ShopController::class, 'edit'])
            ->name('edit');

        Route::put('/{shop}', [ShopController::class, 'update'])
            ->name('update');

        Route::put('/{shop}/password', [ShopController::class, 'updatePassword'])
            ->name('password.update');
    });



    Route::prefix('vouchers')->name('vouchers.')->group(function () {

        Route::get('/', [GiftVoucherController::class, 'index'])
            ->name('index');

        Route::get('/create', [GiftVoucherController::class, 'create'])
            ->name('create');

        Route::post('/', [GiftVoucherController::class, 'store'])
            ->name('store');

        Route::get('/{voucher}/edit', [GiftVoucherController::class, 'edit'])
            ->name('edit');

        Route::put('/{voucher}', [GiftVoucherController::class, 'update'])
            ->name('update');

        Route::post('/{voucher}/revoke', [GiftVoucherController::class, 'revoke'])
            ->name('revoke');

        Route::get(
            '/{voucher}/download-qr',
            [GiftVoucherController::class, 'downloadQr']
        )->name('download-qr');

        Route::post(
            '/redemptions/{redemption}/confirm-payout',
            [GiftVoucherController::class, 'confirmPayout']
        )->name('redemptions.confirm-payout');
    });

    Route::prefix('resell-vouchers')->name('resell-vouchers.')->group(function () {

        Route::get('/', [ResellVoucherController::class, 'index'])
            ->name('index');

        Route::get('/create', [ResellVoucherController::class, 'create'])
            ->name('create');

        Route::post('/', [ResellVoucherController::class, 'store'])
            ->name('store');

        Route::get(
            '/{voucher}/download-qr',
            [ResellVoucherController::class, 'downloadQr']
        )->name('download-qr');
    });

    Route::prefix('reports')->name('reports.')->group(function () {

        Route::get('/voucher-redemptions', [AdminReportController::class, 'voucherRedemptions'])
            ->name('voucher-redemptions');

        Route::get(
            '/voucher-redemptions/shop/{shop}',
            [AdminReportController::class, 'shopVoucherRedemptions']
        )->name('voucher-redemptions.shop');
    });

    Route::prefix('resellers')->name('resellers.')->group(function () {

        Route::get('/', [ResellerController::class, 'index'])
            ->name('index');

        Route::get('/create', [ResellerController::class, 'create'])
            ->name('create');

        Route::post('/', [ResellerController::class, 'store'])
            ->name('store');

        Route::get('/{reseller}/edit', [ResellerController::class, 'edit'])
            ->name('edit');

        Route::put('/{reseller}', [ResellerController::class, 'update'])
            ->name('update');
    });
});
