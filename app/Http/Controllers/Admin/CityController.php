<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\GenerateCityContentJob;
use App\Models\City;
use App\Models\Domain;
use App\Models\DomainCity;
use App\Models\ServicePage;
use App\Models\State;
use App\Services\ContentGeneratorService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with('state');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $cities = $query->orderBy('name')->paginate(30);
        $states = State::orderBy('name')->get();

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
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cities',
            'county' => 'nullable|string|max:255',
            'population' => 'nullable|integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $city = City::create($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', "City {$city->name} created!");
    }

    public function edit(City $city)
    {
        $city->load('state', 'domins');
        $states = State::orderBy('name')->get();

        return view('admin.cities.edit', compact('city', 'states'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cities,slug,'.$city->id,
            'county' => 'nullable|string|max:255',
            'population' => 'nullable|integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'nearby_cities' => 'nullable|array',
            'zip_codes' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $city->update($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', "City {$city->name} updated!");
    }

    public function show(City $city)
    {
        $city->load('state', 'servicePages', 'domainCities.domain');

        return view('admin.cities.show', compact('city'));
    }

    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', "City {$city->name} deleted!");
    }

    public function toggleStatus(City $city)
    {
        $city->update(['is_active' => ! $city->is_active]);

        $status = $city->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "City {$city->name} {$status}!");
    }

    public function toggleContentGenerated(City $city)
    {
        $domain = Domain::current();
        $domainCity = DomainCity::where('domain_id', $domain?->id)
            ->where('city_id', $city->id)
            ->first();

        if ($domainCity) {
            $domainCity->update(['content_generated' => ! $domainCity->content_generated]);
        }

        return redirect()->back()->with('success', 'Content generation status toggled!');
    }

    public function generatePages(City $city)
    {
        $domain = Domain::current();
        $serviceTypes = $domain?->getServiceTypes() ?? [];

        $dispatched = 0;
        foreach ($serviceTypes as $type) {
            $existingPage = ServicePage::where('city_id', $city->id)
                ->where('domain_id', $domain?->id)
                ->where('service_type', $type)
                ->first();

            if (! $existingPage) {
                $page = ServicePage::create([
                    'domain_id' => $domain?->id,
                    'city_id' => $city->id,
                    'service_type' => $type,
                    'slug' => $type.'-rental-'.$city->slug,
                    'is_published' => false,
                ]);

                if (app()->bound(ContentGeneratorService::class)) {
                    GenerateCityContentJob::dispatch($city, $domain?->id, $type);
                    $dispatched++;
                }
            }
        }

        if ($dispatched > 0) {
            return redirect()->back()->with('success', "Dispatched {$dispatched} content generation jobs!");
        }

        return redirect()->back()->with('info', 'All pages already exist or no content service available.');
    }

    public function generationProgress(City $city)
    {
        $domain = Domain::current();
        $cacheKey = "city_content_progress_{$city->id}_".($domain?->id ?? 'default');
        $progress = Cache::get($cacheKey, ['total' => 0, 'completed' => 0, 'percentage' => 0]);

        return response()->json($progress);
    }

    public function deletePages(City $city)
    {
        $domain = Domain::current();
        $deleted = ServicePage::where('city_id', $city->id)
            ->where('domain_id', $domain?->id)
            ->delete();

        return redirect()->back()->with('success', "Deleted {$deleted} service pages!");
    }

    public function updateGmb(Request $request, City $city)
    {
        $validated = $request->validate([
            'google_business_url' => 'nullable|url|max:500',
        ]);

        $domain = Domain::current();
        DomainCity::where('domain_id', $domain?->id)
            ->where('city_id', $city->id)
            ->update(['gmb_url' => $validated['google_business_url'] ?? null]);

        return redirect()->back()->with('success', 'Google Business URL updated!');
    }

    public function importJson(Request $request)
    {
        $validated = $request->validate([
            'json_file' => 'required|file|mimes:json|max:2048',
        ]);

        $data = json_decode(file_get_contents($request->file('json_file')->getRealPath()), true);

        if (! is_array($data)) {
            return redirect()->back()->with('error', 'Invalid JSON file.');
        }

        $imported = 0;
        foreach ($data as $item) {
            if (empty($item['name']) || empty($item['state_code'])) {
                continue;
            }

            $state = State::where('code', $item['state_code'])->first();
            if (! $state) {
                continue;
            }

            $city = City::firstOrCreate(
                ['name' => $item['name'], 'state_id' => $state->id],
                [
                    'slug' => Str::slug($item['name'].'-'.$state->code),
                    'county' => $item['county'] ?? null,
                    'population' => $item['population'] ?? null,
                    'latitude' => $item['latitude'] ?? null,
                    'longitude' => $item['longitude'] ?? null,
                    'is_active' => true,
                ]
            );

            if ($city->wasRecentlyCreated) {
                $imported++;
            }
        }

        return redirect()->back()->with('success', "Imported {$imported} cities!");
    }

    public function getSampleJson(City $city)
    {
        $domain = Domain::current() ?? Domain::first();
        $domainName = $domain?->domain ?? 'example.com';
        $serviceLabel = $domain?->primary_service ?? 'Service';
        $types = $domain?->getServiceTypes() ?? ['general', 'construction', 'wedding', 'event', 'luxury', 'party', 'emergency', 'residential'];

        $samplePages = [];
        foreach ($types as $type) {
            $typeLabel = $domain?->getServiceTypeLabel($type) ?? ucfirst($type).' '.$serviceLabel;

            $samplePages[] = [
                'service_type' => $type,
                'slug' => strtolower($city->name).'-'.strtolower($type).'-'.strtolower($city->state->code),
                'h1_title' => $typeLabel.' in '.$city->name.', '.$city->state->code.' | Same-Day Delivery',
                'h2_headings' => [
                    'Why Choose Our '.$typeLabel.' Services in '.$city->name.'?',
                    'Professional '.$typeLabel.' for All Your Needs',
                    'Serving '.$city->name.' and Surrounding Areas',
                ],
                'meta_title' => $typeLabel.' '.$city->name.', '.$city->state->code.' | Free Quote & Same-Day Delivery',
                'meta_description' => 'Looking for '.$typeLabel.' in '.$city->name.', '.$city->state->code.'? We offer fast delivery, competitive prices, and quality service. Get your free quote today! Serving '.$city->name.', '.$city->state->name.' and nearby areas.',
                'meta_keywords' => $type.' '.$serviceLabel.' '.$city->name.', quality service '.$city->state->code.', reliable '.$city->name,
                'og_title' => $typeLabel.' '.$city->name.' - Call Now for Free Quote',
                'og_description' => 'Professional '.$typeLabel.' services in '.$city->name.', '.$city->state->code.'. Same-day delivery available. Call us today!',
                'og_image' => '/images/'.$type.'-rental-'.strtolower($city->name).'-'.strtolower($city->state->code).'.jpg',
                'og_url' => 'https://'.$domainName.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/'.$type,
                'twitter_card' => 'summary_large_image',
                'twitter_title' => $typeLabel.' '.$city->name.' | Free Quote',
                'twitter_description' => 'Get professional '.$typeLabel.' in '.$city->name.'. Same-day delivery available!',
                'twitter_image' => '/images/social/'.$type.'-'.strtolower($city->name).'-'.strtolower($city->state->code).'.jpg',
                'canonical_url' => 'https://'.$domainName.'/'.strtolower($city->name).'-'.$type.'-'.strtolower($city->state->code),
                'schema_markup' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'LocalBusiness',
                    'name' => $typeLabel.' - '.$city->name,
                    'description' => 'Professional '.$typeLabel.' services in '.$city->name.', '.$city->state->code.'. Serving residential, commercial, and construction clients.',
                    'telephone' => '+1-XXX-XXX-XXXX',
                    'email' => 'info@'.$domainName,
                    'url' => 'https://'.$domainName.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/'.$type,
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
                    'serviceType' => $typeLabel,
                    'hasOfferCatalog' => [
                        '@type' => 'OfferCatalog',
                        'name' => $serviceLabel.' Services - '.$city->name,
                        'itemListElement' => [
                            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Standard '.$serviceLabel, 'description' => 'Standard unit for construction and events']],
                            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Deluxe '.$serviceLabel, 'description' => 'Premium unit with enhanced features']],
                            ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Accessible Unit', 'description' => 'Wheelchair accessible '.$serviceLabel]],
                        ],
                    ],
                    'review' => ['@type' => 'Review', 'reviewRating' => ['@type' => 'Rating', 'ratingValue' => '4.8', 'bestRating' => '5'], 'author' => ['@type' => 'Organization', 'name' => $domain?->business_name ?? 'Our Company']],
                    'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => '4.8', 'reviewCount' => '127', 'bestRating' => '5'],
                    'sameAs' => $domain?->google_business_url ? explode(',', $domain->google_business_url) : [],
                ],
                'faq_schema' => [
                    ['@type' => 'Question', 'name' => 'How much does '.$typeLabel.' cost in '.$city->name.'?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Our '.$typeLabel.' prices in '.$city->name.' start at competitive rates. We offer competitive pricing and free quotes. Contact us for exact pricing based on your needs.']],
                    ['@type' => 'Question', 'name' => 'Do you offer same-day delivery in '.$city->name.'?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes! We offer same-day delivery in '.$city->name.' for orders placed before noon. Weekend and emergency delivery available.']],
                ],
                'breadcrumb_schema' => [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://'.$domainName],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $city->name, 'item' => 'https://'.$domainName.'/'.strtolower($city->name).'-'.strtolower($city->state->code)],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => ucfirst($type).' Rental', 'item' => 'https://'.$domainName.'/'.strtolower($city->name).'-'.strtolower($city->state->code).'/'.$type],
                    ],
                ],
                'content' => '<h2>Welcome to '.$city->name."'s Premier ".$typeLabel.' Service</h2>
                <p>Looking for reliable '.$typeLabel.' in '.$city->name.', '.$city->state->code.'? You\'ve come to the right place. We are the leading provider of portable sanitation solutions in the '.$city->name.' area, serving both residential and commercial clients with top-quality equipment and exceptional customer service.</p>

                <h2>Why Choose Our '.$typeLabel.' Services in '.$city->name.'?</h2>
                <p>When it comes to portable sanitation in '.$city->name.', we stand out from the competition. Here\'s why contractors, event planners, and homeowners trust us:</p>
                <ul>
                <li><strong>Fast Delivery:</strong> We offer same-day delivery in '.$city->name.' and surrounding areas.</li>
                <li><strong>Clean Units:</strong> All our units are thoroughly cleaned and sanitized before delivery.</li>
                <li><strong>Competitive Pricing:</strong> Get the best value for your money with our transparent pricing.</li>
                <li><strong>Professional Service:</strong> Our team is dedicated to providing excellent customer service.</li>
                <li><strong>Local Expertise:</strong> We know '.$city->name.' and can recommend the best solutions for your needs.</li>
                </ul>

                <h2>Our '.$typeLabel.' Options in '.$city->name.'</h2>
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

                <h2>Get Your Free Quote for '.$typeLabel.' in '.$city->name.'</h2>
                <p>Ready to get started? Contact us today for a free quote on '.$typeLabel.' in '.$city->name.', '.$city->state->code.'. Our team will work with you to find the best solution for your needs and budget.</p>
                <p>Call us now at {{PHONE_LINK}} or fill out our online form to request a quote. We look forward to serving you in '.$city->name.'!</p>',
                'word_count' => 450,
                'internal_links' => [
                    ['text' => 'construction site rentals', 'url' => '/construction-rental-'.$city->slug],
                    ['text' => 'wedding rentals', 'url' => '/wedding-rental-'.$city->slug],
                    ['text' => 'event rentals', 'url' => '/event-rental-'.$city->slug],
                ],
            ];
        }

        return response()->json($samplePages);
    }

    public function generateServiceImages(string $type, string $cityName): array
    {
        if (! app()->bound(ImageService::class)) {
            return [];
        }

        $domain = Domain::current() ?? Domain::first();
        $typeLabel = $domain?->getServiceTypeLabel($type) ?? ucfirst($type).' Service';
        $altText = $typeLabel.' in '.$cityName;

        return app(ImageService::class)->getRandomImagesForContent(3, $altText);
    }
}
