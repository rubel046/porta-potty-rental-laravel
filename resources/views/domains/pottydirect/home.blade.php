@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@php
    $phoneRaw = domain_phone_raw();
    $phoneDisplay = domain_phone_display();
@endphp
@section('title', 'Porta Potty Rental | Same-Day Delivery USA | Call '.$phoneDisplay.' for Free Quote')
@section('meta_description', 'Need porta potty rental near you? Potty Direct offers same-day delivery of clean portable toilets for construction sites, events & weddings. Get your free quote today! Call '.$phoneDisplay)
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

        $businessSchema = [
            "@context" => "https://schema.org",
            "@type" => ["LocalBusiness", "HomeAndConstructionBusiness"],
            "@id" => $url . "#business",
            "name" => $domain?->business_name ?? "Potty Direct",
            "alternateName" => $domain?->primary_service ?? "Portable Restroom Rental",
            "description" => $domain?->tagline ?? "Portable restroom rental service across the USA. Same-day delivery available.",
            "url" => $url,
            "telephone" => $phone,
            "priceRange" => "$$",
            "image" => $url . "/og-image.jpg",
            "areaServed" => $areaServed,
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
                "areaServed" => "US",
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
                ["@type" => "Question", "name" => "How much does a porta potty rental cost?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Pricing varies by location, quantity, unit type, and rental duration. Standard units start around $100-175 per day, while deluxe or ADA-compliant units cost more. Call us for a personalized quote."]],
                ["@type" => "Question", "name" => "Do you offer same-day porta potty delivery?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes! We offer same-day delivery in most service areas when you call before 2 PM. Subject to availability. For guaranteed delivery, we recommend booking at least 24 hours in advance."]],
                ["@type" => "Question", "name" => "What types of porta potty units do you offer?", "acceptedAnswer" => ["@type" => "Answer", "text" => "We offer standard portable toilets, deluxe flushable units with handwashing stations, ADA-compliant accessible units, high-rise units for multi-story construction, and luxury restroom trailers for weddings and upscale events."]],
                ["@type" => "Question", "name" => "Do you offer restroom trailers for events?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes! Our luxury restroom trailers feature climate control, porcelain fixtures, mirrors, and elegant interiors — perfect for weddings, corporate events, and upscale gatherings. They include running water and daily servicing."]],
                ["@type" => "Question", "name" => "How many porta potties do I need for my event?", "acceptedAnswer" => ["@type" => "Answer", "text" => "A general rule is 1 standard unit per 50 guests for a 4-hour event, or 1 unit per 25 guests for an 8-hour event. If alcohol is served, add 20% more units. For construction sites, OSHA requires 1 unit per 20 workers. Call us and we\'ll help you determine the right number."]],
                ["@type" => "Question", "name" => "What is included in the rental?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Our rental includes delivery, setup, pickup, and for weekly/monthly rentals, regular servicing (cleaning, sanitizing, and restocking of toilet paper). No hidden fees — the price we quote is the price you pay."]],
                ["@type" => "Question", "name" => "Do units include hand sanitizer?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, all our standard units include hand sanitizer dispensers. Deluxe units come with handwashing stations with soap and paper towels. We can also provide standalone handwashing stations for any event or job site."]],
                ["@type" => "Question", "name" => "How far in advance should I book?", "acceptedAnswer" => ["@type" => "Answer", "text" => "For construction sites, book 1-2 weeks ahead. For events, we recommend booking 2-4 weeks in advance, especially during spring and fall peak season. Last-minute bookings may be possible — call us to check availability."]],
                ["@type" => "Question", "name" => "How often are porta potties serviced?", "acceptedAnswer" => ["@type" => "Answer", "text" => "For weekly and monthly rentals, our standard service includes once-per-week cleaning, pumping, sanitizing, and restocking of supplies. For high-traffic locations or events, we offer twice-weekly or daily servicing."]],
                ["@type" => "Question", "name" => "Do you provide ADA-accessible portable restrooms?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, we offer fully ADA-compliant portable restrooms with extra-wide doors for wheelchair access, interior grab bars, lowered seats, and spacious interiors. Public events may be required to include accessible units."]],
                ["@type" => "Question", "name" => "What areas do you service?", "acceptedAnswer" => ["@type" => "Answer", "text" => "We service cities and counties across the state. Enter your zip code or city on our locations page to see if we service your area, or call us for quick confirmation."]],
                ["@type" => "Question", "name" => "Do portable toilets need water or electricity?", "acceptedAnswer" => ["@type" => "Answer", "text" => "No, our standard portable toilets are completely self-contained and require no water, electricity, or plumbing. They use a chemical solution in the holding tank that controls odors and breaks down waste. Deluxe flushable units need water for handwashing only."]],
                ["@type" => "Question", "name" => "How do you dispose of waste responsibly?", "acceptedAnswer" => ["@type" => "Answer", "text" => "All waste is collected by licensed professionals and transported to approved treatment facilities. We follow strict EPA and local regulations for disposal. Our company uses eco-friendly cleaning products and biodegradable chemicals whenever possible."]],
                ["@type" => "Question", "name" => "Do you offer single-day rentals?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, we offer single-day rentals for events and short-term needs. Pricing is based on the number of units and delivery distance. Extended rentals (weekly/monthly) offer better rates with servicing included."]],
                ["@type" => "Question", "name" => "What if a unit needs servicing during my rental?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Simply call us and we\'ll send a technician to service or replace the unit. For weekly/monthly rentals, our regular servicing schedule ensures units stay clean and functional. Emergency service is available for critical situations."]],
                ["@type" => "Question", "name" => "Is there a deposit or hidden fees?", "acceptedAnswer" => ["@type" => "Answer", "text" => "We believe in transparent pricing. Quotes include delivery, setup, servicing, and pickup — no hidden fees. Deposits vary by rental size and duration. We\'ll provide a full breakdown before you commit."]]
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
                Need a Porta Potty Fast?
                <span class="block text-emerald-400 text-xl sm:text-2xl md:text-3xl lg:text-4xl mt-2 font-bold">
                    Clean units · Same-day · No hidden fees.
                </span>
            </h1>

            <p class="text-base sm:text-lg text-slate-300 mb-5 sm:mb-7 max-w-xl leading-relaxed">
                We deliver portable restrooms nationwide for construction sites, weddings,
                outdoor events, and emergency jobs. Call now for an instant quote.
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
                @if(($reviewCount ?? 0) > 0)
                    <span class="text-slate-600" aria-hidden="true">·</span>
                    <span class="inline-flex items-center gap-1.5"><x-icon name="star" class="w-4 h-4 text-amber-400" />{{ number_format($reviewRating ?? 4.9, 1) }}/5 ({{ $reviewCount }}+ reviews)</span>
                @endif
            </div>
        </div>
    </div>
