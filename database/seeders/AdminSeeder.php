<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@giftvouchersystem.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ],
            [
                'name' => 'Operations Admin',
                'email' => 'ops@giftvouchersystem.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ],
            [
                'name' => 'Finance Admin',
                'email' => 'finance@giftvouchersystem.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ],
        ];

        foreach ($admins as $admin) {
            Admin::updateOrCreate(
                ['email' => $admin['email']],
                $admin
            );
        }
    }
}
