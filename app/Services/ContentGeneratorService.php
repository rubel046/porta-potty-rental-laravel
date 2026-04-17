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

class ContentGeneratorService
{
    protected ?MultiAiService $aiService = null;

    protected ?ImageService $imageService = null;

    public function __construct()
    {
        if (app()->bound(MultiAiService::class)) {
            $this->aiService = app(MultiAiService::class);
        }

        if (app()->bound(ImageService::class)) {
            $this->imageService = app(ImageService::class);
        }
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
        $serviceTypesList = $domain?->getServiceTypes() ?? ['standard', 'deluxe', 'ada', 'luxury'];
        $serviceTypesText = implode(', ', array_map(fn ($t) => ucfirst($t), $serviceTypesList));

        $nearbyCitiesList = $this->getNearbyCities($city);
        $nearbyCitiesText = implode(', ', $nearbyCitiesList);

        $prompt = <<<PROMPT
Act like a senior SEO strategist, local SEO expert, and high-conversion content writer with 40+ years of experience ranking USA service-based websites on Google (especially local lead generation sites for {$primaryKeyword}). You understand Google's E-E-A-T requirements and always prioritize Experience, Expertise, Authoritativeness, and Trustworthiness.

Your goal is to generate highly detailed, 100% unique, human-like, SEO-optimized content that ranks fast and drives phone call leads for {$businessName} in {$city->name}, {$state->code}.

TASK: Return a VALID JSON object with EXACTLY this structure:

DO NOT include FAQs in the main content - they will be generated separately.
DO NOT include testimonials in the main content - they will be generated separately.
{
    "h1_title": "An SEO-optimized H1 title (max 80 chars) - must include service + city",
    "meta_title": "SEO title tag (50-60 chars) - include keyword, city, state + CTA/benefit",
    "meta_description": "Meta description (120-160 chars) - compelling, includes service, city, urgency + CTA",
    "content": "Write 2000-3000 words of HIGH-CONVERTING SEO content in markdown format. Start with ## heading. Include bullet points, local keywords, pricing hint, and strong CTA. DO NOT include FAQs in content. STRICT WORD COUNT: 2000-3000 words minimum.",
    "faqs": [{"question": "...", "answer": "..."}, ...],
    "testimonials": [{"customer_name": "...", "content": "...", "rating": 5}, ...]
}

Step-by-step requirements:

1) Keyword Optimization:
- Use primary keyword: {$serviceLabel} {$city->name}
- Add 3–5 secondary keywords ({$secondaryKeywords})
- Include long-tail keywords (same-day delivery, emergency rental, near me, etc.)
- Include geo modifiers naturally: "near me", "in {$city->name}", "local {$city->name}"
- Maintain natural keyword density (1–2%)
- Avoid keyword stuffing

2) Local SEO Optimization:
- Mention {$city->name} and {$state->code} naturally 10–20 times
- Include geo phrases: "near me", "local {$city->name}", "serving {$city->name} and nearby areas"
- Add local intent phrases (same-day delivery, fast service in {$city->name})
- Make content feel locally relevant (not generic)

3) Content Structure (MANDATORY inside "content"):
- Start with ## heading (include {$city->name} + primary keyword)
- Introduction (local + benefit-driven, include E-E-A-T signals like "serving {$city->name} for 20+ years")
- H2: Why Choose {$businessName} (trust signals: experience, licensing, local presence)
- H2: Our Services (with H3 for each type: {$serviceTypesText})
- H2: Use Cases & Applications (construction sites, events, weddings, emergency, residential)
- H2: Serving {$city->name} & Surrounding Areas (include nearby cities: {$nearbyCitiesText})
- H2: Call to Action section
- INTERNAL LINKING: Naturally link to other service types using placeholders: {{SERVICE_LINK:construction}}, {{SERVICE_LINK:wedding}}, {{SERVICE_LINK:event}}, {{SERVICE_LINK:luxury}}, {{SERVICE_LINK:party}}, {{SERVICE_LINK:emergency}}, {{SERVICE_LINK:residential}}
- Voice search optimization: Include conversational phrases people speak (e.g., "How much does a... cost", "Where can I rent...")

