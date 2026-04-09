<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        Domain::firstOrCreate(
            ['domain' => 'pottydirect.com'],
            [
                'name' => 'Potty Direct',
                'display_name' => 'Potty Direct',
                'primary_color' => '#22C55E',
                'is_active' => true,
            ]
        );
    }
}
