<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\City;
use App\Models\Domain;
use App\Models\Faq;
use App\Models\ServicePage;
use App\Models\State;
use App\Models\Testimonial;
use App\Providers\DomainViewHelper;
use App\Services\ContentGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /**
     * Homepage
     */
    public function home()
    {
        $domain = Domain::current();
        $domainId = $domain?->id ?? 'default';

        // Homepage is USA-wide - no city-specific data
        // City pages (/city-slug) handle city-specific content
        $topCities = [];
        $featuredCities = [];

        $states = Cache::remember("home_active_states_{$domainId}", 3600, function () {
            return State::whereHas('domainStates', function ($q) {
                $q->where('status', true);
            })
                ->withCount(['cities' => function ($q) {
                    $q->active();
                }])
                ->orderBy('name')
                ->take(8)
                ->get()
                ->toArray();
        });

        $recentPosts = BlogPost::published()
            ->latest('published_at')
            ->take(6)
            ->get()
            ->toArray();

        // Cache a pool, shuffle in PHP — avoids ORDER BY RAND() on every request
        $testimonialPool = Cache::remember("home_testimonial_pool_{$domainId}", 3600, function () {
            return Testimonial::where('is_featured', true)
                ->where('is_active', true)
                ->take(30)
                ->get()
                ->toArray();
        });
        $testimonials = collect($testimonialPool)->shuffle()->take(3);

        $stats = Cache::remember("home_stats_{$domainId}", 3600, function () use ($domainId) {
            $totalCities = DB::table('domain_cities')
                ->where('domain_id', $domainId)
                ->distinct('city_id')
                ->count('city_id');

            $generatedCities = DB::table('domain_cities')
                ->where('domain_id', $domainId)
                ->whereExists(function ($q) use ($domainId) {
                    $q->select(DB::raw(1))
                        ->from('service_pages')
                        ->whereColumn('service_pages.city_id', 'domain_cities.city_id')
                        ->where('service_pages.generation_status', 'success')
                        ->where('service_pages.domain_id', $domainId);
                })
                ->distinct('city_id')
                ->count('city_id');

            $totalStates = DB::table('domain_states')
                ->where('domain_id', $domainId)
                ->count();

            $generatedStates = DB::table('domain_states')
                ->where('domain_id', $domainId)
                ->whereExists(function ($q) use ($domainId) {
                    $q->select(DB::raw(1))
                        ->from('service_pages')
                        ->join('cities', 'service_pages.city_id', '=', 'cities.id')
                        ->whereColumn('cities.state_id', 'domain_states.state_id')
                        ->where('service_pages.generation_status', 'success')
                        ->where('service_pages.domain_id', $domainId);
                })
                ->count();

            return compact('generatedCities', 'totalCities', 'generatedStates', 'totalStates');
        });

        // Primary city data for NAP/Geo schema
        // Homepage is USA-wide by default - city-specific content is on city pages
        // Geo-redirect middleware handles redirecting to city pages when detection works
        $primaryCity = null;
        $latitude = $primaryCity['latitude'] ?? null;
        $longitude = $primaryCity['longitude'] ?? null;
        $cityAddress = $primaryCity['name'] ?? null;
        $stateCodeLocal = $primaryCity['state']['code'] ?? null;
        $postalCode = $primaryCity['zip_code'] ?? null;

        return view(DomainViewHelper::resolveForController('home'), compact(
            'featuredCities', 'states', 'recentPosts', 'testimonials', 'topCities', 'stats',
            'latitude', 'longitude', 'cityAddress', 'stateCodeLocal', 'postalCode'
        ));
    }

    /**
     * Services Page
     */
    public function services()
    {
        $serviceTypes = [
            'standard' => [
                'key' => 'standard',
                'name' => 'Standard Porta Potties',
                'short_name' => 'Standard',
                'icon' => 'building',
                'description' => 'The most common portable toilet for construction sites and outdoor events. Basic, functional, and OSHA compliant.',
                'features' => [
                    'Non-splash urinal',
                    'Ventilation system',
                    'Anti-slip floor',
                    'Hand sanitizer dispenser',
                    'Toilet paper holder',
                ],
                'best_for' => ['Construction Sites', 'Outdoor Events', 'Work Zones'],
            ],
            'deluxe' => [
                'key' => 'deluxe',
                'name' => 'Deluxe Flushable Units',
                'short_name' => 'Deluxe',
                'icon' => 'water-drop',
                'description' => 'Premium portable toilet with flushing toilet and hand wash station. Ideal for weddings and upscale events.',
                'features' => [
                    'Flushing toilet',
                    'Hand sink with running water',
                    'Interior mirror',
                    'Improved ventilation',
                    'Interior lighting',
                ],
                'best_for' => ['Weddings', 'Private Events', 'Corporate Events'],
            ],
            'ada' => [
                'key' => 'ada',
                'name' => 'ADA Accessible Units',
                'short_name' => 'ADA',
                'icon' => 'accessibility',
                'description' => 'Wheelchair-accessible portable restroom with extra-wide door, grab bars, and spacious interior.',
                'features' => [
                    'Extra-wide door (60" wide)',
                    'Interior grab bars',
                    'Non-slip flooring',
                    'Lowered seat height',
                    'Spacious interior (90" ceiling)',
                ],
                'best_for' => ['Public Events', 'ADA Compliance', 'Construction Sites'],
            ],
            'luxury' => [
                'key' => 'luxury',
                'name' => 'Luxury Restroom Trailers',
                'short_name' => 'Luxury',
                'icon' => 'sparkles',
                'description' => 'High-end restroom trailers with climate control, porcelain fixtures, and elegant interiors.',
                'features' => [
                    'Climate controlled (A/C & heat)',
                    'Porcelain flush toilets',
                    'Vanity with mirror',
                    'LED lighting',
                    'Men\'s and women\'s sides',
                ],
                'best_for' => ['VIP Events', 'Weddings', 'Film Productions'],
            ],
            'shower' => [
                'key' => 'shower',
                'name' => 'Portable Shower Units',
                'short_name' => 'Showers',
                'icon' => 'water-drop',
                'description' => 'Private portable shower stalls for construction sites, events, and disaster relief situations.',
                'features' => [
                    'Hot and cold water',
                    'Privacy curtains',
                    'Drainage system',
                    'Changing area',
                    'Soap dispensers',
                ],
                'best_for' => ['Construction Sites', 'Camping', 'Disaster Relief'],
            ],
            'mobile' => [
                'key' => 'mobile',
                'name' => 'Mobile Restroom Trailers',
                'short_name' => 'Mobile',
                'icon' => 'truck',
                'description' => 'Self-contained mobile restroom units that can be transported to any location.',
                'features' => [
                    'Multiple fixtures',
                    'Climate control',
                    'Fresh water system',
                    'Waste holding tanks',
                    'Generator powered',
                ],
                'best_for' => ['Remote Sites', 'Film Sets', 'Emergency Response'],
            ],
            'vip' => [
                'key' => 'vip',
                'name' => 'VIP Event Restrooms',
                'short_name' => 'VIP',
                'icon' => 'briefcase',
                'description' => 'Premium restroom solutions for VIP events, executive gatherings, and high-profile occasions.',
                'features' => [
                    'Upscale interiors',
                    'Climate controlled',
                    'Premium fixtures',
                    'Attendant available',
                    'Custom branding options',
                ],
                'best_for' => ['Corporate Events', 'Galas', 'Celebrity Events'],
            ],
            'construction' => [
                'key' => 'construction',
                'name' => 'Construction Site Packages',
                'short_name' => 'Construction',
                'icon' => 'building',
                'description' => 'Complete sanitation packages designed for construction sites with OSHA compliance.',
                'features' => [
                    'Multiple standard units',
                    'Weekly servicing included',
                    'OSHA documentation',
                    'On-site supervisor',
                    'Volume discounts',
                ],
                'best_for' => ['Large Construction', 'High-rise Projects', 'Road Work'],
            ],
            'holding' => [
                'key' => 'holding',
                'name' => 'Holding Tank Rentals',
                'short_name' => 'Holding Tanks',
                'icon' => 'cube',
                'description' => 'Large capacity holding tanks for remote job sites and locations without access to sewage connections.',
                'features' => [
                    'Large capacity (500-1000 gallon)',
                    'Remote location ready',
                    'Regular pumping service',
                    'Multiple unit connections',
                    'Weather resistant',
                ],
                'best_for' => ['Remote Sites', 'Mining', 'Oil Fields', 'Pipeline Projects'],
            ],
            'sanitizer' => [
                'key' => 'sanitizer',
                'name' => 'Hand Sanitizer Stations',
                'short_name' => 'Sanitizer',
                'icon' => 'wash',
                'description' => 'Standalone hand sanitizer and hand washing stations to complement your rental.',
                'features' => [
                    'Touchless dispensers',
                    'Soap and water',
                    'Paper towel stations',
                    'Wheelchair accessible',
                    'Standalone or mounted',
                ],
                'best_for' => ['Events', 'Construction Sites', 'Food Service', 'Healthcare'],
            ],
            'dumpster' => [
                'key' => 'dumpster',
                'name' => 'Dumpster Rental',
                'short_name' => 'Dumpster',
                'icon' => 'trash',
                'description' => 'Roll-off dumpsters for construction debris, event waste, and sanitation waste disposal. Available in multiple sizes.',
                'features' => [
                    'Various sizes (10-40 yard)',
                    'Same-day delivery',
                    'Flexible pickup schedules',
                    'Permit assistance',
                    'Recycling available',
                ],
                'best_for' => ['Construction Sites', 'Events', 'Home Renovations', 'Commercial'],
            ],
            'septic' => [
                'key' => 'septic',
                'name' => 'Septic Service',
                'short_name' => 'Septic',
                'icon' => 'wrench',
                'description' => 'Professional septic tank pumping, inspection, and maintenance services for residential and commercial properties.',
                'features' => [
                    'Septic tank pumping',
                    'System inspection',
                    'Maintenance contracts',
                    'Emergency pumping',
                    'Grease trap service',
                ],
                'best_for' => ['Residential', 'Commercial', 'Restaurants', 'Farm Properties'],
            ],
        ];

        $addOns = [
            [
                'icon' => 'wash',
                'name' => 'Hand Wash Stations',
                'description' => 'Standalone hand washing units with soap and paper towels.',
            ],
            [
                'icon' => 'bolt',
                'name' => 'Lighting Packages',
                'description' => 'Solar-powered lights for nighttime events and construction sites.',
            ],
            [
                'icon' => 'sparkles',
                'name' => 'Extra Servicing',
                'description' => 'Additional weekly cleaning and restocking beyond standard service.',
            ],
            [
                'icon' => 'water-drop',
                'name' => 'Fresh Water Delivery',
                'description' => 'Fresh water delivery for luxury trailers and hand wash stations.',
            ],
            [
                'icon' => 'wrench',
                'name' => '24/7 Emergency Service',
                'description' => 'Emergency pumping, repairs, and additional units available 24/7.',
            ],
            [
                'icon' => 'shield-check',
                'name' => 'Compliance Documentation',
                'description' => 'OSHA compliance paperwork, service logs, and audit support.',
            ],
            [
                'icon' => 'cube',
                'name' => 'Extra Supplies',
                'description' => 'Additional toilet paper, hand sanitizer, and deodorizer refills.',
            ],
            [
                'icon' => 'home',
                'name' => 'Long-Term Rentals',
                'description' => 'Discounted rates for monthly and long-term rental agreements.',
                'price' => 'Up to 40% off',
            ],
        ];

        return view(DomainViewHelper::resolveForController('services'), compact('serviceTypes', 'addOns'));
    }

    /**
     * Pricing Page - No specific prices shown
     */
    public function pricing()
    {
        $pricingInfo = [
            [
                'icon' => 'building',
                'title' => 'Standard Rental',
                'description' => 'Basic, functional units perfect for construction sites and work areas. OSHA compliant and budget-friendly.',
                'best_for' => 'Construction Sites, Work Zones, Outdoor Projects',
                'includes' => [
                    'Weekly servicing and cleaning',
                    'Delivery and setup',
                    'OSHA compliant',
                    'Hand sanitizer included',
                ],
                'cta' => 'Get Quote for Standard Units',
            ],
            [
                'icon' => 'water-drop',
                'title' => 'Deluxe Flushable Unit',
                'description' => 'Premium units with flushing toilet and hand sink. Ideal for events where guests expect more comfort.',
                'best_for' => 'Weddings, Private Events, Corporate Functions',
                'includes' => [
                    'Flushing toilet',
                    'Hand sink with running water',
                    'Interior mirror and lighting',
                    'Weekly servicing included',
                ],
                'cta' => 'Get Quote for Deluxe Units',
            ],
            [
                'icon' => 'accessibility',
                'title' => 'ADA Accessible Unit',
                'description' => 'Wheelchair-accessible units that meet all federal accessibility requirements.',
                'best_for' => 'Public Events, ADA Compliance, Venues',
                'includes' => [
                    'Extra-wide door for wheelchair access',
                    'Interior grab bars',
                    'Non-slip flooring',
                    'Spacious interior',
                ],
                'cta' => 'Get Quote for ADA Units',
            ],
            [
                'icon' => 'sparkles',
                'title' => 'Luxury Restroom Trailer',
                'description' => 'High-end trailers with climate control, porcelain fixtures, and elegant interiors.',
                'best_for' => 'VIP Events, Weddings, Film Productions',
                'includes' => [
                    'Climate controlled (A/C & heat)',
                    'Porcelain flush toilets',
                    'Vanity with mirror',
                    'Men\'s and women\'s sides',
                ],
                'cta' => 'Get Quote for Luxury Trailers',
            ],
            [
                'icon' => 'water-drop',
                'title' => 'Portable Shower Unit',
                'description' => 'Private shower stalls for construction sites, events, and remote locations.',
                'best_for' => 'Construction Sites, Camping, Events',
                'includes' => [
                    'Hot and cold water',
                    'Privacy curtains',
                    'Drainage system',
                    'Changing area',
                ],
                'cta' => 'Get Quote for Shower Units',
            ],
            [
                'icon' => 'cube',
                'title' => 'Holding Tank',
                'description' => 'Large capacity tanks for remote job sites without sewage access.',
                'best_for' => 'Remote Sites, Mining, Oil Fields',
                'includes' => [
                    '500-1000 gallon capacity',
                    'Remote location ready',
                    'Regular pumping service',
                    'Weather resistant',
                ],
                'cta' => 'Get Quote for Holding Tanks',
            ],
        ];

        $factors = [
            [
                'title' => 'Number of Units',
                'description' => 'The more units you rent, the better value you get. We offer volume discounts for large orders.',
            ],
            [
                'title' => 'Rental Duration',
                'description' => 'Daily, weekly, and monthly rentals available. Long-term rentals come with significant savings.',
            ],
            [
                'title' => 'Unit Type',
                'description' => 'Standard, deluxe, ADA, and luxury units have different pricing based on features and amenities.',
            ],
            [
                'title' => 'Location',
                'description' => 'Delivery distance and local regulations can affect pricing. Call us for location-specific quotes.',
            ],
            [
                'title' => 'Servicing Frequency',
                'description' => 'Weekly servicing is included. Extra servicing or event-only rentals may have different pricing.',
            ],
        ];

        return view(DomainViewHelper::resolveForController('pricing'), compact('pricingInfo', 'factors'));
    }

    /**
     * About Page
     */
    public function about()
    {
        $domain = Domain::current();

        return view(DomainViewHelper::resolveForController('about'));
    }

    /**
     * Privacy Page
     */
    public function privacy()
    {
        return view(DomainViewHelper::resolveForController('privacy'));
    }

    /**
     * Terms Page
     */
    public function terms()
    {
        return view(DomainViewHelper::resolveForController('terms'));
    }

    /**
     * City Service Page (SEO এর মূল পেজ)
     */
    public function cityPage(string $slug)
    {
        $domain = Domain::current();
        $domainId = $domain?->id ?? 'default';

        // Negative cache: bots probe random slugs; skip the DB lookup for known-404s
        if (Cache::has("slug_404_{$domainId}_{$slug}")) {
            abort(404);
        }

        $query = ServicePage::where('slug', $slug)
            ->where('is_published', true)
            ->with([
                'city.state',
                'city.phoneNumbers',
                'city.faqs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'city.testimonials' => fn ($q) => $q->where('is_active', true),
            ]);

        if ($domain) {
            $query->whereHas('city', function ($q) use ($domain) {
                $q->whereHas('domainCities', function ($dq) use ($domain) {
                    $dq->where('domain_id', $domain->id)->where('status', true);
                });
            });
        } else {
            $query->whereHas('city', fn ($q) => $q->where('is_active', true));
        }

        $servicePage = $query->first();

        if (! $servicePage) {
            Cache::put("slug_404_{$domainId}_{$slug}", true, 600);
            abort(404);
        }

        $city = $servicePage->city;

        // View count বাড়ান — run after response so the LCP isn't blocked by a DB write
        defer(fn () => $servicePage->incrementViews());

        // FAQs - use eager loaded collection
        $cityFaqs = $city->faqs
            ->where('is_active', true)
            ->where('service_type', $servicePage->service_type)
            ->sortBy('sort_order');
        if ($cityFaqs->isEmpty()) {
            $faqs = $city->faqs
                ->where('is_active', true)
                ->sortBy('sort_order');
        } else {
            $faqs = $cityFaqs;
        }

        // Testimonials - use eager loaded collection, filter by service type
        $testimonials = $city->testimonials
            ->where('is_active', true)
            ->where('service_type', $servicePage->service_type)
            ->shuffle()
            ->take(4);

        // Fallback to general testimonials if none found for service type
        if ($testimonials->isEmpty()) {
            $testimonials = $city->testimonials
                ->where('is_active', true)
                ->where('service_type', 'general')
                ->shuffle()
                ->take(4);
        }

        $nearbyNames = $city->getNearbyAreaNames();
        $nearbyCityPages = City::active()
            ->whereIn('name', $nearbyNames)
            ->with('state')
            ->has('servicePages')
            ->take(8)
            ->get();

        $otherServices = ServicePage::where('city_id', $city->id)
            ->where('id', '!=', $servicePage->id)
            ->where('is_published', true)
            ->get();

        $relatedPosts = BlogPost::published()
            ->where(function ($q) use ($city, $servicePage) {
                $q->where('city_id', $city->id)
                    ->orWhere('title', 'LIKE', "%{$servicePage->service_type}%");
            })
            ->take(3)
            ->get();

        $schemaMarkup = $servicePage->generateSchemaMarkup();

        $faqSchema = null;
        if ($faqs->isNotEmpty()) {
            $faqSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqs->map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq->answer,
                    ],
                ])->toArray(),
            ];
        }

        // NOTE: we intentionally do NOT inject Review/AggregateRating schema here
        // even though $testimonials exists. Those testimonials are AI-generated and
        // marking them up would violate Google's Review Snippet policy. To re-enable,
        // wire a real-reviews source (Google Business Profile sync) and gate on
        // config('reviews.count') being set.

        return view(DomainViewHelper::resolveForController('service'), compact(
            'servicePage', 'city', 'faqs', 'testimonials',
            'nearbyCityPages', 'otherServices', 'relatedPosts',
            'schemaMarkup', 'faqSchema', 'domain'
        ));
    }

    /**
     * State Page — সেই রাজ্যের সব শহর দেখাবে
     */
    public function statePage(string $slug, string $stateSlug, ContentGeneratorService $contentService)
    {
        $domain = Domain::current();
        $state = State::where('slug', $stateSlug)
            ->where('is_active', true)
            ->with('domainStates')
            ->firstOrFail();

        defer(fn () => $state->incrementViews());

        $cities = $state->activeCities()
            ->has('servicePages')
            ->with('state')
            ->byPriority()
            ->paginate(30);

        // getStatePageContent runs AI calls in the worst case — cache hard
        $stateContent = Cache::remember("state_content_{$state->id}", 3600, fn () => $contentService->getStatePageContent($state)
        );
        $faqs = collect($stateContent['faqs'] ?? []);
        $images = $state->images ?? [];

        return view(DomainViewHelper::resolveForController('state'), compact('state', 'cities', 'stateContent', 'faqs', 'images', 'domain'));
    }

    /**
     * All Locations Page
     */
    public function locations(Request $request)
    {
        $search = trim($request->get('q', ''));
        $domain = Domain::current();
        $domainId = $domain?->id ?? 'default';

        $fetchStates = function () use ($search, $domain) {
            return State::select(['id', 'name', 'slug', 'code'])
                ->whereHas('domainStates', function ($q) use ($domain) {
                    $q->where('status', true);
                    if ($domain) {
                        $q->where('domain_id', $domain->id);
                    }
                })
                ->with(['cities' => function ($q) use ($search, $domain) {
                    $q->select(['id', 'state_id', 'name', 'slug', 'latitude', 'longitude', 'priority']);
                    $q->active();
                    $q->whereHas('servicePages', fn ($sp) => $sp->where('is_published', true)->when($domain, fn ($q) => $q->where('domain_id', $domain->id)));
                    if ($domain) {
                        $q->whereIn('id', fn ($sub) => $sub->select('city_id')->from('domain_cities')->where('domain_id', $domain->id)->where('status', true));
                    }
                    if (! $search) {
                        $q->orderBy('priority', 'desc')->orderBy('name')->limit(200);
                    } else {
                        $q->orderBy('name');
                    }
                }])
                ->orderBy('name')
                ->get();
        };

        // Do NOT cache to avoid serialization issues with Eloquent Collections
        $states = $fetchStates();

        return view(DomainViewHelper::resolveForController('locations'), compact('states', 'search', 'domain'));
    }

    /**
     * FAQ Schema Generate
     */
    protected function generateFaqSchema($faqs): array
    {
        if ($faqs->isEmpty()) {
            return [];
        }

        $faqItems = $faqs->map(function ($faq) {
            return [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq->answer,
                ],
            ];
        })->toArray();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqItems,
        ];
    }
}
