<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Shop::updateOrCreate(
                ['shop_code' => 'SHOP' . str_pad($i, 3, '0', STR_PAD_LEFT)],
                [
                    'shop_name' => 'TEST SHOP' . $i,
                    'phone' => '09123456' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'password' => Hash::make('shop12345'),
                    'status' => 'active',
                    'created_by_admin_id' => 1, // Super Admin
                ]
            );
        }
    }
}