4) Featured Snippet Optimization (for FAQ answers):
- Structure answers for Google Position #0
- Use clear ### H3 headings matching the question
- Answers: 40-60 words, concise, direct answer first
- Include "how to" and "what is" style questions
- Use bullet lists for step-by-step content
- Featured snippet placeholders: {{SERVICE_LINK:construction}}, {{SERVICE_LINK:wedding}}, etc.

5) FAQs (required - 8-15 questions):
- Generate unique FAQ questions for {$serviceLabel} in {$city->name}, {$state->code}
- Cover: pricing, delivery, duration, unit types, booking process, permits, accessibility
- Include voice search questions: "How much does... cost in {$city->name}?", "Where to rent... near {$city->name}?"
- Use conversational/long-tail keywords
- Answers: concise (40-60 words), include local context

7) Testimonials (required - 2-4 testimonials):
- Generate realistic customer testimonials for {$serviceLabel} in {$city->name}, {$state->code}
- Include: customer_name (realistic first name or initials), content (1-2 sentences), rating (4-5 stars)
- Vary scenarios: construction supervisor, wedding planner, event organizer, homeowner, project manager
- Include E-E-A-T in testimonials: "they've been serving {$city->name} for years", "professional local company"
- Link other services: {{SERVICE_LINK:construction}}, {{SERVICE_LINK:wedding}}, {{SERVICE_LINK:event}}, etc.

8) E-E-A-T & Trust Signals:
- Emphasize {$businessName}'s local experience ({$city->name} area)
- Mention years in business, local team
- Include trust badges, licensing, certifications
- Add "serving {$city->name} and surrounding areas for X+ years"
- Include local customer references

9) Conversion Optimization:
- Include phone CTA at least 3–5 times using {{PHONE_LINK}}
- Add urgency: same-day delivery, fast setup, limited availability
- Add trust signals: clean & sanitized units, reliable service, local experts, affordable pricing
- Focus on benefits over features

10) PHONE NUMBER FORMATTING - CRITICAL (MUST FOLLOW):
- When including phone number in content, FAQs, or testimonials, use EXACTLY this placeholder: {{PHONE_LINK}}
- DO NOT use any other phone number format
- Example CORRECT: "Call us at {{PHONE_LINK}} for a quote"
- Example WRONG: "Call us at (888) 555-0199 for a quote"
- Example WRONG: "Call us at <a href="tel:...">...</a> for a quote"
- FAILURE TO USE THE PLACEHOLDER WILL RESULT IN INCORRECT OUTPUT

11) Pricing Rule (IMPORTANT):
- DO NOT include any specific price numbers
- Use soft pricing language:
  - "affordable pricing"
  - "competitive rates"
  - "budget-friendly options"
  - "custom quotes available"

12) Writing Style:
- 100% human-like (no robotic tone)
- Conversational, persuasive, easy to read (Grade 6-8)
- Avoid repeating patterns across outputs
- Make each section feel natural and helpful
- Include 3-5 internal links to other service types naturally throughout content

13) SEO Constraints:
- h1_title must be different from meta_title
- meta_title ≤60 characters
- meta_description ≤160 characters
- Use power words (fast, affordable, reliable, same-day)

11) Strict Output Rules:
- Return ONLY valid JSON
- Do NOT add explanations or markdown outside JSON
- Do NOT add extra fields
- Ensure all fields are filled
- Ensure JSON is properly formatted

Self-check before output:
- Content is 2000+ words
- No pricing numbers used
- Keywords included naturally
- Strong CTAs present
- City/state clearly present
- Content is unique and conversion-focused
- 8-15 FAQs included
- 2-4 Testimonials included
- 3-5 internal links included
- ALL phone numbers use ONLY the placeholder: {{PHONE_LINK}}
- ALL service links use ONLY placeholders: {{SERVICE_LINK:construction}}, {{SERVICE_LINK:wedding}}, etc.