</section>

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
                    ['key' => 'standard',     'icon' => 'building',       'name' => 'Standard',      'blurb' => 'OSHA-compliant basics for job sites.'],
                    ['key' => 'deluxe',       'icon' => 'water-drop',     'name' => 'Deluxe Flush',  'blurb' => 'Flushing toilet + hand sink.'],
                    ['key' => 'ada',          'icon' => 'accessibility',  'name' => 'ADA Accessible','blurb' => 'Wheelchair access, grab bars.'],
                    ['key' => 'luxury',       'icon' => 'sparkles',       'name' => 'Luxury Trailer','blurb' => 'Climate-controlled, porcelain.'],
                    ['key' => 'shower',       'icon' => 'shower',         'name' => 'Portable Shower','blurb' => 'Hot &amp; cold water stalls.'],
                    ['key' => 'construction', 'icon' => 'users',          'name' => 'Construction',  'blurb' => 'Bulk site packages, weekly service.'],
                    ['key' => 'dumpster',     'icon' => 'trash',          'name' => 'Dumpster',      'blurb' => 'Roll-off, 10–40 yard.'],
                    ['key' => 'septic',       'icon' => 'wrench',         'name' => 'Septic Service','blurb' => 'Pump-outs, maintenance.'],
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
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-3 text-balance">Why call us first</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">
                You get a straight answer, fast delivery, and a clean unit. That's it. No upsells, no hidden fees, no phone menus.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $pillars = [
                    ['icon' => 'phone',        'title' => 'Real humans, fast',       'body' => 'Calls answered in under 30 seconds during business hours. Never voicemail first.'],
                    ['icon' => 'truck',        'title' => 'Same-day when possible',  'body' => 'Order by 2 PM and we aim to deliver before end of day in most markets.'],
                    ['icon' => 'shield-check', 'title' => 'Licensed & insured',      'body' => 'Proper permits, proper coverage, OSHA-compliant units. No liability surprises.'],
                    ['icon' => 'currency-dollar','title' => 'Transparent pricing',   'body' => 'The price we quote is the price you pay. No fuel surcharge bait-and-switch.'],
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
     TYPICAL JOBS (honest use-cases — no invented reviews)
     ================================================================ --}}
