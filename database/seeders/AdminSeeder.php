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
                'name' => 'Admin 1',
                'email' => 'admin1@example.com',
                'phone' => '+919876543210',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin 2',
                'email' => 'admin2@example.com',
                'phone' => '+919876543211',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin 3',
                'email' => 'admin3@example.com',
                'phone' => '+919876543212',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}

