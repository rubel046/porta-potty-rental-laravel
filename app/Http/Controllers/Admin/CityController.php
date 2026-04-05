<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateCityContentJob;
use App\Models\City;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $states = State::orderBy('name')->get();

        $query = City::with('state')->withCount(['servicePages', 'callLogs']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $cities = $query->orderByDesc('is_active')
            ->orderByDesc('priority')
            ->orderBy('name')
            ->paginate(30);

        return view('admin.cities.index', compact('cities', 'states'));
    }

    public function create()
    {
        $states = State::orderBy('name')->get();

        return view('admin.cities.create', compact('states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'state_id' => 'required|exists:states,id',
            'area_codes' => 'nullable|string|max:100',
            'population' => 'nullable|integer',
            'priority' => 'nullable|integer|min:0|max:10',
            'nearby_cities' => 'nullable|string',
            'climate_info' => 'nullable|string|max:500',
            'local_events' => 'nullable|string',
            'construction_info' => 'nullable|string|max:500',
        ]);

        $state = State::find($validated['state_id']);
        $slug = strtolower(str_replace(' ', '-', $validated['name']))
            .'-'.strtolower($state->code);

        $validated['slug'] = $slug;

        if (! empty($validated['nearby_cities'])) {
            $validated['nearby_cities'] = array_map(
                'trim',
                explode(',', $validated['nearby_cities'])
            );
        }

        $city = City::create($validated);

        // Auto-generate pages if requested
        if ($request->has('generate_pages')) {
            $this->generatePages($city);

            return redirect()->route('admin.cities.index')
                ->with('success', "City '{$city->name}' created with service pages!");
        }

        return redirect()->route('admin.cities.index')
            ->with('success', "City '{$city->name}' created!");
    }

    public function edit(City $city)
    {
        $states = State::orderBy('name')->get();
        $city->load('servicePages', 'phoneNumbers');

        return view('admin.cities.edit', compact('city', 'states'));
    }

    public function show(City $city)
    {
        $city->load(['state', 'servicePages', 'phoneNumbers', 'callLogs', 'blogPosts']);

        $cacheKey = "city_content_generation_{$city->id}";
        $generationStatus = Cache::get("{$cacheKey}_status", 'idle');
        $generationProgress = Cache::get("{$cacheKey}_progress", 0);
        $currentType = Cache::get("{$cacheKey}_current_type");
        $generationErrors = Cache::get("{$cacheKey}_errors", []);
        $startedAt = Cache::get("{$cacheKey}_started_at");

        if ($generationStatus === 'completed' || $generationStatus === 'failed') {
            Cache::forget("{$cacheKey}_status");
            Cache::forget("{$cacheKey}_progress");
            Cache::forget("{$cacheKey}_current_type");
            Cache::forget("{$cacheKey}_errors");
            Cache::forget("{$cacheKey}_started_at");
            $generationStatus = 'idle';
            $generationProgress = 0;
            $generationErrors = [];
        }

        return view('admin.cities.show', compact('city', 'generationStatus', 'generationProgress', 'currentType', 'generationErrors', 'startedAt'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'state_id' => 'required|exists:states,id',
            'area_codes' => 'nullable|string|max:100',
            'population' => 'nullable|integer',
            'priority' => 'nullable|integer|min:0|max:10',
            'nearby_cities' => 'nullable|string',
            'climate_info' => 'nullable|string|max:500',
            'local_events' => 'nullable|string',
            'construction_info' => 'nullable|string|max:500',
            'is_active' => 'nullable',
        ]);

        if (! empty($validated['nearby_cities']) && is_string($validated['nearby_cities'])) {
            $validated['nearby_cities'] = array_map(
                'trim',
                explode(',', $validated['nearby_cities'])
            );
        }

        $validated['is_active'] = $request->has('is_active');

        $city->update($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', "City '{$city->name}' updated!");
    }

    public function destroy(City $city)
    {
        $name = $city->name;
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', "City '{$name}' deleted!");
    }

    public function generatePages(City $city)
    {
        $cacheKey = "city_content_generation_{$city->id}";

        if (Cache::get("{$cacheKey}_status") === 'processing') {
            return redirect()->back()->with('info', 'Content generation is already in progress!');
        }

        Cache::put("{$cacheKey}_status", 'processing', now()->addMinutes(30));
        Cache::put("{$cacheKey}_progress", 0, now()->addMinutes(30));
        Cache::put("{$cacheKey}_current_type", null, now()->addMinutes(30));
        Cache::put("{$cacheKey}_started_at", now()->toIso8601String(), now()->addMinutes(60));

        GenerateCityContentJob::dispatch($city);

        return redirect()->back()->with('success', 'Content generation started in background! Refresh the page to see progress.');
    }

    public function generationProgress(City $city)
    {
        $cacheKey = "city_content_generation_{$city->id}";

        return response()->json([
            'status' => Cache::get("{$cacheKey}_status", 'idle'),
            'progress' => Cache::get("{$cacheKey}_progress", 0),
            'current_type' => Cache::get("{$cacheKey}_current_type"),
            'started_at' => Cache::get("{$cacheKey}_started_at"),
        ]);
    }

    public function deletePages(City $city)
    {
        $pageCount = $city->servicePages()->count();
        $faqCount = $city->faqs()->count();
        $testimonialCount = $city->testimonials()->count();

        $city->servicePages()->delete();
        $city->faqs()->delete();
        $city->testimonials()->delete();

        return redirect()->back()
            ->with('success', "Deleted {$pageCount} pages, {$faqCount} FAQs, and {$testimonialCount} testimonials for {$city->name}!");
    }

    public function importJson(Request $request, City $city)
    {
        $jsonData = $request->input('json_content');

        if (empty($jsonData)) {
            return redirect()->back()->with('error', 'Please paste JSON content');
        }

        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->with('error', 'Invalid JSON format: '.json_last_error_msg());
        }

        $pagesCreated = 0;
        $pagesUpdated = 0;
        $errors = [];

        // Validate and import service pages
        if (isset($data['service_pages']) && is_array($data['service_pages'])) {
            foreach ($data['service_pages'] as $pageData) {
                try {
                    if (empty($pageData['service_type']) || empty($pageData['slug'])) {
                        $errors[] = 'Skipped page: missing service_type or slug';

                        continue;
                    }

                    $slug = strtolower($pageData['slug']);
                    $exists = $city->servicePages()->where('slug', $slug)->exists();

                    // Process content - add internal links if provided
                    $content = $pageData['content'] ?? '';
                    if (isset($pageData['internal_links']) && is_array($pageData['internal_links'])) {
                        foreach ($pageData['internal_links'] as $link) {
                            $url = $link['url'] ?? '';
                            $anchor = $link['anchor_text'] ?? '';
                            if ($url && $anchor) {
                                $content = str_replace(
                                    $anchor,
                                    '<a href="'.$url.'" class="text-blue-600 hover:underline">'.$anchor.'</a>',
                                    $content
                                );
                            }
                        }
                    }

                    // Transform phone numbers to clickable tel: links
                    // Match patterns like (888) 555-0199, 888-555-0199, +1 888 555 0199, etc.
                    $phonePattern = '/(\+?1?[\s\-.]?)?\(?[0-9]{3}\)?[\s\-.]?[0-9]{3}[\s\-.]?[0-9]{4}/';
                    $content = preg_replace_callback($phonePattern, function ($matches) {
                        $phone = preg_replace('/[^0-9+]/', '', $matches[0]);
                        if (strlen($phone) === 10) {
                            $phone = '1'.$phone;
                        }

                        return '<a href="tel:+'.$phone.'" class="text-blue-600 font-semibold hover:underline">'.$matches[0].'</a>';
                    }, $content);

                    // Auto-add service images from storage - only one unique image per page
                    $serviceType = $pageData['service_type'] ?? 'general';
                    try {
                        $imageHtml = $this->generateServiceImages($serviceType, $city->name);

                        if (! empty($imageHtml['hero']) && strpos($content, basename($imageHtml['hero'])) === false) {
                            // Insert only once - after 2nd heading (first match only)
                            $headingPattern = '/(<h[23][^>]*>.*?<\/h[23]>)/i';
                            $content = preg_replace($headingPattern, '$1'."\n".$imageHtml['hero'], $content, 1);
                        }
                    } catch (\Exception $e) {
                        // Skip images if error
                    }

                    // Auto-link service type keywords to their respective pages
                    $serviceKeywords = [
                        'construction' => ['construction site', 'job site', 'construction project', 'building site'],
                        'wedding' => ['wedding', 'reception', 'bride', 'groom'],
                        'event' => ['event', 'festival', 'concert', 'corporate event'],
                        'luxury' => ['luxury', 'vip', 'executive', 'premium'],
                        'party' => ['party', 'celebration', 'backyard'],
                        'emergency' => ['emergency', 'urgent', '24/7', 'immediate'],
                        'residential' => ['residential', 'home renovation', 'diy', 'homeowner'],
                    ];

                    foreach ($serviceKeywords as $type => $keywords) {
                        if ($type === $serviceType) {
                            continue;
                        }
                        $pageUrl = '/services#'.$type;
                        foreach ($keywords as $keyword) {
                            $pattern = '/\b('.preg_quote($keyword, '/').')/i';
                            if (preg_match($pattern, $content) && strpos($content, $pageUrl) === false) {
                                $content = preg_replace($pattern, '<a href="'.$pageUrl.'" class="text-blue-600 hover:underline">$1</a>', $content, 1);
                            }
                        }
                    }

                    $wordCount = ! empty($content)
                        ? str_word_count(strip_tags($content))
                        : ($pageData['word_count'] ?? 0);

                    $page = $city->servicePages()->updateOrCreate(
                        ['slug' => $slug],
                        [
                            'service_type' => $pageData['service_type'],
                            'h1_title' => $pageData['h1_title'] ?? null,
                            'meta_title' => $pageData['meta_title'] ?? null,
                            'meta_description' => $pageData['meta_description'] ?? null,
                            'content' => $content,
                            'word_count' => $wordCount,
                            'is_published' => $pageData['is_published'] ?? true,
                            'published_at' => isset($pageData['published_at']) ? Carbon::parse($pageData['published_at']) : now(),
                            'canonical_url' => $pageData['canonical_url'] ?? null,
                            'phone_number' => $pageData['phone_number'] ?? null,
                            'schema_markup' => $pageData['schema_markup'] ?? null,
                        ]
                    );

                    // Calculate SEO score after import
                    $page->calculateSeoScore();

                    if ($exists) {
                        $pagesUpdated++;
                    } else {
                        $pagesCreated++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error with page {$pageData['service_type']}: ".$e->getMessage();
                }
            }
        }

        // Import FAQs
        $faqsCreated = 0;
        if (isset($data['faqs']) && is_array($data['faqs'])) {
            foreach ($data['faqs'] as $i => $faqData) {
                if (empty($faqData['question']) || empty($faqData['answer'])) {
                    continue;
                }

                $city->faqs()->updateOrCreate(
                    ['question' => $faqData['question']],
                    [
                        'answer' => $faqData['answer'],
                        'service_type' => $faqData['service_type'] ?? 'general',
                        'sort_order' => $faqData['sort_order'] ?? $i,
                        'is_active' => $faqData['is_active'] ?? true,
                    ]
                );
                $faqsCreated++;
            }
        }

        // Import testimonials
        $testimonialsCreated = 0;
        if (isset($data['testimonials']) && is_array($data['testimonials'])) {
            foreach ($data['testimonials'] as $tData) {
                if (empty($tData['customer_name']) || empty($tData['content'])) {
                    continue;
                }

                $city->testimonials()->updateOrCreate(
                    ['customer_name' => $tData['customer_name']],
                    [
                        'content' => $tData['content'],
                        'rating' => $tData['rating'] ?? 5,
                        'service_type' => $tData['service_type'] ?? 'general',
                        'is_active' => $tData['is_active'] ?? true,
                    ]
                );
                $testimonialsCreated++;
            }
        }

        // Recalculate SEO scores for all pages
        $city->servicePages()->each(fn ($page) => $page->calculateSeoScore());

        $message = "Imported {$pagesCreated} pages, updated {$pagesUpdated} pages, {$faqsCreated} FAQs, {$testimonialsCreated} testimonials.";

        if (! empty($errors)) {
            $message .= ' Warnings: '.implode('; ', array_slice($errors, 0, 3));
        }

        return redirect()->back()->with('success', $message);
    }

    public function getSampleJson(City $city)
    {
        $domain = 'pottydirect.com';
        $types = ['general', 'construction', 'wedding', 'event', 'luxury', 'party', 'emergency', 'residential'];

        $samplePages = [];
        foreach ($types as $type) {
            $samplePages[] = [
                'service_type' => $type,
                'slug' => strtolower($city->name).'-'.strtolower($type).'-'.strtolower($city->state->code),
                'h1_title' => ucfirst($type).' Porta Potty Rental in '.$city->name.', '.$city->state->code.' | Same-Day Delivery',
                'h2_headings' => [
                    'Why Choose Our '.ucfirst($type).' Porta Potty Rental Services in '.$city->name.'?',
                    'Professional '.ucfirst($type).' Porta Potty Rental for All Your Needs',
                    'Serving '.$city->name.' and Surrounding Areas',
                ],
                'meta_title' => ucfirst($type).' Porta Potty Rental '.$city->name.', '.$city->state->code.' | Free Quote & Same-Day Delivery',
                'meta_description' => 'Looking for '.ucfirst($type).' Porta Potty Rental in '.$city->name.', '.$city->state->code.'? We offer fast delivery, competitive prices, and clean units. Get your free quote today! Servicing '.$city->name.', '.$city->state->name.' and nearby areas.',
                'meta_keywords' => $type.' porta potty rental '.$city->name.', portable toilet '.$city->state->code.', cheap porta potty '.$city->name,
                'og_title' => ucfirst($type).' Porta Potty Rental '.$city->name.' - Call Now for Free Quote',
                'og_description' => 'Professional '.ucfirst($type).' Porta Potty Rental services in '.$city->name.', '.$city->state->code.'. Same-day delivery available. Call us today!',
                'og_image' => '/images/'.$type.'-rental-'.strtolower($city->name).'-'.strtolower($city->state->code).'.jpg',
                'og_url' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/'.$type,
                'twitter_card' => 'summary_large_image',
                'twitter_title' => ucfirst($type).' Porta Potty Rental '.$city->name.' | Free Quote',
                'twitter_description' => 'Get professional '.ucfirst($type).' Porta Potty Rental in '.$city->name.'. Same-day delivery available!',
                'twitter_image' => '/images/social/'.$type.'-'.strtolower($city->name).'-'.strtolower($city->state->code).'.jpg',
                'canonical_url' => 'https://'.$domain.'/'.strtolower($city->name).'-'.$type.'-'.strtolower($city->state->code),
                'schema_markup' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'LocalBusiness',
                    'name' => ucfirst($type).' Porta Potty Rental - '.$city->name,
                    'description' => 'Professional '.ucfirst($type).' Porta Potty Rental services in '.$city->name.', '.$city->state->code.'. Serving residential, commercial, and construction clients.',
                    'telephone' => '+1-XXX-XXX-XXXX',
                    'email' => 'info@'.$domain,
                    'url' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/'.$type,
                    'priceRange' => '$$',
                    'openingHours' => '24/7',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => '123 Service Street',
                        'addressLocality' => $city->name,
                        'addressRegion' => $city->state->code,
                        'postalCode' => 'XXXXX',
                        'addressCountry' => 'US',
                    ],
                    'geo' => [
                        '@type' => 'GeoCoordinates',
                        'latitude' => '25.7617',
                        'longitude' => '-80.1918',
                    ],
                    'areaServed' => [
                        '@type' => 'City',
                        'name' => $city->name,
                    ],
                    'serviceType' => ucfirst($type).' Porta Potty Rental',
                    'hasOfferCatalog' => [
                        '@type' => 'OfferCatalog',
                        'name' => 'Portable Restroom Services - '.$city->name,
                        'itemListElement' => [
                            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Standard Portable Toilet', 'description' => 'Standard unit for construction and events']],
                            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Deluxe Flushable Unit', 'description' => 'Premium unit with flushing toilet and sink']],
                            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'ADA Accessible Unit', 'description' => 'Wheelchair accessible portable toilet']],
                        ],
                    ],
                    'review' => ['@type' => 'Review', 'reviewRating' => ['@type' => 'Rating', 'ratingValue' => '4.8', 'bestRating' => '5'], 'author' => ['@type' => 'Organization', 'name' => 'Porta Potty Direct']],
                    'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => '4.8', 'reviewCount' => '127', 'bestRating' => '5'],
                    'sameAs' => ['https://www.facebook.com/pottydirect', 'https://twitter.com/pottydirect', 'https://instagram.com/pottydirect'],
                ],
                'faq_schema' => [
                    ['@type' => 'Question', 'name' => 'How much does '.ucfirst($type).' Porta Potty Rental cost in '.$city->name.'?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Our '.ucfirst($type).' Porta Potty Rental prices in '.$city->name.' start at $150/week for standard units. We offer competitive pricing and free quotes. Contact us for exact pricing based on your needs.']],
                    ['@type' => 'Question', 'name' => 'Do you offer same-day delivery in '.$city->name.'?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes! We offer same-day delivery in '.$city->name.' for orders placed before noon. Weekend and emergency delivery available.']],
                ],
                'breadcrumb_schema' => [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://'.$domain],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $city->name, 'item' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code)],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => ucfirst($type).' Rental', 'item' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/'.$type],
                    ],
                ],
                'content' => '<h2>Welcome to '.$city->name."'s Premier ".ucfirst($type).' Porta Potty Rental Service</h2>
                <p>Looking for reliable '.ucfirst($type).' Porta Potty Rental in '.$city->name.', '.$city->state->code.'? You\'ve come to the right place. We are the leading provider of portable sanitation solutions in the '.$city->name.' area, serving both residential and commercial clients with top-quality equipment and exceptional customer service.</p>

                <h2>Why Choose Our '.ucfirst($type).' Porta Potty Rental Services in '.$city->name.'?</h2>
                <p>When it comes to portable sanitation in '.$city->name.', we stand out from the competition. Here\'s why contractors, event planners, and homeowners trust us:</p>
                <ul>
                <li><strong>Fast Delivery:</strong> We offer same-day delivery in '.$city->name.' and surrounding areas.</li>
                <li><strong>Clean Units:</strong> All our units are thoroughly cleaned and sanitized before delivery.</li>
                <li><strong>Competitive Pricing:</strong> Get the best value for your money with our transparent pricing.</li>
                <li><strong>Professional Service:</strong> Our team is dedicated to providing excellent customer service.</li>
                <li><strong>Local Expertise:</strong> We know '.$city->name.' and can recommend the best solutions for your needs.</li>
                </ul>

                <h2>Our '.ucfirst($type).' Porta Potty Rental Options in '.$city->name.'</h2>
                <p>We offer a variety of portable sanitation options to meet your specific needs in '.$city->name.':</p>

                <h3>Standard Portable Toilets</h3>
                <p>Our standard units are perfect for construction sites, outdoor events, and residential projects in '.$city->name.'. Each unit includes a toilet, urinal, and hand sanitizer dispenser.</p>

                <h3>Deluxe Flushable Units</h3>
                <p>For events and weddings in '.$city->name.' that require a more upscale option, our deluxe flushable units offer a premium experience with flushing toilet, sink with running water, and mirror.</p>

                <h3>ADA Accessible Units</h3>
                <p>We provide ADA-compliant accessible portable toilets in '.$city->name.' for events and construction sites that require wheelchair-accessible facilities.</p>

                <h3>Luxury Restroom Trailers</h3>
                <p>For VIP events, weddings, and corporate functions in '.$city->name.', our luxury restroom trailers offer climate-controlled comfort, multiple stalls, and upscale amenities.</p>

                <h2>Serving '.$city->name.' and Surrounding Areas</h2>
                <p>We\'re proud to serve '.$city->name.' and the greater '.$city->state->name.' area with our professional portable sanitation services. Whether you\'re in downtown '.$city->name.' or the surrounding suburbs, we can deliver to your location.</p>

                <h2>Get Your Free Quote for '.ucfirst($type).' Porta Potty Rental in '.$city->name.'</h2>
                <p>Ready to get started? Contact us today for a free quote on '.ucfirst($type).' Porta Potty Rental in '.$city->name.', '.$city->state->code.'. Our team will work with you to find the best solution for your needs and budget.</p>
                <p>Call us now at [PHONE] or fill out our online form to request a quote. We look forward to serving you in '.$city->name.'!</p>',
                'word_count' => 450,
                'internal_links' => [
                    ['url' => '/'.strtolower($city->name).'-'.strtolower($city->state->code), 'anchor_text' => $city->name.' Porta Potty Rental'],
                    ['url' => '/'.strtolower($city->name).'-'.strtolower($city->state->code).'/construction', 'anchor_text' => 'Construction Site Toilets '.$city->name],
                    ['url' => '/'.strtolower($city->name).'-'.strtolower($city->state->code).'/wedding', 'anchor_text' => 'Wedding Restroom Rentals '.$city->name],
                    ['url' => '/blog/porta-potty-guide-'.strtolower($city->name).'-'.strtolower($city->state->code), 'anchor_text' => 'Porta Potty Rental Guide for '.$city->name],
                ],
                'related_pages' => [
                    ['slug' => strtolower($city->name).'-construction-'.strtolower($city->state->code), 'title' => 'Construction Site Rental'],
                    ['slug' => strtolower($city->name).'-wedding-'.strtolower($city->state->code), 'title' => 'Wedding Rental'],
                    ['slug' => strtolower($city->name).'-event-'.strtolower($city->state->code), 'title' => 'Event Rental'],
                ],
                'featured_image' => '/storage/service-images/'.$type.'-'.strtolower($city->name).'-'.strtolower($city->state->code).'.jpg',
                'images' => [
                    ['url' => '/storage/service-images/'.$type.'-1-'.strtolower($city->name).'-'.strtolower($city->state->code).'.jpg', 'alt' => ucfirst($type).' Porta Potty Rental '.$city->name],
                    ['url' => '/storage/service-images/'.$type.'-2-'.strtolower($city->name).'-'.strtolower($city->state->code).'.jpg', 'alt' => 'Portable Toilet at '.$city->name.' Event'],
                ],
                'is_published' => true,
                'published_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
                'allow_indexing' => true,
                'allow_following' => true,
                'seo_score_breakdown' => [
                    'title_length' => 65, 'title_has_city' => true, 'title_has_service' => true,
                    'description_length' => 155, 'description_has_cta' => true, 'word_count' => 450,
                    'has_h1' => true, 'h1_has_keyword' => true, 'h1_has_city' => true,
                    'has_schema' => true, 'has_faq_schema' => true, 'has_canonical' => true,
                    'has_images' => true, 'has_internal_links' => true, 'has_keywords' => true,
                ],
            ];
        }

        $sampleFaqs = [
            [
                'question' => 'How much does porta potty rental cost in '.$city->name.'?',
                'answer' => 'Our rental prices in '.$city->name.' start at $150/week for standard units. Deluxe units and luxury trailers cost more. Contact us for a custom quote based on your specific needs and duration.',
                'service_type' => 'general',
                'sort_order' => 0,
                'is_active' => true,
            ],
            [
                'question' => 'Do you offer same-day delivery in '.$city->name.'?',
                'answer' => 'Yes! We offer same-day delivery in '.$city->name.' for orders placed before noon. We also provide emergency delivery services for urgent needs.',
                'service_type' => 'general',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'What\'s included in the rental price in '.$city->name.'?',
                'answer' => 'Our rental price in '.$city->name.' includes delivery, setup, weekly servicing, and pickup. Extra services like cleaning or longer rental periods may have additional costs.',
                'service_type' => 'general',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        $sampleTestimonials = [
            [
                'customer_name' => 'Michael R.',
                'customer_title' => 'Construction Project Manager',
                'company' => 'ABC Construction',
                'location' => $city->name.', '.$city->state->code,
                'content' => 'We\'ve been using this company for our construction site portable toilets in '.$city->name.' for over 2 years. Their service is exceptional - always on time, units are clean, and their customer service is outstanding. Highly recommend for any construction project in '.$city->name.'!',
                'rating' => 5,
                'service_type' => 'construction',
                'is_featured' => true,
                'is_active' => true,
                'project_type' => 'Commercial Construction',
                'project_duration' => '6 months',
                'units_rented' => '15',
                'verified' => true,
                'review_date' => '2025-03-15',
                'schema_markup' => [
                    '@type' => 'Review',
                    'reviewRating' => ['@type' => 'Rating', 'ratingValue' => '5', 'bestRating' => '5'],
                    'author' => ['@type' => 'Person', 'name' => 'Michael R.', 'jobTitle' => 'Construction Project Manager'],
                    'itemReviewed' => ['@type' => 'LocalBusiness', 'name' => 'Porta Potty Direct '.$city->name],
                    'location' => ['@type' => 'Place', 'address' => ['@type' => 'PostalAddress', 'addressLocality' => $city->name]],
                ],
            ],
        ];

        $sample = [
            'metadata' => [
                'format_version' => '2.0',
                'generated_at' => now()->toDateTimeString(),
                'purpose' => 'SEO-optimized service page content for AI generation',
                'city' => [
                    'id' => $city->id,
                    'name' => $city->name,
                    'slug' => $city->slug,
                    'state' => ['code' => $city->state->code, 'name' => $city->state->name],
                    'population' => $city->population,
                    'area_codes' => $city->area_codes ?? [],
                    'zip_codes' => is_array($city->nearby_cities) ? [] : [],
                ],
                'instructions' => [
                    'title' => 'Use this JSON as a comprehensive reference for generating high-ranking SEO content',
                    'guidelines' => [
                        'Maintain keyword density of 1-2% for primary keywords',
                        'Include LSI keywords naturally throughout content',
                        'Use header tags (H1, H2, H3) with keywords',
                        'Keep meta description under 160 characters',
                        'Keep title tag under 60 characters',
                        'Include location signals throughout content',
                        'Use schema markup exactly as provided',
                        'Include FAQ schema for featured snippets',
                        'Link to related pages using provided anchor text',
                        'Use high-quality images with descriptive alt text',
                        'Target 1500+ words for main content',
                    ],
                ],
            ],
            'keywords' => [
                'primary' => ['porta potty rental '.$city->name, 'portable toilet rental '.$city->name.' '.$city->state->code, 'porta potty '.$city->name, 'portable toilet '.$city->name],
                'secondary' => ['construction site toilets '.$city->name, 'event restroom rental '.$city->name, 'wedding restroom trailer '.$city->name, 'cheap porta potty '.$city->name],
                'long_tail' => ['same day porta potty delivery '.$city->name, 'emergency portable toilet rental '.$city->name, 'construction site portable toilets for rent', 'luxury restroom trailer rental '.$city->name],
                'geo_modifiers' => ['near '.$city->name, 'in '.$city->name.' '.$city->state->code, 'near me', 'local '.$city->name],
            ],
            'service_pages' => $samplePages,
            'faqs' => $sampleFaqs,
            'testimonials' => $sampleTestimonials,
            'service_areas' => [
                ['name' => $city->name, 'state' => $city->state->code, 'zip_codes' => ['XXXXX', 'XXXXX', 'XXXXX']],
                ['name' => 'Nearby City 1', 'state' => $city->state->code, 'zip_codes' => ['XXXXX']],
                ['name' => 'Nearby City 2', 'state' => $city->state->code, 'zip_codes' => ['XXXXX']],
            ],
            'competitors' => [
                ['name' => 'Company A', 'distance' => '5 miles', 'rating' => 4.2],
                ['name' => 'Company B', 'distance' => '8 miles', 'rating' => 4.0],
            ],
            'site_structure' => [
                'homepage' => 'https://'.$domain,
                'city_page' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code),
                'service_pages' => [
                    'general' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/general',
                    'construction' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/construction',
                    'wedding' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/wedding',
                    'event' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/event',
                    'luxury' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/luxury',
                    'party' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/party',
                    'emergency' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/emergency',
                    'residential' => 'https://'.$domain.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/residential',
                ],
                'blog' => 'https://'.$domain.'/blog',
                'about' => 'https://'.$domain.'/about',
                'contact' => 'https://'.$domain.'/contact',
            ],
            'content_guidelines' => [
                'word_count' => ['minimum' => 1500, 'recommended' => 2500, 'ideal' => 3500],
                'keyword_density' => ['primary' => '1-2%', 'secondary' => '0.5-1%', 'LSI' => '2-3%'],
                'readability' => ['grade_level' => '6-8', 'sentence_length' => '15-20 words', 'paragraph_length' => '3-4 sentences'],
                'structure' => ['h1_count' => 1, 'h2_count' => '4-6', 'h3_count' => '8-12', 'bullet_lists' => '3-5', 'internal_links' => '5-10'],
                'schema_required' => ['LocalBusiness', 'FAQPage', 'BreadcrumbList', 'Review', 'AggregateRating'],
                'media' => ['images_min' => 3, 'images_recommended' => 5, 'alt_text_required' => true, 'image_size' => '1200x630'],
            ],
        ];

        return response()->json($sample, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function generateServiceImages(string $type, string $cityName): array
    {
        $storagePath = '/storage/service-images';

        // Get all images from the service-images directory
        $allImages = glob(public_path('storage/service-images/*.webp'));

        // Shuffle for variety
        shuffle($allImages);

        // Select up to 4 images
        $galleryImages = array_slice($allImages, 0, min(4, count($allImages)));

        $heroImage = ! empty($galleryImages) ? $galleryImages[0] : null;

        $result = [
            'hero' => '',
            'gallery' => '',
        ];

        // Hero image at top
        if ($heroImage) {
            $filename = basename($heroImage);
            $altText = ucfirst($type).' Porta Potty Rental in '.$cityName;
            $result['hero'] = <<<HTML
<div class="my-8">
    <img src="{$storagePath}/{$filename}" alt="{$altText}" class="w-full h-64 object-cover rounded-xl shadow-lg" loading="eager">
    <p class="text-sm text-gray-500 mt-2 text-center">Professional portable sanitation services in {$cityName}</p>
</div>
HTML;
        }

        // Gallery section before CTA
        if (count($galleryImages) > 1) {
            $galleryHtml = '<div class="my-8 grid grid-cols-2 md:grid-cols-4 gap-4">';
            foreach (array_slice($galleryImages, 1) as $img) {
                $filename = basename($img);
                $galleryHtml .= <<<HTML
<div class="aspect-w-16 aspect-h-12 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
    <img src="{$storagePath}/{$filename}" alt="Portable toilet rental {$cityName}" class="object-cover w-full h-32 hover:scale-105 transition-transform duration-300" loading="lazy">
</div>
HTML;
            }
            $galleryHtml .= '</div>';
            $result['gallery'] = $galleryHtml;
        }

        return $result;
    }
}