<section class="py-14 sm:py-20 px-4 sm:px-6 bg-white">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-3 text-balance">What a typical job looks like</h2>
            <p class="text-slate-600 max-w-2xl mx-auto">
                Not a testimonial — a snapshot of the kinds of rentals we handle every week.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @php
                $jobs = [
                    [
                        'icon' => 'building',
                        'tag' => 'Construction',
                        'title' => 'Long-term job site',
                        'lines' => [
                            'Commercial build, 6-month lease',
                            '4 standard units + 1 ADA',
                            'Weekly servicing included',
                            'Invoiced monthly, net-30',
                        ],
                    ],
                    [
                        'icon' => 'sparkles',
                        'tag' => 'Event',
                        'title' => 'Weekend wedding',
                        'lines' => [
                            '150-guest outdoor venue',
                            '2 deluxe flush + 1 luxury trailer',
                            'Friday delivery, Sunday pickup',
                            'Fully sanitized between uses',
                        ],
                    ],
                    [
                        'icon' => 'bolt',
                        'tag' => 'Emergency',
                        'title' => 'Same-day storm response',
                        'lines' => [
                            'Post-storm cleanup crew',
                            '6 units delivered in under 4 hours',
                            '24-hour initial rental, extensible',
                            'Direct dispatch, no call menus',
                        ],
                    ],
                ];
            @endphp

            @foreach($jobs as $job)
                <article class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <x-icon name="{{ $job['icon'] }}" class="w-5 h-5" />
                        </div>
                        <span class="text-[11px] uppercase tracking-wider font-semibold text-emerald-700">{{ $job['tag'] }}</span>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-3">{{ $job['title'] }}</h3>
                    <ul class="space-y-1.5 text-sm text-slate-600">
                        @foreach($job['lines'] as $line)
                            <li class="flex items-start gap-2">
                                <x-icon name="check" class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" />
                                <span>{{ $line }}</span>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </div>
</section>

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
     LEAD FORM (secondary CTA — for visitors who won't call cold)
     ================================================================ --}}