IMPORTANT FINAL CHECK: Verify every single phone number uses ONLY {{PHONE_LINK}} and service links use ONLY {{SERVICE_LINK:service-type}}

Take a deep breath and work on this problem step-by-step.
PROMPT;

        $systemPrompt = 'You are an SEO content writer. Phone numbers: ONLY use {{PHONE_LINK}}. Service links: ONLY use {{SERVICE_LINK:service-type}}. Never output actual phone numbers or URLs. Always return valid JSON.';

        $jsonResponse = $this->aiService->generateJsonContent($prompt, $systemPrompt);

        if (! $jsonResponse || ! isset($jsonResponse['content'])) {
            throw new \RuntimeException("AI JSON generation failed for {$city->name} ({$serviceType})");
        }

        $h1Title = $jsonResponse['h1_title'] ?? "{$serviceLabel} in {$city->name}, {$state->code}";
        $metaTitle = $jsonResponse['meta_title'] ?? "{$serviceLabel} in {$city->name}, {$state->code}";
        $metaDescription = $jsonResponse['meta_description'] ?? "{$serviceLabel} in {$city->name}, {$state->code}. Call for quote!";
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

    public function generateStatePageContent(State $state): array
    {
        if (! $this->aiService) {
            throw new \RuntimeException('AI service not configured. Please set up MultiAiService to generate content.');
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

        $prompt = <<<PROMPT
Act like a senior SEO strategist with 40+ years of experience ranking USA service-based websites on Google for {$primaryKeyword}.

Generate a comprehensive state landing page for {$primaryKeyword} in {$stateName}, {$stateCode}.

Return VALID JSON:
{
    "h1_title": "SEO H1 title (max 80 chars)",
    "meta_title": "Meta title (50-60 chars)",
    "meta_description": "Meta description (120-160 chars)",
    "content": "1000+ words markdown content. STRICT WORD COUNT: minimum 1000 words.",
    "faqs": [{"question": "...", "answer": "..."}, ...],
    "testimonials": [{"customer_name": "...", "content": "...", "rating": 5}, ...]
}

Requirements:
- Focus on {$primaryKeyword} service in {$stateName}, {$stateCode}
- MUST include {$stateName} specifically in FAQs (e.g., "How much does {$primaryKeyword} cost in {$stateName}?")
- Mention {$cityCount} cities we serve throughout {$stateName}
- Each FAQ must be UNIQUE and specific to {$stateName} - do NOT reuse generic FAQs
- Use {{PHONE_LINK}} for phone numbers
- Use {{SERVICE_LINK:type}} for internal links
- No pricing numbers - use soft pricing language
- Include 3-5 unique FAQs about {$stateName} specifically
- Include 2-3 realistic testimonials from {$stateName} customers
PROMPT;

        $systemPrompt = 'You are an SEO writer. Use {{PHONE_LINK}} and {{SERVICE_LINK:type}}. Return valid JSON only.';

        $jsonResponse = $this->aiService->generateJsonContent($prompt, $systemPrompt);

        if (! $jsonResponse || ! isset($jsonResponse['content'])) {
            throw new \RuntimeException("AI generation failed for state {$stateName}");
        }

        $content = $jsonResponse['content'] ?? '';
        $contentCleaned = $this->applyLinkConversions($content);
        $wordCount = str_word_count($content);
        $images = $this->getImagesForContent();

        return [
            'h1_title' => $jsonResponse['h1_title'] ?? "{$primaryKeyword} in {$stateName}, {$stateCode}",
            'meta_title' => $jsonResponse['meta_title'] ?? "{$primaryKeyword} {$stateName} | Fast Delivery",
            'meta_description' => $jsonResponse['meta_description'] ?? "{$primaryKeyword} in {$stateName}. Call now!",
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

    public function generateFaqs(City $city, ?string $serviceType = null): array
    {
        if ($this->aiService && $serviceType) {
            $domain = Domain::current() ?? Domain::first();
            $state = $city->state;
            $serviceLabel = $domain ? $domain->getServiceTypeLabel($serviceType) : 'Service';

            $prompt = "Generate 5 FAQs for {$serviceLabel} in {$city->name}, {$state->code}. Return JSON: [{\"question\": \"...\", \"answer\": \"...\"}]";

            $json = $this->aiService->generateJsonContent($prompt, 'Return valid JSON array.');

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

            $prompt = "Generate 3 testimonials for {$serviceLabel} in {$city->name}, {$state->code}. Return JSON: [{\"customer_name\": \"...\", \"content\": \"...\", \"rating\": 5}]";

            $json = $this->aiService->generateJsonContent($prompt, 'Return valid JSON array.');

            return $json ?? [];
        }

        return [];
    }

    protected function applyLinkConversions(string $text): string
    {
        $text = preg_replace('/\{\{PHONE_LINK\}\}/', '{{PHONE_LINK}}', $text);
        $text = preg_replace('/\{\{SERVICE_LINK:(\w+)\}\}/', '{{SERVICE_LINK:$1}}', $text);

        return $this->ensureCorrectPhoneLinks($text);
    }

    protected function ensureCorrectPhoneLinks(string $content): string
    {
        $phoneDisplay = domain_phone_display();
        $phoneRaw = domain_phone_raw();
        $styledLink = "<a href=\"tel:{$phoneRaw}\" class=\"text-blue-600 font-semibold hover:underline\">{$phoneDisplay}</a>";

        return str_replace('{{PHONE_LINK}}', $styledLink, $content);
    }

    public function ensureServiceLinks(string $content, ?City $city = null): string
    {
        $serviceTypes = [
            'general',
            'construction',
            'wedding',
            'event',
            'luxury',
            'party',
            'emergency',
            'residential',
            'standard',
            'deluxe',
            'ada',
            'shower',
            'contact',
            'porta_potty_rental',
            'dumpster_rental',
            'toilet_trailer_rental',
        ];
        $domain = Domain::current() ?? Domain::first();
        $slugPrefix = $domain?->getServiceSlugPrefix() ?? 'service';

        foreach ($serviceTypes as $type) {
            $pattern = '/\{\{SERVICE_LINK:'.$type.'\}\}/i';
            if (preg_match($pattern, $content)) {
                if ($type === 'contact') {
                    $slug = '/contact';
                    $label = 'Contact Us';
                } elseif (in_array($type, ['porta_potty_rental', 'dumpster_rental', 'toilet_trailer_rental'])) {
                    $baseType = str_replace('_rental', '', $type);
                    $slug = $city ? "{$baseType}-{$slugPrefix}-rental-{$city->slug}" : "/{$baseType}-service";
                    $label = ucwords(str_replace('_', ' ', $type));
                } else {
                    $slug = $city ? "{$type}-{$slugPrefix}-rental-{$city->slug}" : "/{$type}-service";
                    $label = ucfirst($type).' Service';
                }
                $content = preg_replace($pattern, "<a href=\"{$slug}\" class=\"text-blue-600 font-semibold hover:underline\">{$label}</a>", $content);
            }
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
        $primaryKeyword = $domain?->primary_keyword ?? 'porta potty rental';
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
Act like a senior SEO strategist, content marketing expert, and local lead generation specialist with 40+ years of experience ranking USA service-based blogs (especially {$primaryService} and local services).

Your goal is to generate a HIGH-QUALITY, 100% unique, human-like, SEO-optimized blog post that ranks on Google, attracts USA traffic, and converts visitors into phone call leads for {$websiteUrl}.

Task: Create a long-form blog post (2000–3000 words) targeting low-competition, high-intent keywords in the {$primaryService} niche. with a strong emphasis on local relevance and timely content.

CONTEXT:
- Category: {$category->name}
- Category Description: {$category->description}
- Location: {$locationContext}
- Business: {$businessName}
- Website: {$websiteUrl}
- Primary Keyword: {$primaryKeyword}
- Secondary Keywords: {$secondaryKeywords}
- Nearby Cities: {$nearbyCitiesText}
- Service Types: {$serviceTypesText}
- Content Angle #{$iteration}: {$variationAngle}

Special Instruction (HIGH PRIORITY):
- If there are any recent events, local news, seasonal trends, emergencies, construction booms, festivals, or city-specific developments in {$cityName} or surrounding areas, you MUST prioritize incorporating them into the content.
- Align the blog angle with current affairs when relevant (e.g., events needing rentals, disaster response, local regulations, infrastructure projects).
- This content must feel timely, locally aware, and contextually relevant.

IMPORTANT: This is post #{$iteration} for this location. Generate UNIQUE content different from previous posts. Focus on a different angle, different keywords, and different structure than typical posts.

Requirements:
1) Keyword Research (MANDATORY):
- Identify 1 primary keyword (low competition + good search volume)
- Identify 5–10 secondary + LSI keywords
- Include long-tail keywords (e.g., "same day {$primaryService} near me")
- Focus on USA search intent only

2) SEO Optimization:
- Use primary keyword in H1, first paragraph, and throughout naturally (1–2%)
- Use secondary keywords across H2/H3 sections
- Optimize for featured snippets (clear answers, bullet points)
- Add internal linking suggestions naturally

3) Content Structure:
- H1: SEO-optimized blog title (with keyword + benefit)
- Introduction (hook + problem + solution)
- Multiple H2 sections (informational + intent-driven)
- H3 subsections for depth
- Bullet points for readability
- Real-world use cases (construction, events, weddings, emergency)

4) Local SEO:
- Include {$cityName} naturally throughout
- Mention {$stateCode} and {$stateName} context
- Include nearby cities: {$nearbyCitiesText}
- Add local intent phrases

5) Conversion Optimization:
- Include strong CTAs throughout
- Use phone number: {{PHONE_LINK}}
- Add trust signals (clean units, fast delivery, local experts)
- Encourage urgency (same-day delivery, limited availability)

