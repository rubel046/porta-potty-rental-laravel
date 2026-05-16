<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Models\Neighborhood;
use App\Models\NeighborhoodServicePage;
use App\Services\MultiAiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateNeighborhoodContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 15;
    public int $timeout = 600;

    public function __construct(
        public Neighborhood $neighborhood,
        public ?Domain $domain = null,
        public array $types = []
    ) {
        if (empty($this->types) && $this->domain) {
            $this->types = $this->domain->getServiceTypes();
        }
    }

    public function handle(MultiAiService $ai): void
    {
        $domain = $this->domain ?? Domain::current() ?? Domain::first();
        $city = $this->neighborhood->city;
        $state = $city->state;

        if (!$city || !$state) {
            Log::error('Neighborhood missing city or state', ['neighborhood_id' => $this->neighborhood->id]);

            return;
        }

        $serviceLabel = $domain->getServiceTypeLabel($this->types[0] ?? 'general');
        $businessName = $domain->business_name ?? 'Our Company';
        $phoneRaw = domain_phone_raw();
        $phoneDisplay = domain_phone_display();
        $nearbyCitiesList = $city->nearby_cities ?? [];
        $nearbyCitiesText = is_array($nearbyCitiesList) ? implode(', ', array_slice($nearbyCitiesList, 0, 5)) : '';

        foreach ($this->types as $type) {
            $typeLabel = $domain->getServiceTypeLabel($type);

            $prompt = <<<PROMPT
You are a local SEO content writer for {$businessName}, a porta potty rental company.

Write content for a service page targeting: "{$typeLabel} in {$this->neighborhood->name}, {$city->name}, {$state->code}"

CONTEXT:
- Neighborhood: {$this->neighborhood->name}
- City: {$city->name}
- State: {$state->code} ({$state->name})
- Nearby cities: {$nearbyCitiesText}
- Service type: {$typeLabel}
- Business: {$businessName}

Neighborhood description: {$this->neighborhood->description}

STRUCTURE (content field — use ## headings, write 400-800 words):
- ## {$typeLabel} in {$this->neighborhood->name}, {$city->name}
- ## Why choose our {$typeLabel} service in {$this->neighborhood->name}
- ## Service area — mention the neighborhood, nearby streets/areas
- ## Delivery & setup
- ## Pricing (soft language, direct to call)
- ## Call to action with {{PHONE_LINK}}

Requirements:
- Reference the neighborhood name naturally 3-5 times
- Mention the parent city and state
- Be specific about service area
- Use {{PHONE_LINK}} for phone
- No marketing fluff, no "hassle-free", no "state-of-the-art"
- Sound like a local operator who knows this area

Return VALID JSON:
{
    "h1_title": "H1 with {$typeLabel} and {$this->neighborhood->name} (max 80 chars)",
    "meta_title": "SEO title (50-60 chars)",
    "meta_description": "140-160 chars with phone and location",
    "content": "Markdown with ## headings, 400-800 words"
}
PROMPT;

            try {
                $data = $ai->generateJsonContent($prompt, 'Return only valid JSON, no code fences.');

                if (!$data || empty($data['content'])) {
                    Log::warning('Neighborhood AI gen failed', [
                        'neighborhood' => $this->neighborhood->name,
                        'type' => $type,
                    ]);
                    continue;
                }

                // Build slug
                $slug = Str::slug("{$type}-{$this->neighborhood->name}-{$city->slug}");

                // Replace phone links
                $content = str_replace('{{PHONE_LINK}}', "<a href=\"tel:{$phoneRaw}\" class=\"text-blue-600 font-semibold hover:underline\">{$phoneDisplay}</a>", $data['content']);

                NeighborhoodServicePage::updateOrCreate(
                    [
                        'neighborhood_id' => $this->neighborhood->id,
                        'domain_id' => $domain->id,
                        'service_type' => $type,
                    ],
                    [
                        'slug' => $slug,
                        'h1_title' => $data['h1_title'] ?? "{$typeLabel} in {$this->neighborhood->name}",
                        'meta_title' => $data['meta_title'] ?? "{$typeLabel} {$this->neighborhood->name}, {$city->name} | {$businessName}",
                        'meta_description' => $data['meta_description'] ?? "{$typeLabel} in {$this->neighborhood->name}, {$city->name}. Call {$phoneDisplay}.",
                        'content' => $content,
                        'word_count' => str_word_count(strip_tags($content)),
                        'is_published' => true,
                        'published_at' => now(),
                        'generation_status' => 'success',
                        'generated_at' => now(),
                    ]
                );

            } catch (\Throwable $e) {
                Log::error('Neighborhood content generation error', [
                    'neighborhood' => $this->neighborhood->name,
                    'type' => $type,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
