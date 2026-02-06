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

        // Web guard now uses admins
        'web' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

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
