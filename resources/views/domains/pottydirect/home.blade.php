@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@php
    $phoneRaw = domain_phone_raw();
    $phoneDisplay = domain_phone_display();
@endphp
@php
    $domain = \App\Models\Domain::current();
    $cityName = $topCities[0]['name'] ?? 'your city';
    $stateName = $topCities[0]['state']['name'] ?? 'your state';
    $stateCode = $topCities[0]['state']['code'] ?? 'TX';
    $zipCode = $topCities[0]['zip_code'] ?? '75001';
    $nearbyCity1 = $topCities[1]['name'] ?? 'nearby city';
    $nearbyCity2 = $topCities[2]['name'] ?? 'nearby city';
    $nearbyCity3 = $topCities[3]['name'] ?? 'nearby city';
    $county = $topCities[0]['county'] ?? 'County';
    $nearbyZip1 = $topCities[1]['zip_code'] ?? '75002';
@endphp

@section('title', 'Porta Potty Rental '.$cityName.', '.$stateName.' | Same-Day Delivery | Call '.$phoneDisplay)
@section('meta_description', 'Need porta potty rental in '.$cityName.', '.$stateName.'? Same-day delivery for construction, events & emergency sanitation. Call '.$phoneDisplay.' for instant quotes.')
@section('canonical', url('/'))
@section('phone_raw', $phoneRaw)
@section('phone_display', $phoneDisplay)

