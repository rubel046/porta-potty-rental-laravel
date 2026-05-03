<?php

namespace App\Services;

use App\Models\BlogCategory;
use App\Models\City;
use App\Models\Domain;
use App\Models\DomainState;
use App\Models\Faq;
use App\Models\State;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContentGeneratorService
{
    protected ?MultiAiService $aiService = null;

    protected ?ImageService $imageService = null;

    public function __construct(?MultiAiService $aiService = null, ?ImageService $imageService = null)
    {
        $this->aiService = $aiService;
        $this->imageService = $imageService;
    }

    public function generateServicePageContent(City $city, string $serviceType = 'general'): array
    {
        if (! $this->aiService) {
            throw new \RuntimeException('AI service not configured. Please set up MultiAiService to generate content.');
        }

        return $this->generateFromAI($city, $serviceType);
    }

    protected function generateFromAI(City $city, string $serviceType): array
    {
        $domain = Domain::current() ?? Domain::first();
        $state = $city->state;

        $serviceLabel = $domain
            ? $domain->getServiceTypeLabel($serviceType)
            : "General {$city->name} Service";

        $primaryKeyword = $domain?->primary_keyword ?? $city->name.' service';
        $secondaryKeywords = $domain?->getSecondaryKeywordsFormatted();
        $businessName = $domain?->business_name ?? 'Our Company';
        $serviceTypesList = $domain?->getServiceTypes() ?? ['general'];
        $serviceTypesText = implode(', ', array_map(fn ($t) => $domain->getServiceTypeLabel($t), $serviceTypesList));

        $nearbyCitiesList = $this->getNearbyCities($city);
        $nearbyCitiesText = implode(', ', $nearbyCitiesList);

        // Use domain-specific prompt if available, otherwise use default
        $replacements = [
            '{service_label}' => $serviceLabel,
            '{city_name}' => $city->name,
            '{state_code}' => $state->code,
            '{primary_keyword}' => $primaryKeyword,
            '{secondary_keywords}' => $secondaryKeywords,
            '{business_name}' => $businessName,
            '{service_types_text}' => $serviceTypesText,
            '{nearby_cities}' => $nearbyCitiesText,
        ];

        $prompt = $this->getDefaultServicePagePrompt($replacements);

        $systemPrompt = 'You are an SEO content writer that MUST generate ALL fields. CRITICAL RULES: 1) h1_title, meta_title, meta_description, content MUST ALL be non-empty strings. 2) Phone numbers: ONLY use {{PHONE_LINK}}. 3) Service links: ONLY use {{SERVICE_LINK:service-type}}. 4) Never output actual phone numbers or URLs. 5) Always return valid JSON. 6) If you fail to provide any required field, the system will reject your response.';

        $maxAttempts = 3;
        $attempt = 0;
        $jsonResponse = null;

        while ($attempt < $maxAttempts) {
            $attempt++;
            $jsonResponse = $this->aiService->generateJsonContent($prompt, $systemPrompt);

            if (! $jsonResponse || ! isset($jsonResponse['content'])) {
                Log::warning("Service page generation attempt {$attempt} failed for {$city->name} ({$serviceType}): Invalid JSON");

                continue;
            }

            // Force validation - all required fields must be non-empty strings
            $requiredFields = ['h1_title', 'meta_title', 'meta_description', 'content'];
            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (empty($jsonResponse[$field]) || ! is_string($jsonResponse[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (empty($missingFields)) {
                break; // Success - all fields present
            }

            Log::warning("Service page generation attempt {$attempt} failed for {$city->name} ({$serviceType})", [
                'missing_fields' => $missingFields,
            ]);

            // Strengthen prompt for retry
            $prompt .= "\n\nWARNING: Previous attempt failed. You MUST provide ALL required fields including: ".implode(', ', $missingFields).'. All fields must be non-empty strings.';
        }

        if ($attempt >= $maxAttempts || ! $jsonResponse || ! isset($jsonResponse['content'])) {
            throw new \RuntimeException("AI failed to generate all required data for {$city->name} ({$serviceType}) after {$maxAttempts} attempts");
        }

        $h1Title = $jsonResponse['h1_title'];
        $metaTitle = $jsonResponse['meta_title'];
        $metaDescription = str_replace('{{PHONE_LINK}}', domain_phone_display(), $jsonResponse['meta_description']);
        // Replace [Company Name] with actual business name
        $domain = Domain::current() ?? Domain::first();
        $businessName = $domain?->business_name ?? 'Our Company';
        $metaDescription = str_replace('[Company Name]', $businessName, $metaDescription);
        $content = $jsonResponse['content'];
        $faqs = $jsonResponse['faqs'] ?? [];
        $testimonials = $jsonResponse['testimonials'] ?? [];

        $images = $this->getImagesForContent();
        $contentWithImages = $this->embedImagesInContent($content, $images, $serviceType, $city->name);
        $contentCleaned = $this->applyLinkConversions($contentWithImages);
        $contentWithServiceLinks = $this->ensureServiceLinks($contentCleaned, $city);

        $slugPrefix = $domain?->getServiceSlugPrefix() ?? 'service';

        return [
            'slug' => "{$serviceType}-{$slugPrefix}-rental-{$city->slug}",
            'service_type' => $serviceType,
            'h1_title' => $h1Title,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'content' => $contentWithServiceLinks,
            'images' => $images,
            'word_count' => str_word_count(strip_tags($content)),
            'faqs' => array_map(fn ($faq) => [
                'question' => $faq['question'],
                'answer' => $this->ensureServiceLinks($this->applyLinkConversions($faq['answer']), $city),
            ], $faqs),
            'testimonials' => array_map(fn ($t) => [
                'customer_name' => $t['customer_name'],
                'content' => $this->ensureServiceLinks($this->applyLinkConversions($t['content']), $city),
                'rating' => $t['rating'] ?? 5,
            ], $testimonials),
        ];
    }

    protected function getDefaultServicePagePrompt(array $replacements): string
    {
        $prompt = <<<'PROMPT'
You are {business_name}'s dispatch coordinator. You've personally scheduled porta potty deliveries in {city_name}, {state_code} for over a decade — to construction sites along I-45, wedding venues in the suburbs, music festivals downtown, storm-cleanup crews after hurricanes. You know which neighborhoods have permit headaches, which road closures affect delivery in {city_name}, and what customers in this market actually ask about.

A prospect from {city_name} just submitted a request for {service_label}. Write the content for the web page they'll land on while their quote is being prepared. Write it like a detailed, concrete email reply — specific, useful, no marketing fluff. Assume the reader has already seen three competitor sites and is deciding who to call.

Return a VALID JSON object with EXACTLY this structure. ALL fields are MANDATORY. If you return empty or missing fields the response will be rejected.

{
    "h1_title": "H1 heading (max 80 chars). Must mention {service_label} and {city_name}. Lead with value/specificity, not just keywords. Avoid 'Welcome to' / 'The best'.",
    "meta_title": "SEO title tag (50-60 chars). Include primary keyword, {city_name} or {state_code}, and a concrete benefit or hook.",
    "meta_description": "140-160 characters. Specific, includes a reason-to-call (same-day delivery / transparent pricing / no hidden fees) and a CTA. Do NOT pad to hit length.",
    "content": "Markdown. Start with ## heading. Length is whatever actually answers the reader's questions — do not pad. Include at least 3 local details that a stranger to {city_name} wouldn't know (permits, neighborhoods, typical event venues, weather considerations, road/access notes). Include 2-3 {{SERVICE_LINK:...}} internal links where relevant, and at least one {{PHONE_LINK}} CTA.",
    "faqs": [{"question": "...", "answer": "..."}, ...],
    "testimonials": [{"customer_name": "...", "content": "...", "rating": 5}, ...]
}

CONTENT RULES

Voice: matter-of-fact, concrete, no superlatives. Use second person ("you") sparingly. Avoid "nestled", "hassle-free", "look no further", "rest assured", "state-of-the-art" — these are the words AI detectors flag.

Structure (content field):
- ## intro heading — what this page covers, grounded in {city_name}
- ## Who we deliver to in {city_name} — brief list of customer types with one specific local example each (e.g. "road crews working the Loop 610 reconstruction")
- ## What's included in a {service_label} rental — bullet list of what's actually in the unit / service window
- ## Delivery in {city_name} — how long it takes, any local quirks (permits, restricted streets, weather windows)
- ## Pricing — use soft language ("starting rates", "quotes depend on quantity and duration") but acknowledge the reader wants a number; direct them to call or include a price range only if you are explicitly given one
- ## Call to action — one concrete reason to call right now (same-day availability / seasonal booking / limited inventory)

Local anchoring (MANDATORY — at least 3 of these):
- Name a real {city_name} highway, district, or neighborhood
- Reference {state_name}'s regulatory context (state permit rules, OSHA enforcement, seasonal weather)
- Name actual industries or venue types present in {city_name}
- Nearby cities we serve: {nearby_cities_text}
- Generic "in {city_name}" repetition is NOT local anchoring. You need facts a local would recognize.

FAQs (6-10 questions): answer the specific questions someone in {city_name} would Google before calling. Prefer questions that include "{city_name}" or "{state_code}" in the query. Answers: 40-70 words, direct answer first, then detail.

Testimonials (2-3 max): write as illustrative scenarios, not claimed real customers. Use realistic first names + initial. KEEP THEM BELIEVABLE — specific job type, specific week/month, specific thing that went right. No superlatives. If you don't have a real scenario to draw on, omit rather than fabricate.

Phone formatting: {{PHONE_LINK}} ONLY. Never output a literal phone number or anchor tag.

Internal links: use {{SERVICE_LINK:construction}}, {{SERVICE_LINK:wedding}}, {{SERVICE_LINK:event}}, {{SERVICE_LINK:luxury}}, {{SERVICE_LINK:party}}, {{SERVICE_LINK:emergency}}, {{SERVICE_LINK:residential}} — pick 2-3 that relate to the reader's intent.

Do NOT use the words "hassle-free", "look no further", "rest assured", "state-of-the-art", "nestled", "cutting-edge", "your go-to". Avoid "we understand that..." openings. Avoid listing generic benefits ("professionalism, reliability, commitment to excellence").

Output: valid JSON only, no markdown fences, no commentary.
PROMPT;

        return str_replace(array_keys($replacements), array_values($replacements), $prompt);
    }

    protected function getImagesForContent(): array
    {
        if (! $this->imageService) {
            return [];
        }

        $count = rand(3, 5);

        try {
            return $this->imageService->getRandomImagesForContent($count);
        } catch (\Exception $e) {
            Log::warning("Failed to get images: {$e->getMessage()}");

            return [];
        }
    }

    protected function embedImagesInContent(string $content, array $images, string $serviceType = 'general', ?string $cityName = null): string
    {
        if (empty($images)) {
            return $content;
        }

        // Already has domain prefix from ImageService
        $location = $cityName ? " in {$cityName}" : '';
        $imageSection = "\n\n## Our Work\n\n";

        foreach ($images as $image) {
            $altText = ($image['alt'] ?? 'Service').$location;
            $path = $image['path'] ?? '';  // Already has "pottydirect/service-images/..."
            $url = '/storage/'.$path;
            $encodedUrl = str_replace(' ', '%20', $url);
            $imageSection .= "<img src=\"{$encodedUrl}\" alt=\"{$altText}\" width=\"800\" height=\"600\" loading=\"lazy\" />\n";
        }

        if (str_contains(strtolower($content), '## why choose')) {
            $parts = preg_split('/(## why choose)/i', $content, 2);
            if (count($parts) === 3) {
                return $parts[0].$imageSection.$parts[1].$parts[2];
            }
        }

        return $content.$imageSection;
    }

    public function generateStatePageContent(State $state, ?Domain $domain = null): array
    {
        if (! $this->aiService) {
            throw new \RuntimeException('AI service not configured. Please set up MultiAiService to generate content.');
        }

        if ($domain) {
            Domain::setCurrent($domain);
        }

        return $this->generateStateFromAI($state);
    }

    protected function generateStateFromAI(State $state): array
    {
        $domain = Domain::current() ?? Domain::first();
        $primaryKeyword = $domain?->primary_keyword ?? 'service';
        $stateName = $state->name;
        $stateCode = $state->code;
        $cityCount = $state->activeCities()->count();

        $replacements = [
            '{state_name}' => $stateName,
            '{state_code}' => $stateCode,
            '{primary_keyword}' => $primaryKeyword,
            '{city_count}' => $cityCount,
        ];

        $prompt = $this->getDefaultStatePagePrompt($replacements);

        $systemPrompt = 'You are an SEO content writer that MUST generate ALL fields. CRITICAL RULES: 1) h1_title, meta_title, meta_description, content MUST ALL be non-empty strings. 2) Use {{PHONE_LINK}} and {{SERVICE_LINK:type}}. 3) Return valid JSON only. 4) If you fail to provide any required field, the system will reject your response.';

        $maxAttempts = 3;
        $attempt = 0;
        $jsonResponse = null;

        while ($attempt < $maxAttempts) {
            $attempt++;
            $jsonResponse = $this->aiService->generateJsonContent($prompt, $systemPrompt);

            if (! $jsonResponse || ! isset($jsonResponse['content'])) {
                Log::warning("State page generation attempt {$attempt} failed for {$stateName}: Invalid JSON");

                continue;
            }

            // Force validation - all required fields must be non-empty strings
            $requiredFields = ['h1_title', 'meta_title', 'meta_description', 'content'];
            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (empty($jsonResponse[$field]) || ! is_string($jsonResponse[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (empty($missingFields)) {
                break; // Success - all fields present
            }

            Log::warning("State page generation attempt {$attempt} failed for {$stateName}", [
                'missing_fields' => $missingFields,
            ]);

            // Strengthen prompt for retry
            $prompt .= "\n\nWARNING: Previous attempt failed. You MUST provide ALL required fields including: ".implode(', ', $missingFields).'. All fields must be non-empty strings.';
        }

        if ($attempt >= $maxAttempts || ! $jsonResponse || ! isset($jsonResponse['content'])) {
            throw new \RuntimeException("AI failed to generate all required data for state {$stateName} after {$maxAttempts} attempts");
        }

        $content = $jsonResponse['content'];
        $contentCleaned = $this->applyLinkConversions($content);
        $wordCount = str_word_count($content);
        $images = $this->getImagesForContent();

        return [
            'h1_title' => $jsonResponse['h1_title'],
            'meta_title' => $jsonResponse['meta_title'],
            'meta_description' => $jsonResponse['meta_description'],
            'content' => $this->ensureServiceLinks($contentCleaned),
            'word_count' => $wordCount,
            'faqs' => array_map(fn ($faq) => [
                'question' => $faq['question'],
                'answer' => $this->ensureServiceLinks($this->applyLinkConversions($faq['answer'])),
            ], $jsonResponse['faqs'] ?? []),
            'testimonials' => array_map(fn ($t) => [
                'customer_name' => $t['customer_name'] ?? 'Customer',
                'content' => $t['content'] ?? '',
                'rating' => $t['rating'] ?? 5,
            ], $jsonResponse['testimonials'] ?? []),
            'images' => $images,
        ];
    }

    protected function getDefaultStatePagePrompt(array $replacements): string
    {
        $prompt = <<<'PROMPT'
You work for {business_name} and you know {state_name}'s porta potty rental market intimately — which metros have the most demand, what permit rules differ by county, which industries drive volume (construction, energy, ag, tourism), and how the seasons affect delivery schedules. You're writing a state overview page for someone who just landed here and doesn't know yet which of our {city_count} {state_name} cities they need.

Your job: make this page actually useful to a visitor trying to rent in {state_name}, not another SEO template. Write like you're briefing a regional sales manager on the market.

Return a VALID JSON object. ALL fields mandatory:

{
    "h1_title": "H1 heading (max 80 chars). Mention {state_name} and the primary service. Be specific, not generic.",
    "meta_title": "50-60 chars. {state_name} + primary keyword + one benefit.",
    "meta_description": "140-160 chars. Lead with the specific reason someone should read this page: coverage across {state_name}, delivery windows, cities served.",
    "content": "Markdown. Start with ## heading. Length is whatever fits the content naturally — do not pad. Include at least 3 things specific to {state_name}: regulatory context, industry drivers, typical lead times, climate considerations.",
    "faqs": [{"question": "...", "answer": "..."}, ...],
    "testimonials": []
}

CONTENT RULES

Voice: matter-of-fact, factual, avoids hype. Avoid "nestled", "hassle-free", "look no further", "state-of-the-art". Avoid "we understand that..." openings. No sentences that would work verbatim for any other state.

Structure:
- ## intro — what {state_name} rental demand looks like (volume, seasonality, drivers)
- ## Cities we serve in {state_name} — reference {city_count} cities, mention 5-8 largest/most-served by name if possible
- ## What {state_name} customers typically need — honest breakdown by segment (construction, events, emergency)
- ## Delivery windows in {state_name} — typical lead times, seasonal caveats (winter for northern states, storm season for coastal, etc.)
- ## Getting a quote — short, CTA to {{PHONE_LINK}}

State-specific anchoring (required):
- Reference {state_name}'s economy/industries
- Reference regulatory or climate factors specific to {state_name}
- Reference major {state_name} metros / regions

FAQs (6-8): focus on questions a {state_name} customer would actually ask. Each FAQ MUST reference {state_name} or {state_code}. Answer 40-70 words, specific.

No testimonials on state pages.

Pricing: soft language only. No hard numbers unless explicitly provided.

Phone: {{PHONE_LINK}} only. Service links: {{SERVICE_LINK:type}}.

Output: valid JSON only.
PROMPT;

        return str_replace(array_keys($replacements), array_values($replacements), $prompt);
    }

    public function generateFaqs(City $city, ?string $serviceType = null): array
    {
        if ($this->aiService && $serviceType) {
            $domain = Domain::current() ?? Domain::first();
            $state = $city->state;
            $serviceLabel = $domain ? $domain->getServiceTypeLabel($serviceType) : 'Service';

            $prompt = "You're a dispatch coordinator for {$serviceLabel} in {$city->name}, {$state->code}. Write 5 FAQs a real local customer would ask before calling for a quote. Each question should be specific to {$city->name} or the service. Answers: 40-70 words, direct answer first. No marketing fluff. No superlatives. Return JSON: [{\"question\": \"...\", \"answer\": \"...\"}]";

            $json = $this->aiService->generateJsonContent($prompt, 'Return valid JSON array. No code fences.');

            return $json ?? [];
        }

        return [];
    }

    public function generateTestimonials(City $city, ?string $serviceType = null): array
    {
        if ($this->aiService && $serviceType) {
            $domain = Domain::current() ?? Domain::first();
            $state = $city->state;
            $serviceLabel = $domain ? $domain->getServiceTypeLabel($serviceType) : 'Service';

            // NOTE: These are illustrative scenarios — never marked up as Review schema.
            // Intended for on-page display only. If real customer reviews become available,
            // source them from Google Business Profile or a verified review platform instead.
            $prompt = "Write 3 illustrative customer scenarios (NOT fabricated reviews) for {$serviceLabel} in {$city->name}, {$state->code}. Each scenario: realistic first name + last initial, 1-2 sentences describing a concrete job (type of event/site, what went right, quirks). No superlatives. Specific and plausible over glowing. Return JSON: [{\"customer_name\": \"...\", \"content\": \"...\", \"rating\": 5}]";

            $json = $this->aiService->generateJsonContent($prompt, 'Return valid JSON array.');

            return $json ?? [];
        }

        return [];
    }

    protected function applyLinkConversions(string $text): string
    {
        // Keep {SERVICE_LINK:type} format - will be converted to actual links in ensureServiceLinks()
        $text = preg_replace('/\{\{PHONE_LINK\}\}/', '{{PHONE_LINK}}', $text);

        // Replace [Company Name] with actual business name
        $domain = Domain::current() ?? Domain::first();
        $businessName = $domain?->business_name ?? 'Our Company';
        $text = str_replace('[Company Name]', $businessName, $text);

        return $this->ensureCorrectPhoneLinks($text);
    }

    protected function ensureCorrectPhoneLinks(string $content): string
    {
        $phoneDisplay = domain_phone_display();
        $phoneRaw = domain_phone_raw();
        $styledLink = "<a href=\"tel:{$phoneRaw}\" class=\"text-blue-600 font-semibold hover:underline\">{$phoneDisplay}</a>";

        $content = str_replace('{{PHONE_LINK}}', $styledLink, $content);

        // Replace [Company Name] with actual business name
        $domain = Domain::current() ?? Domain::first();
        $businessName = $domain?->business_name ?? 'Our Company';
        $content = str_replace('[Company Name]', $businessName, $content);

        return $content;
    }

    public function ensureServiceLinks(string $content, ?City $city = null): string
    {
        $domain = Domain::current() ?? Domain::first();
        $serviceTypes = $domain?->getServiceTypes() ?? ['general'];
        $domainUrl = $domain?->domain ? "https://{$domain->domain}" : url('/');

        foreach ($serviceTypes as $type) {
            $pattern = '/\{\{SERVICE_LINK:'.$type.'\}\}/i';
            if (preg_match($pattern, $content)) {
                $label = $domain?->getServiceTypeLabel($type) ?? ucfirst($type);
                $url = "{$domainUrl}/services#{$type}";
                $content = preg_replace($pattern, "<a href=\"{$url}\" class=\"text-blue-600 font-semold hover:underline\">{$label}</a>", $content);
            }
        }

        // Handle contact link
        if (str_contains($content, '{{SERVICE_LINK:contact}}')) {
            $content = str_replace('{{SERVICE_LINK:contact}}', '<a href="/about" class="text-blue-600 font-semibold hover:underline">Contact Us</a>', $content);
        }

        return $content;
    }

    protected function getNearbyCities(City $city): array
    {
        $nearby = $city->nearby_cities ?? [];
        if (empty($nearby)) {
            return [];
        }

        if (is_string($nearby)) {
            $nearby = json_decode($nearby, true) ?? [];
        }

        return array_slice(array_filter((array) $nearby), 0, 5);
    }

    public function getStatePageContent(State $state): array
    {
        $domain = Domain::current() ?? Domain::first();
        $cacheKey = "state_content_{$state->id}_".($domain?->id ?? 'default');

        return Cache::remember($cacheKey, 86400, function () use ($state, $domain) {
            $domainState = DomainState::where('domain_id', $domain?->id)
                ->where('state_id', $state->id)
                ->first();

            if ($domainState && $domainState->content) {
                $faqs = Faq::where('domain_id', $domain?->id)
                    ->where('state_id', $state->id)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();

                $testimonials = Testimonial::where('domain_id', $domain?->id)
                    ->where('state_id', $state->id)
                    ->where('is_active', true)
                    ->get();

                return [
                    'h1_title' => $domainState->h1_title,
                    'meta_title' => $domainState->meta_title,
                    'meta_description' => $domainState->meta_description,
                    'content' => $domainState->content,
                    'word_count' => $domainState->word_count,
                    'images' => $domainState->images,
                    'faqs' => $faqs->map(fn ($faq) => [
                        'question' => $faq->question,
                        'answer' => $faq->answer,
                    ])->toArray(),
                    'testimonials' => $testimonials->map(fn ($t) => [
                        'customer_name' => $t->customer_name,
                        'content' => $t->content,
                        'rating' => $t->rating,
                    ])->toArray(),
                ];
            }

            return [];
        });
    }

    public function generateBlogPostContent(BlogCategory $category, ?City $city = null, int $iteration = 1): array
    {
        if (! $this->aiService) {
            return [
                'success' => false,
                'error' => 'AI service not configured. Please check MultiAiService setup.',
            ];
        }

        $domain = Domain::current() ?? Domain::first();
        $state = $city?->state;
        $businessName = $domain?->business_name ?? 'Our Company';
        $websiteUrl = $domain?->website_url ?? 'https://example.com';
        $primaryKeyword = $domain?->primary_keyword ?? 'service rental';
        $primaryService = $domain?->primary_service;
        $secondaryKeywords = $domain?->getSecondaryKeywordsFormatted() ?? '';
        $serviceTypes = $domain?->getServiceTypes() ?? ['construction', 'event', 'wedding', 'luxury', 'party', 'emergency', 'residential'];
        $nearbyCitiesList = $city ? $this->getNearbyCities($city) : [];
        $nearbyCitiesText = implode(', ', $nearbyCitiesList);

        $locationContext = $city ? "{$city->name}" : 'your area';
        $cityName = $city?->name ?? '';
        $stateCode = $state?->code ?? '';
        $stateName = $state?->name ?? '';

        if ($state) {
            $locationContext .= ", {$state->code}";
        }

        $serviceTypesText = implode(', ', array_map(fn ($t) => ucfirst($t), $serviceTypes));
        $imagePath = @$this->imageService->getRandomImagesForContent(1)[0]['path'] ?? '';

        // Add variation based on iteration
        $variationAngle = match ($iteration) {
            1 => 'Focus on cost-effective solutions and budget-friendly options',
            2 => 'Focus on convenience, speed of delivery, and emergency services',
            3 => 'Focus on luxury options, events, and premium customer experience',
            default => 'Provide a comprehensive overview with practical tips and real-world examples'
        };

        $prompt = <<<PROMPT
You're writing a blog post for {$businessName}, a {$primaryService} company serving {$cityName}, {$stateName} and nearby cities. The audience: people actually Googling a question about {$primaryService} — homeowners planning a backyard wedding, construction PMs staffing up, event coordinators working a festival. They want practical, specific answers — not a generic "Ultimate Guide" that reads like every other blog.

CONTEXT:
- Category: {$category->name}
- Category Description: {$category->description}
- Location focus: {$locationContext}
- Business: {$businessName}
- Website: {$websiteUrl}
- Primary Keyword: {$primaryKeyword}
- Secondary Keywords: {$secondaryKeywords}
- Nearby Cities: {$nearbyCitiesText}
- Service Types: {$serviceTypesText}
- Content Angle: {$variationAngle}

This is post #{$iteration} for this location. Use a different angle from prior posts — different sub-topic, different reader persona, different structure.

CONTENT RULES

Voice: sound like a trade-publication writer, not a marketer. Skip hype. No "Ultimate Guide", "Everything You Need to Know", "The Best...", "Hassle-free", "State-of-the-art". Avoid "we understand that..." openings. Avoid listing vague benefits ("professionalism, reliability, commitment to excellence").

Length: whatever the topic actually needs. Some questions deserve 800 tight words. Some deserve 3000. Do NOT pad to hit a word count. If you run out of substance, stop.

Structure:
- H1 title that tells the reader what they'll learn — specific, not clickbait
- Opening paragraph: answer the core question in the first 3 sentences (featured-snippet pattern). Then elaborate.
- 3-6 H2 sections, each answering a distinct sub-question. Use H3 only for genuine sub-sub-sections.
- Bullet lists where they fit, prose where they don't. Don't bullet-list everything.
- End with a single specific CTA using {{PHONE_LINK}}. Not 4 CTAs.

Specificity (required):
- At least 3 concrete facts, numbers, or rules a reader can't guess (OSHA ratios, typical lead times, permit processes, common mistakes)
- Reference {$cityName} only when the content genuinely requires local context — do not sprinkle it unnaturally
- Link to 2-3 of our service pages using {{SERVICE_LINK:type}} where relevant

Pricing: if you give a range, only use the one I've provided in context. Otherwise use soft pricing language and redirect to a quote.

Do NOT use: "nestled", "hassle-free", "state-of-the-art", "look no further", "cutting-edge", "your go-to", "rest assured", "in today's fast-paced world", "peace of mind".

OUTPUT (valid JSON only, no code fences):
{
    "title": "H1 (max 80 chars) — specific and curiosity-driven",
    "slug": "url-friendly-slug",
    "excerpt": "2-3 sentences, 200-300 chars. State what the post covers and who it's for. No CTA, no phone placeholder.",
    "content": "Markdown. Length as needed, not padded. Includes one {{PHONE_LINK}} near the end and 2-3 {{SERVICE_LINK:type}} links.",
    "meta_title": "50-60 chars",
    "meta_description": "140-160 chars, concrete",
    "focus_keyword": "primary focus keyword",
    "secondary_keywords": ["keyword1", "keyword2", "keyword3"]
}
PROMPT;

        try {
            $maxAttempts = 3;
            $attempt = 0;
            $lastError = null;

            while ($attempt < $maxAttempts) {
                $attempt++;
                $data = $this->aiService->generateJsonContent($prompt);

                if ($data === null) {
                    $lastError = 'AI returned empty or invalid response. Please check API configuration.';
                    Log::warning("Blog post generation attempt {$attempt} failed: AI returned null");

                    continue;
                }

                // Validate required fields
                $requiredFields = ['title', 'content', 'meta_title', 'meta_description', 'excerpt'];
                $missingFields = [];
                foreach ($requiredFields as $field) {
                    if (empty($data[$field]) || ! is_string($data[$field])) {
                        $missingFields[] = $field;
                    }
                }

                if (! empty($missingFields)) {
                    $lastError = 'AI failed to generate required fields: '.implode(', ', $missingFields);
                    Log::warning("Blog post generation attempt {$attempt} failed", [
                        'missing_fields' => $missingFields,
                        'data_keys' => array_keys($data),
                    ]);

                    // Strengthen prompt for retry
                    $prompt .= "\n\nWARNING: Previous attempt failed. You MUST provide ALL fields including: ".implode(', ', $missingFields).'. All fields must be non-empty strings.';

                    continue;
                }

                // Success - all required fields present
                break;
            }

            if ($attempt >= $maxAttempts) {
                return [
                    'success' => false,
                    'error' => $lastError ?? 'AI failed to generate all required data after {$maxAttempts} attempts.',
                ];
            }

            $excerpt = $this->ensureServiceLinks($data['excerpt'], $city);
            $content = $this->applyLinkConversions($this->ensureServiceLinks($data['content'], $city));

            return [
                'success' => true,
                'title' => $data['title'] ?? '',
                'slug' => $data['slug'] ?? '',
                'excerpt' => $excerpt,
                'content' => $content,
                'meta_title' => $data['meta_title'] ?? '',
                'meta_description' => $data['meta_description'] ?? '',
                'focus_keyword' => $data['focus_keyword'] ?? '',
                'secondary_keywords' => $data['secondary_keywords'] ?? [],
                'featured_image' => $imagePath,
            ];
        } catch (\Exception $e) {
            Log::error('ContentGenerator: Blog post generation failed', [
                'category' => $category->id,
                'city' => $city?->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function generateExcerpt(BlogPost $post): string
    {
        if (! $this->aiService) {
            return '';
        }

        $city = $post->city;
        $category = $post->category;

        $prompt = <<<PROMPT
You are an SEO expert writing a unique blog excerpt.

Context:
- Blog Title: {$post->title}
- Category: {$category?->name}
- City: {$city?->name}
- State: {$city?->state?->name}
- Content Preview: {$this->getContentPreview($post->content)}

Task: Write a UNIQUE SEO-optimized excerpt (2-3 sentences, 250-350 characters).

Rules:
- Include the primary keyword naturally
- Mention a specific benefit or pain point
- Add local context ({$city?->name})
- MUST be different from the first paragraph of content
- Write like a human, not AI
- No fluff or repetitive phrasing
- No pricing numbers
- Do NOT include {{PHONE_LINK}}

Output: Return ONLY the excerpt text, nothing else.
PROMPT;

        try {
            $excerpt = $this->aiService->generateContent($prompt);

            if (! $excerpt || strlen($excerpt) < 100) {
                Log::warning('AI generated excerpt too short or empty', [
                    'post_id' => $post->id,
                    'excerpt' => $excerpt,
                ]);

                return '';
            }

            return $this->ensureServiceLinks($excerpt, $city);
        } catch (\Exception $e) {
            Log::error('Excerpt generation failed', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);

            return '';
        }
    }

    protected function getContentPreview(string $content): string
    {
        return Str::limit(strip_tags($content), 500);
    }
}