<x-lead-form source="homepage" />

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
                ['q' => 'How much does a porta potty rental cost?',                  'a' => 'Pricing varies by location, quantity, unit type, and rental duration. Standard units start around $100–175 per day, while deluxe or ADA-compliant units cost more. <a href="' . route('pricing') . '" class="text-emerald-600 hover:underline">View our full pricing guide</a> or call us for a personalized quote.'],
                ['q' => 'Do you offer same-day porta potty delivery?',                'a' => 'Yes — in most service areas, when you call before 2 PM. Subject to availability. For guaranteed delivery, we recommend booking at least 24 hours in advance.'],
                ['q' => 'What types of porta potty units do you offer?',              'a' => 'Standard portable toilets, <a href="' . route('services') . '#deluxe" class="text-emerald-600 hover:underline">deluxe flushable units</a> with handwashing stations, <a href="' . route('services') . '#ada" class="text-emerald-600 hover:underline">ADA-compliant accessible units</a>, <a href="' . route('services') . '#luxury" class="text-emerald-600 hover:underline">luxury restroom trailers</a>, and high-rise units for multi-story construction.'],
                ['q' => 'Do you offer restroom trailers for events?',                 'a' => 'Yes. Our <a href="' . route('services') . '#luxury" class="text-emerald-600 hover:underline">luxury restroom trailers</a> feature climate control, porcelain fixtures, mirrors, and elegant interiors — perfect for weddings, corporate events, and upscale gatherings.'],
                ['q' => 'How many porta potties do I need for my event?',             'a' => 'A general rule: 1 standard unit per 50 guests for a 4-hour event, or 1 unit per 25 guests for an 8-hour event. If alcohol is served, add 20% more. For construction sites, OSHA requires 1 unit per 20 workers. Call us and we\'ll help you determine the right number.'],
                ['q' => 'What is included in the rental?',                            'a' => 'Delivery, setup, pickup, and — for weekly/monthly rentals — regular servicing (cleaning, sanitizing, toilet-paper restock). <a href="' . route('pricing') . '" class="text-emerald-600 hover:underline">No hidden fees</a> — the price we quote is the price you pay.'],
                ['q' => 'Do units include hand sanitizer?',                           'a' => 'Yes, all standard units include hand sanitizer dispensers. Deluxe units include handwashing stations with soap and paper towels. We also provide standalone <a href="' . route('services') . '#handwash" class="text-emerald-600 hover:underline">hand wash stations</a> for any event or job site.'],
                ['q' => 'How far in advance should I book?',                          'a' => 'Construction sites: 1–2 weeks ahead. Events: 2–4 weeks, especially spring and fall peak season. Last-minute bookings may still be possible — call to check <a href="' . route('locations') . '" class="text-emerald-600 hover:underline">availability in your area</a>.'],
                ['q' => 'How often are porta potties serviced?',                      'a' => 'Weekly and monthly rentals include once-per-week cleaning, pumping, sanitizing, and supply restock. For high-traffic sites or events, we offer twice-weekly or daily servicing.'],
                ['q' => 'Do you provide ADA-accessible portable restrooms?',          'a' => 'Yes — fully <a href="' . route('services') . '#ada" class="text-emerald-600 hover:underline">ADA-compliant portable restrooms</a> with extra-wide doors for wheelchair access, interior grab bars, lowered seats, and spacious interiors.'],
                ['q' => 'What areas do you service?',                                 'a' => 'Cities and counties across the state. <a href="' . route('locations') . '" class="text-emerald-600 hover:underline">Enter your zip or city</a> to confirm, or call us.'],
                ['q' => 'Do portable toilets need water or electricity?',             'a' => 'No. Standard units are self-contained and need no water, electricity, or plumbing. A chemical solution in the tank controls odors and breaks down waste. Deluxe flushable units need water for handwashing only.'],
                ['q' => 'How do you dispose of waste responsibly?',                   'a' => 'Waste is collected by licensed professionals and transported to approved treatment facilities. We follow EPA and local regulations and use eco-friendly cleaning products wherever possible.'],
                ['q' => 'Do you offer single-day rentals?',                           'a' => 'Yes. <a href="' . route('pricing') . '" class="text-emerald-600 hover:underline">Pricing depends on unit count and delivery distance</a>. Weekly and monthly rentals include servicing at better rates.'],
                ['q' => 'What if a unit needs servicing during my rental?',           'a' => 'Call us and we\'ll send a technician to service or replace the unit. For weekly/monthly rentals, regular servicing keeps units clean and functional. Emergency service is available for critical situations.'],
                ['q' => 'Is there a deposit or hidden fees?',                         'a' => 'Transparent pricing. <a href="' . route('pricing') . '" class="text-emerald-600 hover:underline">Quotes include delivery, setup, servicing, and pickup</a> — no hidden fees. Deposits vary by rental size and duration.'],
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
        <h2 class="text-3xl sm:text-4xl font-bold mb-3 text-balance">Ready when you are.</h2>
        <p class="text-slate-300 mb-7 max-w-xl mx-auto">
            Pick up the phone. Tell us what you need. We'll quote a real number and deliver — often the same day.
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
            <span>Answered in under 30 seconds by a real person — no robocalls.</span>
        </p>
        <p class="text-xs text-slate-400 mt-3">
            Or <a href="{{ route('locations') }}" class="text-emerald-400 hover:text-emerald-300 underline">find your city</a> for local pricing.
        </p>
    </div>
</section>

@endsection
