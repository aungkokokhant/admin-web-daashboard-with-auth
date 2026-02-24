<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Reseller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ResellerSeeder extends Seeder
{
    public function run(): void
    {
        // Get first admin (Super Admin)
        $admin = Admin::where('email', 'admin@giftvouchersystem.com')->first();

        if (!$admin) {
            return;
        }

        $resellers = [
            [
                'name' => 'Reseller One',
                'email' => 'reseller1@giftvouchersystem.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_by_admin_id' => $admin->id,
            ],
            [
                'name' => 'Reseller Two',
                'email' => 'reseller2@giftvouchersystem.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_by_admin_id' => $admin->id,
            ],
        ];

        foreach ($resellers as $reseller) {
            Reseller::updateOrCreate(
                ['email' => $reseller['email']],
                $reseller
            );
        }
    }
}
