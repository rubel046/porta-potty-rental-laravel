<?php

namespace Database\Seeders;

use App\Models\Domain;
use Illuminate\Database\Seeder;

class DomainConfigSeeder extends Seeder
{
    public function run(): void
    {
        $domainsConfig = [
            'pottydirect.com' => [
                'name' => 'Potty Direct',
                'business_name' => 'Potty Direct',
                'primary_keyword' => 'porta potty rental',
                'secondary_keywords' => [
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
                ],
                'primary_service' => 'porta potty',
                'service_types' => ['general', 'construction', 'wedding', 'event', 'luxury', 'party', 'emergency', 'residential', 'portable'],
                'service_labels' => [
                    'general' => 'Standard Porta Potty Rental',
                    'construction' => 'Construction Site Porta Potty',
                    'wedding' => 'Wedding Event Porta Potty',
                    'event' => 'Event Porta Potty Rental',
                    'luxury' => 'Luxury Porta Potty Trailer',
                    'party' => 'Party Porta Potty Rental',
                    'emergency' => 'Emergency Porta Potty',
                    'residential' => 'Residential Porta Potty',
                    'portable' => 'Portable Porta Potty Rental',
                ],
                'slug_prefix' => 'porta-potty',
                'tagline' => 'Same Day Porta Potty Rental — Serving Communities Across the USA',
                'cta_phone' => '+18336529344',
                'primary_color' => '#22C55E',
                'is_active' => true,
            ],
            'plumbingpro.com' => [
                'name' => 'Plumbing Pro',
                'business_name' => 'Plumbing Pro',
                'primary_keyword' => 'plumbing services',
                'secondary_keywords' => [
                    'emergency plumber near me',
                    'drain cleaning service near me',
                    'pipe repair near me',
                    'water heater installation',
                    'sewer line repair',
                ],
                'primary_service' => 'plumbing',
                'service_types' => ['drain-cleaning', 'pipe-repair', 'water-heater', 'sewer-line', 'emergency'],
                'service_labels' => [
                    'drain-cleaning' => 'Drain Cleaning Service',
                    'pipe-repair' => 'Pipe Repair Service',
                    'water-heater' => 'Water Heater Service',
                    'sewer-line' => 'Sewer Line Service',
                    'emergency' => 'Emergency Plumbing',
                ],
                'slug_prefix' => 'plumbing',
                'cta_phone' => '+18336529345',
                'primary_color' => '#273bd3',
                'is_active' => true,
            ],
        ];

        foreach ($domainsConfig as $domainName => $config) {
            $domain = Domain::updateOrCreate(
                ['domain' => $domainName],
                $config
            );

            $this->command->info($domain->wasRecentlyCreated
                ? "Created domain: {$config['name']}"
                : "Updated domain: {$config['name']}");
            $this->command->line('Service types: '.implode(', ', $config['service_types']));
        }

        $this->command->info('✅ All domains seeded successfully');
    }
}
