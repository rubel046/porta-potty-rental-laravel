<?php

namespace App\Services;

use App\Models\City;
use App\Models\Domain;
use App\Models\State;
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
    "content": "Write 2000-2500 words of HIGH-CONVERTING SEO content in markdown format. Start with ## heading. Include bullet points, local keywords, pricing hint, and strong CTA. DO NOT include FAQs in content.",
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

        $location = $cityName ? " in {$cityName}" : '';
        $imageSection = "\n\n## Our Work\n\n";

        foreach ($images as $image) {
            $altText = ($image['alt'] ?? 'Service').$location;
            $path = $image['path'] ?? '';
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
    "content": "1500-2000 words markdown content",
    "faqs": [{"question": "...", "answer": "..."}, ...]
}

Requirements:
- Focus on {$primaryKeyword} service in {$stateName}
- Mention {$cityCount} cities we serve
- Include service types and benefits
- Use {{PHONE_LINK}} for phone numbers
- Use {{SERVICE_LINK:type}} for internal links
- No pricing numbers - use soft pricing language
PROMPT;

        $systemPrompt = 'You are an SEO writer. Use {{PHONE_LINK}} and {{SERVICE_LINK:type}}. Return valid JSON only.';

        $jsonResponse = $this->aiService->generateJsonContent($prompt, $systemPrompt);

        if (! $jsonResponse || ! isset($jsonResponse['content'])) {
            throw new \RuntimeException("AI generation failed for state {$stateName}");
        }

        $content = $jsonResponse['content'] ?? '';
        $contentCleaned = $this->applyLinkConversions($content);
        $wordCount = str_word_count($content);

        return [
            'h1_title' => $jsonResponse['h1_title'] ?? "{$primaryKeyword} in {$stateName}, {$stateCode}",
            'meta_title' => $jsonResponse['meta_title'] ?? "{$primaryKeyword} {$stateName} | Fast Delivery",
            'meta_description' => $jsonResponse['meta_description'] ?? "{$primaryKeyword} in {$stateName}. Call now!",
            'content' => $contentCleaned,
            'word_count' => $wordCount,
            'faqs' => array_map(fn ($faq) => [
                'question' => $faq['question'],
                'answer' => $this->applyLinkConversions($faq['answer']),
            ], $jsonResponse['faqs'] ?? []),
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

        $label = $serviceType ? ucfirst($serviceType) : 'Rental';

        return [
            ['question' => "How much does {$label} cost in {$city->name}?", 'answer' => 'Pricing varies. Call for a free quote tailored to your needs.'],
            ['question' => 'Do you offer same-day delivery?', 'answer' => 'Yes! Call before 2 PM for same-day service (subject to availability).'],
            ['question' => 'How often are units serviced?', 'answer' => 'Weekly cleaning, pumping, sanitizing included. Daily service available for events.'],
            ['question' => 'How many units do I need?', 'answer' => '1 unit per 50 guests (4hr event) or 1 per 25 guests (8hr). Call for exact calculation.'],
            ['question' => 'What areas do you serve?', 'answer' => "We serve {$city->name} and all surrounding communities."],
        ];
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

        return [
            ['customer_name' => 'John D.', 'content' => 'Great service! Units were clean and delivered on time.', 'rating' => 5],
            ['customer_name' => 'Sarah M.', 'content' => 'Professional team. Very satisfied with the experience.', 'rating' => 5],
            ['customer_name' => 'Mike R.', 'content' => 'Excellent service for our project. Recommend!', 'rating' => 5],
        ];
    }

    protected function applyLinkConversions(string $text): string
    {
        $text = preg_replace('/\{\{PHONE_LINK\}\}/', '{{PHONE_LINK}}', $text);
        $text = preg_replace('/\{\{SERVICE_LINK:(\w+)\}\}/', '{{SERVICE_LINK:$1}}', $text);

        return $this->ensureCorrectPhoneLinks($text);
    }

    protected function ensureCorrectPhoneLinks(string $content): string
    {
        $phoneDisplay = config('contact.phone.display', '(888) 555-0199');
        $phoneRaw = config('contact.phone.raw', '+18885550199');
        $styledLink = "<a href=\"tel:{$phoneRaw}\" class=\"text-blue-600 font-semibold hover:underline\">{$phoneDisplay}</a>";

        return str_replace('{{PHONE_LINK}}', $styledLink, $content);
    }

    public function ensureServiceLinks(string $content, ?City $city = null): string
    {
        $serviceTypes = ['construction', 'wedding', 'event', 'luxury', 'party', 'emergency', 'residential', 'standard', 'deluxe', 'ada', 'shower'];
        $domain = Domain::current() ?? Domain::first();
        $slugPrefix = $domain?->getServiceSlugPrefix() ?? 'service';

        foreach ($serviceTypes as $type) {
            $pattern = '/\{\{SERVICE_LINK:'.$type.'\}\}/i';
            if (preg_match($pattern, $content)) {
                $slug = $city ? "{$type}-{$slugPrefix}-rental-{$city->slug}" : "/{$type}-service";
                $content = preg_replace($pattern, "<a href=\"{$slug}\" class=\"text-blue-600 font-semibold hover:underline\">".ucfirst($type).' Service</a>', $content);
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
}