@push('schema')
    @php
        $url = url('/');
        $phone = domain_phone_raw();
        $domain = \App\Models\Domain::current();

        $reviewRating = config('reviews.rating', 4.9);
        $reviewCount = config('reviews.count')
            ?? \App\Models\Testimonial::where('is_active', true)->count();

        $areaServed = collect($topCities ?? [])->map(fn($c) => ["@type" => "City", "name" => $c['name']])->toArray();
        if (empty($areaServed)) {
            $areaServed = [["@type" => "Country", "name" => "United States"]];
        }

        // Get primary city geo data for NAP consistency
        $primaryCity = !empty($topCities) ? $topCities[0] : null;
        $latitude = $primaryCity['latitude'] ?? 32.7767;
        $longitude = $primaryCity['longitude'] ?? -96.7970;
        $streetAddress = $domain?->address ?? ($primaryCity['name'] ?? 'Main Street');
        $cityAddress = $primaryCity['name'] ?? 'Dallas';
        $stateAddress = $primaryCity['state']['name'] ?? 'Texas';
        $stateCodeLocal = $primaryCity['state']['code'] ?? 'TX';
        $postalCode = $primaryCity['zip_code'] ?? '75201';

        $businessSchema = [
            "@context" => "https://schema.org",
            "@type" => ["LocalBusiness", "HomeAndConstructionBusiness", "EmergencyService"],
            "@id" => $url . "#business",
            "name" => $domain?->business_name ?? "Potty Direct",
            "alternateName" => [$domain?->primary_service ?? "Portable Restroom Rental", "Porta Potty Rental " . $cityAddress, "Portable Toilet Rental Near Me"],
            "description" => $domain?->tagline ?? "Portable restroom rental service across " . $stateAddress . ". Same-day delivery available in " . $cityAddress . " and surrounding areas.",
            "url" => $url,
            "telephone" => $phone,
            "priceRange" => "$$-$$$",
            "image" => [$url . "/og-image.jpg", $url . "/logo.png"],
            "logo" => $url . "/logo.png",
            "photograph" => $url . "/og-image.jpg",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $streetAddress,
                "addressLocality" => $cityAddress,
                "addressRegion" => $stateCodeLocal,
                "postalCode" => $postalCode,
                "addressCountry" => "US"
            ],
            "geo" => [
                "@type" => "GeoCoordinates",
                "latitude" => (float) $latitude,
                "longitude" => (float) $longitude
            ],
            "areaServed" => array_merge($areaServed, [
                ["@type" => "State", "name" => $stateAddress],
                ["@type" => "Country", "name" => "United States"]
            ]),
            "openingHoursSpecification" => [
                [
                    "@type" => "OpeningHoursSpecification",
                    "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                    "opens" => config('contact.hours_open', '07:00'),
                    "closes" => config('contact.hours_close', '20:00'),
                ],
            ],
            "contactPoint" => [[
                "@type" => "ContactPoint",
                "telephone" => $phone,
                "contactType" => "customer service",
                "contactOption" => ["TollFree", "HearingImpairedSupported"],
                "areaServed" => ["US", $stateAddress],
                "availableLanguage" => ["English"],
                "hoursAvailable" => [[
                    "@type" => "OpeningHoursSpecification",
                    "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
                    "opens" => config('contact.hours_open', '07:00'),
                    "closes" => config('contact.hours_close', '20:00'),
                ]],
            ]],
            "hasOfferCatalog" => [
                "@type" => "OfferCatalog",
                "name" => ($domain?->primary_service ?? "Portable Restroom") . " Rentals",
                "itemListElement" => [
                    ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Standard Portable Restroom Rental"]],
                    ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Deluxe Flushable Unit"]],
                    ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "ADA Accessible Unit"]],
                    ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Luxury Restroom Trailer"]],
                    ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Dumpster Rental"]],
                    ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Septic Service"]]
                ]
            ],
            "aggregateRating" => ($reviewCount ?? null) ? [
                "@type" => "AggregateRating",
                "ratingValue" => (string) $reviewRating,
                "reviewCount" => (string) $reviewCount,
                "bestRating" => "5",
            ] : null,
        ];
        $businessSchema = array_filter($businessSchema);

        $orgSchema = null; // emitted site-wide via layout.blade.php

        $websiteSchema = null; // emitted site-wide via layout.blade.php

        $faqSchema = [
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => [
                ["@type" => "Question", "name" => "How much does porta potty rental cost in {$cityName}?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Rates start at $100-175/day for standard units in {$cityName}, with discounts for long-term and bulk orders. Call {$phoneDisplay} for a no-obligation custom quote tailored to your specific needs."]],
                ["@type" => "Question", "name" => "Do you offer same-day porta potty delivery in {$stateName}?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes! Order by 2PM for same-day delivery to {$cityName} and surrounding areas. Call {$phoneDisplay} to check real-time availability and secure your delivery slot."]],
                ["@type" => "Question", "name" => "Are your portable toilets ADA-compliant?", "acceptedAnswer" => ["@type" => "Answer", "text" => "All ADA units meet federal accessibility standards, and we provide permit certification for {$stateName} projects. Call {$phoneDisplay} to order compliant units for your job site or event."]],
                ["@type" => "Question", "name" => "Do you service construction sites in {$county} County?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, we provide long-term construction rentals with weekly pumping, restocking, and 24/7 emergency service throughout {$county} County. Call {$phoneDisplay} for competitive jobsite rates."]],
                ["@type" => "Question", "name" => "Can I rent porta potties for a one-day event in {$cityName}?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Absolutely! We offer short-term event rentals with delivery, setup, and post-event removal in {$cityName}. Call {$phoneDisplay} to plan your event sanitation needs today."]]
            ]
        ];

        // Review schema removed — requires real verifiable reviews (e.g. from Google Business Profile)
        // before marking up as schema. Set REVIEWS_COUNT in .env once you have an audited count,
        // and add a reviews-sync integration to populate real reviewer names. Google's review-snippet
        // policy prohibits invented testimonials in Review markup.
        $reviewSchema = null;

        $breadcrumbSchema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => [
                ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => url('/')],
                ["@type" => "ListItem", "position" => 2, "name" => "Porta Potty Rental", "item" => url('/services')],
                ["@type" => "ListItem", "position" => 3, "name" => "Locations", "item" => url('/locations')]
            ]
        ];

        $serviceSchema = [
            "@context" => "https://schema.org",
            "@type" => "ItemList",
            "name" => "Portable Restroom Rental Services",
            "description" => "We offer a variety of porta potty rental options across the USA",
            "itemListElement" => [
                [
                    "@type" => "ListItem",
                    "position" => 1,
                    "item" => [
                        "@type" => "Service",
                        "name" => "Standard Portable Toilet Rental",
                        "description" => "Basic portable toilet for construction sites and outdoor events. OSHA compliant with non-splash urinal, ventilation, and hand sanitizer.",
                        "provider" => ["@type" => "LocalBusiness", "name" => $domain?->business_name ?? "Potty Direct"],
                        "areaServed" => ["@type" => "Country", "name" => "United States"],
                        "priceRange" => "$100-175/day"
                    ]
                ],
                [
                    "@type" => "ListItem",
                    "position" => 2,
                    "item" => [
                        "@type" => "Service",
                        "name" => "Deluxe Flushable Unit Rental",
                        "description" => "Premium portable toilet with flushing toilet, sink with running water, interior mirror, and handwashing station.",
                        "provider" => ["@type" => "LocalBusiness", "name" => $domain?->business_name ?? "Potty Direct"],
                        "areaServed" => ["@type" => "Country", "name" => "United States"],
                        "priceRange" => "$175-275/day"
                    ]
                ],
                [
                    "@type" => "ListItem",
                    "position" => 3,
                    "item" => [
                        "@type" => "Service",
                        "name" => "ADA Accessible Portable Restroom",
                        "description" => "Wheelchair accessible portable toilet with extra-wide door, interior grab bars, non-slip flooring, and spacious interior.",
                        "provider" => ["@type" => "LocalBusiness", "name" => $domain?->business_name ?? "Potty Direct"],
                        "areaServed" => ["@type" => "Country", "name" => "United States"],
                        "priceRange" => "$175-275/day"
                    ]
                ],
                [
                    "@type" => "ListItem",
                    "position" => 4,
                    "item" => [
                        "@type" => "Service",
                        "name" => "Luxury Restroom Trailer Rental",
                        "description" => "Premium climate-controlled restroom trailers with porcelain fixtures, vanity, lighting, men's and women's sides. Perfect for weddings and upscale events.",
                        "provider" => ["@type" => "LocalBusiness", "name" => $domain?->business_name ?? "Potty Direct"],
                        "areaServed" => ["@type" => "Country", "name" => "United States"],
                        "priceRange" => "$500-1500/day"
                    ]
                ]
            ]
        ];
        $howtoSchema = [
            "@context" => "https://schema.org",
            "@type" => "HowTo",
            "name" => "How to Rent a Porta Potty in 3 Steps",
            "description" => "Learn how to rent a portable toilet for construction sites, events, or weddings in just 3 simple steps.",
            "step" => [
                ["@type" => "HowToStep", "name" => "Call for a Quote", "text" => "Contact us at " . domain_phone_display() . " and tell us how many porta potties you need, what type, and your location. We'll provide an instant, transparent quote.", "url" => url('/') . "#pricing"],
                ["@type" => "HowToStep", "name" => "We Deliver", "text" => "We deliver clean, sanitized portable toilets to your location. Same-day delivery available when you call before 2 PM.", "url" => url('/') . "#services"],
                ["@type" => "HowToStep", "name" => "We Maintain & Pick Up", "text" => "For weekly/monthly rentals, we provide regular servicing. When you're done, we handle pickup — no hassle for you.", "url" => url('/') . "#services"]
            ],
            "totalTime" => "PT10M"
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($businessSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    @if($reviewSchema)
        <script type="application/ld+json">{!! json_encode($reviewSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    @endif
    <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($howtoSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush


@section('content')

{{-- ================================================================
     HERO
     ================================================================ --}}
@php
    $host = request()->getHost();
    $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host);
    if ($prefix === 'localhost' || !\Illuminate\Support\Facades\Storage::disk('public')->exists($prefix . '/hero-banner-images')) {
        $prefix = 'pottydirect';
    }
    $heroImages = \Illuminate\Support\Facades\Cache::remember("hero_images_{$prefix}", 3600, function () use ($prefix) {
        return collect(\Illuminate\Support\Facades\Storage::disk('public')->files($prefix . '/hero-banner-images'))
            ->filter(fn($f) => in_array(pathinfo($f, PATHINFO_EXTENSION), ['webp', 'jpg', 'jpeg', 'png']))
            ->values()
            ->all();
    });
    $randomHero = !empty($heroImages) ? $heroImages[array_rand($heroImages)] : $prefix . '/hero-banner-images/default.webp';
    $heroUrl = asset('storage/' . $randomHero);
@endphp

<section class="relative min-h-[420px] sm:min-h-[480px] md:min-h-[560px] flex items-center overflow-hidden bg-slate-900">
    {{-- Hero Image --}}
    <div class="absolute inset-0">
        <img src="{{ $heroUrl }}" alt="Portable toilet rental for construction and events"
             class="w-full h-full object-cover opacity-40"
             width="1920" height="1080"
             loading="eager" fetchpriority="high" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 via-slate-900/75 to-slate-900/50"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 md:py-20 w-full">
        <div class="max-w-3xl">
            {{-- Urgency pill --}}
            <div class="inline-flex items-center gap-2 bg-emerald-500/15 backdrop-blur-sm border border-emerald-400/30 text-emerald-200 text-xs sm:text-sm px-3 py-1.5 rounded-full mb-4 sm:mb-5">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse" aria-hidden="true"></span>
                <span>Same-day delivery · Order by 2&nbsp;PM</span>
            </div>

        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-3 sm:mb-4 leading-[1.1] tracking-tight text-balance">
                 Porta Potty Rental in {{ $cityName }}, {{ $stateName }}
                 <span class="block text-emerald-400 text-xl sm:text-2xl md:text-3xl lg:text-4xl mt-2 font-bold">
                     Fast, Clean, Reliable — Same-Day Delivery
                 </span>
             </h1>

             <p class="text-base sm:text-lg text-slate-300 mb-5 sm:mb-7 max-w-xl leading-relaxed">
                 Portable toilet rental near me just got easier. We deliver sanitized, OSHA-compliant
                 porta potties to {{ $cityName }} job sites, events, and residential projects in hours, not days.
             </p>

            {{-- Primary CTA + subtle secondary --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-5 mb-3">
                <a href="tel:{{ $phoneRaw ?? domain_phone_raw() }}"
                   data-tracking-label="home-hero"
                   class="flex items-center justify-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl sm:text-2xl font-bold py-4 px-7 sm:px-9 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] active:scale-[0.98] min-h-[44px] whitespace-nowrap">
                    <x-icon name="phone" class="w-6 h-6" />
                    <span>{{ $phoneDisplay ?? domain_phone_display() }}</span>
                </a>
                <a href="#services"
                   class="inline-flex items-center justify-center sm:justify-start gap-1.5 text-slate-300 hover:text-white font-medium text-sm transition min-h-[44px]">
                    <span>Browse services</span>
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>

            {{-- Trust microcopy --}}
            <p class="text-sm text-emerald-300 font-medium flex items-center gap-2 mb-5">
                <x-icon name="check-circle" class="w-4 h-4 flex-shrink-0" />
                <span>Answered in under 30 seconds by a real person — no robocalls.</span>
            </p>

            {{-- ZIP / city coverage checker — high-intent users answer "do you serve my area?" in 1 click --}}
            <form action="{{ route('locations') }}" method="GET" class="mb-6 max-w-md">
                <label for="hero-coverage-input" class="block text-[11px] text-slate-400 uppercase tracking-[0.12em] font-semibold mb-2">
                    Check service area
                </label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <x-icon name="map-pin" class="w-5 h-5" />
                        </span>
                        <input id="hero-coverage-input"
                               type="text"
                               name="q"
                               inputmode="search"
                               autocomplete="postal-code"
                               placeholder="ZIP code or city"
                               class="w-full pl-10 pr-3 py-3 rounded-lg bg-white text-slate-900 placeholder:text-slate-400 border border-white/10 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-sm sm:text-base min-h-[44px]">
                    </div>
                    <button type="submit"
                            class="bg-white hover:bg-slate-100 text-slate-900 font-semibold text-sm sm:text-base px-5 rounded-lg transition shadow-md min-h-[44px] inline-flex items-center gap-1.5">
                        Check
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </button>
                </div>
            </form>

            {{-- Trust row (consolidated, no separate trust-bar section below) --}}
            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs sm:text-sm text-slate-300">
                <span class="inline-flex items-center gap-1.5"><x-icon name="shield-check" class="w-4 h-4 text-emerald-400" />Licensed &amp; Insured</span>
                <span class="text-slate-600" aria-hidden="true">·</span>
                <span class="inline-flex items-center gap-1.5"><x-icon name="truck" class="w-4 h-4 text-emerald-400" />Same-day delivery</span>
                <span class="text-slate-600" aria-hidden="true">·</span>
                <span class="inline-flex items-center gap-1.5"><x-icon name="currency-dollar" class="w-4 h-4 text-emerald-400" />No hidden fees</span>
                <span class="text-slate-600" aria-hidden="true">·</span>
                <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="w-4 h-4 text-emerald-400" />Open 7AM-8PM Daily</span>
                @if(($reviewCount ?? 0) > 0)
                    <span class="text-slate-600" aria-hidden="true">·</span>
                    <span class="inline-flex items-center gap-1.5" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                        <x-icon name="star" class="w-4 h-4 text-amber-400" />
                        <span itemprop="ratingValue">{{ number_format($reviewRating ?? 4.9, 1) }}</span>/<span itemprop="bestRating">5</span>
                        (<span itemprop="reviewCount">{{ $reviewCount }}+</span> reviews)
                    </span>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     NAP (Name, Address, Phone) - Visible for Local SEO
     ================================================================ --}}
<div class="bg-slate-100 border-y border-slate-200 py-4 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8 text-sm text-slate-700">
        <div class="flex items-center gap-2">
            <x-icon name="building" class="w-4 h-4 text-emerald-600" />
            <span class="font-semibold">{{ $domain?->business_name ?? 'Potty Direct' }}</span>
        </div>
        <div class="hidden sm:block text-slate-300">|</div>
        <div class="flex items-center gap-2">
            <x-icon name="map-pin" class="w-4 h-4 text-emerald-600" />
            <span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                <span itemprop="addressLocality">{{ $cityAddress ?? $cityName }}</span>,
                <span itemprop="addressRegion">{{ $stateCodeLocal ?? $stateCode }}</span>
                <span itemprop="postalCode">{{ $postalCode ?? $zipCode }}</span>
            </span>
        </div>
        <div class="hidden sm:block text-slate-300">|</div>
        <div class="flex items-center gap-2">
            <x-icon name="phone" class="w-4 h-4 text-emerald-600" />
            <a href="tel:{{ $phoneRaw }}" class="font-semibold text-emerald-700 hover:text-emerald-800" itemprop="telephone">{{ $phoneDisplay }}</a>
        </div>
    </div>
</div>

{{-- ================================================================
     SERVICES
     ================================================================ --}}
<section id="services" class="py-14 sm:py-20 px-4 sm:px-6 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10 sm:mb-14">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-3 text-balance">What we rent</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">
                From single-unit construction sites to luxury restroom trailers for weddings. Same-day delivery available in most markets.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
             @php
                 $homeServices = [
                     ['key' => 'standard',     'icon' => 'building',       'name' => 'Standard Porta Potty Rentals',      'blurb' => 'Affordable single-unit portable toilets for small job sites, backyard events, and short-term needs in '.$cityName.'.'],
                     ['key' => 'deluxe',       'icon' => 'water-drop',     'name' => 'Deluxe Portable Restrooms',  'blurb' => 'Flushable units with sinks, mirrors, and climate control for weddings and corporate events.'],
                     ['key' => 'ada',          'icon' => 'accessibility',  'name' => 'ADA-Compliant Porta Potties','blurb' => 'Spacious wheelchair-accessible units meeting all federal ADA standards for '.$stateName.' projects.'],
                     ['key' => 'construction', 'icon' => 'users',          'name' => 'Construction Site Toilets',  'blurb' => 'Heavy-duty graffiti-resistant units built for long-term '.$cityName.' job sites with weekly servicing.'],
                     ['key' => 'luxury',       'icon' => 'sparkles',       'name' => 'Event Porta Potties','blurb' => 'High-capacity units for festivals and large gatherings in '.$county.' County.'],
                     ['key' => 'shower',       'icon' => 'shower',         'name' => 'Portable Shower Units','blurb' => 'Hot & cold water stalls for construction sites and emergency response.'],
                     ['key' => 'dumpster',     'icon' => 'trash',          'name' => 'Dumpster Rental',      'blurb' => 'Roll-off dumpsters 10-40 yard for '.$stateName.' construction debris.'],
                     ['key' => 'septic',       'icon' => 'wrench',         'name' => 'Septic Service','blurb' => 'Professional pumping and maintenance for residential and commercial properties.'],
                 ];
                 $pricingEnabled = (bool) config('service_pricing.enabled', false);
                 $priceRanges = config('service_pricing.ranges', []);
             @endphp

             @foreach($homeServices as $svc)
                 @php
                     $range = $priceRanges[$svc['key']] ?? null;
                 @endphp
                 <a href="{{ route('services') }}#{{ $svc['key'] }}"
                    class="group relative bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 hover:border-emerald-300 hover:shadow-lg transition-all focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                     <div class="w-11 h-11 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3 group-hover:bg-emerald-500 group-hover:text-white transition">
                         <x-icon name="{{ $svc['icon'] }}" class="w-6 h-6" />
                     </div>
                     <h3 class="text-sm sm:text-base font-semibold text-slate-900 mb-1">{{ $svc['name'] }}</h3>
                     <p class="text-xs sm:text-sm text-slate-500 leading-snug">{!! $svc['blurb'] !!}</p>
                     @if($pricingEnabled && $range)
                         <p class="mt-2 text-[11px] sm:text-xs font-semibold text-emerald-700">
                             From ${{ $range['low'] }}/day
                         </p>
                     @endif
                 </a>
             @endforeach
         </div>

        <div class="mt-10 text-center">
            <a href="{{ route('services') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold min-h-[44px]">
                View all services
                <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        </div>
    </div>
</section>

{{-- ================================================================
     WHY CHOOSE US
     ================================================================ --}}
<section class="py-14 sm:py-20 px-4 sm:px-6 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10 sm:mb-14">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-3 text-balance">Why Choose {{ $domain?->business_name ?? 'Us' }} for Porta Potty Rentals</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">
                We prioritize speed, cleanliness, and transparency to get you sanitation without hassle.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $pillars = [
                    ['icon' => 'truck',        'title' => 'Same-Day Delivery',       'body' => 'Order by 2PM for delivery to '.$cityName.' addresses same day. Call '.$phoneDisplay.' to check availability.'],
                    ['icon' => 'phone',        'title' => '24/7 Live Support',  'body' => 'Real people answer your call, no automated menus. Average answer time: 10 seconds.'],
                    ['icon' => 'currency-dollar', 'title' => 'Transparent Pricing',      'body' => 'Flat rates, no hidden fees, no surprise charges. The quote we give is what you pay.'],
                    ['icon' => 'shield-check','title' => 'Fully Sanitized',   'body' => 'Every unit is deep-cleaned, disinfected, and stocked pre-delivery. OSHA & ADA compliant.'],
                ];
            @endphp

            @foreach($pillars as $p)
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center mb-4">
                        <x-icon name="{{ $p['icon'] }}" class="w-6 h-6" />
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">{{ $p['title'] }}</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $p['body'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================================================================
     SERVING AREAS (location-based SEO)
     ================================================================ --}}
<section class="py-14 sm:py-20 px-4 sm:px-6 bg-white">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-3 text-balance">Serving {{ $cityName }} & Surrounding {{ $stateName }} Areas</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">
                We're a local porta potty rental company serving {{ $cityName }}, {{ $stateName }} and nearby communities.
            </p>
        </div>

        <div class="bg-slate-50 rounded-2xl p-6 sm:p-8 border border-slate-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold text-slate-900 mb-3">Communities We Serve</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-sm font-medium">{{ $cityName }}</span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-sm font-medium">{{ $nearbyCity1 }}</span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-sm font-medium">{{ $nearbyCity2 }}</span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-sm font-medium">{{ $nearbyCity3 }}</span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-sm">{{ $county }} County</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 mb-3">Zip Codes Covered</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-sm">{{ $zipCode }}</span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-sm">{{ $nearbyZip1 }}</span>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-sm">All {{ $stateCode }} zip codes</span>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <p class="text-sm text-slate-600 mb-4">
                    <strong>Portable toilet rental near me in {{ $zipCode }}?</strong> We've got you covered with same-day delivery to your exact location.
                </p>
                <a href="tel:{{ $phoneRaw }}"
                   data-tracking-label="home-areas"
                   class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white font-bold py-3 px-6 rounded-full shadow-lg shadow-amber-500/30 transition hover:scale-[1.02] min-h-[44px]">
                    <x-icon name="phone" class="w-5 h-5" />
                    <span>Call {{ $phoneDisplay }} to confirm service to your area</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     TESTIMONIALS (What Customers Say)
     ================================================================ --}}
