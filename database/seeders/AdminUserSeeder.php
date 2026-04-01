<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@yourdomain.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@yourdomain.com',
                'password' => Hash::make('YourSecurePassword2026!'),
                'email_verified_at' => now(),
            ]
        );
    }
}
