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
                'email' => 'admin@akmart.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ],
            [
                'name' => 'Operations Admin',
                'email' => 'ops@akmart.com',
                'password' => Hash::make('password123'),
                'status' => 'active',
            ],
            [
                'name' => 'Finance Admin',
                'email' => 'finance@akmart.com',
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
