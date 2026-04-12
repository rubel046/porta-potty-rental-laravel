<?php

namespace App\Services;

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
        $phoneDisplay = domain_phone_display();
        $phoneRaw = domain_phone_raw();
        $styledLink = "<a href=\"tel:{$phoneRaw}\" class=\"text-blue-600 font-semibold hover:underline\">{$phoneDisplay}</a>";

        return str_replace('{{PHONE_LINK}}', $styledLink, $content);
    }

    public function ensureServiceLinks(string $content, ?City $city = null): string
    {
        $serviceTypes = ['general', 'construction', 'wedding', 'event', 'luxury', 'party', 'emergency', 'residential', 'standard', 'deluxe', 'ada', 'shower', 'contact'];
        $domain = Domain::current() ?? Domain::first();
        $slugPrefix = $domain?->getServiceSlugPrefix() ?? 'service';

        foreach ($serviceTypes as $type) {
            $pattern = '/\{\{SERVICE_LINK:'.$type.'\}\}/i';
            if (preg_match($pattern, $content)) {
                if ($type === 'contact') {
                    $slug = '/contact';
                    $label = 'Contact Us';
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

            return $this->generateStateContent($state);
        });
    }

    protected function generateStateContent(State $state): array
    {
        $domain = Domain::current() ?? Domain::first();
        $bizName = $domain?->business_name ?? 'Our Company';
        $serviceName = $domain?->primary_service ?? 'Portable Restroom Rental';

        $content = <<<HTML
<h2>Welcome to {$state->name}'s Premier {$serviceName} Service</h2>
<p>At {$bizName}, we are proud to serve the residents and businesses of {$state->name} with top-quality portable restroom solutions. Whether you're managing a construction site, planning a wedding, or organizing a community event, we have the perfect portable restroom for your needs. With years of experience serving {$state->name}'s diverse communities, we understand the unique challenges and requirements that come with providing exceptional portable sanitation services in this great state. Our team has built a reputation for excellence, reliability, and customer satisfaction that sets us apart from other providers in the region. We believe that every customer deserves access to clean, comfortable, and reliable portable restroom facilities, regardless of the size or nature of their project or event.</p>

<p>Our commitment to quality, reliability, and customer satisfaction has made us a trusted name across {$state->name}. We take pride in every unit we deliver, ensuring it's clean, well-maintained, and ready for immediate use. From the smallest job site to the largest outdoor festival, our team is equipped to handle projects of any size. We believe that every interaction with our customers is an opportunity to demonstrate our commitment to excellence and build lasting relationships. Our fleet of modern, well-maintained units combined with our experienced team ensures that we can handle even the most demanding projects with ease and professionalism.</p>

<p>When you choose {$bizName} for your {$serviceName} needs in {$state->name}, you're not just renting a portable restroom – you're partnering with a company that genuinely cares about your project's success. Our team is dedicated to providing personalized service that meets your specific requirements, whether you're organizing a small backyard event or managing a large-scale construction project. We understand that every project is unique, and we work closely with our customers to ensure their specific needs are met. Our customer-first approach has earned us a loyal following across {$state->name}, with many customers returning to us for all their portable sanitation needs.</p>

<p>We also offers comprehensive planning assistance for events, helping you determine the right number of units, optimal placement, and scheduling that works with your event timeline. Our expertise extends beyond just providing units – we're your partners in ensuring your event or project runs smoothly. We provide detailed site assessments, recommend appropriate unit quantities based on expected attendance or workforce size, and offer flexible delivery and pickup schedules to accommodate your timeline.</p>

<h2>Why Choose Our {$serviceName} Services in {$state->name}?</h2>
<p>We understand that every project and event has unique requirements. That's why we offer a comprehensive range of portable restroom solutions tailored to {$state->name}'s diverse communities. From the bustling cities to rural areas, our team delivers exceptional service with unbeatable benefits:</p>
<ul>
<li>Same-day delivery and pickup available throughout {$state->name}</li>
<li>Competitive pricing with no hidden fees or surprise charges</li>
<li>Clean, well-maintained units that exceed industry hygiene standards</li>
<li>24/7 customer support for emergencies and urgent needs</li>
<li>Flexible rental periods that adapt to your project timeline</li>
<li>Professional installation and setup by trained technicians</li>
<li>Regular servicing during rental period to maintain cleanliness</li>
<li>Eco-friendly cleaning solutions that protect the environment</li>
<li>Free quotes and consultations with no obligation</li>
<li>Fully licensed and insured operations for your peace of mind</li>
<li>Multiple unit types to meet diverse needs</li>
<li>Bulk discounts for large orders and long-term rentals</li>
</ul>

<p>Our team undergoes continuous training to ensure we provide the best customer experience in the industry. We believe in building long-term relationships with our clients through consistent, reliable service and outstanding support. Every member of our team is dedicated to ensuring your complete satisfaction, from the first phone call to the final pickup. We take pride in our reputation and work hard every day to maintain the high standards that our customers expect.</p>

<p>We also offer flexible scheduling options to accommodate your project's timeline. Whether you need units for a single day, a weekend, or several months, we have rental plans that fit your needs and budget. Our goal is to provide solutions that work for you, not just for today but throughout the entire duration of your project. We understand that sometimes plans change, and we work with our customers to accommodate schedule modifications whenever possible.</p>

<h2>Our Comprehensive Services in {$state->name}</h2>
<p>At {$bizName}, we offer far more than just standard portable restrooms. Our diverse service portfolio includes multiple solutions designed to meet every possible need:</p>

<h3>Standard Portable Restrooms - The Industry Workhorse</h3>
<p>Perfect for construction sites, outdoor events, and work areas requiring basic sanitation. Our standard units represent the gold standard in portable sanitation. Each unit is built for durability and long-term use, featuring:</p>
<ul>
<li>Non-splash urinal for added convenience in high-traffic situations</li>
<li>Advanced ventilation system that promotes air circulation</li>
<li>Anti-slip floor surface for safety in all weather conditions</li>
<li>Hand sanitizer dispenser for improved hygiene</li>
<li>Toilet paper holder with ample supply capacity</li>
<li>Interior hook for bags and personal belongings</li>
<li>Stable entry ramp for easy access</li>
<li>Durable construction that withstands heavy use</li>
<li>Privacy lock for security</li>
<li>Ventilation windows for air flow</li>
</ul>

<p>These units meet all OSHA workplace requirements and are ideal for construction sites, outdoor work areas, and events where basic but reliable facilities are needed. They provide dependable service while keeping costs manageable for projects with tight budgets.</p>

<h3>Deluxe Flushable Units - Elevated Comfort</h3>
<p>Ideal for weddings, corporate events, and upscale gatherings where comfort is paramount. Our deluxe units provide a significantly enhanced experience compared to standard portable restrooms. These premium units are perfect for events where your guests expect more comfortable facilities. Features include:</p>
<ul>
<li>Fully functional flushing toilet with full water supply</li>
<li>Hand sink with soap and paper towels</li>
<li>Interior mirror and enhanced lighting</li>
<li>Improved ventilation system for better air quality</li>
<li>Spacious interior design for increased comfort</li>
<li>Climate control options available for outdoor events</li>
<li>Premium finishing throughout the interior</li>
<li>Vanity area with counter space</li>
</ul>

<p>These units are particularly popular for weddings, corporate events, upscale private parties, and any gathering where guests expect higher-quality facilities. Many customers upgrade their entire event to deluxe units to provide their guests with the best possible experience.</p>

<h3>ADA Accessible Units - Ensuring Accessibility for All</h3>
<p>We are deeply committed to accessibility and inclusivity. Our ADA-compliant units ensure that everyone can access our facilities at your event or worksite, regardless of physical abilities. These thoughtfully designed units comply with all federal accessibility requirements and include:</p>
<ul>
<li>Extra-wide entry door that accommodates wheelchairs (at least 32 inches clear)</li>
<li>Interior grab bars on both sides for stability and support</li>
<li>Spacious interior to allow wheelchair maneuverability</li>
<li>Lowered toilet seat at accessible height</li>
<li>Non-slip flooring throughout the entire unit</li>
<li>External access ramp for ground-level entry</li>
<li>Easy-release privacy hardware</li>
<li>Lowered sink and mirror for wheelchair access</li>
</ul>

<p>These units are essential for public events where accessibility is required by law, but they're also the right choice for any event or site where inclusive design matters. Many customers order ADA units as a standard part of their event planning to ensure all guests can participate fully.</p>

<h3>Luxury Restroom Trailers - The Ultimate Experience</h3>
<p>Make your special event truly memorable with our premium luxury restroom trailers. These state-of-the-art mobile restrooms represent the pinnacle of portable sanitation, offering an experience that rivals indoor bathroom facilities. Perfect for high-end weddings, VIP events, corporate functions, and exclusive gatherings:</p>
<ul>
<li>Central air conditioning and heating for year-round comfort</li>
<li>Premium porcelain toilets (not plastic composite)</li>
<li>Luxury sinks with flowing water and premium fixtures</li>
<li>Full-length mirrors for guest convenience</li>
<li>Integrated stereo music system</li>
<li>Ambient LED lighting for elegant atmosphere</li>
<li>Granite or marble vanity counters</li>
<li>Multiple private stall options</li>
<li>Separate men's and women's trailer options</li>
<li>Hands-free fixtures for improved hygiene</li>
<li>Climate-controlled changing areas</li>
<li>Professional attendant available upon request</li>
</ul>

<p>These trailers are the definitive choice for events where only the best will do. They're particularly popular for weddings, charity galas, corporate retreats, and high-profile events where guest experience is paramount. Several trailer units can be linked together for larger events, providing ample facilities for hundreds of guests.</p>

<h3>Portable Shower Units - Complete Facility Solutions</h3>
<p>For events and worksites requiring shower facilities, we offer complete clean, private portable shower units with both hot and cold water options. These versatile units are ideal for:</p>
<ul>
<li>Multi-day outdoor festivals and camping events</li>
<li>Construction site worker facilities</li>
<li>Disaster relief and emergency response operations</li>
<li>Film and television production sites</li>
<li>Athletic events and endurance competitions</li>
<li>Remote work camps and locations</li>
</ul>

<p>Each shower unit includes private shower stalls, hot water capability, non-slip flooring, changing areas, and basic amenities. We can provide single units or entire shower trailers depending on your needs.</p>

<p>To learn more about our complete range of services, visit our <a href="{{SERVICE_LINK:general}}" class="text-blue-600 font-semibold hover:underline">General Service</a> page or explore our specialized <a href="{{SERVICE_LINK:construction}}" class="text-blue-600 font-semibold hover:underline">Construction Services</a>.</p>

<h2>Comprehensive Coverage Across {$state->name}</h2>
<p>We proudly serve major cities and communities throughout {$state->name}. Our extensive network ensures we can deliver units quickly to any location in the state. Whether you're in a major metropolitan area or a rural community, our dedicated team is ready to serve you with minimal notice. We've invested heavily in building our network of delivery vehicles and trained personnel across {$state->name}, enabling us to offer:</p>
<ul>
<li>Rapid delivery to all major cities including metropolitan areas</li>
<li>Scheduled deliveries to rural and remote locations</li>
<li>Same-day service availability in key population centers</li>
<li>Next-day service virtually everywhere in the state</li>
<li>Emergency response capabilities for urgent needs</li>
<li>Flexible pickup schedules to match your timeline</li>
</ul>

<p>Our local presence throughout {$state->name} means we understand the unique characteristics of each region. We know the local regulations, permit requirements, and terrain in different parts of the state. We navigate these complexities for you, handling all the details so you can focus on your project or event.</p>

<p>We maintain strategically positioned depots and delivery hubs throughout {$state->name} to minimize response times and delivery costs. This network allows us to offer same-day service in most urban areas and ensures we can reach even the most remote locations efficiently.</p>

<h2>Events We Proudly Serve in {$state->name}</h2>
<p>From intimate backyard gatherings to massive community festivals, we provide portable restroom solutions for virtually every type of event in {$state->name}:</p>
<ul>
<li>Weddings and receptions of all sizes and styles</li>
<li>Music festivals, concerts, and outdoor concerts</li>
<li>Professional sporting events and tournaments</li>
<li>Community festivals, fairs, and celebrations</li>
<li>Corporate events, conferences, and seminars</li>
<li>Construction sites and major construction projects</li>
<li>Film and television production locations</li>
<li>Disaster relief and emergency response efforts</li>
<li>Road construction and infrastructure projects</li>
<li>Agricultural fairs and farm events</li>
<li>School and university events</li>
<li>Religious gatherings and camp meetings</li>
<li>Political events and rallies</li>
<li>Historical reenactments and living history events</li>
</ul>

<p>No event is too big or too small. Our experience spans the full spectrum, from intimate gatherings with just a few guests to massive festivals attracting thousands of attendees. We've served everything from small family reunions to major professional sporting events and large-scale music festivals.</p>

<h2>Construction Site Services in {$state->name}</h2>
<p>Construction sites have specific requirements when it comes to portable sanitation, and {$bizName} specializes in meeting these demanding needs. At {$bizName}, we understand the unique challenges construction sites face and provide comprehensive solutions that keep your workers comfortable, productive, and compliant with all regulations. Our construction site services include:</p>
<ul>
<li>OSHA-compliant units that meet all workplace safety regulations</li>
<li>Customized servicing schedules based on crew size</li>
<li>Bulk unit quantities for large construction projects</li>
<li>Flexible long-term rental options with discounted rates</li>
<li>Delivery and pickup scheduling that works with project timelines</li>
<li>High-capacity units with urinals for high-traffic needs</li>
<li>Hand-washing station availability</li>
<li>On-site maintenance and rapid replacement when needed</li>
<li>Site-specific planning and layout consultation</li>
<li>Coordination with general contractors and project managers</li>
</ul>

<p>We maintain strong relationships with general contractors, construction companies, and project managers throughout {$state->name}. Our construction division understands the demands of construction sites and provides reliable service that keeps your project moving forward without sanitation-related disruptions.</p>

<h2>Our Dedication to {$state->name}</h2>
<p>At {$bizName}, we believe in investing in and giving back to the {$state->name} community. We participate in local events, support charitable organizations, and create jobs for local residents. When you rent from {$bizName}, you're supporting a business that genuinely cares about {$state->name}'s continued growth and prosperity.</p>

<p>Our community involvement includes:</p>
<ul>
<li>Sponsoring local community events and charitable organizations</li>
<li>Employing team members from local communities</li>
<li>Supporting local businesses and vendors</li>
<li>Participating in community planning and emergency preparedness</li>
<li>Providing reduced-cost services for qualifying non-profits</li>
<li>Training programs that develop local workforce skills</li>
</ul>

<p>We also strongly prioritize environmental sustainability throughout {$state->name}. Our commitment to protecting {$state->name}'s beautiful environment includes:</p>
<ul>
<li>Using only bio-degradable cleaning products</li>
<li>Solar-powered lighting options available for events</li>
<li>Water conservation measures in all our operations</li>
<li>Proper waste disposal at licensed treatment facilities only</li>
<li>Following all EPA and {$state->name} environmental regulations</li>
<li>Recycling programs for unit materials and packaging</li>
<li>Electric delivery vehicles where feasible</li>
</ul>

<p>We're committed to keeping {$state->name} clean and beautiful while providing essential services that both communities and businesses need.</p>

<h2 id="faqs">Frequently Asked Questions About Our {$state->name} Services</h2>
<p>We commonly receive questions from customers in {$state->name}. Below are detailed answers to help you plan your project or event. If you don't see your question here, please contact us at {{PHONE_LINK}}.</p>

<h2>Obtaining a Free Quote in {$state->name}</h2>
<p>Ready to get started? We make it easy to get a free, personalized quote for your {$serviceName} needs in {$state->name}. Our quote process is straightforward and comes with no obligation. Here's what you can expect when you contact us:</p>
<ul>
<li>Free, no-obligation quotes provided within hours</li>
<li>Same-day response to all customer inquiries</li>
<li>Flexible scheduling options that work with your timeline</li>
<li>Professional delivery and setup by trained technicians</li>
<li>Outstanding customer support throughout your rental period</li>
<li>No hidden fees or surprise charges ever</li>
<li>Price-matching on comparable quotes from competitors</li>
</ul>

<p>Call us now at {{PHONE_LINK}} to speak with our knowledgeable and friendly team. We look forward to serving you in {$state->name} and making your event or project a complete success!</p>
HTML;

        $content = $this->applyLinkConversions($content);
        $content = $this->ensureCorrectPhoneLinks($content);
        $content = $this->ensureServiceLinks($content);

        $faqs = $this->generateStateFaqs($state, $serviceName);

        return [
            'content' => $content,
            'faqs' => $faqs,
        ];
    }

    protected function generateStateFaqs(State $state, string $serviceName): array
    {
        $domain = Domain::current() ?? Domain::first();
        $bizName = $domain?->business_name ?? 'Our Company';

        return [
            [
                'question' => "How much does {$serviceName} cost in {$state->name}?",
                'answer' => "Pricing varies based on unit type, quantity, and rental duration. Contact us at {{PHONE_LINK}} for a personalized quote tailored to your needs in {$state->name}.",
            ],
            [
                'question' => "Do you offer same-day delivery in {$state->name}?",
                'answer' => "Yes! We offer same-day delivery for most orders in {$state->name}. Call before 2 PM for best availability.",
            ],
            [
                'question' => 'What types of units are available?',
                'answer' => 'We offer standard units, deluxe flushable units, ADA accessible units, and luxury restroom trailers. Visit our services page for more information.',
            ],
            [
                'question' => 'How many units do I need for my event?',
                'answer' => "A general rule is 1 unit per 50 guests for a 4-hour event. For construction sites, OSHA requires 1 unit per 20 workers. Contact us and we'll help you determine the right number.",
            ],
            [
                'question' => "Do you service all areas of {$state->name}?",
                'answer' => "Yes, we proudly serve cities and communities throughout {$state->name}. Contact us to confirm service in your specific area.",
            ],
        ];
    }
}
