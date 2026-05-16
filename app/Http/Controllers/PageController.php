<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\City;
use App\Models\Domain;
use App\Models\Faq;
use App\Models\Neighborhood;
use App\Models\NeighborhoodServicePage;
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

        // Homepage is USA-wide — show top cities with published service pages
        $topCities = Cache::remember("home_top_cities_{$domainId}", 3600, function () use ($domain) {
            $cities = City::whereHas('domainCities', function ($q) use ($domain) {
                    $q->where('domain_id', $domain->id);
                })
                ->whereHas('servicePages', function ($q) use ($domain) {
                    $q->where('domain_id', $domain->id)->where('is_published', true);
                })
                ->with('state')
                ->inRandomOrder()
                ->take(12)
                ->get();

            // Fallback: random domain cities if no published pages exist yet
            if ($cities->isEmpty()) {
                $cities = City::whereHas('domainCities', function ($q) use ($domain) {
                        $q->where('domain_id', $domain->id);
                    })
                    ->with('state')
                    ->inRandomOrder()
                    ->take(12)
                    ->get();
            }

            // Eager-load service pages to avoid N+1 in the view
            $cityIds = $cities->pluck('id');
            $pages = ServicePage::whereIn('city_id', $cityIds)
                ->where('domain_id', $domain->id)
                ->where('is_published', true)
                ->get()
                ->keyBy('city_id');

            return $cities->map(function ($city) use ($pages) {
                $data = $city->toArray();
                $data['service_page'] = optional($pages->get($city->id))->slug;
                return $data;
            })->toArray();
        });

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
            'states', 'recentPosts', 'testimonials', 'topCities', 'stats',
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
            'portable-urinal' => [
                'key' => 'portable-urinal',
                'name' => 'Portable Urinal Stations',
                'short_name' => 'Urinals',
                'icon' => 'water-drop',
                'description' => 'Standalone portable urinal stations for festivals, concerts, sporting events, and high-traffic areas. Reduces wait times and complements restroom trailers.',
                'features' => [
                    'Standalone male/female urinal units',
                    'High-capacity waste tank',
                    'Privacy screening',
                    'Easy setup and teardown',
                    'Odor control system',
                ],
                'best_for' => ['Festivals', 'Concerts', 'Sporting Events', 'Fairs'],
            ],
            'handwash-trailer' => [
                'key' => 'handwash-trailer',
                'name' => 'Hand Wash Trailers',
                'short_name' => 'Hand Wash',
                'icon' => 'wash',
                'description' => 'Trailer-mounted hand washing stations with multiple sinks, hot/cold running water, and soap dispensers. Ideal for events, food service, and construction sites.',
                'features' => [
                    '4-6 hand wash stations per trailer',
                    'Hot and cold running water',
                    'Soap and paper towel dispensers',
                    'Waste water holding tank',
                    'ADA compliant options',
                ],
                'best_for' => ['Food Festivals', 'Construction Sites', 'Corporate Events', 'Fairs'],
            ],
            'temporary-fencing' => [
                'key' => 'temporary-fencing',
                'name' => 'Temporary Fencing & Barriers',
                'short_name' => 'Fencing',
                'icon' => 'shield-check',
                'description' => 'Portable fencing, crowd control barriers, and privacy screens for construction sites, events, and restricted areas.',
                'features' => [
                    'Chain link and panel fencing',
                    'Crowd control barriers',
                    'Privacy screens and mesh',
                    'Gate and lock options',
                    'Same-day setup available',
                ],
                'best_for' => ['Construction Sites', 'Outdoor Events', 'Festivals', 'Road Work'],
            ],
            'highrise' => [
                'key' => 'highrise',
                'name' => 'High-Rise Construction Toilets',
                'short_name' => 'High-Rise',
                'icon' => 'building',
                'description' => 'Compact porta potties engineered for multi-story construction projects. Designed to fit in service elevators or be crane-lifted to upper floors.',
                'features' => [
                    'Compact footprint fits service elevators',
                    'Crane-liftable with reinforced lifting points',
                    'Lightweight composite construction',
                    'OSHA compliant for vertical builds',
                    'Easy to move floor-to-floor',
                ],
                'best_for' => ['High-Rise Construction', 'Urban Job Sites', 'Multi-Story Projects', 'Rooftop Work'],
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
            [
                'icon' => 'bolt',
                'name' => 'Generator Rentals',
                'description' => 'Portable generators to power luxury restroom trailers, lighting, and event equipment.',
            ],
            [
                'icon' => 'document',
                'name' => 'Restroom Signage',
                'description' => 'ADA-compliant restroom signs, directional markers, and wayfinding signage for events.',
            ],
            [
                'icon' => 'building',
                'name' => 'Privacy Screens',
                'description' => 'Portable privacy enclosures and screening walls for restroom areas and event spaces.',
            ],
            [
                'icon' => 'sparkles',
                'name' => 'Deodorizing Service',
                'description' => 'Professional odor control treatment and fragrance maintenance for all rental units.',
            ],
            [
                'icon' => 'home',
                'name' => 'Baby Changing Stations',
                'description' => 'Portable baby changing tables available as add-ons for family-friendly events and venues.',
            ],
        ];

        $pricingEnabled = config('service_pricing.enabled', false);

        return view(DomainViewHelper::resolveForController('services'), compact('serviceTypes', 'addOns', 'pricingEnabled'));
    }

    /**
     * Pricing Page - No specific prices shown
     */
    public function pricing()
    {
        $pricingEnabled = config('service_pricing.enabled', false);
        $priceRanges = config('service_pricing.ranges', []);

        $pricingInfo = [
            [
                'key' => 'standard',
                'icon' => 'building',
                'title' => 'Standard Porta Potty',
                'short_title' => 'Standard',
                'daily_label' => '$89 – $175',
                'weekly_label' => '$445 – $875',
                'monthly_label' => '$1,335 – $2,625',
                'description' => 'Basic, OSHA-compliant units perfect for construction sites and work zones. Includes sanitizer dispenser and weekly servicing.',
                'best_for' => 'Construction Sites, Work Zones, Outdoor Projects',
                'popular' => true,
                'includes' => [
                    'Weekly servicing and cleaning',
                    'Delivery and setup within 50 miles',
                    'OSHA compliant design',
                    'Hand sanitizer dispenser',
                    'Toilet paper and deodorizer',
                    'Pickup at end of rental',
                ],
            ],
            [
                'key' => 'deluxe',
                'icon' => 'water-drop',
                'title' => 'Deluxe Flushable Unit',
                'short_title' => 'Deluxe',
                'daily_label' => '$150 – $275',
                'weekly_label' => '$750 – $1,375',
                'monthly_label' => '$2,250 – $4,125',
                'description' => 'Premium units with flushing toilet and hand sink. Ideal for weddings and upscale events where guest comfort matters.',
                'best_for' => 'Weddings, Private Events, Corporate Functions',
                'popular' => false,
                'includes' => [
                    'Flushing toilet with foot pump',
                    'Hand sink with running water',
                    'Interior mirror and lighting',
                    'Improved ventilation system',
                    'Weekly servicing included',
                    'Delivery and setup',
                ],
            ],
            [
                'key' => 'ada',
                'icon' => 'accessibility',
                'title' => 'ADA Accessible Unit',
                'short_title' => 'ADA',
                'daily_label' => '$125 – $250',
                'weekly_label' => '$625 – $1,250',
                'monthly_label' => '$1,875 – $3,750',
                'description' => 'Wheelchair-accessible units meeting all federal ADA requirements. Required for many public events and job sites.',
                'best_for' => 'Public Events, ADA Compliance, Venues',
                'popular' => false,
                'includes' => [
                    'Extra-wide 60" door for wheelchair access',
                    'Interior grab bars',
                    'Non-slip flooring',
                    'Spacious interior with 90" ceiling',
                    'Lowered seat height',
                    'Weekly servicing included',
                ],
            ],
            [
                'key' => 'luxury',
                'icon' => 'sparkles',
                'title' => 'Luxury Restroom Trailer',
                'short_title' => 'Luxury',
                'daily_label' => '$500 – $2,500',
                'weekly_label' => '$2,500 – $12,500',
                'monthly_label' => '$7,500 – $37,500',
                'description' => 'High-end trailers with climate control, porcelain fixtures, and elegant interiors. Perfect for VIP events and weddings.',
                'best_for' => 'VIP Events, Weddings, Film Productions',
                'popular' => false,
                'includes' => [
                    'Climate controlled (A/C & heat)',
                    'Porcelain flush toilets',
                    'Vanity with mirror and sink',
                    'LED interior lighting',
                    'Men\'s and women\'s separate sides',
                    'Fresh water system',
                ],
            ],
            [
                'key' => 'shower',
                'icon' => 'water-drop',
                'title' => 'Portable Shower Unit',
                'short_title' => 'Shower',
                'daily_label' => '$150 – $400',
                'weekly_label' => '$750 – $2,000',
                'monthly_label' => '$2,250 – $6,000',
                'description' => 'Private shower stalls for construction sites, camping events, and disaster relief. Hot and cold water included.',
                'best_for' => 'Construction Sites, Camping, Disaster Relief',
                'popular' => false,
                'includes' => [
                    'Hot and cold running water',
                    'Privacy curtains and changing area',
                    'Drainage system',
                    'Soap and towel dispensers',
                    'Weekly servicing',
                    'Delivery and setup',
                ],
            ],
            [
                'key' => 'construction',
                'icon' => 'building',
                'title' => 'Construction Site Package',
                'short_title' => 'Construction',
                'daily_label' => 'From $89/unit',
                'weekly_label' => 'Volume pricing',
                'monthly_label' => 'Up to 40% off',
                'description' => 'Complete sanitation packages for construction sites. Includes multiple units, OSHA documentation, and volume discounts.',
                'best_for' => 'Large Construction, High-Rise Projects, Road Work',
                'popular' => false,
                'includes' => [
                    'Multiple standard units (quantity-based pricing)',
                    'Weekly servicing for all units',
                    'OSHA compliance documentation',
                    'On-site supervisor for large projects',
                    'Volume discounts for 5+ units',
                    'Flexible pickup scheduling',
                ],
            ],
        ];

        $factors = [
            [
                'title' => 'Number of Units',
                'description' => 'The more units you rent, the better value you get. We offer volume discounts for orders of 5+ units.',
            ],
            [
                'title' => 'Rental Duration',
                'description' => 'Daily, weekly, and monthly rentals available. Long-term rentals (30+ days) come with significant savings.',
            ],
            [
                'title' => 'Unit Type',
                'description' => 'Standard, deluxe, ADA, luxury, and specialty units have different pricing based on features and amenities.',
            ],
            [
                'title' => 'Location',
                'description' => 'Delivery distance and local regulations can affect pricing. Most locations within 50 miles of our service centers include free delivery.',
            ],
            [
                'title' => 'Servicing Frequency',
                'description' => 'Weekly servicing is included in all standard rentals. Events may require more frequent servicing at additional cost.',
            ],
        ];

        return view(DomainViewHelper::resolveForController('pricing'), compact('pricingInfo', 'factors', 'pricingEnabled', 'priceRanges'));
    }

    /**
     * Units Calculator Page
     */
    public function calculator()
    {
        return view(DomainViewHelper::resolveForController('calculator'));
    }

    /**
     * Pillar Page: Complete Guide to Porta Potty Rental
     */
    public function pillarPage()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('complete-guide-to-porta-potty-rental'), compact('testimonials'));
    }

    /**
     * Wedding Porta Potty Rental Page
     */
    public function wedding()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('wedding'), compact('testimonials'));
    }

    /**
     * Festival Portable Toilets Page
     */
    public function festival()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('festival'), compact('testimonials'));
    }

    /**
     * Construction Site Porta Potty Rental Page
     */
    public function constructionLanding()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('construction-landing'), compact('testimonials'));
    }

    /**
     * Central FAQ Page
     */
    public function faq()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('faq'), compact('testimonials'));
    }

    /**
     * OSHA Porta Potty Requirements Guide
     */
    public function oshaGuide()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('osha-guide'), compact('testimonials'));
    }

    /**
     * Standard vs Deluxe vs Luxury Comparison Page
     */
    public function comparison()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('comparison'), compact('testimonials'));
    }

    /**
     * Porta Potty Types Guide Page
     */
    public function typesGuide()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('porta-potty-types-guide'), compact('testimonials'));
    }

    /**
     * Porta Potty Cleaning Process Page
     */
    public function cleaningProcess()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('porta-potty-cleaning-process'), compact('testimonials'));
    }

    /**
     * Sports Event Porta Potty Rental Page
     */
    public function sportsEvent()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('sports-event-porta-potty-rental'), compact('testimonials'));
    }

    /**
     * Municipal Porta Potty Rental Page
     */
    public function municipal()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('municipal-porta-potty-rental'), compact('testimonials'));
    }

    /**
     * Porta Potty Rental Cost Landing Page
     */
    public function costPage()
    {
        $pricingEnabled = config('service_pricing.enabled', false);
        $priceRanges = config('service_pricing.ranges', []);
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('porta-potty-rental-cost'), compact('pricingEnabled', 'priceRanges', 'testimonials'));
    }

    /**
     * Porta Potty Rental for Parties Landing Page
     */
    public function partyPage()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('porta-potty-rental-for-parties'), compact('testimonials'));
    }

    /**
     * Emergency Porta Potty Rental Landing Page
     */
    public function emergencyPage()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('emergency-porta-potty-rental'), compact('testimonials'));
    }

    /**
     * Restroom Trailer Rental Landing Page
     */
    public function restroomTrailerPage()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('restroom-trailer-rental'), compact('testimonials'));
    }

    /**
     * How Many Porta Potties Do I Need? Landing Page
     */
    public function howManyPage()
    {
        $testimonials = $this->getGlobalTestimonials();
        return view(DomainViewHelper::resolveForController('how-many-porta-potties-do-i-need'), compact('testimonials'));
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
     * Neighborhood Service Page
     */
    public function neighborhoodPage(string $slug)
    {
        $domain = Domain::current();
        $domainId = $domain?->id ?? 'default';

        $page = NeighborhoodServicePage::where('slug', $slug)
            ->where('is_published', true)
            ->with(['neighborhood.city.state', 'domain'])
            ->first();

        if (! $page || ! $page->neighborhood || ! $page->neighborhood->city) {
            abort(404);
        }

        $neighborhood = $page->neighborhood;
        $city = $neighborhood->city;
        $serviceType = $page->service_type;
        $domainLabel = $domain?->getServiceTypeLabel($serviceType) ?? ucfirst($serviceType).' Rental';

        $page->increment('views');

        $relatedPages = NeighborhoodServicePage::where('neighborhood_id', $neighborhood->id)
            ->where('id', '!=', $page->id)
            ->where('is_published', true)
            ->limit(3)
            ->get();

        return view(DomainViewHelper::resolveForController('neighborhood'), compact(
            'page', 'neighborhood', 'city', 'domain',
            'serviceType', 'domainLabel', 'relatedPages'
        ));
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

        $neighborhoods = Neighborhood::where('city_id', $city->id)
            ->whereHas('servicePages', fn ($q) => $q->where('is_published', true)->where('domain_id', $domainId))
            ->orderBy('priority', 'desc')
            ->orderBy('name')
            ->get();

        $schemaMarkup = $servicePage->generateSchemaMarkup();

        $faqSchema = null;
        if ($faqs->isNotEmpty()) {
            $mainEntity = $faqs
                ->filter(fn ($faq) => !empty($faq->question) && !empty($faq->answer))
                ->map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => strip_tags($faq->question),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => strip_tags($faq->answer),
                    ],
                ])->values()->toArray();

            if (!empty($mainEntity)) {
                $faqSchema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $mainEntity,
                ];
            }
        }

        // NOTE: we intentionally do NOT inject Review/AggregateRating schema here
        // even though $testimonials exists. Those testimonials are AI-generated and
        // marking them up would violate Google's Review Snippet policy. To re-enable,
        // wire a real-reviews source (Google Business Profile sync) and gate on
        // config('reviews.count') being set.

        $latitude = $city->latitude;
        $longitude = $city->longitude;
        $cityAddress = $city->name . ', ' . $city->state->code;
        $stateCodeLocal = $city->state->code;
        $postalCode = ! empty($city->zip_codes) ? $city->zip_codes[0] : null;

        return view(DomainViewHelper::resolveForController('service'), compact(
            'servicePage', 'city', 'faqs', 'testimonials',
            'nearbyCityPages', 'otherServices', 'relatedPosts',
            'neighborhoods',
            'schemaMarkup', 'faqSchema', 'domain',
            'latitude', 'longitude', 'cityAddress', 'stateCodeLocal', 'postalCode'
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

        // Abort 404 if requested page exceeds last page
        if ($cities->lastPage() > 0 && $cities->currentPage() > $cities->lastPage()) {
            abort(404);
        }

        // getStatePageContent runs AI calls in the worst case — cache hard
        $stateContent = Cache::remember("state_content_{$state->id}", 3600, fn () => $contentService->getStatePageContent($state)
        );
        $faqs = collect($stateContent['faqs'] ?? []);
        $images = $state->images ?? [];
        $serviceTypes = [
            ['key' => 'general', 'name' => 'All', 'icon' => 'map-pin'],
            ['key' => 'standard', 'name' => 'Standard', 'icon' => 'building'],
            ['key' => 'deluxe', 'name' => 'Deluxe', 'icon' => 'water-drop'],
            ['key' => 'ada', 'name' => 'ADA', 'icon' => 'accessibility'],
            ['key' => 'luxury', 'name' => 'Luxury', 'icon' => 'sparkles'],
            ['key' => 'construction', 'name' => 'Construction', 'icon' => 'building'],
        ];

        return view(DomainViewHelper::resolveForController('state'), compact('state', 'cities', 'stateContent', 'faqs', 'images', 'domain', 'serviceTypes'));
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

    protected function getGlobalTestimonials(int $limit = 3): \Illuminate\Support\Collection
    {
        $domain = Domain::current();
        $domainId = $domain?->id ?? 'default';
        $pool = Cache::remember("landing_testimonial_pool_{$domainId}", 3600, function () {
            return Testimonial::where('is_featured', true)
                ->where('is_active', true)
                ->take(30)
                ->get()
                ->toArray();
        });
        return collect($pool)->shuffle()->take($limit);
    }
}
