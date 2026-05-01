<?php

namespace App\Console\Commands;

use App\Models\Domain;
use Illuminate\Console\Command;

class PopulateDomainData extends Command
{
    protected $signature = 'domain:populate';

    protected $description = 'Populate domain with backward-compatible service types and labels';

    public function handle(): void
    {
        $domain = Domain::first();

        if (! $domain) {
            $this->error('No domain found. Please create a domain first.');

            return;
        }

        $domain->update([
            'service_types' => [
                'general', 'construction', 'wedding', 'event', 'luxury',
                'party', 'emergency', 'residential', 'portable',
            ],
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
            'content_prompts' => [
                'service_page' => 'Act like a senior SEO strategist. Generate 2000-3000 words for {service_label} in {city_name}, {state_code}. Primary keyword: {primary_keyword}. Include FAQs and testimonials. Use {{PHONE_LINK}} for phone.',
                'state_page' => 'Generate 1000+ word state page for {state_name}, {state_code} focusing on {primary_keyword}. Use {{PHONE_LINK}} for phone.',
            ],
            'slug_prefix' => 'porta-potty',
        ]);

        $this->info("Domain '{$domain->name}' updated with backward-compatible service types and labels.");
    }
}