@if($testimonials && count($testimonials) > 0)
<section class="py-14 sm:py-20 px-4 sm:px-6 bg-slate-50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-3 text-balance">What Customers Say About Our Porta Potty Rentals</h2>
            <p class="text-slate-600">Real reviews from {{ $cityName }} area customers:</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($testimonials as $testimonial)
                <article class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-lg transition">
                    <div class="flex items-center gap-1 mb-3">
                        @for($i = 0; $i < ($testimonial['rating'] ?? 5); $i++)
                            <x-icon name="star" class="w-4 h-4 text-amber-400" />
                        @endfor
                    </div>
                    <p class="text-sm text-slate-600 mb-4 italic">"{{ $testimonial['content'] ?? 'Great service and fast delivery!' }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr($testimonial['customer_name'] ?? 'J', 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ $testimonial['customer_name'] ?? 'Customer' }}</div>
                            <div class="text-xs text-slate-500">{{ $testimonial['location'] ?? $cityName.', '.$stateName }}</div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="tel:{{ $phoneRaw }}"
               data-tracking-label="home-testimonials"
               class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white font-bold py-3 px-6 rounded-full shadow-lg shadow-amber-500/30 transition hover:scale-[1.02] min-h-[44px]">
                <x-icon name="phone" class="w-5 h-5" />
                <span>Call {{ $phoneDisplay }} to Join Our Happy Customers</span>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     HOW IT WORKS
     ================================================================ --}}
