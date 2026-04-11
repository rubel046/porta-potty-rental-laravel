@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@section('title', 'Portable Restroom Rental | Same-Day Delivery Across the USA | Potty Direct')
@section('meta_description', 'Looking for porta potty rental near you? Potty Direct offers same-day delivery of clean portable toilets for construction sites, outdoor events, and weddings. Get your free quote today! Call '.domain_phone_display().'!')
@section('canonical', url('/'))

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();
$domain = \App\Models\Domain::current();

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
    "areaServed" => ["@type" => "Country", "name" => "United States"],
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
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Luxury Restroom Trailer"]]
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
        ["@type" => "Question", "name" => "How many porta potties do I need for my event?", "acceptedAnswer" => ["@type" => "Answer", "text" => "A general rule is 1 standard unit per 50 guests for a 4-hour event, or 1 unit per 25 guests for an 8-hour event. If alcohol is served, add 20% more units. For construction sites, OSHA requires 1 unit per 20 workers. Call us and we'll help you determine the right number."]],
        ["@type" => "Question", "name" => "What is included in the rental?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Our rental includes delivery, setup, pickup, and for weekly/monthly rentals, regular servicing (cleaning, sanitizing, and restocking of toilet paper). No hidden fees — the price we quote is the price you pay."]],
        ["@type" => "Question", "name" => "Do units include hand sanitizer?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, all our standard units include hand sanitizer dispensers. Deluxe units come with handwashing stations with soap and paper towels. We can also provide standalone handwashing stations for any event or job site."]],
        ["@type" => "Question", "name" => "How far in advance should I book?", "acceptedAnswer" => ["@type" => "Answer", "text" => "For construction sites, book 1-2 weeks ahead. For events, we recommend booking 2-4 weeks in advance, especially during spring and fall peak season. Last-minute bookings may be possible — call us to check availability."]],
        ["@type" => "Question", "name" => "How often are porta potties serviced?", "acceptedAnswer" => ["@type" => "Answer", "text" => "For weekly and monthly rentals, our standard service includes once-per-week cleaning, pumping, sanitizing, and restocking of supplies. For high-traffic locations or events, we offer twice-weekly or daily servicing."]],
        ["@type" => "Question", "name" => "Do you provide ADA-accessible portable restrooms?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, we offer fully ADA-compliant portable restrooms with extra-wide doors for wheelchair access, interior grab bars, lowered seats, and spacious interiors. Public events may be required to include accessible units."]],
        ["@type" => "Question", "name" => "What areas do you service?", "acceptedAnswer" => ["@type" => "Answer", "text" => "We service cities and counties across the state. Enter your zip code or city on our locations page to see if we service your area, or call us for quick confirmation."]],
        ["@type" => "Question", "name" => "Do portable toilets need water or electricity?", "acceptedAnswer" => ["@type" => "Answer", "text" => "No, our standard portable toilets are completely self-contained and require no water, electricity, or plumbing. They use a chemical solution in the holding tank that controls odors and breaks down waste. Deluxe flushable units need water for handwashing only."]],
        ["@type" => "Question", "name" => "How do you dispose of waste responsibly?", "acceptedAnswer" => ["@type" => "Answer", "text" => "All waste is collected by licensed professionals and transported to approved treatment facilities. We follow strict EPA and local regulations for disposal. Our company uses eco-friendly cleaning products and biodegradable chemicals whenever possible."]],
        ["@type" => "Question", "name" => "Do you offer single-day rentals?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, we offer single-day rentals for events and short-term needs. Pricing is based on the number of units and delivery distance. Extended rentals (weekly/monthly) offer better rates with servicing included."]],
        ["@type" => "Question", "name" => "What if a unit needs servicing during my rental?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Simply call us and we'll send a technician to service or replace the unit. For weekly/monthly rentals, our regular servicing schedule ensures units stay clean and functional. Emergency service is available for critical situations."]],
        ["@type" => "Question", "name" => "Is there a deposit or hidden fees?", "acceptedAnswer" => ["@type" => "Answer", "text" => "We believe in transparent pricing. Quotes include delivery, setup, servicing, and pickup — no hidden fees. Deposits vary by rental size and duration. We'll provide a full breakdown before you commit."]]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($businessSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
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
                    <span class="hidden sm:inline">Same-Day</span> Delivery Available
                </div>

                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold text-white mb-4 sm:mb-6 leading-tight">
                    Rent Porta Potties<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-300 text-xl sm:text-2xl md:text-3xl lg:text-4xl">
                        Anywhere in the USA — Same-Day Delivery
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

                {{-- Trust Indicators - Wraps better on mobile --}}
                <div class="flex flex-wrap items-center gap-x-3 sm:gap-x-6 gap-y-2 text-xs sm:text-sm trust-indicators">
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
                    Porta Potty Rental Options for Every Need
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    From basic construction units to luxury restroom trailers —
                    we have the right <strong>portable toilet rental</strong> solution for every budget and event type.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                {{-- Standard --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-blue-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">🚻</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Standard Portable Toilet</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Perfect for construction sites & basic outdoor needs
                    </p>
                    <ul class="text-sm text-slate-600 space-y-1.5 sm:space-y-2 mb-4 sm:mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Non-splash urinal</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Hand sanitizer</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Ventilation system</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Weekly servicing</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- Deluxe --}}
                <div class="bg-white border-2 border-emerald-400 rounded-2xl p-4 sm:p-6 text-center
                            hover:shadow-xl hover:shadow-emerald-100/50 transition-all duration-300
                            group relative overflow-visible">
                    <div class="absolute -top-3 sm:-top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-400 to-amber-500
                                text-white text-xs sm:text-sm font-extrabold px-3 sm:px-5 py-1.5 sm:py-2 rounded-full shadow-xl shadow-amber-500/40 z-20">
                        ⭐ MOST POPULAR
                    </div>
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-emerald-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 mt-1 sm:mt-2 group-hover:scale-110 transition-transform duration-300">🚿</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Deluxe Flushable Unit</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Flushing toilet with hand wash station
                    </p>
                    <ul class="text-sm text-slate-600 space-y-1.5 sm:space-y-2 mb-4 sm:mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Flushing toilet</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Sink with running water</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Interior mirror</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Weekly servicing</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                              text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-emerald-500/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- ADA --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-blue-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">♿</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">ADA Accessible Unit</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Wheelchair accessible, fully ADA compliant
                    </p>
                    <ul class="text-sm text-slate-600 space-y-1.5 sm:space-y-2 mb-4 sm:mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Extra-wide door</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Interior grab bars</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Non-slip flooring</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Spacious interior</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- Luxury --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-purple-200 hover:shadow-xl hover:shadow-purple-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-purple-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">✨</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Luxury Restroom Trailer</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Premium restroom trailers for upscale events
                    </p>
                    <ul class="text-sm text-slate-600 space-y-1.5 sm:space-y-2 mb-4 sm:mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Climate controlled</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Porcelain fixtures</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Vanity & lighting</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Men's & women's sides</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-purple-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Free Quote</span>
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
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
    {{-- PRICING TABLE --}}
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

            <div class="text-center mt-8 sm:mt-10">
                <a href="tel:{{ domain_phone_raw() }}"
                   class="inline-flex items-center gap-2 sm:gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                          text-white font-bold text-base sm:text-lg py-3 sm:py-4 px-6 sm:px-8 rounded-full
                          shadow-xl shadow-emerald-500/25 transition-all hover:scale-105">
                    📞 Get Your Custom Quote
                </a>
                <p class="text-slate-500 text-sm mt-3">Personalized pricing based on your needs</p>
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
                                📍 {{ $state['name'] }}
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
