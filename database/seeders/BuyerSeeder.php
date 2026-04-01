<?php

namespace Database\Seeders;

use App\Models\Buyer;
use Illuminate\Database\Seeder;

class BuyerSeeder extends Seeder
{
    public function run(): void
    {
        Buyer::updateOrCreate(
            ['phone' => env('DEFAULT_BUYER_PHONE', '+10000000000')],
            [
                'company_name' => 'Primary Buyer',
                'contact_name' => 'Buyer Contact',
                'phone' => env('DEFAULT_BUYER_PHONE', '+10000000000'),
                'email' => 'buyer@example.com',
                'payout_per_call' => 10.00,
                'daily_call_cap' => 20,
                'monthly_call_cap' => 500,
                'business_hours' => [
                    'start' => '07:00',
                    'end' => '20:00',
                ],
                'timezone' => 'America/Chicago',
                'ring_timeout' => 25,
                'priority' => 1,
                'is_active' => true,
            ]
        );
    }
}
