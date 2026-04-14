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
    "description" => "Customer reviews and testimonials for Potty Direct porta potty rental service",
    "itemListElement" => [
        ["@type" => "Review", "itemReviewed" => ["@type" => "LocalBusiness", "name" => "Potty Direct"], "reviewRating" => ["@type" => "Rating", "ratingValue" => "5"], "author" => ["@type" => "Person", "name" => "Mike Thompson"], "reviewBody" => "We use Potty Direct for all our job sites. Same-day delivery, always clean units, and no surprise charges on the invoice. Their team is professional and reliable."],
        ["@type" => "Review", "itemReviewed" => ["@type" => "LocalBusiness", "name" => "Potty Direct"], "reviewRating" => ["@type" => "Rating", "ratingValue" => "5"], "author" => ["@type" => "Person", "name" => "Sarah Martinez"], "reviewBody" => "Planned a wedding for 200 guests and needed 6 porta potties. They delivered and picked up on time. The units were spotless! Highly recommend for any event."],
        ["@type" => "Review", "itemReviewed" => ["@type" => "LocalBusiness", "name" => "Potty Direct"], "reviewRating" => ["@type" => "Rating", "ratingValue" => "5"], "author" => ["@type" => "Person", "name" => "David Chen"], "reviewBody" => "Been working with them for 5 years. Always competitive pricing, never had an issue with delivery timing. They treat my job sites like their own. Outstanding service!"]
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
                "provider" => ["@type" => "LocalBusiness", "name" => "Potty Direct"],
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
                "provider" => ["@type" => "LocalBusiness", "name" => "Potty Direct"],
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
                "provider" => ["@type" => "LocalBusiness", "name" => "Potty Direct"],
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
                "provider" => ["@type" => "LocalBusiness", "name" => "Potty Direct"],
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
                 loading="eager">
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
                    Need a <strong class="text-white">porta potty rental</strong> for your construction site, outdoor event, or wedding?
                    We deliver clean, sanitized <strong class="text-white">portable toilet rentals</strong> to all 50 states!
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full sm:w-auto bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                              text-lg sm:text-xl md:text-2xl font-bold
                              py-3 sm:py-4 px-6 sm:px-10 rounded-full shadow-2xl shadow-emerald-500/30
                              transition-all hover:scale-105 hover:shadow-emerald-500/50
                              flex items-center justify-center gap-2 sm:gap-3 hero-cta-btn">
                        <span class="text-xl sm:text-2xl">📞</span>
                        {{ domain_phone_display() }}
                    </a>
                    <a href="{{ route('locations') }}"
                       class="w-full sm:w-auto bg-white/10 hover:bg-white/20 backdrop-blur-sm
                              border border-white/20 text-white text-base sm:text-lg font-semibold
                              py-3 sm:py-4 px-6 sm:px-8 rounded-full
                              transition-all hover:scale-105
                              flex items-center justify-center gap-2">
                        📍 Find Your City
                    </a>
                </div>

                {{-- Location Search Bar - Clean Professional Design --}}
                <div class="w-full max-w-xl">
                    <form action="{{ route('locations') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <select name="quick_city" 
                                    class="w-full bg-white/95 backdrop-blur-sm border border-white/20 text-white text-base py-3.5 pl-11 pr-10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 cursor-pointer appearance-none"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%239%20(22%2C%20205%2C%2025%2C%2020%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22M6%206l4%204%204-4%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem;"
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
                                        <option value="{{ url($citySlug) }}" class="text-slate-800">{{ $city['name'] }}, {{ $city['state']['code'] ?? '' }}</option>
                                    @endif
                                @endforeach
                                <option value="{{ route('locations') }}" class="text-slate-800">View All Locations</option>
                            </select>
                        </div>
                        <div class="flex-1 relative sm:flex-none sm:w-40">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11.5A2.5 2.5 0 109.5 9a2.5 2.5 0 002.5 2.5z"/>
                                </svg>
                            </div>
                            <input type="text" name="q" placeholder="Enter zip code"
                                   class="w-full bg-white/95 backdrop-blur-sm border border-white/20 text-white text-base py-3.5 pl-11 pr-4 rounded-full placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold py-3.5 px-8 rounded-full transition-all shadow-lg hover:shadow-emerald-500/40 flex items-center justify-center gap-2 whitespace-nowrap">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Search</span>
                        </button>
                    </form>
                </div>

                {{-- Trust Indicators - Wraps better on mobile --}}
                <div class="flex flex-wrap items-center gap-x-3 sm:gap-x-6 gap-y-2 mt-4 text-xs sm:text-sm trust-indicators">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <div class="flex">
                            <span class="text-yellow-400">★</span><span class="text-yellow-400">★</span><span class="text-yellow-400">★</span><span class="text-yellow-400">★</span><span class="text-yellow-400">★</span>
                        </div>
                        <span class="text-slate-300 font-medium">4.9/5</span>
                    </div>
                    <div class="hidden xsm:block h-4 w-px bg-white/20"></div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="inline-flex items-center justify-center w-4 sm:w-5 h-4 sm:h-5 bg-emerald-500 rounded-full text-white text-xs font-bold">✓</span>
                        <span class="text-slate-300 font-medium">Licensed & Insured</span>
                    </div>
                    <div class="hidden xsm:block h-4 w-px bg-white/20"></div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="inline-flex items-center justify-center w-4 sm:w-5 h-4 sm:h-5 bg-emerald-500 rounded-full text-white text-xs font-bold">✓</span>
                        <span class="text-slate-300 font-medium">Same-Day</span>
                    </div>
                    <div class="hidden xsm:block h-4 w-px bg-white/20"></div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="inline-flex items-center justify-center w-4 sm:w-5 h-4 sm:h-5 bg-emerald-500 rounded-full text-white text-xs font-bold">✓</span>
                        <span class="text-slate-300 font-medium">No Hidden Fees</span>
                    </div>
                </div>

                {{-- Trust Badges Row - Simplified on mobile --}}
                <div class="flex flex-wrap items-center gap-x-3 sm:gap-x-6 gap-y-2 mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-white/10 text-xs text-slate-400">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">🏢</span>
                        <span class="hidden sm:inline">BBB A+</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">📋</span>
                        <span class="hidden sm:inline">OSHA</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">🏗️</span>
                        <span class="hidden sm:inline">25+ Yrs</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">🇺🇸</span>
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

    {{-- ============================================ --}}
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 sm:gap-6">
                {{-- Standard --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-blue-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">🚻</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Standard Porta Potty</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Construction & Events
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Quote</span>
                    </a>
                </div>

                {{-- Deluxe --}}
                <div class="bg-white border-2 border-emerald-400 rounded-2xl p-4 sm:p-6 text-center
                            hover:shadow-xl hover:shadow-emerald-100/50 transition-all duration-300
                            group relative overflow-visible">
                    <div class="absolute -top-3 sm:-top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-400 to-amber-500
                                text-white text-xs sm:text-sm font-extrabold px-3 sm:px-5 py-1.5 sm:py-2 rounded-full shadow-xl shadow-amber-500/40 z-20">
                        ⭐ POPULAR
                    </div>
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-emerald-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 mt-1 sm:mt-2 group-hover:scale-110 transition-transform duration-300">🚿</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Deluxe Flushable</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Weddings & Events
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                              text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-emerald-500/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Quote</span>
                    </a>
                </div>

                {{-- ADA --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-blue-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">♿</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">ADA Accessible</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Wheelchair Friendly
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Quote</span>
                    </a>
                </div>

                {{-- Luxury --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-purple-200 hover:shadow-xl hover:shadow-purple-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-purple-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">✨</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Luxury Trailer</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        VIP Events
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-purple-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Quote</span>
                    </a>
                </div>

                {{-- Dumpster --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-amber-200 hover:shadow-xl hover:shadow-amber-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-amber-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">🗑️</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Dumpster Rental</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Construction & Events
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-amber-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Quote</span>
                    </a>
                </div>

                {{-- Septic --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-teal-200 hover:shadow-xl hover:shadow-teal-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-teal-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">🔧</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Septic Service</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Pumping & Maintenance
                    </p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-teal-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Quote</span>
                    </a>
                </div>
            </div>

            <div class="text-center mt-6 sm:mt-8">
                <a href="{{ route('services') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold inline-flex items-center gap-2">
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
                    <span class="text-2xl sm:text-3xl">🚽</span>
                    <span class="font-semibold text-slate-700 group-hover:text-emerald-600 transition text-sm sm:text-base">Our Services</span>
                </a>
                <a href="{{ route('pricing') }}" class="flex flex-col items-center gap-2 group">
                    <span class="text-2xl sm:text-3xl">💰</span>
                    <span class="font-semibold text-slate-700 group-hover:text-emerald-600 transition text-sm sm:text-base">View Pricing</span>
                </a>
                <a href="{{ route('locations') }}" class="flex flex-col items-center gap-2 group">
                    <span class="text-2xl sm:text-3xl">📍</span>
                    <span class="font-semibold text-slate-700 group-hover:text-emerald-600 transition text-sm sm:text-base">All Locations</span>
                </a>
                <a href="{{ route('blog.index') }}" class="flex flex-col items-center gap-2 group">
                    <span class="text-2xl sm:text-3xl">📝</span>
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
                        🏙️ Porta Potty Rental Near You
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
    {{-- HOW MANY UNITS DO I NEED? (SEO + CRO) --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-14 px-3 sm:px-4 bg-white border-y border-slate-200">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-8 sm:mb-10">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-800 mb-2">
                    📊 How Many Porta Potties Do I Need?
                </h2>
                <p class="text-sm sm:text-base text-slate-500">
                    Use our quick guide to calculate the right number of units for your project or event
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                <div class="bg-slate-50 rounded-xl sm:rounded-2xl p-5 sm:p-6 border border-slate-200">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        🏗️ Construction Sites
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-slate-600 text-sm">1-10 workers</span>
                            <span class="font-bold text-emerald-600">1 unit</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-slate-600 text-sm">11-20 workers</span>
                            <span class="font-bold text-emerald-600">2 units</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-slate-600 text-sm">21-40 workers</span>
                            <span class="font-bold text-emerald-600">3 units</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-slate-600 text-sm">41-100 workers</span>
                            <span class="font-bold text-emerald-600">5 units</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-slate-600 text-sm">100+ workers</span>
                            <span class="font-bold text-emerald-600">1 per 20</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 mt-4">*OSHA requires 1 unit per 20 workers</p>
                </div>

                <div class="bg-slate-50 rounded-xl sm:rounded-2xl p-5 sm:p-6 border border-slate-200">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        🎉 Events & Parties
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-slate-600 text-sm">Up to 50 guests</span>
                            <span class="font-bold text-emerald-600">2 units</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-slate-600 text-sm">50-100 guests</span>
                            <span class="font-bold text-emerald-600">3 units</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-slate-600 text-sm">100-250 guests</span>
                            <span class="font-bold text-emerald-600">4-5 units</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-200">
                            <span class="text-slate-600 text-sm">250-500 guests</span>
                            <span class="font-bold text-emerald-600">6-8 units</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-slate-600 text-sm">500+ guests</span>
                            <span class="font-bold text-emerald-600">1 per 50</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 mt-4">*Add 20% more if alcohol is served</p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-slate-500 text-sm mb-4">Not sure? Our team can help you calculate!</p>
                <a href="tel:{{ domain_phone_raw() }}"
                   class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2.5 sm:py-3 px-5 sm:px-6 rounded-lg sm:rounded-xl transition-all text-sm sm:text-base">
                    📞 Call for Free Consultation
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- EMERGENCY SERVICE BANNER --}}
    {{-- ============================================ --}}
    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white py-2.5 sm:py-3 px-3 sm:px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 text-center sm:text-left text-xs sm:text-sm">
            <div class="flex items-center gap-1.5 sm:gap-2">
                <span class="animate-pulse text-lg">🚨</span>
                <span class="font-bold">Need Urgent Delivery?</span>
            </div>
            <span class="text-red-100 hidden sm:inline">Same-day emergency service available in most areas.</span>
            <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-1.5 sm:gap-2 bg-white text-red-600 font-bold px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg hover:bg-red-50 transition text-xs sm:text-sm">
                📞 Call Now
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
                            'icon' => '🏗️',
                            'title' => 'Construction Sites',
                            'desc' => 'OSHA-compliant portable toilet rental units with weekly servicing. Keep your crew comfortable and your job site compliant with federal regulations.',
                            'link_text' => 'Construction Rentals →',
                            'bg' => 'from-amber-50 to-orange-50 border-amber-200',
                        ],
                        [
                            'icon' => '💒',
                            'title' => 'Weddings & Receptions',
                            'desc' => 'Elegant deluxe and luxury restroom options that complement your outdoor wedding with style and comfort for your guests.',
                            'link_text' => 'Wedding Rentals →',
                            'bg' => 'from-rose-50 to-pink-50 border-rose-200',
                        ],
                        [
                            'icon' => '🎪',
                            'title' => 'Festivals & Events',
                            'desc' => 'Multiple unit packages for events of any size. From intimate gatherings to 10,000+ attendee festivals.',
                            'link_text' => 'Event Rentals →',
                            'bg' => 'from-violet-50 to-purple-50 border-violet-200',
                        ],
                        [
                            'icon' => '🎉',
                            'title' => 'Backyard Parties',
                            'desc' => 'Birthday parties, family reunions, graduation celebrations — keep guests comfortable with clean portable restrooms.',
                            'link_text' => 'Party Rentals →',
                            'bg' => 'from-blue-50 to-indigo-50 border-blue-200',
                        ],
                        [
                            'icon' => '🏠',
                            'title' => 'Home Renovations',
                            'desc' => 'Bathroom under construction? Keep a portable toilet on-site for workers and family convenience.',
                            'link_text' => 'Home Rentals →',
                            'bg' => 'from-teal-50 to-emerald-50 border-teal-200',
                        ],
                        [
                            'icon' => '🏃',
                            'title' => 'Sports Events',
                            'desc' => '5K runs, tournaments, tailgating — portable restrooms for athletes and spectators at any sporting event.',
                            'link_text' => 'Sports Rentals →',
                            'bg' => 'from-green-50 to-emerald-50 border-green-200',
                        ],
                    ];
                @endphp

                @foreach($useCases as $useCase)
                    <div class="bg-gradient-to-br {{ $useCase['bg'] }} border rounded-xl sm:rounded-2xl p-4 sm:p-6
                            hover:shadow-lg transition-all duration-300 group cursor-pointer">
                        <div class="text-3xl sm:text-4xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">
                            {{ $useCase['icon'] }}
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">
                            {{ $useCase['title'] }}
                        </h3>
                        <p class="text-slate-600 text-sm mb-4 leading-relaxed">
                            {{ $useCase['desc'] }}
                        </p>
                        <a href="tel:{{ domain_phone_raw() }}"
                           class="text-blue-600 font-semibold text-sm group-hover:text-blue-700 transition">
                             {{ $useCase['link_text'] }}
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
                    <div class="text-3xl sm:text-4xl mb-3">🚚</div>
                    <h3 class="font-bold text-slate-800 mb-2 text-sm sm:text-base">Same-Day Delivery</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Order by 2 PM and get delivery today in most areas</p>
                </div>
                <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 text-center border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all">
                    <div class="text-3xl sm:text-4xl mb-3">💰</div>
                    <h3 class="font-bold text-slate-800 mb-2 text-sm sm:text-base">No Hidden Fees</h3>
                    <p class="text-xs sm:text-sm text-slate-500">The price we quote is the price you pay — guaranteed</p>
                </div>
                <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 text-center border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all">
                    <div class="text-3xl sm:text-4xl mb-3">🧼</div>
                    <h3 class="font-bold text-slate-800 mb-2 text-sm sm:text-base">Clean & Sanitized</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Every unit professionally cleaned before delivery</p>
                </div>
                <div class="bg-white rounded-xl sm:rounded-2xl p-5 sm:p-6 text-center border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all">
                    <div class="text-3xl sm:text-4xl mb-3">📞</div>
                    <h3 class="font-bold text-slate-800 mb-2 text-sm sm:text-base">24/7 Support</h3>
                    <p class="text-xs sm:text-sm text-slate-500">We're always here for emergencies — day or night</p>
                </div>
            </div>

            <div class="mt-8 sm:mt-10 text-center">
                <a href="tel:{{ domain_phone_raw() }}"
                   class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-full transition-all text-sm sm:text-base shadow-lg shadow-emerald-500/25 hover:scale-105">
                    📞 {{ domain_phone_display() }} — Call Now
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
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
                    📞 Call Now — Rent a Porta Potty Today
                </a>
            </div>

            {{-- Video Section (NEW) --}}
            <div class="mt-10 sm:mt-12">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden max-w-3xl mx-auto">
                    <div class="relative aspect-video bg-gradient-to-br from-slate-900 to-slate-800 flex items-center justify-center">
                        {{-- Placeholder - Replace with actual video embed --}}
                        <div class="text-center p-8">
                            <div class="text-6xl sm:text-7xl mb-4">🎬</div>
                            <h4 class="text-white font-bold text-lg sm:text-xl mb-2">
                                See Why Thousands Trust Potty Direct
                            </h4>
                            <p class="text-slate-400 text-sm mb-4">
                                60-second overview of our services
                            </p>
                            <button type="button"
                                    class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2.5 px-6 rounded-full transition flex items-center gap-2 mx-auto"
                                    onclick="alert('Video player would open here. Upload your company intro video and embed it.')">
                                <span class="text-lg">▶</span> Watch Video
                            </button>
                        </div>
                        {{-- When ready, replace above with:
                        <iframe
                            src="https://www.youtube.com/embed/YOUR_VIDEO_ID"
                            title="Potty Direct Company Overview"
                            class="absolute inset-0 w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                        --}}
                    </div>
                    <div class="p-4 bg-slate-50 border-t border-slate-200">
                        <p class="text-center text-sm text-slate-500">
                            Learn about our <strong>same-day delivery</strong>, <strong>clean units</strong>, and <strong>transparent pricing</strong> in under a minute.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- TRUST STATS COUNTER --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-gradient-to-r from-emerald-500 to-emerald-600">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center">
                <div class="text-white">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-2">25+</div>
                    <div class="text-emerald-100 text-xs sm:text-sm font-medium">Years</div>
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
    {{-- AS SEEN IN / MEDIA MENTIONS --}}
    {{-- ============================================ --}}
    <section class="py-8 sm:py-10 px-3 sm:px-4 bg-slate-50 border-y border-slate-200">
        <div class="max-w-5xl mx-auto">
            <p class="text-center text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4 sm:mb-6">
                Featured in Leading Industry Publications
            </p>
            <div class="flex flex-wrap justify-center items-center gap-6 sm:gap-8 md:gap-12 opacity-60">
                <div class="text-center">
                    <div class="text-2xl sm:text-3xl font-bold text-slate-700">Forbes</div>
                    <div class="text-xs text-slate-500">Partner</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl sm:text-3xl font-bold text-slate-700">Construction</div>
                    <div class="text-xs text-slate-500">Weekly</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl sm:text-3xl font-bold text-slate-700">Events</div>
                    <div class="text-xs text-slate-500">Today</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl sm:text-3xl font-bold text-slate-700">BizNews</div>
                    <div class="text-xs text-slate-500">Network</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl sm:text-3xl font-bold text-slate-700">Wedding</div>
                    <div class="text-xs text-slate-500">Planner</div>
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
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">✅</div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">OSHA Compliant</div>
                                <div class="text-xs sm:text-sm text-slate-500">Safety regulations</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">♿</div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">ADA Certified</div>
                                <div class="text-xs sm:text-sm text-slate-500">Wheelchair accessible</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">🏢</div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">BBB A+ Rated</div>
                                <div class="text-xs sm:text-sm text-slate-500">Better Business Bureau</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">🌿</div>
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
    <section class="py-10 sm:py-12 md:py-16 lg:py-20 px-3 sm:px-4 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">
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
    <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-gradient-to-r from-emerald-500 to-emerald-600">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4 sm:mb-6">
                Our Promise to You
            </h2>
            <p class="text-emerald-100 text-base sm:text-lg mb-8 max-w-2xl mx-auto">
                We stand behind our service with ironclad guarantees. If you're not satisfied, we'll make it right.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-2">🛡️</div>
                    <h3 class="font-bold text-white text-sm sm:text-base mb-1">100% Satisfaction Guarantee</h3>
                    <p class="text-emerald-100 text-xs sm:text-sm">We'll redo any service at no charge if you're not completely satisfied.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-2">💵</div>
                    <h3 class="font-bold text-white text-sm sm:text-base mb-1">Price Match Guarantee</h3>
                    <p class="text-emerald-100 text-xs sm:text-sm">Find a lower price? We'll match it. No hidden fees, ever.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-2">⚡</div>
                    <h3 class="font-bold text-white text-sm sm:text-base mb-1">Same-Day Emergency</h3>
                    <p class="text-emerald-100 text-xs sm:text-sm">Need it urgently? We prioritize emergency deliveries.</p>
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
                    <span>⭐</span> <span class="hidden sm:inline">Trusted by</span> 50,000+ Customers
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
                                <span class="text-amber-400 text-lg">★</span>
                            @endfor
                        </div>
                        <p class="text-slate-600 mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base">"{{ $testimonial['text'] }}"</p>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-lg">
                                {{ substr($testimonial['name'], 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm sm:text-base">{{ $testimonial['name'] }}</div>
                                <div class="text-xs sm:text-sm text-slate-500">{{ $testimonial['role'] }} · {{ $testimonial['location'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Google Reviews CTA --}}
            <div class="mt-8 sm:mt-10 md:mt-12 text-center">
                <div class="inline-flex flex-col sm:flex-row items-center gap-3 sm:gap-4 bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg border border-slate-200">
                    <div class="text-3xl sm:text-4xl">🌐</div>
                    <div class="text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-2 mb-1">
                            <span class="text-red-500 font-bold text-sm sm:text-base">Google</span>
                            <span class="text-amber-400 text-sm sm:text-base">★★★★★</span>
                        </div>
                        <div class="text-xs sm:text-sm text-slate-600">See more reviews on Google</div>
                    </div>
                    <a href="https://search.google.com/search?q=Potty+Direct+reviews" target="_blank"
                       class="mt-3 sm:mt-0 ml-0 sm:ml-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition-all text-sm">
                        Read Reviews →
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- VIDEO TESTIMONIALS (SEO + Trust) --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-14 px-3 sm:px-4 bg-white border-y border-slate-200">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-8 sm:mb-10">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-slate-800 mb-2">
                    🎥 See What Our Customers Say
                </h2>
                <p class="text-sm sm:text-base text-slate-500">
                    Real testimonials from construction pros, event planners, and homeowners
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                <div class="relative aspect-video bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl sm:rounded-2xl overflow-hidden group cursor-pointer">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-4xl">▶</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-slate-900/90 to-transparent">
                        <p class="text-white font-semibold text-sm">Mike T. — Construction Manager</p>
                        <p class="text-slate-300 text-xs">Same-day delivery, clean units</p>
                    </div>
                </div>

                <div class="relative aspect-video bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl sm:rounded-2xl overflow-hidden group cursor-pointer">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-4xl">▶</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-slate-900/90 to-transparent">
                        <p class="text-white font-semibold text-sm">Sarah M. — Event Coordinator</p>
                        <p class="text-slate-300 text-xs">Wedding day flawless</p>
                    </div>
                </div>

                <div class="relative aspect-video bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl sm:rounded-2xl overflow-hidden group cursor-pointer">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-4xl">▶</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-slate-900/90 to-transparent">
                        <p class="text-white font-semibold text-sm">David C. — Contractor</p>
                        <p class="text-slate-300 text-xs">5 years of reliable service</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="text-xs sm:text-sm text-slate-500 mb-3">🎬 More video testimonials coming soon</p>
                <a href="https://youtube.com/@pottydirect" target="_blank"
                   class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                    Subscribe on YouTube →
                </a>
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
                   class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                          text-white font-bold text-lg py-4 px-6 rounded-xl shadow-lg shadow-emerald-500/25
                          transition-all hover:scale-[1.02] active:scale-[0.98]
                          flex items-center justify-center gap-3">
                    <span class="text-xl">📞</span>
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
                            {{-- Image Placeholder --}}
                            <div class="h-36 sm:h-48 bg-gradient-to-br from-blue-100 to-emerald-50
                                flex items-center justify-center text-5xl sm:text-6xl
                                group-hover:scale-105 transition-transform duration-500">
                                🚽
                            </div>

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
                                    {{ $post->excerpt }}
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
                                <a href="{{ route('state.page', $state['slug']) }}" class="hover:text-emerald-600 transition">
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
                    We provide <strong>porta potty rental services</strong> in all 50 states. Enter your zip code above to find a location near you.
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
            @endphp

            <div class="space-y-3">
                @foreach($homeFaqs as $faq)
                    <details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
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

            <div class="text-center mt-6 sm:mt-8">
                <a href="{{ route('blog.index') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold text-sm sm:text-base">
                    View more porta potty rental guides →
                </a>
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
               class="inline-block bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                      text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold
                      py-3 sm:py-4 md:py-5 px-8 sm:px-10 md:px-14 rounded-full shadow-2xl
                      transition-all hover:scale-105
                      shadow-emerald-500/40 animate-pulse">
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