<section class="py-14 sm:py-20 px-4 sm:px-6 bg-slate-50">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-10 sm:mb-14">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-3 text-balance">Three steps, done today</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">From the moment you call to the moment your unit's on site.</p>
        </div>

        <ol class="relative grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            {{-- Connector line (desktop only, behind the cards) --}}
            <div aria-hidden="true" class="hidden md:block absolute top-4 left-[16.666%] right-[16.666%] h-0.5 border-t-2 border-dashed border-emerald-200"></div>

            @php
                $steps = [
                    ['n' => '1', 'icon' => 'phone',    'title' => 'Call for a quote',  'body' => 'Tell us what you need and where. We quote a straight number — no ballpark wiggle-room.'],
                    ['n' => '2', 'icon' => 'truck',    'title' => 'We deliver',         'body' => 'Clean, sanitized, placed exactly where you want it. Same-day on orders before 2 PM in most markets.'],
                    ['n' => '3', 'icon' => 'check-circle', 'title' => 'We service + haul away', 'body' => 'Weekly or monthly servicing for long rentals. When you\'re done, we pick it up. No hassle on your end.'],
                ];
            @endphp

            @foreach($steps as $step)
                <li class="relative bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 md:left-6 md:translate-x-0 w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm font-bold shadow-md">{{ $step['n'] }}</div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 mt-3 md:mt-2">
                        <x-icon name="{{ $step['icon'] }}" class="w-6 h-6" />
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $step['body'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ================================================================
     VIDEO CTA — Phone-Call Only Focus (form removed)
     ================================================================ --}}
