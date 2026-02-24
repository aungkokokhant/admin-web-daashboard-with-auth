<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [

        // Admin Web Guard (Session Based)
        'web' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        // ✅ NEW: Reseller Guard (Session Based)
        'reseller' => [
            'driver' => 'session',
            'provider' => 'resellers',
        ],

        // Shop API Guard (Sanctum)
        'shop' => [
            'driver' => 'sanctum',
            'provider' => 'shops',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        // Admin accounts
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        // ✅ NEW: Reseller accounts
        'resellers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Reseller::class,
        ],

        // Shop accounts
        'shops' => [
            'driver' => 'eloquent',
            'model' => App\Models\Shop::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];
