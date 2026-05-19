<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Keyword;
use Illuminate\Database\Seeder;

class KeywordSeeder extends Seeder
{
    public function run(): void
    {
        $domain = Domain::where('domain', 'pottydirect.com')->first();

        if (! $domain) {
            $this->command->warn('PottyDirect domain not found — skipping keyword seeder');

            return;
        }

        $keywords = [
            // ===== TIER 1: HIGH PRIORITY (High volume, broad intent, money keywords) =====
            ['keyword' => 'porta potty rental', 'volume' => 18100, 'competition' => 'medium', 'cpc' => 8.50, 'service_type' => null, 'tier' => 1],
            ['keyword' => 'portable toilet rental', 'volume' => 12100, 'competition' => 'medium', 'cpc' => 7.20, 'service_type' => null, 'tier' => 1],
            ['keyword' => 'portable restroom rental', 'volume' => 8100, 'competition' => 'medium', 'cpc' => 6.80, 'service_type' => null, 'tier' => 1],
            ['keyword' => 'porta potty rental near me', 'volume' => 8100, 'competition' => 'low', 'cpc' => 9.50, 'service_type' => null, 'tier' => 1],
            ['keyword' => 'portable toilet rental near me', 'volume' => 5400, 'competition' => 'low', 'cpc' => 8.90, 'service_type' => null, 'tier' => 1],
            ['keyword' => 'rent a porta potty', 'volume' => 2900, 'competition' => 'low', 'cpc' => 7.50, 'service_type' => null, 'tier' => 1],
            ['keyword' => 'portable restroom rental near me', 'volume' => 2400, 'competition' => 'low', 'cpc' => 7.00, 'service_type' => null, 'tier' => 1],
            ['keyword' => 'same day porta potty rental near me', 'volume' => 1200, 'competition' => 'low', 'cpc' => 11.50, 'service_type' => null, 'tier' => 1],
            ['keyword' => 'same day portable toilet rental near me', 'volume' => 900, 'competition' => 'low', 'cpc' => 10.80, 'service_type' => null, 'tier' => 1],
            ['keyword' => 'local porta potty rental company', 'volume' => 800, 'competition' => 'low', 'cpc' => 6.50, 'service_type' => null, 'tier' => 1],

            // ===== TIER 1: CONSTRUCTION =====
            ['keyword' => 'construction site porta potty rental', 'volume' => 1900, 'competition' => 'low', 'cpc' => 9.00, 'service_type' => 'construction', 'tier' => 1],
            ['keyword' => 'porta potty for construction workers', 'volume' => 1300, 'competition' => 'low', 'cpc' => 8.50, 'service_type' => 'construction', 'tier' => 1],
            ['keyword' => 'job site portable toilet rental', 'volume' => 1100, 'competition' => 'low', 'cpc' => 8.00, 'service_type' => 'construction', 'tier' => 1],
            ['keyword' => 'portable toilet for construction site', 'volume' => 1600, 'competition' => 'low', 'cpc' => 8.20, 'service_type' => 'construction', 'tier' => 1],
            ['keyword' => 'construction porta potty', 'volume' => 1900, 'competition' => 'low', 'cpc' => 8.00, 'service_type' => 'construction', 'tier' => 1],
            ['keyword' => 'construction site toilet rental', 'volume' => 590, 'competition' => 'low', 'cpc' => 9.00, 'service_type' => 'construction', 'tier' => 1],
            ['keyword' => 'porta potty for job site near me', 'volume' => 720, 'competition' => 'low', 'cpc' => 8.80, 'service_type' => 'construction', 'tier' => 1],
            ['keyword' => 'portable toilet service for construction site', 'volume' => 480, 'competition' => 'low', 'cpc' => 7.50, 'service_type' => 'construction', 'tier' => 1],

            // ===== TIER 1: WEDDING =====
            ['keyword' => 'wedding porta potty rental', 'volume' => 1600, 'competition' => 'low', 'cpc' => 12.00, 'service_type' => 'wedding', 'tier' => 1],
            ['keyword' => 'porta potty rental for wedding', 'volume' => 720, 'competition' => 'low', 'cpc' => 13.50, 'service_type' => 'wedding', 'tier' => 1],
            ['keyword' => 'wedding restroom trailer rental', 'volume' => 880, 'competition' => 'low', 'cpc' => 15.00, 'service_type' => 'wedding', 'tier' => 1],
            ['keyword' => 'wedding restroom rental', 'volume' => 1100, 'competition' => 'low', 'cpc' => 12.50, 'service_type' => 'wedding', 'tier' => 1],
            ['keyword' => 'outdoor wedding bathroom rental', 'volume' => 590, 'competition' => 'low', 'cpc' => 14.00, 'service_type' => 'wedding', 'tier' => 1],
            ['keyword' => 'bathroom rentals for weddings', 'volume' => 720, 'competition' => 'low', 'cpc' => 11.00, 'service_type' => 'wedding', 'tier' => 1],

            // ===== TIER 1: EVENT =====
            ['keyword' => 'event porta potty rental near me', 'volume' => 1300, 'competition' => 'low', 'cpc' => 7.50, 'service_type' => 'event', 'tier' => 1],
            ['keyword' => 'event toilet rental', 'volume' => 1300, 'competition' => 'low', 'cpc' => 6.50, 'service_type' => 'event', 'tier' => 1],
            ['keyword' => 'outdoor event restroom rental', 'volume' => 880, 'competition' => 'low', 'cpc' => 7.00, 'service_type' => 'event', 'tier' => 1],
            ['keyword' => 'festival porta potty rental', 'volume' => 1000, 'competition' => 'low', 'cpc' => 8.00, 'service_type' => 'event', 'tier' => 1],
            ['keyword' => 'temporary restroom rental for event', 'volume' => 620, 'competition' => 'low', 'cpc' => 6.80, 'service_type' => 'event', 'tier' => 1],
            ['keyword' => 'portable toilet rental for sporting event', 'volume' => 480, 'competition' => 'low', 'cpc' => 7.20, 'service_type' => 'event', 'tier' => 1],

            // ===== TIER 1: EMERGENCY =====
            ['keyword' => 'emergency porta potty rental', 'volume' => 880, 'competition' => 'low', 'cpc' => 14.00, 'service_type' => 'emergency', 'tier' => 1],
            ['keyword' => '24 hour porta potty rental', 'volume' => 590, 'competition' => 'low', 'cpc' => 13.00, 'service_type' => 'emergency', 'tier' => 1],
            ['keyword' => 'last minute porta potty rental', 'volume' => 480, 'competition' => 'low', 'cpc' => 12.50, 'service_type' => 'emergency', 'tier' => 1],
            ['keyword' => 'porta potty delivery today', 'volume' => 720, 'competition' => 'low', 'cpc' => 11.00, 'service_type' => 'emergency', 'tier' => 1],
            ['keyword' => 'portable toilet needed today', 'volume' => 390, 'competition' => 'low', 'cpc' => 10.50, 'service_type' => 'emergency', 'tier' => 1],

            // ===== TIER 1: STANDARD / GENERAL =====
            ['keyword' => 'cheap porta potty rental', 'volume' => 880, 'competition' => 'low', 'cpc' => 5.50, 'service_type' => 'standard', 'tier' => 1],
            ['keyword' => 'standard porta potty rental', 'volume' => 590, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'standard', 'tier' => 1],
            ['keyword' => 'affordable porta potty rental near me', 'volume' => 720, 'competition' => 'low', 'cpc' => 6.00, 'service_type' => 'standard', 'tier' => 1],

            // ===== TIER 2: MEDIUM PRIORITY =====
            ['keyword' => 'luxury restroom trailer rental', 'volume' => 480, 'competition' => 'low', 'cpc' => 18.00, 'service_type' => 'luxury', 'tier' => 2],
            ['keyword' => 'portable restroom trailer rental', 'volume' => 600, 'competition' => 'low', 'cpc' => 15.00, 'service_type' => 'luxury', 'tier' => 2],
            ['keyword' => 'VIP restroom trailer rental', 'volume' => 320, 'competition' => 'low', 'cpc' => 20.00, 'service_type' => 'luxury', 'tier' => 2],
            ['keyword' => 'climate controlled restroom trailer rental', 'volume' => 260, 'competition' => 'low', 'cpc' => 16.00, 'service_type' => 'luxury', 'tier' => 2],
            ['keyword' => 'upscale porta potty rental', 'volume' => 300, 'competition' => 'low', 'cpc' => 14.00, 'service_type' => 'luxury', 'tier' => 2],
            ['keyword' => 'deluxe porta potty rental', 'volume' => 280, 'competition' => 'low', 'cpc' => 8.00, 'service_type' => 'deluxe', 'tier' => 2],
            ['keyword' => 'flushing porta potty rental', 'volume' => 390, 'competition' => 'low', 'cpc' => 7.50, 'service_type' => 'deluxe', 'tier' => 2],
            ['keyword' => 'ADA compliant porta potty rental', 'volume' => 480, 'competition' => 'low', 'cpc' => 6.00, 'service_type' => 'ada', 'tier' => 2],
            ['keyword' => 'handicap porta potty rental near me', 'volume' => 390, 'competition' => 'low', 'cpc' => 6.50, 'service_type' => 'ada', 'tier' => 2],
            ['keyword' => 'handicap accessible portable toilet rental', 'volume' => 350, 'competition' => 'low', 'cpc' => 6.00, 'service_type' => 'ada', 'tier' => 2],
            ['keyword' => 'party porta potty rental near me', 'volume' => 590, 'competition' => 'low', 'cpc' => 7.00, 'service_type' => 'party', 'tier' => 2],
            ['keyword' => 'temporary toilet rental for party', 'volume' => 320, 'competition' => 'low', 'cpc' => 7.50, 'service_type' => 'party', 'tier' => 2],
            ['keyword' => 'backyard party porta potty rental', 'volume' => 350, 'competition' => 'low', 'cpc' => 7.00, 'service_type' => 'party', 'tier' => 2],
            ['keyword' => 'residential porta potty rental', 'volume' => 480, 'competition' => 'low', 'cpc' => 6.00, 'service_type' => 'residential', 'tier' => 2],
            ['keyword' => 'monthly porta potty rental', 'volume' => 590, 'competition' => 'low', 'cpc' => 5.50, 'service_type' => 'residential', 'tier' => 2],
            ['keyword' => 'long term porta potty rental', 'volume' => 390, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'residential', 'tier' => 2],
            ['keyword' => 'portable bathroom rental with running water', 'volume' => 480, 'competition' => 'low', 'cpc' => 8.00, 'service_type' => 'deluxe', 'tier' => 2],
            ['keyword' => 'hand wash station rental', 'volume' => 390, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'sanitizer', 'tier' => 2],
            ['keyword' => 'handwashing station rental near me', 'volume' => 320, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'sanitizer', 'tier' => 2],
            ['keyword' => 'hand wash trailer rental', 'volume' => 200, 'competition' => 'low', 'cpc' => 7.00, 'service_type' => 'handwash-trailer', 'tier' => 2],
            ['keyword' => 'portable hand wash station rental', 'volume' => 390, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'sanitizer', 'tier' => 2],

            // ===== TIER 2: INFORMATIONAL (high intent, drives calls) =====
            ['keyword' => 'how much does porta potty rental cost', 'volume' => 1200, 'competition' => 'low', 'cpc' => 4.50, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'porta potty rental cost', 'volume' => 2400, 'competition' => 'medium', 'cpc' => 5.00, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'cost of porta potty', 'volume' => 1800, 'competition' => 'medium', 'cpc' => 4.80, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'porta potty rental price per day', 'volume' => 880, 'competition' => 'low', 'cpc' => 4.00, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'how much to rent a porta potty for a day', 'volume' => 720, 'competition' => 'low', 'cpc' => 4.50, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'porta potty rental prices per day', 'volume' => 210, 'competition' => 'low', 'cpc' => 4.00, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'affordable porta potty rental', 'volume' => 590, 'competition' => 'low', 'cpc' => 5.50, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'how many porta potties do i need', 'volume' => 800, 'competition' => 'low', 'cpc' => 3.50, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'how many porta potties for 100 guests', 'volume' => 590, 'competition' => 'low', 'cpc' => 3.00, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'how many porta potties for 200 guests', 'volume' => 390, 'competition' => 'low', 'cpc' => 3.00, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'porta potty calculator', 'volume' => 300, 'competition' => 'low', 'cpc' => 3.50, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'porta potty rental quote', 'volume' => 480, 'competition' => 'low', 'cpc' => 6.00, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'porta potty rental weekly rate', 'volume' => 390, 'competition' => 'low', 'cpc' => 4.50, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'porta potty rental monthly rate', 'volume' => 480, 'competition' => 'low', 'cpc' => 4.00, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'porta potty delivery fee', 'volume' => 320, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => null, 'tier' => 2],
            ['keyword' => 'standard vs luxury porta potty cost', 'volume' => 260, 'competition' => 'low', 'cpc' => 4.00, 'service_type' => null, 'tier' => 2],

            // ===== TIER 2: CONSTRUCTION SPECIFIC =====
            ['keyword' => 'OSHA portable toilet requirements', 'volume' => 900, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'construction', 'tier' => 2],
            ['keyword' => 'OSHA compliant porta potty', 'volume' => 260, 'competition' => 'low', 'cpc' => 5.50, 'service_type' => 'construction', 'tier' => 2],
            ['keyword' => 'construction site sanitation', 'volume' => 500, 'competition' => 'low', 'cpc' => 4.50, 'service_type' => 'construction', 'tier' => 2],
            ['keyword' => 'portable restroom for construction workers', 'volume' => 390, 'competition' => 'low', 'cpc' => 7.00, 'service_type' => 'construction', 'tier' => 2],
            ['keyword' => 'high rise porta potty rental', 'volume' => 200, 'competition' => 'low', 'cpc' => 9.00, 'service_type' => 'highrise', 'tier' => 2],

            // ===== TIER 2: EVENT SPECIFIC =====
            ['keyword' => 'concert portable toilet rental', 'volume' => 400, 'competition' => 'low', 'cpc' => 8.00, 'service_type' => 'event', 'tier' => 2],
            ['keyword' => 'porta potty for outdoor concert', 'volume' => 350, 'competition' => 'low', 'cpc' => 7.50, 'service_type' => 'event', 'tier' => 2],
            ['keyword' => 'outdoor concert restroom rental', 'volume' => 300, 'competition' => 'low', 'cpc' => 7.00, 'service_type' => 'event', 'tier' => 2],
            ['keyword' => 'porta potty for music festival', 'volume' => 480, 'competition' => 'low', 'cpc' => 8.50, 'service_type' => 'event', 'tier' => 2],
            ['keyword' => 'porta potty rental for festival', 'volume' => 590, 'competition' => 'low', 'cpc' => 7.00, 'service_type' => 'event', 'tier' => 2],
            ['keyword' => 'porta potty for farmers market', 'volume' => 200, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'event', 'tier' => 2],
            ['keyword' => 'porta potty for street fair', 'volume' => 250, 'competition' => 'low', 'cpc' => 5.50, 'service_type' => 'event', 'tier' => 2],
            ['keyword' => 'porta potty for tailgate party', 'volume' => 300, 'competition' => 'low', 'cpc' => 6.00, 'service_type' => 'party', 'tier' => 2],
            ['keyword' => 'porta potty for graduation party', 'volume' => 320, 'competition' => 'low', 'cpc' => 5.50, 'service_type' => 'party', 'tier' => 2],
            ['keyword' => 'porta potty for birthday party', 'volume' => 480, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'party', 'tier' => 2],

            // ===== TIER 3: LONG-TAIL / NICHE =====
            ['keyword' => 'same day porta potty delivery', 'volume' => 480, 'competition' => 'low', 'cpc' => 10.00, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'how much is a porta potty to rent', 'volume' => 390, 'competition' => 'low', 'cpc' => 4.00, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'do i need permit for porta potty rental', 'volume' => 260, 'competition' => 'low', 'cpc' => 3.50, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'how early to book porta potty rental', 'volume' => 200, 'competition' => 'low', 'cpc' => 3.00, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'what size porta potty do i need', 'volume' => 250, 'competition' => 'low', 'cpc' => 3.00, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'porta potty rental contract', 'volume' => 200, 'competition' => 'low', 'cpc' => 3.00, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'porta potty rental discount', 'volume' => 250, 'competition' => 'low', 'cpc' => 3.50, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'porta potty cleaning service', 'volume' => 300, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'porta potty sanitization service', 'volume' => 200, 'competition' => 'low', 'cpc' => 4.50, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'portable restroom pump out service', 'volume' => 200, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'porta potty with sink rental', 'volume' => 350, 'competition' => 'low', 'cpc' => 6.00, 'service_type' => 'deluxe', 'tier' => 3],
            ['keyword' => 'porta potty with hand sanitizer', 'volume' => 300, 'competition' => 'low', 'cpc' => 4.00, 'service_type' => 'standard', 'tier' => 3],
            ['keyword' => 'portable shower rental', 'volume' => 300, 'competition' => 'low', 'cpc' => 12.00, 'service_type' => 'shower', 'tier' => 3],
            ['keyword' => 'portable urinal station rental', 'volume' => 250, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'portable-urinal', 'tier' => 3],
            ['keyword' => 'portable urinal rental', 'volume' => 300, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'portable-urinal', 'tier' => 3],
            ['keyword' => 'dumpster rental', 'volume' => 12000, 'competition' => 'high', 'cpc' => 12.00, 'service_type' => 'dumpster', 'tier' => 3],
            ['keyword' => 'septic tank pumping', 'volume' => 2400, 'competition' => 'medium', 'cpc' => 8.00, 'service_type' => 'septic', 'tier' => 3],
            ['keyword' => 'temporary fence rental', 'volume' => 1800, 'competition' => 'medium', 'cpc' => 6.00, 'service_type' => 'temporary-fencing', 'tier' => 3],
            ['keyword' => 'holding tank rental', 'volume' => 200, 'competition' => 'low', 'cpc' => 6.00, 'service_type' => 'holding', 'tier' => 3],
            ['keyword' => 'portable restroom for camping', 'volume' => 480, 'competition' => 'low', 'cpc' => 5.00, 'service_type' => 'portable', 'tier' => 3],
            ['keyword' => 'portable toilet for film set', 'volume' => 200, 'competition' => 'low', 'cpc' => 8.00, 'service_type' => 'event', 'tier' => 3],
            ['keyword' => 'fancy porta potty rental', 'volume' => 250, 'competition' => 'low', 'cpc' => 10.00, 'service_type' => 'luxury', 'tier' => 3],

            // ===== TIER 3: GEO TEMPLATES (resolved at generation time) =====
            ['keyword' => 'porta potty rental [city] [state]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'portable toilet rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'porta potty rental [city] same day', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'same day porta potty rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'emergency porta potty rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'emergency', 'tier' => 3],
            ['keyword' => 'affordable porta potty rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'construction porta potty rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'construction', 'tier' => 3],
            ['keyword' => 'event porta potty rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'event', 'tier' => 3],
            ['keyword' => 'luxury restroom trailer rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'luxury', 'tier' => 3],
            ['keyword' => 'porta potty delivery [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'rent a porta potty [city] today', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'porta potty rental prices [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'portable restroom rental [county]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'portable toilet rental [city] [state]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'rent a porta potty in [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => null, 'tier' => 3],
            ['keyword' => 'dumpster rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'dumpster', 'tier' => 3],
            ['keyword' => 'septic tank pumping [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'septic', 'tier' => 3],
            ['keyword' => 'hand washing station rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'sanitizer', 'tier' => 3],
            ['keyword' => 'portable shower rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'shower', 'tier' => 3],
            ['keyword' => 'temporary fence rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'temporary-fencing', 'tier' => 3],
            ['keyword' => 'portable urinal rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'portable-urinal', 'tier' => 3],
            ['keyword' => 'hand wash trailer rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'handwash-trailer', 'tier' => 3],
            ['keyword' => 'holding tank rental [city]', 'volume' => null, 'competition' => 'low', 'cpc' => null, 'service_type' => 'holding', 'tier' => 3],
        ];

        $count = 0;
        foreach ($keywords as $data) {
            Keyword::updateOrCreate(
                ['domain_id' => $domain->id, 'keyword' => $data['keyword']],
                $data
            );
            $count++;
        }

        $this->command->info("Seeded {$count} keywords for {$domain->name}");
    }
}