<section class="py-14 sm:py-20 px-4 sm:px-6 bg-slate-50">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-3 text-balance">See Why {{ $cityName }} Calls Us First</h2>
        <p class="text-slate-600 mb-8 max-w-2xl mx-auto">Watch how we deliver clean, sanitized porta potties across {{ $stateName }} in hours, not days.</p>

        <div class="mt-10 sm:mt-12">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden max-w-3xl mx-auto">
                    <div class="relative aspect-video bg-slate-900">
                        <iframe
                            src="https://www.youtube-nocookie.com/embed/qnmJ31rg118?rel=0"
                            title="Porta Potty Rental - {{ $domain?->business_name ?? 'Potty Direct' }}"
                            class="absolute inset-0 w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    </div>
                    <div class="p-4 bg-slate-50 border-t border-slate-200">
                        <p class="text-center text-sm text-slate-500">
                            Learn about our <strong>same-day delivery</strong>, <strong>clean units</strong>, and
                            <strong>transparent pricing</strong> in under a minute.
                        </p>
                    </div>
                </div>
            </div>

        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 sm:p-8">
            <p class="text-lg sm:text-xl font-bold text-slate-900 mb-2">Ready to order? Call now for same-day delivery in {{ $cityName }}.</p>
            <a href="tel:{{ $phoneRaw ?? domain_phone_raw() }}"
               data-tracking-label="home-video-cta"
               class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl sm:text-2xl font-bold py-4 px-8 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition hover:scale-[1.02] min-h-[44px]">
                <x-icon name="phone" class="w-6 h-6" />
                <span>{{ $phoneDisplay ?? domain_phone_display() }}</span>
            </a>
            <p class="text-sm text-slate-500 mt-3">Average answer time: 10 seconds | 24/7 live support</p>
        </div>
    </div>
