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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    /**
     * Homepage
     */
    public function home()
    {
        $featuredCities = Cache::remember('featured_cities', 3600, function () {
            return City::active()
                ->with(['state', 'servicePages'])
                ->byPriority()
                ->take(20)
                ->get()
                ->toArray();
        });

        $states = Cache::remember('active_states', 3600, function () {
            return State::where('is_active', true)
                ->whereHas('cities', fn ($q) => $q->where('is_active', true))
                ->withCount(['cities' => fn ($q) => $q->where('is_active', true)])
                ->orderBy('name')
                ->take(8)
                ->get()
                ->toArray();
        });

        $recentPosts = BlogPost::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        $testimonials = Testimonial::where('is_featured', true)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view(DomainViewHelper::resolveForController('home'), compact(
            'featuredCities', 'states', 'recentPosts', 'testimonials'
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
                'icon' => '🚻',
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
                'icon' => '🚿',
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
                'icon' => '♿',
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
                'icon' => '✨',
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
                'icon' => '🚿',
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
                'icon' => '🚐',
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
                'icon' => '👔',
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
                'icon' => '🏗️',
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
                'icon' => '🛢️',
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
                'icon' => '🧸',
                'description' => 'Standalone hand sanitizer and hand washing stations to complement your porta potty rental.',
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
                'name' => 'Waste Container Rentals',
                'short_name' => 'Dumpsters',
                'icon' => '🗑️',
                'description' => 'Roll-off dumpsters for construction debris, event waste, and porta potty waste disposal.',
                'features' => [
                    'Various sizes (10-40 yard)',
                    'Same-day delivery',
                    'Flexible pickup schedules',
                    'Permit assistance',
                    'Recycling available',
                ],
                'best_for' => ['Construction Sites', 'Events', 'Home Renovations', 'Commercial'],
            ],
        ];

        $addOns = [
            [
                'icon' => '🧼',
                'name' => 'Hand Wash Stations',
                'description' => 'Standalone hand washing units with soap and paper towels.',
            ],
            [
                'icon' => '💡',
                'name' => 'Lighting Packages',
                'description' => 'Solar-powered lights for nighttime events and construction sites.',
            ],
            [
                'icon' => '🧹',
                'name' => 'Extra Servicing',
                'description' => 'Additional weekly cleaning and restocking beyond standard service.',
            ],
            [
                'icon' => '🚰',
                'name' => 'Fresh Water Delivery',
                'description' => 'Fresh water delivery for luxury trailers and hand wash stations.',
            ],
            [
                'icon' => '🔧',
                'name' => '24/7 Emergency Service',
                'description' => 'Emergency pumping, repairs, and additional units available 24/7.',
            ],
            [
                'icon' => '📋',
                'name' => 'Compliance Documentation',
                'description' => 'OSHA compliance paperwork, service logs, and audit support.',
            ],
            [
                'icon' => '🧻',
                'name' => 'Extra Supplies',
                'description' => 'Additional toilet paper, hand sanitizer, and deodorizer refills.',
            ],
            [
                'icon' => '🏠',
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
                'icon' => '🚻',
                'title' => 'Standard Porta Potty',
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
                'icon' => '🚿',
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
                'icon' => '♿',
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
                'icon' => '✨',
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
                'icon' => '🚿',
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
                'icon' => '🛢️',
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
     * City Service Page (SEO এর মূল পেজ)
     */
    public function cityPage(string $slug)
    {
        $domain = Domain::current();

        $query = ServicePage::where('slug', $slug)
            ->where('is_published', true)
            ->with(['city.state', 'city.phoneNumbers']);

        if ($domain) {
            $query->whereHas('city', function ($q) use ($domain) {
                $q->whereHas('domainCities', function ($dq) use ($domain) {
                    $dq->where('domain_id', $domain->id)->where('status', true);
                });
            });
        } else {
            $query->whereHas('city', fn ($q) => $q->where('is_active', true));
        }

        $servicePage = $query->firstOrFail();

        $city = $servicePage->city;

        // View count বাড়ান
        $servicePage->incrementViews();

        // FAQs
        $cityFaqs = $city->getActiveFaqs($servicePage->service_type);
        if ($cityFaqs->isEmpty()) {
            $faqs = $city->getActiveFaqs();
        } else {
            $faqs = $cityFaqs;
        }

        // Testimonials - filter by service type
        $testimonials = Testimonial::where('city_id', $city->id)
            ->where('service_type', $servicePage->service_type)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Fallback to general testimonials if none found for service type
        if ($testimonials->isEmpty()) {
            $testimonials = Testimonial::where('city_id', $city->id)
                ->where('service_type', 'general')
                ->where('is_active', true)
                ->inRandomOrder()
                ->take(4)
                ->get();
        }

        // Nearby cities with pages
        $nearbyNames = $city->getNearbyAreaNames();
        $nearbyCityPages = City::active()
            ->whereIn('name', $nearbyNames)
            ->with('state')
            ->has('servicePages')
            ->take(8)
            ->get();

        // Other service types for this city
        $otherServices = ServicePage::where('city_id', $city->id)
            ->where('id', '!=', $servicePage->id)
            ->where('is_published', true)
            ->get();

        // Related blog posts
        $relatedPosts = BlogPost::published()
            ->where(function ($q) use ($city, $servicePage) {
                $q->where('city_id', $city->id)
                    ->orWhere('title', 'LIKE', "%{$servicePage->service_type}%");
            })
            ->take(3)
            ->get();

        // Schema markup
        $schemaMarkup = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $servicePage->heading,
            'description' => $servicePage->seo_description,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $city->name,
                'addressRegion' => $city->state->code,
                'addressCountry' => 'US',
            ],
            'telephone' => $servicePage->phone_raw,
            'priceRange' => '$$',
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    'opens' => '00:00',
                    'closes' => '23:59',
                ],
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => $city->name,
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '4.9',
                'reviewCount' => '500',
            ],
        ];

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

        // Add reviews to schema if testimonials exist
        if ($testimonials->isNotEmpty()) {
            $schemaMarkup['review'] = $testimonials->map(fn ($t) => [
                '@type' => 'Review',
                'reviewRating' => [
                    '@type' => 'Rating',
                    'ratingValue' => $t->rating ?? 5,
                ],
                'author' => [
                    '@type' => 'Person',
                    'name' => $t->customer_name,
                ],
                'reviewBody' => $t->content,
            ])->toArray();
        }

        return view(DomainViewHelper::resolveForController('service'), compact(
            'servicePage', 'city', 'faqs', 'testimonials',
            'nearbyCityPages', 'otherServices', 'relatedPosts',
            'schemaMarkup', 'faqSchema'
        ));
    }

    /**
     * State Page — সেই রাজ্যের সব শহর দেখাবে
     */
    public function statePage(string $stateSlug)
    {
        $state = State::where('slug', $stateSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $cities = $state->activeCities()
            ->has('servicePages')
            ->with('state')
            ->byPriority()
            ->paginate(30);

        return view(DomainViewHelper::resolveForController('state'), compact('state', 'cities'));
    }

    /**
     * All Locations Page
     */
    public function locations(Request $request)
    {
        $search = $request->get('q', '');

        $states = State::whereHas('domainStates', function ($q) {
            $q->where('status', true);
        })
            ->with(['cities' => function ($q) use ($search) {
                $q->active()->has('servicePages')->orderBy('name');
                if ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                }
            }])
            ->orderBy('name')
            ->get();

        return view(DomainViewHelper::resolveForController('locations'), compact('states', 'search'));
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