6) Writing Style:
- 100% human-like, conversational, helpful
- Simple English (Grade 6–8 readability)
- Avoid robotic or repetitive phrasing
- Make content informative + actionable

7) Pricing Rule:
- DO NOT include any exact price numbers
- Use soft language: "affordable pricing", "competitive rates", "custom quotes"

8) Output Format:
Return a VALID JSON object with EXACTLY this structure:

{
    "title": "SEO-optimized H1 title (max 80 chars)",
    "slug": "url-friendly-slug",
    "excerpt": "Write a compelling blog excerpt (250-350 WORDS) that summarizes the blog post content. Include key benefits, local context, and a CTA. Do NOT include {{PHONE_LINK}} in excerpt.",
    "content": "Write 2000-3000 words of HIGH-QUALITY SEO blog content in markdown format. Include proper heading hierarchy (## H2, ### H3), bullet points, and structured content. Include CTAs with phone number {{PHONE_LINK}}.",
    "meta_title": "SEO title (50-60 chars)",
    "meta_description": "Meta description (120-160 chars)",
    "focus_keyword": "primary focus keyword",
    "secondary_keywords": ["keyword1", "keyword2", "keyword3"],
}

Constraints:
- Focus ONLY on USA audience
- Content must be 100% unique
- Avoid fluff — provide real value
- Optimize for ranking + conversions
- Do NOT output anything outside the defined JSON structure
- Do NOT use markdown code fences

Self-check before output:
- [ ] Keyword strategy applied correctly
- [ ] Content is 2000+ words
- [ ] CTAs included with phone number {{PHONE_LINK}}
- [ ] Local SEO optimized
- [ ] JSON is complete and valid
PROMPT;

        try {
            $data = $this->aiService->generateJsonContent($prompt);

            if ($data === null) {
                return [
                    'success' => false,
                    'error' => 'AI returned empty or invalid response. Please check API configuration.',
                ];
            }

            if (! isset($data['content'])) {
                Log::error('ContentGenerator: Missing content field', [
                    'data_keys' => $data ? array_keys($data) : [],
                ]);

                return [
                    'success' => false,
                    'error' => 'AI response missing content field.',
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
}