</section>

{{-- ================================================================
     FAQ
     ================================================================ --}}
<section id="faq" class="py-14 sm:py-20 px-4 sm:px-6 bg-white">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-3 text-balance">Frequently asked questions</h2>
            <p class="text-slate-600">Everything we get asked before people call.</p>
        </div>

        @php
            $homeFaqs = [
                ['q' => 'How much does porta potty rental cost in '.$cityName.'?',                  'a' => 'Rates start at $100-175/day for standard units in '.$cityName.', with discounts for long-term and bulk orders. Call '.$phoneDisplay.' for a no-obligation custom quote.'],
                ['q' => 'Do you offer same-day porta potty delivery in '.$stateName.'?',                'a' => 'Yes! Order by 2PM for same-day delivery to '.$cityName.' and surrounding areas. Call '.$phoneDisplay.' to check real-time availability.'],
                ['q' => 'What types of porta potty units do you offer?',              'a' => 'Standard portable toilets, <a href="' . route('services') . '#deluxe" class="text-emerald-600 hover:underline">deluxe flushable units</a> with handwashing stations, <a href="' . route('services') . '#ada" class="text-emerald-600 hover:underline">ADA-compliant accessible units</a>, and <a href="' . route('services') . '#luxury" class="text-emerald-600 hover:underline">luxury restroom trailers</a>. Call '.$phoneDisplay.' to discuss your needs.'],
                ['q' => 'Do you offer restroom trailers for events?',                 'a' => 'Yes. Our <a href="' . route('services') . '#luxury" class="text-emerald-600 hover:underline">luxury restroom trailers</a> feature climate control, porcelain fixtures, and elegant interiors for weddings and corporate events in '.$cityName.'. Call '.$phoneDisplay.' to book.'],
                ['q' => 'How many porta potties do I need for my event?',             'a' => '1 standard unit per 50 guests for a 4-hour event. If alcohol is served, add 20% more. For construction sites, OSHA requires 1 unit per 20 workers. Call '.$phoneDisplay.' and we\'ll help you determine the right number.'],
                ['q' => 'What is included in the rental?',                            'a' => 'Delivery, setup, pickup, and — for weekly/monthly rentals — regular servicing. No hidden fees — the price we quote is the price you pay. Call '.$phoneDisplay.' for transparent pricing.'],
            ];
            $visibleFaqs = array_slice($homeFaqs, 0, 6);
            $hiddenFaqs = array_slice($homeFaqs, 6);
        @endphp

        <div x-data="{ expanded: false }">
            <div class="space-y-3" id="faq-container">
                @foreach($visibleFaqs as $faq)
                    <details id="faq-{{ \Illuminate\Support\Str::slug(\Illuminate\Support\Str::limit($faq['q'], 50, '')) }}"
                            class="bg-white border border-slate-200 rounded-xl hover:shadow-md transition group scroll-mt-24">
                        <summary class="flex justify-between items-start gap-4 p-5 cursor-pointer list-none">
                            <h3 class="text-sm sm:text-base font-semibold text-slate-900 group-hover:text-emerald-600 transition flex-1">{{ $faq['q'] }}</h3>
                            <span aria-hidden="true" class="flex-shrink-0 w-7 h-7 rounded-full bg-slate-100 group-hover:bg-emerald-500 group-hover:text-white text-slate-500 flex items-center justify-center text-lg font-bold transition group-open:rotate-45">+</span>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 leading-relaxed text-sm sm:text-base">
                            <p>{!! $faq['a'] !!}</p>
                        </div>
                    </details>
                @endforeach

                <div x-show="expanded" x-collapse x-cloak class="space-y-3">
                    @foreach($hiddenFaqs as $faq)
                        <details id="faq-{{ \Illuminate\Support\Str::slug(\Illuminate\Support\Str::limit($faq['q'], 50, '')) }}"
                                class="bg-white border border-slate-200 rounded-xl hover:shadow-md transition group scroll-mt-24">
                            <summary class="flex justify-between items-start gap-4 p-5 cursor-pointer list-none">
                                <h3 class="text-sm sm:text-base font-semibold text-slate-900 group-hover:text-emerald-600 transition flex-1">{{ $faq['q'] }}</h3>
                                <span aria-hidden="true" class="flex-shrink-0 w-7 h-7 rounded-full bg-slate-100 group-hover:bg-emerald-500 group-hover:text-white text-slate-500 flex items-center justify-center text-lg font-bold transition group-open:rotate-45">+</span>
                            </summary>
                            <div class="px-5 pb-5 text-slate-600 leading-relaxed text-sm sm:text-base">
                                <p>{!! $faq['a'] !!}</p>
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>

            @if(count($hiddenFaqs) > 0)
                <div class="text-center mt-6">
                    <button type="button"
                            @click="expanded = !expanded"
                            :aria-expanded="expanded ? 'true' : 'false'"
                            class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold min-h-[44px]">
                        <span x-text="expanded ? 'Show fewer questions' : 'Show all {{ count($homeFaqs) }} questions'"></span>
                        <x-icon name="chevron-down" class="w-4 h-4 transition-transform" x-bind:class="expanded && 'rotate-180'" />
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ================================================================
     RECENT BLOG POSTS (if any)
     ================================================================ --}}
