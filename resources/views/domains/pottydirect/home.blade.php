@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@section('title', 'Porta Potty Rental | Same-Day Delivery USA | Call '.domain_phone_display().' for Free Quote')
@section('meta_description', 'Need porta potty rental near you? Potty Direct offers same-day delivery of clean portable toilets for construction sites, events & weddings. Get your free quote today! Call '.domain_phone_display())
@section('canonical', url('/'))

@push('schema')
    @php
        $url = url('/');
        $phone = domain_phone_raw();
        $domain = \App\Models\Domain::current();

        $areaServed = collect($topCities ?? [])->map(fn($c) => ["@type" => "City", "name" => $c['name']])->toArray();
        if (empty($areaServed)) {
            $areaServed = [["@type" => "Country", "name" => "United States"]];
        }

        $businessSchema = [
            "@context" => "https://schema.org",
            "@type" => "LocalBusiness",
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
                ["@type" => "OpeningHoursSpecification", "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"], "opens" => "00:00", "closes" => "23:59"]
            ],
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
            "aggregateRating" => ["@type" => "AggregateRating", "ratingValue" => "4.9", "reviewCount" => "500"]
        ];

        $orgSchema = [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "@id" => $url . "#organization",
            "name" => $domain?->business_name ?? "Potty Direct",
            "url" => $url,
            "logo" => $url . "/logo.png",
            "sameAs" => ["https://www.facebook.com/pottydirect", "https://www.twitter.com/pottydirect"],
            "contactPoint" => ["@type" => "ContactPoint", "telephone" => $phone, "contactType" => "customer service", "areaServed" => "US", "availableLanguage" => "English"]
        ];

        $websiteSchema = [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "@id" => $url . "#website",
            "url" => $url,
            "name" => ($domain?->business_name ?? "Potty Direct") . " - " . ($domain?->primary_service ?? "Portable Restroom Rental"),
            "publisher" => ["@id" => $url . "#organization"],
            "potentialAction" => ["@type" => "SearchAction", "target" => $url . "/locations?q={search_term_string}", "query-input" => "required name=search_term_string"]
        ];

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

        $reviewSchema = [
            "@context" => "https://schema.org",
            "@type" => "Collection",
            "name" => "Customer Reviews",
            "description" => "Customer reviews and testimonials for " . ($domain?->business_name ?? "Potty Direct") . " porta potty rental service",
            "aggregateRating" => ["@type" => "AggregateRating", "ratingValue" => "4.9", "reviewCount" => "3"],
            "itemListElement" => [
                ["@type" => "Review", "itemReviewed" => ["@type" => "LocalBusiness", "name" => $domain?->business_name ?? "Potty Direct"], "reviewRating" => ["@type" => "Rating", "ratingValue" => "5"], "author" => ["@type" => "Person", "name" => "Mike Thompson"], "reviewBody" => "We use " . ($domain?->business_name ?? "Potty Direct") . " for all our job sites. Same-day delivery, always clean units, and no surprise charges on the invoice. Their team is professional and reliable."],
                ["@type" => "Review", "itemReviewed" => ["@type" => "LocalBusiness", "name" => $domain?->business_name ?? "Potty Direct"], "reviewRating" => ["@type" => "Rating", "ratingValue" => "5"], "author" => ["@type" => "Person", "name" => "Sarah Martinez"], "reviewBody" => "Planned a wedding for 200 guests and needed 6 porta potties. They delivered and picked up on time. The units were spotless! Highly recommend for any event."],
                ["@type" => "Review", "itemReviewed" => ["@type" => "LocalBusiness", "name" => $domain?->business_name ?? "Potty Direct"], "reviewRating" => ["@type" => "Rating", "ratingValue" => "5"], "author" => ["@type" => "Person", "name" => "David Chen"], "reviewBody" => "Been working with them for 5 years. Always competitive pricing, never had an issue with delivery timing. They treat my job sites like their own. Outstanding service!"]
            ]
        ];

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
    <script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($reviewSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($howtoSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

    {{-- ============================================ --}}
    {{-- HERO SECTION --}}
    {{-- ============================================ --}}
    @php
        // Get domain prefix from URL directly (no DB query)
        $host = request()->getHost();
        $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host); // "pottydirect.com" → "pottydirect"

        // Fallback for local development
        if ($prefix === 'localhost' || !Storage::disk('public')->exists($prefix . '/hero-banner-images')) {
            $prefix = 'pottydirect';
        }

        $heroImages = collect(Storage::disk('public')->files($prefix . '/hero-banner-images'))
            ->filter(fn($f) => in_array(pathinfo($f, PATHINFO_EXTENSION), ['webp', 'jpg', 'jpeg', 'png']))
            ->toArray();
        $randomHero = !empty($heroImages) ? $heroImages[array_rand($heroImages)] : $prefix . '/hero-banner-images/default.webp';
        $heroUrl = asset('storage/' . $randomHero);
    @endphp

    <section class="relative min-h-[500px] sm:min-h-[560px] md:min-h-[680px] flex items-center overflow-hidden">
        {{-- Hero Background Image --}}
        <div class="absolute inset-0">
            <img src="{{ $heroUrl }}" alt="Portable toilet rental for construction and events"
                 class="w-full h-full object-cover"
                 width="1920"
                 height="1080"
                 loading="eager"
                 decoding="async">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/75 to-slate-900/60"></div>
        </div>

        {{-- Decorative Elements --}}
        <div class="absolute top-16 sm:top-20 right-4 sm:right-10 w-32 sm:w-48 md:w-64 h-32 sm:h-48 md:h-64 bg-emerald-500/10 rounded-full blur-3xl desktop-only"></div>
        <div class="absolute bottom-16 sm:bottom-20 left-4 sm:left-10 w-24 sm:w-32 md:w-48 h-24 sm:h-32 md:h-48 bg-blue-500/10 rounded-full blur-3xl desktop-only"></div>

        <div class="relative max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-12 sm:py-16 md:py-28 w-full">
            <div class="max-w-2xl md:max-w-3xl">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 bg-emerald-500/20 backdrop-blur-sm border border-emerald-400/30 text-emerald-300 text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-4 sm:mb-6">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="hidden sm:inline">Same-Day</span> Delivery Available — Order by 2PM
                </div>

                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold text-white mb-4 sm:mb-6 leading-tight">
                    Rent Porta Potties Anywhere in the USA<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-300 text-xl sm:text-2xl md:text-3xl lg:text-4xl">
                        Same-Day Delivery • Clean Units • No Hidden Fees
                    </span>
                </h1>

                <p class="text-base sm:text-lg md:text-xl text-slate-300 mb-4 sm:mb-6 leading-relaxed max-w-xl">
                    Need a <strong class="text-white">porta potty rental</strong> for your construction site, outdoor
                    event, or wedding?
                    We deliver clean, sanitized <strong class="text-white">portable toilet rentals</strong> to all 50
                    states!
                </p>

                {{-- CTA Buttons - PHONE IS PRIMARY CTA --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                    {{-- PRIMARY CTA: Phone number as large button --}}
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full sm:w-auto bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500
                              text-white text-xl sm:text-2xl md:text-3xl font-bold
                              py-4 sm:py-5 px-8 sm:px-12 rounded-2xl shadow-2xl shadow-amber-500/40
                              transition-all hover:scale-[1.02] hover:shadow-amber-500/50
                              flex items-center justify-center gap-3 sm:gap-4
                              ring-4 ring-amber-400/30 animate-pulse-slow">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <span>{{ domain_phone_display() }}</span>
                    </a>
                    
                    {{-- SECONDARY CTA: Find city --}}
                    <a href="{{ route('locations') }}"
                       class="w-full sm:w-auto bg-white/10 hover:bg-white/20 backdrop-blur-sm
                              border-2 border-white/30 text-white text-base sm:text-lg font-semibold
                              py-3 sm:py-4 px-6 sm:px-8 rounded-xl
                              transition-all hover:scale-[1.02]
                              flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span>Find Your City</span>
                    </a>
                </div>

                {{-- Urgency Badge - High contrast for visibility on dark background --}}
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 bg-amber-500/90 backdrop-blur-sm text-white text-xs sm:text-sm px-3 py-1.5 rounded-full font-semibold shadow-lg">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>Same-day delivery: Order by 2PM</span>
                    </span>
                </div>

                {{-- Location Search Bar - Improved --}}
                <div class="w-full max-w-2xl">
                    <form action="{{ route('locations') }}" method="GET"
                          class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <select name="quick_city"
                                    class="w-full bg-white border-2 border-white/20 text-slate-800 text-base py-3.5 pl-11 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 cursor-pointer appearance-none font-medium"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22M6%206l4%204%204-4%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem;"
                                    onchange="if(this.value){window.location=this.value;}">
                                <option value="" class="text-slate-800">Select your city...</option>
                                @php
                                    $quickCities = collect($topCities ?? $featuredCities ?? [])->take(12);
                                @endphp
                                @foreach($quickCities as $city)
                                    @php
                                        $servicePage = collect($city['service_pages'] ?? [])->firstWhere('service_type', 'general');
                                        $citySlug = $servicePage['slug'] ?? null;
                                    @endphp
                                    @if($citySlug)
                                        <option value="{{ url($citySlug) }}" class="text-slate-800">{{ $city['name'] }}
                                            , {{ $city['state']['code'] ?? '' }}</option>
                                    @endif
                                @endforeach
                                <option value="{{ route('locations') }}" class="text-slate-800">View All Locations
                                </option>
                            </select>
                        </div>
                        <div class="flex-1 relative sm:flex-none sm:w-36">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 11.5A2.5 2.5 0 109.5 9a2.5 2.5 0 002.5 2.5z"/>
                                </svg>
                            </div>
                            <input type="text" name="q" placeholder="Zip code"
                                   class="w-full bg-white border-2 border-white/20 text-slate-800 text-base py-3.5 pl-11 pr-4 rounded-xl placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 font-medium">
                        </div>
                        <button type="submit"
                                class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold py-3.5 px-6 sm:px-8 rounded-xl transition-all shadow-lg hover:shadow-amber-500/40 flex items-center justify-center gap-2 whitespace-nowrap">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Search</span>
                        </button>
                    </form>
                </div>

                {{-- Trust Indicators - Clean SVG version --}}
                <div class="flex flex-wrap items-center gap-x-3 sm:gap-x-6 gap-y-2 mt-4 text-xs sm:text-sm trust-indicators">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <div class="flex">
                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                                <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                                <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                                <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                                <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                                <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                        </div>
                        <span class="text-slate-300 font-medium">4.9/5</span>
                    </div>
                    <div class="hidden xsm:block h-4 w-px bg-white/20"></div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <svg class="w-4 sm:w-5 h-4 sm:h-5 text-emerald-400" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span class="text-slate-300 font-medium">Licensed & Insured</span>
                    </div>
                    <div class="hidden xsm:block h-4 w-px bg-white/20"></div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <svg class="w-4 sm:w-5 h-4 sm:h-5 text-emerald-400" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span class="text-slate-300 font-medium">Same-Day</span>
                    </div>
                    <div class="hidden xsm:block h-4 w-px bg-white/20"></div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <svg class="w-4 sm:w-5 h-4 sm:h-5 text-emerald-400" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span class="text-slate-300 font-medium">No Hidden Fees</span>
                    </div>
                </div>

                {{-- Trust Badges Row - Simplified on mobile --}}
                <div class="flex flex-wrap items-center gap-x-3 sm:gap-x-6 gap-y-2 mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-white/10 text-xs text-slate-400">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect
                                        x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4"/></svg>
                        </span>
                        <span class="hidden sm:inline">BBB A+</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path
                                        d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </span>
                        <span class="hidden sm:inline">OSHA</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.5"><path d="m2 22 10-10"/><path d="m16 8-1.17-1.17"/><path
                                        d="M3.47 12.53 5 11l1.53 1.53a3.5 3.5 0 0 1 0 4.94L5 19l-1.53-1.53a3.5 3.5 0 0 1 0-4.94Z"/></svg>
                        </span>
                        <span class="hidden sm:inline">25+ Yrs</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle
                                        cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path
                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        </span>
                        <span class="hidden sm:inline">50K+</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wave Divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,50 1440,40 L1440,80 L0,80 Z"
                      fill="white"/>
            </svg>
        </div>
    </section>

    {{-- CONSOLIDATED TRUST SIGNALS BAR --}}
    <section class="py-4 sm:py-6 bg-gradient-to-r from-amber-50 to-amber-50 border-b border-amber-100">
        <div class="max-w-6xl mx-auto px-3 sm:px-6">
            <div class="flex flex-wrap justify-center items-center gap-x-6 gap-y-3 text-center">
                {{-- Trust Score --}}
                <div class="flex items-center gap-2">
                    <div class="flex">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm font-bold text-slate-700">4.9/5</span>
                    <span class="text-xs text-slate-500">(500+ reviews)</span>
                </div>
                
                <span class="hidden sm:inline text-emerald-300">|</span>
                
                {{-- BBB --}}
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">BBB A+</span>
                </div>
                
                <span class="hidden sm:inline text-emerald-300">|</span>
                
                {{-- Same-Day --}}
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Same-Day Delivery</span>
                </div>
                
                <span class="hidden sm:inline text-emerald-300">|</span>
                
                {{-- No Hidden Fees --}}
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">No Hidden Fees</span>
                </div>
                
                <span class="hidden md:inline text-emerald-300">|</span>
                
                {{-- 24/7 --}}
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">24/7 Support</span>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVICE OPTIONS --}}
    {{-- ============================================ --}}
    <section id="services" class="py-12 md:py-16 lg:py-20 px-3 sm:px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10 md:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-3 sm:mb-4">
                    Rental Services for Every Need
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    From portable toilets to dumpsters and septic services —
                    we have the right <strong>service solution</strong> for every project and event type.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                {{-- Standard --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-blue-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 flex items-center justify-center text-slate-600 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 22v-4a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v4"/>
                            <path d="M5 22V6a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v1"/>
                            <path d="M19 22V6a2 2 0 0 0-2-2V3a2 2 0 0 0-2-2h0a2 2 0 0 0-2 2v1"/>
                            <rect x="5" y="9" width="14" height="11" rx="1"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Standard Porta Potty</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Construction & Events
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <span>Get Quote</span>
                    </a>
                </div>

                {{-- Deluxe --}}
                <div class="bg-white border-2 border-emerald-400 rounded-2xl p-4 sm:p-6 text-center
                            hover:shadow-xl hover:shadow-emerald-100/50 transition-all duration-300
                            group relative overflow-visible">
                    <div class="absolute -top-3 sm:-top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-400 to-amber-500
                                text-white text-xs sm:text-sm font-extrabold px-3 sm:px-5 py-1.5 sm:py-2 rounded-full shadow-xl shadow-amber-500/40 z-20">
                        POPULAR
                    </div>
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-emerald-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 mt-1 sm:mt-2 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="14" rx="1"/>
                            <path d="M3 9h18"/>
                            <path d="M7 19v-7"/>
                            <path d="M12 19v-4"/>
                            <path d="M17 19v-5"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Deluxe Flushable</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Weddings & Events
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-700
                              text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-amber-500/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <span>Get Quote</span>
                    </a>
                </div>

                {{-- ADA --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-blue-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="17" r="3"/>
                            <path d="M9 17h1"/>
                            <path d="M10 13v-2a2 2 0 1 0-4 0"/>
                            <path d="M14 17H8"/>
                            <path d="M15 17a2 2 0 1 0 4 0"/>
                            <path d="M17 17h2"/>
                            <path d="M14.5 3.5h5"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">ADA Accessible</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Wheelchair Friendly
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <span>Get Quote</span>
                    </a>
                </div>

                {{-- Luxury --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-purple-200 hover:shadow-xl hover:shadow-purple-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-purple-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 22v-7"/>
                            <path d="M16 22v-7"/>
                            <path d="M12 22V8"/>
                            <rect x="3" y="11" width="18" height="10" rx="1"/>
                            <path d="M3 8h18"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Luxury Trailer</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        VIP Events
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-purple-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <span>Get Quote</span>
                    </a>
                </div>

                {{-- Dumpster --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-amber-200 hover:shadow-xl hover:shadow-amber-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-amber-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="13" rx="2"/>
                            <path d="M6 7V4"/>
                            <path d="M18 7V4"/>
                            <path d="M6 10h12"/>
                            <path d="M6 17h12"/>
                            <path d="M9.5 3.5h5"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Dumpster Rental</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Construction & Events
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-amber-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <span>Get Quote</span>
                    </a>
                </div>

                {{-- Septic --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-teal-200 hover:shadow-xl hover:shadow-teal-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-teal-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.7 1.7a1 1 0 0 0 1.4 0l3.3-3.3a1 1 0 0 0 0-1.4L18 3"/>
                            <path d="M5 5a2 2 0 0 0-2 2v5"/>
                            <path d="M5 9a2 2 0 0 0 2 2h5"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Septic Service</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Pumping & Maintenance
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-teal-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <span>Get Quote</span>
                    </a>
                </div>
            </div>

            <div class="text-center mt-6 sm:mt-8">
                <a href="{{ route('services') }}"
                   class="text-emerald-600 hover:text-emerald-700 font-semibold inline-flex items-center gap-2">
                    View All Services →
                </a>
            </div>
        </div>
    </section>

    {{-- Quick Links --}}
    <section class="py-8 sm:py-10 px-3 sm:px-4 bg-slate-50 border-y border-slate-200">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-wrap justify-center gap-6 sm:gap-8 text-center">
                <a href="{{ route('services') }}" class="flex flex-col items-center gap-2 group">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-slate-600 group-hover:text-emerald-600 transition"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M7 22v-4a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v4"/>
                        <path d="M5 22V6a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v1"/>
                        <path d="M19 22V6a2 2 0 0 0-2-2V3a2 2 0 0 0-2-2h0a2 2 0 0 0-2 2v1"/>
                        <rect x="5" y="9" width="14" height="11" rx="1"/>
                    </svg>
                    <span class="font-semibold text-slate-700 group-hover:text-emerald-600 transition text-sm sm:text-base">Our Services</span>
                </a>
                <a href="{{ route('pricing') }}" class="flex flex-col items-center gap-2 group">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-slate-600 group-hover:text-emerald-600 transition"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    <span class="font-semibold text-slate-700 group-hover:text-emerald-600 transition text-sm sm:text-base">View Pricing</span>
                </a>
                <a href="{{ route('locations') }}" class="flex flex-col items-center gap-2 group">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-slate-600 group-hover:text-emerald-600 transition"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span class="font-semibold text-slate-700 group-hover:text-emerald-600 transition text-sm sm:text-base">All Locations</span>
                </a>
                <a href="{{ route('blog.index') }}" class="flex flex-col items-center gap-2 group">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-slate-600 group-hover:text-emerald-600 transition"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <span class="font-semibold text-slate-700 group-hover:text-emerald-600 transition text-sm sm:text-base">Blog</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- POPULAR SERVICE AREAS (NEW - SEO + Internal Linking) --}}
    {{-- ============================================ --}}
    @php
        $popularCities = collect($topCities ?? $featuredCities ?? [])->take(12);
    @endphp
    @if($popularCities->isNotEmpty())
        <section class="py-8 sm:py-10 px-3 sm:px-4 bg-white border-b border-slate-200">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-6 sm:mb-8">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-800 mb-2">
                        Porta Potty Rental Near You
                    </h2>
                    <p class="text-sm sm:text-base text-slate-500">
                        Fast delivery to these cities and surrounding areas
                    </p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
                    @foreach($popularCities as $city)
                        @php
                            $servicePage = collect($city['service_pages'] ?? [])->firstWhere('service_type', 'general');
                            $citySlug = $servicePage['slug'] ?? null;
                        @endphp
                        @if($citySlug)
                            <a href="{{ url($citySlug) }}"
                               class="flex flex-col items-center gap-2 p-3 sm:p-4 bg-slate-50 hover:bg-emerald-50
                                      border border-slate-200 hover:border-emerald-300 rounded-xl transition-all group">
                                <span class="text-lg sm:text-xl">📍</span>
                                <span class="font-semibold text-slate-700 group-hover:text-emerald-700 text-center text-xs sm:text-sm">
                                    {{ $city['name'] }}
                                </span>
                                <span class="text-xs text-slate-400 group-hover:text-emerald-600">
                                    {{ $city['state']['code'] ?? '' }}
                                </span>
                            </a>
                        @endif
                    @endforeach
                </div>
                <div class="text-center mt-6 sm:mt-8">
                    <a href="{{ route('locations') }}"
                       class="text-emerald-600 hover:text-emerald-700 font-semibold text-sm sm:text-base inline-flex items-center gap-2">
                        View All {{ count($popularCities) }}+ Cities →
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================ --}}
    {{-- INTERACTIVE UNIT CALCULATOR WIDGET --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-14 px-3 sm:px-4 bg-gradient-to-br from-slate-50 via-white to-slate-50 border-y border-slate-200">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8 sm:mb-10">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-800 mb-2">
                    🤔 How Many Units Do You Need?
                </h2>
                <p class="text-sm sm:text-base text-slate-500">
                    Use our interactive calculator to find the right number of porta potties
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                {{-- Calculator Tabs --}}
                <div class="flex border-b border-slate-200">
                    <button onclick="switchCalculator('construction')" 
                            id="calc-tab-construction"
                            class="flex-1 py-4 px-6 text-center font-semibold text-sm sm:text-base bg-emerald-500 text-white transition-all calc-tab">
                        🏗️ Construction
                    </button>
                    <button onclick="switchCalculator('event')" 
                            id="calc-tab-event"
                            class="flex-1 py-4 px-6 text-center font-semibold text-sm sm:text-base text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 transition-all calc-tab">
                        🎉 Event / Party
                    </button>
                </div>

                {{-- Calculator Content --}}
                <div class="p-6 sm:p-8">
                    {{-- Construction Calculator --}}
                    <div id="calculator-construction" class="calc-content">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    Number of Workers
                                </label>
                                <input type="range" id="workers-slider" min="1" max="200" value="20" 
                                       class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-500"
                                       oninput="updateConstructionCalc()">
                                <div class="flex justify-between mt-2">
                                    <span class="text-xs text-slate-400">1</span>
                                    <span class="text-lg font-bold text-emerald-600" id="workers-display">20</span>
                                    <span class="text-xs text-slate-400">200+</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                <svg class="w-5 h-5 text-amber-600 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                <span class="text-sm text-amber-800">OSHA requires 1 unit per 20 workers</span>
                            </div>
                        </div>

                        {{-- Result --}}
                        <div class="mt-8 p-6 bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl text-center">
                            <div class="text-white text-sm mb-1">Recommended Units</div>
                            <div class="text-white text-4xl sm:text-5xl font-bold" id="construction-result">2</div>
                            <div class="text-emerald-100 text-sm mt-2">units for your job site</div>
                        </div>

                        <div class="mt-6 text-center">
                            <a href="tel:{{ domain_phone_raw() }}"
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-amber-500/25 transition-all hover:scale-[1.02]">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                Call for Exact Quote
                            </a>
                        </div>
                    </div>

                    {{-- Event Calculator --}}
                    <div id="calculator-event" class="calc-content hidden">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    Number of Guests
                                </label>
                                <input type="range" id="guests-slider" min="10" max="500" value="100" step="10"
                                       class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-500"
                                       oninput="updateEventCalc()">
                                <div class="flex justify-between mt-2">
                                    <span class="text-xs text-slate-400">10</span>
                                    <span class="text-lg font-bold text-blue-600" id="guests-display">100</span>
                                    <span class="text-xs text-slate-400">500+</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-3">
                                    Event Duration
                                </label>
                                <div class="grid grid-cols-3 gap-3">
                                    <button onclick="setEventDuration('short')" 
                                            class="duration-btn py-3 px-4 border-2 border-emerald-500 bg-emerald-500 text-white rounded-xl font-semibold text-sm"
                                            data-duration="short">
                                        4 hours
                                    </button>
                                    <button onclick="setEventDuration('medium')" 
                                            class="duration-btn py-3 px-4 border-2 border-slate-200 text-slate-600 rounded-xl font-semibold text-sm hover:border-emerald-300"
                                            data-duration="medium">
                                        8 hours
                                    </button>
                                    <button onclick="setEventDuration('long')" 
                                            class="duration-btn py-3 px-4 border-2 border-slate-200 text-slate-600 rounded-xl font-semibold text-sm hover:border-emerald-300"
                                            data-duration="long">
                                        All Day
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                <input type="checkbox" id="alcohol-check" class="w-4 h-4 accent-blue-600" onchange="updateEventCalc()">
                                <label for="alcohol-check" class="text-sm text-blue-800">Alcohol will be served (+20% units recommended)</label>
                            </div>
                        </div>

                        {{-- Result --}}
                        <div class="mt-8 p-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-center">
                            <div class="text-white text-sm mb-1">Recommended Units</div>
                            <div class="text-white text-4xl sm:text-5xl font-bold" id="event-result">3</div>
                            <div class="text-blue-100 text-sm mt-2">units for your event</div>
                        </div>

                        <div class="mt-6 text-center">
                            <a href="tel:{{ domain_phone_raw() }}"
                               class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-amber-500/25 transition-all hover:scale-[1.02]">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                Call for Exact Quote
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let eventDuration = 'short';

        function switchCalculator(type) {
            document.querySelectorAll('.calc-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.calc-tab').forEach(tab => {
                tab.classList.remove('bg-emerald-500', 'text-white');
                tab.classList.add('text-slate-500');
            });
            document.getElementById('calculator-' + type).classList.remove('hidden');
            document.getElementById('calc-tab-' + type).classList.add('bg-emerald-500', 'text-white');
            document.getElementById('calc-tab-' + type).classList.remove('text-slate-500');
        }

        function updateConstructionCalc() {
            const workers = parseInt(document.getElementById('workers-slider').value);
            document.getElementById('workers-display').textContent = workers;
            const units = Math.ceil(workers / 20);
            document.getElementById('construction-result').textContent = units;
        }

        function setEventDuration(duration) {
            eventDuration = duration;
            document.querySelectorAll('.duration-btn').forEach(btn => {
                btn.classList.remove('bg-emerald-500', 'text-white', 'border-emerald-500');
                btn.classList.add('border-slate-200', 'text-slate-600');
            });
            const activeBtn = document.querySelector(`[data-duration="${duration}"]`);
            activeBtn.classList.add('bg-emerald-500', 'text-white', 'border-emerald-500');
            activeBtn.classList.remove('border-slate-200', 'text-slate-600');
            updateEventCalc();
        }

        function updateEventCalc() {
            const guests = parseInt(document.getElementById('guests-slider').value);
            document.getElementById('guests-display').textContent = guests;
            
            let ratio = eventDuration === 'short' ? 50 : (eventDuration === 'medium' ? 25 : 20);
            let units = Math.ceil(guests / ratio);
            
            if (document.getElementById('alcohol-check').checked) {
                units = Math.ceil(units * 1.2);
            }
            
            document.getElementById('event-result').textContent = units;
        }

        updateConstructionCalc();
    </script>

    {{-- ============================================ --}}
    {{-- EMERGENCY SERVICE BANNER --}}
    {{-- ============================================ --}}
    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white py-2.5 sm:py-3 px-3 sm:px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 text-center sm:text-left text-xs sm:text-sm">
            <div class="flex items-center gap-1.5 sm:gap-2">
                <svg class="w-5 h-5 animate-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <span class="font-bold">Need Urgent Delivery?</span>
            </div>
            <span class="text-red-100 hidden sm:inline">Same-day emergency service available in most areas.</span>
            <a href="tel:{{ domain_phone_raw() }}"
               class="inline-flex items-center gap-1.5 sm:gap-2 bg-white text-red-600 font-bold px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg hover:bg-red-50 transition text-xs sm:text-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                Call Now
            </a>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- WHO WE SERVE --}}
    {{-- ============================================ --}}
    <section class="py-12 md:py-16 lg:py-20 px-3 sm:px-4 bg-slate-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10 md:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-3 sm:mb-4">
                    Portable Toilet Rental For Every Occasion
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    No matter what you're planning, we have the right
                    <strong>portable sanitation solution</strong> for your event or job site
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @php
                    $useCases = [
                        [
                            'icon' => '<svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m2 22 10-10"/><path d="m16 8-1.17-1.17"/><path d="M3.47 12.53 5 11l1.53 1.53a3.5 3.5 0 0 1 0 4.94L5 19l-1.53-1.53a3.5 3.5 0 0 1 0-4.94Z"/><path d="m8 8 .01.01"/><path d="m10 4 .02.02"/><path d="M14 8h2"/><path d="M12 4v2"/><path d="M16 10v2"/></svg>',
                            'title' => 'Construction Sites',
                            'desc' => 'OSHA-compliant portable toilet rental units with weekly servicing. Keep your crew comfortable and your job site compliant with federal regulations.',
                            'link_text' => 'Construction Rentals',
                            'bg' => 'from-amber-50 to-orange-50 border-amber-200',
                            'color' => 'text-amber-600',
                        ],
                        [
                            'icon' => '<svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>',
                            'title' => 'Weddings & Receptions',
                            'desc' => 'Elegant deluxe and luxury restroom options that complement your outdoor wedding with style and comfort for your guests.',
                            'link_text' => 'Wedding Rentals',
                            'bg' => 'from-rose-50 to-pink-50 border-rose-200',
                            'color' => 'text-rose-600',
                        ],
                        [
                            'icon' => '<svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 5h-4V3a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v2H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z"/><path d="M3 7h18"/><path d="M12 3v2"/><path d="M12 3a1 1 0 0 1 1-1 1 1 0 0 1 1 1 1 1 0 0 1-1 1 1 1 0 0 1-1-1 1 1 0 0 1 1-1"/></svg>',
                            'title' => 'Festivals & Events',
                            'desc' => 'Multiple unit packages for events of any size. From intimate gatherings to 10,000+ attendee festivals.',
                            'link_text' => 'Event Rentals',
                            'bg' => 'from-violet-50 to-purple-50 border-violet-200',
                            'color' => 'text-violet-600',
                        ],
                        [
                            'icon' => '<svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2v8"/><path d="M8 6c0-2.21 2.239-4 5-4s5 1.79 5 4"/><path d="M3 22h18"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg>',
                            'title' => 'Backyard Parties',
                            'desc' => 'Birthday parties, family reunions, graduation celebrations — keep guests comfortable with clean portable restrooms.',
                            'link_text' => 'Party Rentals',
                            'bg' => 'from-blue-50 to-indigo-50 border-blue-200',
                            'color' => 'text-blue-600',
                        ],
                        [
                            'icon' => '<svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
                            'title' => 'Home Renovations',
                            'desc' => 'Bathroom under construction? Keep a portable toilet on-site for workers and family convenience.',
                            'link_text' => 'Home Rentals',
                            'bg' => 'from-teal-50 to-emerald-50 border-teal-200',
                            'color' => 'text-teal-600',
                        ],
                        [
                            'icon' => '<svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="5" r="2"/><path d="M12 22s-4.15-3.36-4.15-8.58c0-2.88 1.12-5.42 4.15-5.42"/><path d="M12 22s4.15-3.36 4.15-8.58c0-2.88-1.12-5.42-4.15-5.42"/></svg>',
                            'title' => 'Sports Events',
                            'desc' => '5K runs, tournaments, tailgating — portable restrooms for athletes and spectators at any sporting event.',
                            'link_text' => 'Sports Rentals',
                            'bg' => 'from-green-50 to-emerald-50 border-green-200',
                            'color' => 'text-green-600',
                        ],
                    ];
                @endphp

                @foreach($useCases as $useCase)
                    <div class="bg-gradient-to-br {{ $useCase['bg'] }} border rounded-xl sm:rounded-2xl p-4 sm:p-6
                            hover:shadow-lg transition-all duration-300 group cursor-pointer">
                        <div class="mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300 {{ $useCase['color'] }}">
                            {!! $useCase['icon'] !!}
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">
                            {{ $useCase['title'] }}
                        </h3>
                        <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                            {{ $useCase['desc'] }}
                        </p>
                        <a href="tel:{{ domain_phone_raw() }}"
                           class="text-blue-600 font-semibold text-sm group-hover:text-blue-700 transition inline-flex items-center gap-1">
                            {{ $useCase['link_text'] }}
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m12 19-7-7 7-7"/>
                                <path d="M19 12H5"/>
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- WHY CHOOSE US (SEO + CRO) --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-14 md:py-16 lg:py-20 px-3 sm:px-4 bg-slate-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10 sm:mb-12 md:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-3 sm:mb-4">
                    Why Choose Potty Direct?
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    Trusted by <strong>50,000+ customers</strong> across the USA for clean, reliable porta potty rentals
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 text-center border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-3 text-emerald-600" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <rect x="1" y="3" width="15" height="13"/>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/>
                        <circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <h3 class="font-bold text-slate-800 mb-2 text-sm sm:text-base">Same-Day Delivery</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Order by 2 PM and get delivery today in most areas</p>
                </div>
                <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 text-center border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-3 text-emerald-600" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    <h3 class="font-bold text-slate-800 mb-2 text-sm sm:text-base">No Hidden Fees</h3>
                    <p class="text-xs sm:text-sm text-slate-500">The price we quote is the price you pay —
                        guaranteed</p>
                </div>
                <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 text-center border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-3 text-emerald-600" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    <h3 class="font-bold text-slate-800 mb-2 text-sm sm:text-base">Clean & Sanitized</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Every unit professionally cleaned before delivery</p>
                </div>
                <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 text-center border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-3 text-emerald-600" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    <h3 class="font-bold text-slate-800 mb-2 text-sm sm:text-base">24/7 Support</h3>
                    <p class="text-xs sm:text-sm text-slate-500">We're always here for emergencies — day or night</p>
                </div>
            </div>

            <div class="mt-8 sm:mt-10 text-center">
                <a href="tel:{{ domain_phone_raw() }}"
                   class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-full transition-all text-sm sm:text-base shadow-lg shadow-emerald-500/25 hover:scale-105">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    {{ domain_phone_display() }} — Call Now
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- HOW IT WORKS --}}
    {{-- ============================================ --}}
    <section class="py-12 md:py-16 lg:py-20 px-3 sm:px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-10 md:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-3 sm:mb-4">
                    How to Rent a Porta Potty in 3 Simple Steps
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500">
                    Renting a portable toilet has never been easier — same-day delivery available
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 md:gap-10 md:gap-12">
                {{-- Step 1 --}}
                <div class="text-center relative">
                    <div class="w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl sm:rounded-2xl
                                flex items-center justify-center text-2xl sm:text-3xl font-bold
                                mx-auto mb-4 sm:mb-6 shadow-xl shadow-blue-500/20">
                        1
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3">Call for a Quote</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                        Tell us how many <strong>porta potties</strong> you need, what type,
                        and your location. We'll give you an instant, transparent quote.
                    </p>
                    <div class="hidden sm:block absolute top-8 -right-4 md:-right-6 lg:-right-10 text-slate-300">
                        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="text-center relative">
                    <div class="w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-xl sm:rounded-2xl
                                flex items-center justify-center text-2xl sm:text-3xl font-bold
                                mx-auto mb-4 sm:mb-6 shadow-xl shadow-emerald-500/20">
                        2
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3">We Deliver</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                        Clean, sanitized portable toilets delivered and set up
                        at your location. <strong>Same-day delivery</strong> available.
                    </p>
                    <div class="hidden sm:block absolute top-8 -right-4 md:-right-6 lg:-right-10 text-slate-300">
                        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="text-center">
                    <div class="w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl sm:rounded-2xl
                                flex items-center justify-center text-2xl sm:text-3xl font-bold
                                mx-auto mb-4 sm:mb-6 shadow-xl shadow-purple-500/20">
                        3
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3">We Maintain & Pick Up</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                        Regular servicing keeps everything fresh. When you're done,
                        we handle pickup — no hassle for you.
                    </p>
                </div>
            </div>

            {{-- CTA --}}
            <div class="text-center mt-10 md:mt-14">
                <a href="tel:{{ domain_phone_raw() }}"
                   class="inline-flex items-center gap-2 sm:gap-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800
                          text-white font-bold text-base sm:text-lg md:text-xl py-3 sm:py-4 px-6 sm:px-8 md:px-10 rounded-full
                          shadow-xl shadow-blue-500/20 transition-all hover:scale-105">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    Call Now — Rent a Porta Potty Today
                </a>
            </div>

            {{-- Video Section (NEW) --}}
            <div class="mt-10 sm:mt-12">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden max-w-3xl mx-auto">
                    <div class="relative aspect-video bg-gradient-to-br from-slate-900 to-slate-800 flex items-center justify-center">
                        {{-- Placeholder - Replace with actual video embed --}}
                        <div class="text-center p-8">
                            <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 text-slate-500" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <polygon points="10 8 16 12 10 16 10 8"/>
                            </svg>
                            <h4 class="text-white font-bold text-lg sm:text-xl mb-2">
                                See Why Thousands Trust Potty Direct
                            </h4>
                            <p class="text-slate-400 text-sm mb-4">
                                60-second overview of our services
                            </p>
                            <button type="button"
                                    class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2.5 px-6 rounded-full transition flex items-center gap-2 mx-auto"
                                    onclick="alert('Video player would open here. Upload your company intro video and embed it.')">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="5 3 19 12 5 21 5 3"/>
                                </svg>
                                Watch Video
                            </button>
                        </div>

                        <iframe
                            src="https://www.youtube.com/embed/qnmJ31rg118?si=Fc2nOSkql9M_-bYZ"
                            title="Potty Direct Company Overview"
                            class="absolute inset-0 w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
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
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- TRUST STATS COUNTER --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-gradient-to-r from-amber-500 to-amber-600">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center">
                <div class="text-white">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-2">25+</div>
                    <div class="text-amber-100 text-xs sm:text-sm font-medium">Years</div>
                </div>
                <div class="text-white">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-2">50K+</div>
                    <div class="text-emerald-100 text-xs sm:text-sm font-medium">Customers</div>
                </div>
                <div class="text-white">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-2">500+</div>
                    <div class="text-emerald-100 text-xs sm:text-sm font-medium">Cities</div>
                </div>
                <div class="text-white">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-2">99%</div>
                    <div class="text-emerald-100 text-xs sm:text-sm font-medium">Satisfaction</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- CERTIFICATIONS BADGES --}}
    {{-- ============================================ --}}
    <section class="py-8 sm:py-10 px-3 sm:px-4 bg-slate-50 border-y border-slate-200">
        <div class="max-w-5xl mx-auto">
            <p class="text-center text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4 sm:mb-6">
                Trusted & Certified
            </p>
            <div class="flex flex-wrap justify-center items-center gap-6 sm:gap-8 md:gap-12">
                <div class="flex items-center gap-2 text-slate-600">
                    <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <span class="text-sm font-semibold">OSHA Compliant</span>
                </div>
                <div class="flex items-center gap-2 text-slate-600">
                    <svg class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span class="text-sm font-semibold">BBB A+ Rated</span>
                </div>
                <div class="flex items-center gap-2 text-slate-600">
                    <svg class="w-6 h-6 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2">
                        <circle cx="12" cy="17" r="3"/>
                        <path d="M9 17h1"/>
                        <path d="M10 13v-2a2 2 0 1 0-4 0"/>
                        <path d="M14 17H8"/>
                        <path d="M15 17a2 2 0 1 0 4 0"/>
                        <path d="M17 17h2"/>
                    </svg>
                    <span class="text-sm font-semibold">ADA Certified</span>
                </div>
                <div class="flex items-center gap-2 text-slate-600">
                    <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    <span class="text-sm font-semibold">EPA Compliant</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- CERTIFICATIONS & GUARANTEES --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-white">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                {{-- Certifications --}}
                <div class="bg-slate-50 rounded-xl sm:rounded-2xl p-5 sm:p-6 md:p-8 border border-slate-200">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-4 sm:mb-6 flex items-center gap-2 sm:gap-3">
                        🏆 Certifications
                    </h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">
                                ✅
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">OSHA Compliant</div>
                                <div class="text-xs sm:text-sm text-slate-500">Safety regulations</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">
                                ♿
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">ADA Certified</div>
                                <div class="text-xs sm:text-sm text-slate-500">Wheelchair accessible</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">
                                🏢
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">BBB A+ Rated</div>
                                <div class="text-xs sm:text-sm text-slate-500">Better Business Bureau</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">
                                🌿
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">EPA Compliant</div>
                                <div class="text-xs sm:text-sm text-slate-500">Eco-friendly disposal</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Service Guarantee --}}
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl sm:rounded-2xl p-5 sm:p-6 md:p-8 text-white">
                    <h3 class="text-lg sm:text-xl font-bold mb-4 sm:mb-6 flex items-center gap-2 sm:gap-3">
                        💯 Our Guarantee
                    </h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="text-emerald-400 text-lg sm:text-xl">✓</span>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Same-Day Delivery</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Call before 2 PM</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="text-emerald-400 text-lg sm:text-xl">✓</span>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">No Hidden Fees</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Price we quote is the price</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="text-emerald-400 text-lg sm:text-xl">✓</span>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Clean Units Guaranteed</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Sanitized before delivery</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="text-emerald-400 text-lg sm:text-xl">✓</span>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">24/7 Emergency</div>
                                <div class="text-slate-400 text-xs sm:text-sm">We're here when you need us</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="text-emerald-400 text-lg sm:text-xl">✓</span>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Licensed & Insured</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Full liability coverage</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-slate-700">
                        <a href="tel:{{ domain_phone_raw() }}"
                           class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg sm:rounded-xl transition-all text-sm sm:text-base">
                            📞 Call for Free Quote
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- WHY CHOOSE US --}}
    {{-- ============================================ --}}
    <section
            class="py-10 sm:py-12 md:py-16 lg:py-20 px-3 sm:px-4 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-8 sm:mb-10 md:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-3 sm:mb-4">
                    Why Choose Our Porta Potty Rental Service?
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-400 max-w-2xl mx-auto">
                    We're not just another rental company — we're your
                    <strong class="text-white">portable sanitation partner</strong>
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @php
                    $features = [
                        ['icon' => '🚚', 'title' => 'Same-Day Porta Potty Delivery', 'desc' => 'Call before 2 PM and get your units delivered today. We understand urgency on job sites.'],
                        ['icon' => '✨', 'title' => 'Spotlessly Clean Units', 'desc' => 'Every unit is professionally cleaned, sanitized, and inspected before delivery.'],
                        ['icon' => '💰', 'title' => 'Transparent Pricing', 'desc' => 'No hidden fees, no surprise charges. The price we quote is the price you pay.'],
                        ['icon' => '🔧', 'title' => 'Weekly Servicing Included', 'desc' => 'Cleaning, pumping, and restocking included with every weekly and monthly rental.'],
                        ['icon' => '📋', 'title' => 'Licensed & Fully Insured', 'desc' => 'Fully licensed and insured for your complete peace of mind on any project.'],
                        ['icon' => '🕐', 'title' => 'Flexible Rental Terms', 'desc' => 'Daily, weekly, monthly, or long-term rentals. No long-term contracts required.'],
                    ];
                @endphp

                @foreach($features as $feature)
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl sm:rounded-2xl p-4 sm:p-6
                            hover:bg-white/10 hover:border-white/20 transition-all duration-300">
                        <div class="text-2xl sm:text-3xl mb-3 sm:mb-4">{{ $feature['icon'] }}</div>
                        <h3 class="text-base sm:text-lg font-bold mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- OUR WARRANTY & PROMISE --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-gradient-to-r from-amber-500 to-amber-600">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4 sm:mb-6">
                Our Promise to You
            </h2>
            <p class="text-amber-100 text-base sm:text-lg mb-8 max-w-2xl mx-auto">
                We stand behind our service with ironclad guarantees. If you're not satisfied, we'll make it right.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-2">🛡️</div>
                    <h3 class="font-bold text-white text-sm sm:text-base mb-1">100% Satisfaction Guarantee</h3>
                    <p class="text-emerald-100 text-xs sm:text-sm">We'll redo any service at no charge if you're not
                        completely satisfied.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-2">💵</div>
                    <h3 class="font-bold text-white text-sm sm:text-base mb-1">Price Match Guarantee</h3>
                    <p class="text-emerald-100 text-xs sm:text-sm">Find a lower price? We'll match it. No hidden fees,
                        ever.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-2">⚡</div>
                    <h3 class="font-bold text-white text-sm sm:text-base mb-1">Same-Day Emergency</h3>
                    <p class="text-emerald-100 text-xs sm:text-sm">Need it urgently? We prioritize emergency
                        deliveries.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- TESTIMONIALS / REVIEWS --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-16 lg:py-20 px-3 sm:px-4 bg-slate-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-8 sm:mb-10 md:mb-14">
                <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 text-xs sm:text-sm font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-3 sm:mb-4">
                    <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="currentColor">
                        <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <span>Trusted by</span> 50,000+ Customers
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-3 sm:mb-4">
                    What Our Customers Say
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    Don't just take our word for it — hear from real customers who rented porta potties from us
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @php
                    $testimonials = [
                        [
                            'name' => 'Mike Thompson',
                            'role' => 'Construction Site Manager',
                            'location' => 'Houston, TX',
                            'text' => 'We use Potty Direct for all our job sites. Same-day delivery, always clean units, and no surprise charges on the invoice. Their team is professional and reliable.',
                            'rating' => 5,
                            'service' => 'Construction Rental'
                        ],
                        [
                            'name' => 'Sarah Martinez',
                            'role' => 'Event Coordinator',
                            'location' => 'Austin, TX',
                            'text' => 'Planned a wedding for 200 guests and needed 6 porta potties. They delivered and picked up on time. The units were spotless! Highly recommend for any event.',
                            'rating' => 5,
                            'service' => 'Wedding Event'
                        ],
                        [
                            'name' => 'David Chen',
                            'role' => 'General Contractor',
                            'location' => 'Phoenix, AZ',
                            'text' => 'Been working with them for 5 years. Always competitive pricing, never had an issue with delivery timing. They treat my job sites like their own. Outstanding service!',
                            'rating' => 5,
                            'service' => 'Commercial Projects'
                        ],
                        [
                            'name' => 'Jennifer Adams',
                            'role' => 'Event Planner',
                            'location' => 'Denver, CO',
                            'text' => 'Our company organizes outdoor festivals. Potty Direct never lets us down — even with last-minute requests for 50+ units. Their team is responsive and units are always clean.',
                            'rating' => 5,
                            'service' => 'Festival Event'
                        ],
                        [
                            'name' => 'Robert Williams',
                            'role' => 'Homeowner',
                            'location' => 'Jacksonville, FL',
                            'text' => 'Needed a porta potty during our home renovation. They delivered the next morning, picked up when scheduled. Simple process and fair pricing. Highly satisfied!',
                            'rating' => 5,
                            'service' => 'Home Renovation'
                        ],
                        [
                            'name' => 'Lisa Park',
                            'role' => 'School Administrator',
                            'location' => 'Seattle, WA',
                            'text' => 'We host outdoor graduation ceremonies every year. Potty Direct provides excellent service with ADA-compliant units. Parents always compliment how clean the restrooms were.',
                            'rating' => 5,
                            'service' => 'School Event'
                        ]
                    ];
                @endphp

                @foreach($testimonials as $testimonial)
                    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg shadow-slate-200/50 border border-slate-100 hover:shadow-xl transition-all">
                        <div class="flex items-center gap-1 mb-3 sm:mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-slate-600 mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base">
                            {!! $testimonial['text'] !!}</p>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-lg">
                                {{ substr($testimonial['name'], 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm sm:text-base">{{ $testimonial['name'] }}</div>
                                <div class="text-xs sm:text-sm text-slate-500">{{ $testimonial['role'] }}
                                    · {{ $testimonial['location'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Google Reviews CTA --}}
            <div class="mt-8 sm:mt-10 md:mt-12 text-center">
                <div class="inline-flex flex-col sm:flex-row items-center gap-3 sm:gap-4 bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg border border-slate-200">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.96 20.53 7.7 23 12 23z"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.96 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <div class="text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-2 mb-1">
                            <span class="text-slate-800 font-bold text-sm sm:text-base">Google Reviews</span>
                            <div class="flex">
                                <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                                <svg class="w-4 h-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xs sm:text-sm text-slate-600">See more reviews on Google</div>
                    </div>
                    <a href="https://search.google.com/search?q=Potty+Direct+reviews" target="_blank"
                       class="mt-3 sm:mt-0 ml-0 sm:ml-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition-all text-sm inline-flex items-center gap-1">
                        Read Reviews
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m12 19-7-7 7-7"/>
                            <path d="M19 12H5"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- PRICING / CALL TO ACTION --}}
    {{-- ============================================ --}}
    <section id="pricing" class="py-10 sm:py-12 md:py-16 lg:py-20 px-3 sm:px-4 bg-slate-50">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8 sm:mb-10 md:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-3 sm:mb-4">
                    Porta Potty Rental Pricing
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500">
                    Competitive rates with <strong>no hidden fees</strong> — call for exact pricing
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-6 sm:p-8 max-w-xl mx-auto">
                <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 text-center">
                    Get Your Free Quote Today
                </h3>
                <p class="text-slate-500 text-sm mb-6 text-center">
                    Call now for immediate assistance
                </p>

                <a href="tel:{{ domain_phone_raw() }}"
                   class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-700
                          text-white font-bold text-lg py-4 px-6 rounded-xl shadow-lg shadow-amber-500/25
                          transition-all hover:scale-[1.02] active:scale-[0.98]
                          flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    {{ domain_phone_display() }}
                </a>

                <p class="text-xs text-slate-400 text-center mt-4">
                    Same-day delivery available • No hidden fees
                </p>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- BLOG / RESOURCES --}}
    {{-- ============================================ --}}
    @if(filled($recentPosts) && count($recentPosts) > 0)
        <section class="py-10 sm:py-12 md:py-16 lg:py-20 px-3 sm:px-4 bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-8 sm:mb-10 md:mb-14">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-3 sm:mb-4">
                        Porta Potty Rental Guides & Tips
                    </h2>
                    <p class="text-sm sm:text-base md:text-lg text-slate-500">
                        Expert advice to help you plan your <strong>portable toilet rental</strong>
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($recentPosts as $post)
                        <a href="{{ $post->url }}"
                           class="bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-xl
                          transition-all duration-300 overflow-hidden group border border-slate-200">
                            @php
                                $postImage = $post->featured_image;
                            @endphp
                            @if($postImage)
                                <img src="{{ asset('storage/' . $postImage) }}"
                                     alt="{{ $post->title }}"
                                     class="h-36 sm:h-48 w-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="h-36 sm:h-48 bg-gradient-to-br from-blue-100 to-emerald-50
                                    flex items-center justify-center text-5xl sm:text-6xl
                                    group-hover:scale-105 transition-transform duration-500">
                                    🚽
                                </div>
                            @endif

                            <div class="p-4 sm:p-6">
                                @if($post->category)
                                    <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">
                            {{ $post->category->name }}
                        </span>
                                @endif

                                <h3 class="font-bold text-slate-800 mt-2 mb-3
                                   group-hover:text-emerald-600 transition line-clamp-2 text-sm sm:text-lg">
                                    {{ $post->title }}
                                </h3>

                                <p class="text-xs sm:text-sm text-slate-500 line-clamp-2 mb-3 sm:mb-4 leading-relaxed">
                                    {!! $post->excerpt !!}
                                </p>

                                <span class="text-xs text-slate-400 flex items-center gap-1.5 sm:gap-2">
                            📖 {{ $post->reading_time_text }}
                        </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="text-center mt-8 sm:mt-10">
                    <a href="{{ route('blog.index') }}"
                       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700
                          font-semibold transition text-sm sm:text-base">
                        View All Articles →
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================ --}}
    {{-- CITIES / LOCATIONS --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-16 lg:py-20 px-3 sm:px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-8 sm:mb-10 md:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-3 sm:mb-4">
                    Porta Potty Rental Across the USA
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500">
                    Find <strong>portable toilet rental</strong> in your city or state
                </p>
            </div>

            {{-- States with Cities --}}
            @forelse($states as $state)
                @if($state['cities_count'] > 0)
                    @php
                        $stateCities = App\Models\City::where('state_id', $state['id'])
                            ->where('is_active', true)
                            ->with(['servicePages' => fn($q) => $q->where('service_type', 'general')->where('is_published', true)])
                            ->orderByDesc('priority')
                            ->orderBy('name')
                            ->limit(10)
                            ->get();
                    @endphp
                    @if($stateCities->count() > 0)
                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-bold text-slate-700 mb-3 sm:mb-4 flex items-center gap-2">
                                <a href="{{ route('state.page', $state['slug']) }}"
                                   class="hover:text-emerald-600 transition">
                                    📍 {{ $state['name'] }}
                                </a>
                                <span class="text-xs sm:text-sm font-normal text-slate-400">
                                ({{ $state['cities_count'] }} {{ Str::plural('city', $state['cities_count']) }})
                            </span>
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($stateCities as $city)
                                    @if($city->servicePages->isNotEmpty())
                                        <a href="{{ url($city->servicePages->first()->slug) }}"
                                           class="px-3 sm:px-4 py-1.5 sm:py-2 bg-white border border-slate-200 rounded-lg text-xs sm:text-sm text-slate-600
                                                  hover:border-emerald-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                            {{ $city->name }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            @empty
                <div class="text-center text-slate-500 py-8">
                    <p>No cities available yet. Please check back soon!</p>
                </div>
            @endforelse

            <div class="text-center mt-8 sm:mt-10">
                <a href="{{ route('locations') }}"
                   class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white
                          font-semibold py-2.5 sm:py-3 px-5 sm:px-6 rounded-lg sm:rounded-xl transition-all text-sm sm:text-base">
                    View All Locations →
                </a>
            </div>

            {{-- Service Area Map (NEW) --}}
            <div class="mt-10 sm:mt-12">
                <h3 class="text-lg sm:text-xl font-bold text-slate-700 mb-4 sm:mb-6 text-center">
                    📍 View Our Service Areas on the Map
                </h3>
                <div class="rounded-2xl overflow-hidden shadow-lg border border-slate-200" style="height: 400px;">
                    <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3168428.454201798!2d-120!3d37!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fb9fe5f285e3d%3A0x80d6b242d0cce1aa!2sUnited%20States!5e0!3m2!1sen!2sus!4v1600000000000!5m2!1sen!2sus"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="USA Service Area Map">
                    </iframe>
                </div>
                <p class="text-center text-slate-500 text-sm mt-3">
                    We provide <strong>porta potty rental services</strong> in all 50 states. Enter your zip code above
                    to find a location near you.
                </p>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- FAQ SECTION --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-16 lg:py-20 px-3 sm:px-4">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-8 sm:mb-10 md:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-3 sm:mb-4">
                    Frequently Asked Questions
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500">
                    Get answers to common questions about our portable toilet rental service
                </p>
            </div>

            @php
                $homeFaqs = [
                    [
                        'q' => 'How much does a porta potty rental cost?',
                        'a' => 'Pricing varies by location, quantity, unit type, and rental duration. Standard units start around $100-175 per day, while deluxe or ADA-compliant units cost more. <a href="' . route('pricing') . '" class="text-emerald-600 hover:underline">View our full pricing guide</a> or call us for a personalized quote.',
                    ],
                    [
                        'q' => 'Do you offer same-day porta potty delivery?',
                        'a' => 'Yes! We offer same-day delivery in most service areas when you call before 2 PM. Subject to availability. For guaranteed delivery, we recommend booking at least 24 hours in advance.',
                    ],
                    [
                        'q' => 'What types of porta potty units do you offer?',
                        'a' => 'We offer <a href="' . route('services') . '#standard" class="text-emerald-600 hover:underline">standard portable toilets</a>, <a href="' . route('services') . '#deluxe" class="text-emerald-600 hover:underline">deluxe flushable units</a> with handwashing stations, <a href="' . route('services') . '#ada" class="text-emerald-600 hover:underline">ADA-compliant accessible units</a>, <a href="' . route('services') . '#luxury" class="text-emerald-600 hover:underline">luxury restroom trailers</a>, and high-rise units for multi-story construction.',
                    ],
                    [
                        'q' => 'Do you offer restroom trailers for events?',
                        'a' => 'Yes! Our <a href="' . route('services') . '#luxury" class="text-emerald-600 hover:underline">luxury restroom trailers</a> feature climate control, porcelain fixtures, mirrors, and elegant interiors — perfect for weddings, corporate events, and upscale gatherings.',
                    ],
                    [
                        'q' => 'How many porta potties do I need for my event?',
                        'a' => 'A general rule is 1 standard unit per 50 guests for a 4-hour event, or 1 unit per 25 guests for an 8-hour event. If alcohol is served, add 20% more units. For construction sites, OSHA requires 1 unit per 20 workers. Call us and we\'ll help you determine the right number.',
                    ],
                    [
                        'q' => 'What is included in the rental?',
                        'a' => 'Our rental includes delivery, setup, pickup, and for weekly/monthly rentals, regular servicing (cleaning, sanitizing, and restocking of toilet paper). <a href="' . route('pricing') . '" class="text-emerald-600 hover:underline">No hidden fees</a> — the price we quote is the price you pay.',
                    ],
                    [
                        'q' => 'Do units include hand sanitizer?',
                        'a' => 'Yes, all our standard units include hand sanitizer dispensers. Deluxe units come with handwashing stations with soap and paper towels. We can also provide standalone <a href="' . route('services') . '#handwash" class="text-emerald-600 hover:underline">hand wash stations</a> for any event or job site.',
                    ],
                    [
                        'q' => 'How far in advance should I book?',
                        'a' => 'For construction sites, book 1-2 weeks ahead. For events, we recommend booking 2-4 weeks in advance, especially during spring and fall peak season. <a href="' . route('locations') . '" class="text-emerald-600 hover:underline">Check availability</a> in your area.',
                    ],
                    [
                        'q' => 'How often are porta potties serviced?',
                        'a' => 'For weekly and monthly rentals, our standard service includes once-per-week cleaning, pumping, sanitizing, and restocking of supplies. For high-traffic locations or events, we offer twice-weekly or daily servicing.',
                    ],
                    [
                        'q' => 'Do you provide ADA-accessible portable restrooms?',
                        'a' => 'Yes, we offer <a href="' . route('services') . '#ada" class="text-emerald-600 hover:underline">fully ADA-compliant portable restrooms</a> with extra-wide doors for wheelchair access, interior grab bars, lowered seats, and spacious interiors.',
                    ],
                    [
                        'q' => 'What areas do you service?',
                        'a' => 'We service cities and counties across the state. <a href="' . route('locations') . '" class="text-emerald-600 hover:underline">Enter your zip code or city</a> to see if we service your area, or call us for quick confirmation.',
                    ],
                    [
                        'q' => 'Do portable toilets need water or electricity?',
                        'a' => 'No, our standard portable toilets are completely self-contained and require no water, electricity, or plumbing. They use a chemical solution in the holding tank that controls odors and breaks down waste. Deluxe flushable units need water for handwashing only.',
                    ],
                    [
                        'q' => 'How do you dispose of waste responsibly?',
                        'a' => 'All waste is collected by licensed professionals and transported to approved treatment facilities. We follow strict EPA and local regulations for disposal. Our company uses eco-friendly cleaning products and biodegradable chemicals whenever possible.',
                    ],
                    [
                        'q' => 'Do you offer single-day rentals?',
                        'a' => 'Yes, we offer single-day rentals for events and short-term needs. <a href="' . route('pricing') . '" class="text-emerald-600 hover:underline">Pricing is based on the number of units</a> and delivery distance. Extended rentals (weekly/monthly) offer better rates with servicing included.',
                    ],
                    [
                        'q' => 'What if a unit needs servicing during my rental?',
                        'a' => 'Simply call us and we\'ll send a technician to service or replace the unit. For weekly/monthly rentals, our regular servicing schedule ensures units stay clean and functional. Emergency service is available for critical situations.',
                    ],
                    [
                        'q' => 'Is there a deposit or hidden fees?',
                        'a' => 'We believe in transparent pricing. <a href="' . route('pricing') . '" class="text-emerald-600 hover:underline">Quotes include delivery, setup, servicing, and pickup</a> — no hidden fees. Deposits vary by rental size and duration. We\'ll provide a full breakdown before you commit.',
                    ],
                ];
                $visibleFaqs = array_slice($homeFaqs, 0, 4);
                $hiddenFaqs = array_slice($homeFaqs, 4);
            @endphp

            <div class="space-y-3" id="faq-container">
                @foreach($visibleFaqs as $faq)
                    <details
                            class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
                        <summary class="flex justify-between items-center p-4 sm:p-5 cursor-pointer
                                    font-semibold text-slate-800 hover:text-emerald-600 transition
                                    list-none text-sm sm:text-base">
                            <span>{{ $faq['q'] }}</span>
                            <span class="text-xl sm:text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500
                                     transition-all duration-300 ml-2 sm:ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-emerald-100 w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center">+</span>
                        </summary>
                        <div class="px-4 sm:px-5 pb-4 sm:pb-5 text-slate-600 leading-relaxed text-sm sm:text-base">
                            <p>{!! $faq['a'] !!}</p>
                        </div>
                    </details>
                @endforeach

                <div id="hidden-faqs" class="hidden space-y-3">
                    @foreach($hiddenFaqs as $faq)
                        <details
                                class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
                            <summary class="flex justify-between items-center p-4 sm:p-5 cursor-pointer
                                        font-semibold text-slate-800 hover:text-emerald-600 transition
                                        list-none text-sm sm:text-base">
                                <span>{{ $faq['q'] }}</span>
                                <span class="text-xl sm:text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500
                                         transition-all duration-300 ml-2 sm:ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-emerald-100 w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center">+</span>
                            </summary>
                            <div class="px-4 sm:px-5 pb-4 sm:pb-5 text-slate-600 leading-relaxed text-sm sm:text-base">
                                <p>{!! $faq['a'] !!}</p>
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>

            <div class="text-center mt-6 sm:mt-8">
                <button onclick="document.getElementById('hidden-faqs').classList.remove('hidden'); this.remove();"
                        class="text-emerald-600 hover:text-emerald-700 font-semibold text-sm sm:text-base inline-flex items-center gap-2">
                    <span>View all {{ count($homeFaqs) }} questions</span>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- FINAL CTA --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 md:py-20 lg:py-28 px-3 sm:px-4 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900
                    text-white text-center relative overflow-hidden">
        {{-- Background decoration - hidden on mobile --}}
        <div class="absolute inset-0 opacity-5 desktop-only">
            <div class="absolute top-10 left-10 text-[150px] sm:text-[200px]">🚽</div>
            <div class="absolute bottom-10 right-10 text-[100px] sm:text-[150px]">🚿</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] sm:w-[400px] md:w-[500px] lg:w-[600px] h-[300px] sm:h-[400px] md:h-[500px] lg:h-[600px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-3xl mx-auto">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4 sm:mb-6">
                Ready to Rent a Porta Potty?
            </h2>
            <p class="text-base sm:text-lg md:text-xl text-slate-400 mb-4 sm:mb-6">
                Call now for a <strong class="text-white">free, no-obligation quote</strong>.
                Same-day delivery available.
            </p>
            <p class="text-sm sm:text-base md:text-lg text-slate-300 mb-6 sm:mb-8 md:mb-10">
                Serving construction sites, events, weddings, and more across the USA
            </p>

            <a href="tel:{{ domain_phone_raw() }}"
               class="inline-block bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500
                      text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold
                      py-3 sm:py-4 md:py-5 px-8 sm:px-10 md:px-14 rounded-full shadow-2xl
                      transition-all hover:scale-105
                      shadow-amber-500/40 animate-pulse">
                📞 {{ domain_phone_display() }}
            </a>

            <div class="mt-6 sm:mt-8 flex flex-wrap justify-center gap-x-3 sm:gap-x-6 gap-y-2 text-xs sm:text-sm text-slate-400">
                <span>📞 24/7 Emergency Service</span>
                <span class="text-slate-600 hidden xsm:block">•</span>
                <span>🚚 Same-Day</span>
                <span class="text-slate-600 hidden xsm:block">•</span>
                <span>💰 Free Quote</span>
            </div>

            <p class="mt-8 sm:mt-10 text-slate-500 text-sm sm:text-base">
                Or <a href="{{ route('locations') }}"
                      class="text-emerald-400 hover:text-emerald-300 underline transition">
                    find your city
                </a> to see local pricing.
            </p>
        </div>
    </section>
@endsection
