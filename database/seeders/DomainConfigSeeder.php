<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Seeder;

class DomainConfigSeeder extends Seeder
{
    public function run(): void
    {
        $portaPottyServiceTypes = [
            'general',
            'construction',
            'wedding',
            'event',
            'luxury',
            'party',
            'emergency',
            'residential',
            'portable',
        ];

        $portaPottySecondaryKeywords = [
            'portable toilet rental',
            'event restroom rental',
            'construction toilets',
            'cheap porta potty rental',
            'party bathroom rental',
        ];

        $domain = Domain::updateOrCreate(
            ['domain' => 'pottydirect.com'],
            [
                'name' => 'Potty Direct',
                'business_name' => 'Potty Direct',
                'primary_keyword' => 'porta potty rental',
                'secondary_keywords' => $portaPottySecondaryKeywords,
                'primary_service' => 'porta potty',
                'service_types' => $portaPottyServiceTypes,
                'tagline' => 'Your Trusted Portable Restroom Rental Experts',
                'primary_color' => '#22C55E',
                'is_active' => true,
            ]
        );

        $this->command->info($domain->wasRecentlyCreated
            ? 'Created default Potty Direct domain'
            : 'Updated pottydirect.com with porta potty configuration');

        $this->command->info('✅ Domain configuration seeded successfully');
        $this->command->line('Service types: '.implode(', ', $portaPottyServiceTypes));
    }
}