@if($recentPosts && count(collect($recentPosts)) > 0)
<section class="py-14 sm:py-20 px-4 sm:px-6 bg-slate-50">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-8 flex-wrap gap-3">
            <div>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-2">From the blog</h2>
                <p class="text-slate-600">Guides, pricing breakdowns, planning tips.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold min-h-[44px] inline-flex items-center gap-1">
                All posts <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach(collect($recentPosts)->take(3) as $post)
                <a href="{{ route('blog.show', $post['slug']) }}"
                   class="group bg-white rounded-2xl overflow-hidden border border-slate-200 hover:shadow-lg transition">
                    @if($post['featured_image'])
                        <div class="aspect-[16/9] overflow-hidden bg-slate-100">
                            <img src="{{ asset('storage/' . $post['featured_image']) }}"
                                 alt="{{ $post['title'] }}"
                                 loading="lazy" decoding="async"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    @else
                        <div class="aspect-[16/9] bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center">
                            <x-icon name="calendar" class="w-12 h-12 text-emerald-400" />
                        </div>
                    @endif
                    <div class="p-5">
                        <time class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($post['published_at'])->format('M j, Y') }}</time>
                        <h3 class="mt-1 font-semibold text-slate-900 group-hover:text-emerald-600 transition line-clamp-2">{{ $post['title'] }}</h3>
                        @if($post['excerpt'])
                            <p class="mt-2 text-sm text-slate-600 line-clamp-2">{!! \Illuminate\Support\Str::limit(strip_tags($post['excerpt']), 120) !!}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     FINAL CTA
     ================================================================ --}}
