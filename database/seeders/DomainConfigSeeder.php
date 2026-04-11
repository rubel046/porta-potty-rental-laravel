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
            'same day porta potty rental near me',
            'same day portable toilet rental near me',
            'emergency porta potty rental',
            '24 hour porta potty rental',
            'last minute porta potty rental',
            'rush porta potty rental',
            'porta potty delivery today',
            'portable toilet needed today',
            'porta potty rental asap',
            'next day porta potty rental',
            'porta potty rental open now',
            'porta potty rental near me',
            'portable toilet rental near me',
            'rent a porta potty near me',
            'porta potty rental [city] [state]',
            'portable toilet rental [city]',
            'same day porta potty rental [city]',
            'porta potty rental [city] phone number',
            'rent a porta potty in [city]',
            'portable restroom rental [county]',
            'porta potty near [landmark / area]',
            'porta potty rental [zip code]',
            'local porta potty rental company',
            'porta potty company near me',
            'where can I rent a porta potty near me',
            'who rents porta potties near me',
            'construction site porta potty rental',
            'job site portable toilet rental',
            'portable toilet for construction workers',
            'portable restroom for work site',
            'porta potty rental for contractors',
            'monthly porta potty rental',
            'long term porta potty rental',
            'weekly porta potty rental',
            'OSHA portable toilet requirements',
            'porta potty for job site near me',
            'portable toilet service for construction site',
            'portable restroom pump out service',
            'porta potty cleaning service',
            'event porta potty rental near me',
            'outdoor event restroom rental',
            'party porta potty rental near me',
            'porta potty rental for festival',
            'outdoor concert restroom rental',
            'portable toilet rental for sporting event',
            'porta potty for outdoor wedding',
            'porta potty rental for graduation party',
            'portable toilet rental for birthday party',
            'portable restroom rental for family reunion',
            'porta potty rental for fair',
            'porta potty rental for 5k race',
            'temporary restroom rental for event',
            'luxury restroom trailer rental',
            'wedding restroom trailer rental',
            'VIP restroom trailer rental',
            'portable restroom trailer rental near me',
            'upscale porta potty rental',
            'deluxe porta potty rental',
            'restroom trailer rental for wedding near me',
            'climate controlled restroom trailer rental',
            'flushing porta potty rental',
            'portable bathroom rental with running water',
            'how much does porta potty rental cost',
            'porta potty rental cost near me',
            'porta potty rental price per day',
            'portable toilet rental rates',
            'affordable porta potty rental near me',
            'cheap portable toilet rental near me',
            'porta potty rental weekly rate',
            'porta potty rental monthly rate',
            'porta potty rental quote',
            'how much to rent a porta potty for a day',
            'porta potty rental prices [city]',
            'ADA compliant porta potty rental',
            'handicap porta potty rental near me',
            'handicap accessible portable toilet rental',
            'porta potty with sink rental',
            'handwashing station rental near me',
            'portable hand wash station rental',
            'porta potty with hand sanitizer',
            'high rise porta potty rental',
            'solar powered portable toilet rental',
            'portable restroom with lighting rental',
            'porta potty rental [city] same day',
            'same day porta potty rental [city]',
            'emergency porta potty rental [city]',
            'portable toilet rental [city] [state]',
            'affordable porta potty rental [city]',
            'construction porta potty rental [city]',
            'event porta potty rental [city]',
            'luxury restroom trailer rental [city]',
            'porta potty delivery [city]',
            'rent a porta potty [city] today',
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
                'tagline' => 'Same Day Porta Potty Rental — Serving Communities Across the USA',
                'cta_phone' => '+18336529344',
                'primary_color' => '#22C55E',
                'is_active' => true,
            ]
        );

        $this->command->info($domain->wasRecentlyCreated
            ? 'Created default Potty Direct domain'
            : 'Updated pottydirect.com with porta potty configuration');

        $this->command->info('✅ Domain configuration seeded successfully');
        $this->command->line('Service types: ' . implode(', ', $portaPottyServiceTypes));
    }
}