<section class="py-14 sm:py-20 px-4 sm:px-6 bg-slate-900 text-white">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-3xl sm:text-4xl font-bold mb-3 text-balance">Get Your Free Porta Potty Rental Quote Today</h2>
        <p class="text-slate-300 mb-7 max-w-xl mx-auto">
            Stop searching "porta potty rental near me" — we're the local experts serving {{ $cityName }}, {{ $stateName }}.
        </p>
        <a href="tel:{{ $phoneRaw ?? domain_phone_raw() }}"
           data-tracking-label="home-final"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl sm:text-2xl font-bold py-4 px-8 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-6 h-6" />
            <span>{{ $phoneDisplay ?? domain_phone_display() }}</span>
        </a>

        {{-- Trust microcopy (consistent with hero) --}}
        <p class="text-sm text-emerald-300 font-medium flex items-center justify-center gap-2 mt-5">
            <x-icon name="check-circle" class="w-4 h-4 flex-shrink-0" />
            <span>24/7 Emergency Line | Average Answer Time: 10 Seconds</span>
        </p>
        <p class="text-xs text-slate-400 mt-3">
            Or <a href="{{ route('locations') }}" class="text-emerald-400 hover:text-emerald-300 underline">find your city</a> for local pricing.
        </p>
    </div>
</section>

@endsection
