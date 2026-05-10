@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@section('title', 'Plumbing Pro | 24/7 Emergency Plumbing Service | Same-Day Plumber')
@section('meta_description', 'Need a plumber? Plumbing Pro offers 24/7 emergency plumbing, drain cleaning, pipe repair, water heater service, and sewer line repair. Call '.domain_phone_display().'!')
@section('canonical', url('/'))

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();
$domain = \App\Models\Domain::current();

$businessSchema = [
    "@context" => "https://schema.org",
    "@type" => "Plumber",
    "@id" => $url . "#business",
    "name" => $domain?->business_name ?? "Plumbing Pro",
    "alternateName" => $domain?->primary_service ?? "Plumbing Services",
    "description" => $domain?->tagline ?? "24/7 emergency plumbing services across the USA.",
    "url" => $url,
    "telephone" => $phone,
    "priceRange" => "$$",
    "image" => $url . "/og-image.jpg",
    "areaServed" => ["@type" => "Country", "name" => "United States"],
    "openingHoursSpecification" => [
        ["@type" => "OpeningHoursSpecification", "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"], "opens" => "00:00", "closes" => "23:59"]
    ],
    "aggregateRating" => ["@type" => "AggregateRating", "ratingValue" => "4.9", "reviewCount" => "500"]
];

$websiteSchema = [
    "@context" => "https://schema.org",
    "@type" => "WebSite",
    "@id" => $url . "#website",
    "url" => $url,
    "name" => ($domain?->business_name ?? "Plumbing Pro") . " - " . ($domain?->primary_service ?? "Plumbing Services"),
    "publisher" => ["@id" => $url . "#business"],
    "potentialAction" => ["@type" => "SearchAction", "target" => $url . "/locations?q={search_term_string}", "query-input" => "required name=search_term_string"]
];

$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        ["@type" => "Question", "name" => "How much does a plumber cost?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Pricing varies by job type and urgency. We offer free estimates and transparent pricing. Call us for a personalized quote."]],
        ["@type" => "Question", "name" => "Do you offer 24/7 emergency plumbing?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes! We are available 24/7 for plumbing emergencies including burst pipes, sewer backups, and gas leaks. Call us anytime."]],
        ["@type" => "Question", "name" => "How long does a water heater installation take?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Most water heater installations take 2-4 hours depending on the unit type and existing setup."]],
        ["@type" => "Question", "name" => "Do you offer free estimates?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, we offer free estimates for all plumbing services including repairs, installations, and replacements."]],
        ["@type" => "Question", "name" => "Do you service both residential and commercial?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, we provide full plumbing services for both residential homes and commercial properties nationwide."]]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($businessSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

    {{-- ============================================ --}}
    {{-- HERO SECTION --}}
    {{-- ============================================ --}}
    @php
        $host = request()->getHost();
        $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host);

        $heroImages = collect(Storage::disk('public')->files($prefix . '/hero-banner-images'))
            ->filter(fn($f) => in_array(pathinfo($f, PATHINFO_EXTENSION), ['webp', 'jpg', 'jpeg', 'png']))
            ->toArray();
        $randomHero = !empty($heroImages) ? $heroImages[array_rand($heroImages)] : $prefix . '/hero-banner-images/default.webp';
        $heroUrl = asset('storage/' . $randomHero);
    @endphp

    <section class="relative min-h-[500px] sm:min-h-[560px] md:min-h-[680px] flex items-center overflow-hidden">
        <div class="absolute inset-0">
            <div class="w-full h-full bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/75 to-slate-900/60"></div>
        </div>

        <div class="absolute top-16 right-4 sm:right-10 w-32 sm:w-48 md:w-64 h-32 sm:h-48 md:h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-16 left-4 sm:left-10 w-24 sm:w-32 md:w-48 h-24 sm:h-32 md:h-48 bg-orange-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-12 sm:py-16 md:py-28 w-full">
            <div class="max-w-2xl md:max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-blue-500/20 backdrop-blur-sm border border-blue-400/30 text-blue-300 text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-4 sm:mb-6">
                    <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                    <span class="hidden sm:inline">24/7</span> Emergency Service Available
                </div>

                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold text-white mb-4 sm:mb-6 leading-tight">
                    Professional Plumbing Services<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-300 text-xl sm:text-2xl md:text-3xl lg:text-4xl">
                        Anywhere in the USA — 24/7 Emergency Service
                    </span>
                </h1>

                <p class="text-base sm:text-lg md:text-xl text-slate-300 mb-4 sm:mb-6 leading-relaxed max-w-xl">
                    Need a <strong class="text-white">reliable plumber</strong> for emergency repairs, drain cleaning, or water heater installation? 
                    We provide <strong class="text-white">professional plumbing services</strong> to homes and businesses across all 50 states!
                </p>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 mb-6 sm:mb-8">
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full sm:w-auto bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700
                              text-lg sm:text-xl md:text-2xl font-bold
                              py-3 sm:py-4 px-6 sm:px-10 rounded-full shadow-2xl shadow-blue-500/30
                              transition-all hover:scale-105 hover:shadow-blue-500/50
                              flex items-center justify-center gap-2 sm:gap-3">
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

                <div class="flex flex-wrap items-center gap-x-3 sm:gap-x-6 gap-y-2 text-xs sm:text-sm">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <div class="flex">
                            <span class="text-yellow-400">★</span><span class="text-yellow-400">★</span><span class="text-yellow-400">★</span><span class="text-yellow-400">★</span><span class="text-yellow-400">★</span>
                        </div>
                        <span class="text-slate-300 font-medium">4.9/5</span>
                    </div>
                    <div class="hidden sm:block h-4 w-px bg-white/20"></div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="inline-flex items-center justify-center w-4 sm:w-5 h-4 sm:h-5 bg-blue-500 rounded-full text-white text-xs font-bold">✓</span>
                        <span class="text-slate-300 font-medium">Licensed & Insured</span>
                    </div>
                    <div class="hidden sm:block h-4 w-px bg-white/20"></div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="inline-flex items-center justify-center w-4 sm:w-5 h-4 sm:h-5 bg-blue-500 rounded-full text-white text-xs font-bold">✓</span>
                        <span class="text-slate-300 font-medium">24/7 Emergency</span>
                    </div>
                    <div class="hidden sm:block h-4 w-px bg-white/20"></div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="inline-flex items-center justify-center w-4 sm:w-5 h-4 sm:h-5 bg-blue-500 rounded-full text-white text-xs font-bold">✓</span>
                        <span class="text-slate-300 font-medium">Upfront Pricing</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-x-3 sm:gap-x-6 gap-y-2 mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-white/10 text-xs text-slate-400">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">🏢</span>
                        <span class="hidden sm:inline">BBB A+</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">🔧</span>
                        <span class="hidden sm:inline">Licensed</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">📋</span>
                        <span class="hidden sm:inline">Insured</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="px-1.5 sm:px-2 py-0.5 bg-white/10 rounded text-sm">🇺🇸</span>
                        <span class="hidden sm:inline">50K+</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,50 1440,40 L1440,80 L0,80 Z" fill="white"/>
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
                    Professional Plumbing Services for Every Need
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    From emergency repairs to full installations —
                    we have the right <strong>plumbing solution</strong> for every home and business.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                {{-- Drain Cleaning --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-blue-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">🪠</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Drain Cleaning</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Professional drain cleaning to clear clogs and keep your pipes flowing
                    </p>
                    <ul class="text-sm text-slate-600 space-y-1.5 sm:space-y-2 mb-4 sm:mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Hydro jetting service</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Drain snaking & augering</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Camera inspection</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Same-day service</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- Pipe Repair --}}
                <div class="bg-white border-2 border-orange-400 rounded-2xl p-4 sm:p-6 text-center
                            hover:shadow-xl hover:shadow-orange-100/50 transition-all duration-300
                            group relative overflow-visible">
                    <div class="absolute -top-3 sm:-top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-orange-400 to-orange-500
                                text-white text-xs sm:text-sm font-extrabold px-3 sm:px-5 py-1.5 sm:py-2 rounded-full shadow-xl shadow-orange-500/40 z-20">
                        ⭐ MOST REQUESTED
                    </div>
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-orange-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 mt-1 sm:mt-2 group-hover:scale-110 transition-transform duration-300">🔧</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Pipe & Leak Repair</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Fast, reliable pipe repair for burst pipes, leaks, and damaged plumbing
                    </p>
                    <ul class="text-sm text-slate-600 space-y-1.5 sm:space-y-2 mb-4 sm:mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Burst pipe repair</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Leak detection & repair</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Slab leak repair</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Water main repair</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700
                              text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-orange-500/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- Water Heater --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-blue-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">🔥</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Water Heater Service</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Installation, repair, and maintenance for all water heater types
                    </p>
                    <ul class="text-sm text-slate-600 space-y-1.5 sm:space-y-2 mb-4 sm:mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Tankless water heaters</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Traditional tank repair</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Water softener install</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Annual maintenance</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- Sewer Line --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-blue-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">🔩</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Sewer Line Repair</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Complete sewer line services from inspection to full replacement
                    </p>
                    <ul class="text-sm text-slate-600 space-y-1.5 sm:space-y-2 mb-4 sm:mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Trenchless repair</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Sewer line replacement</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Video inspection</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Emergency service</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white
                              font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- Emergency --}}
                <div class="bg-white border-2 border-red-400 rounded-2xl p-4 sm:p-6 text-center
                            hover:shadow-xl hover:shadow-red-100/50 transition-all duration-300
                            group relative overflow-visible">
                    <div class="absolute -top-3 sm:-top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-red-500 to-red-600
                                text-white text-xs sm:text-sm font-extrabold px-3 sm:px-5 py-1.5 sm:py-2 rounded-full shadow-xl shadow-red-500/40 z-20">
                        🚨 24/7 AVAILABLE
                    </div>
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-red-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 mt-1 sm:mt-2 group-hover:scale-110 transition-transform duration-300">🚨</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Emergency Plumbing</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        24/7 emergency service for urgent plumbing issues
                    </p>
                    <ul class="text-sm text-slate-600 space-y-1.5 sm:space-y-2 mb-4 sm:mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Burst pipe emergency</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Sewage backup</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Gas leak detection</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> No hot water</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700
                              text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg shadow-red-500/25
                              hover:scale-[1.02] active:scale-[0.98]">
                        <span class="text-lg">📞</span>
                        <span>Call Now — 24/7</span>
                    </a>
                </div>

                {{-- Residential / Commercial --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6 text-center
                            hover:border-purple-200 hover:shadow-xl hover:shadow-purple-100/50 transition-all duration-300
                            group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 sm:w-20 h-16 sm:h-20 bg-purple-50 rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4 group-hover:scale-110 transition-transform duration-300">🏗️</div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Residential & Commercial</h3>
                    <p class="text-slate-500 text-sm mb-4">
                        Complete plumbing solutions for homes and businesses
                    </p>
                    <ul class="text-sm text-slate-600 space-y-1.5 sm:space-y-2 mb-4 sm:mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Bathroom & kitchen</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Toilet & faucet repair</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Gas line services</li>
                        <li class="flex items-center gap-2"><span class="text-blue-500">✓</span> Sump pump install</li>
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
                <a href="{{ route('services') }}" class="text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center gap-2">
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
                    <span class="text-2xl sm:text-3xl">🔧</span>
                    <span class="font-semibold text-slate-700 group-hover:text-blue-600 transition text-sm sm:text-base">Our Services</span>
                </a>
                <a href="{{ route('pricing') }}" class="flex flex-col items-center gap-2 group">
                    <span class="text-2xl sm:text-3xl">💰</span>
                    <span class="font-semibold text-slate-700 group-hover:text-blue-600 transition text-sm sm:text-base">View Pricing</span>
                </a>
                <a href="{{ route('locations') }}" class="flex flex-col items-center gap-2 group">
                    <span class="text-2xl sm:text-3xl">📍</span>
                    <span class="font-semibold text-slate-700 group-hover:text-blue-600 transition text-sm sm:text-base">All Locations</span>
                </a>
                <a href="{{ route('blog.index') }}" class="flex flex-col items-center gap-2 group">
                    <span class="text-2xl sm:text-3xl">📝</span>
                    <span class="font-semibold text-slate-700 group-hover:text-blue-600 transition text-sm sm:text-base">Blog</span>
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
                <span class="font-bold">Plumbing Emergency?</span>
            </div>
            <span class="text-red-100 hidden sm:inline">We're available 24/7 for burst pipes, sewer backups, and gas leaks.</span>
            <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-1.5 sm:gap-2 bg-white text-red-600 font-bold px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg hover:bg-red-50 transition text-xs sm:text-sm">
                📞 Call Now — 24/7
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
                    Plumbing Services For Every Situation
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    No matter what plumbing issue you're facing,
                    we have the right <strong>professional solution</strong> for your home or business
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @php
                    $useCases = [
                        [
                            'icon' => '🏠',
                            'title' => 'Residential Homes',
                            'desc' => 'Full-service residential plumbing from minor faucet repairs to full repiping. We keep your home running smoothly.',
                            'link_text' => 'Residential Services →',
                            'bg' => 'from-blue-50 to-indigo-50 border-blue-200',
                        ],
                        [
                            'icon' => '🏢',
                            'title' => 'Commercial Properties',
                            'desc' => 'Commercial plumbing for offices, retail, and industrial facilities. Minimal downtime guaranteed.',
                            'link_text' => 'Commercial Services →',
                            'bg' => 'from-slate-50 to-gray-50 border-slate-200',
                        ],
                        [
                            'icon' => '🏗️',
                            'title' => 'New Construction',
                            'desc' => 'Rough-in plumbing, fixture installation, and gas line services for new construction projects of all sizes.',
                            'link_text' => 'Construction Services →',
                            'bg' => 'from-amber-50 to-orange-50 border-amber-200',
                        ],
                        [
                            'icon' => '🔧',
                            'title' => 'Emergency Repairs',
                            'desc' => '24/7 emergency plumbing for burst pipes, sewer backups, gas leaks, and any urgent plumbing issue.',
                            'link_text' => 'Emergency Services →',
                            'bg' => 'from-red-50 to-rose-50 border-red-200',
                        ],
                        [
                            'icon' => '🚿',
                            'title' => 'Bathroom Remodeling',
                            'desc' => 'Complete bathroom plumbing for remodels including fixture installation, rerouting, and upgrades.',
                            'link_text' => 'Bathroom Plumbing →',
                            'bg' => 'from-teal-50 to-emerald-50 border-teal-200',
                        ],
                        [
                            'icon' => '🔥',
                            'title' => 'Water Heater Services',
                            'desc' => 'Installation, repair, and replacement of tank and tankless water heaters. Same-day service available.',
                            'link_text' => 'Water Heater Services →',
                            'bg' => 'from-orange-50 to-amber-50 border-orange-200',
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
                    How Our Plumbing Service Works in 3 Simple Steps
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500">
                    Professional plumbing service has never been easier — 24/7 emergency service available
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 md:gap-10 md:gap-12">
                <div class="text-center relative">
                    <div class="w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl sm:rounded-2xl
                                flex items-center justify-center text-2xl sm:text-3xl font-bold
                                mx-auto mb-4 sm:mb-6 shadow-xl shadow-blue-500/20">
                        1
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3">Call or Book Online</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                        Describe your <strong>plumbing issue</strong> — we'll give you an upfront,
                        transparent quote with no hidden fees.
                    </p>
                    <div class="hidden sm:block absolute top-8 -right-4 md:-right-6 lg:-right-10 text-slate-300">
                        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>

                <div class="text-center relative">
                    <div class="w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl sm:rounded-2xl
                                flex items-center justify-center text-2xl sm:text-3xl font-bold
                                mx-auto mb-4 sm:mb-6 shadow-xl shadow-blue-500/20">
                        2
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3">We Arrive & Fix It</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                        Our licensed plumber arrives on time, diagnoses the issue,
                        and gets it fixed right. <strong>Same-day emergency service</strong> available.
                    </p>
                    <div class="hidden sm:block absolute top-8 -right-4 md:-right-6 lg:-right-10 text-slate-300">
                        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl sm:rounded-2xl
                                flex items-center justify-center text-2xl sm:text-3xl font-bold
                                mx-auto mb-4 sm:mb-6 shadow-xl shadow-blue-500/20">
                        3
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3">Peace of Mind</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                        We guarantee our work with full warranty coverage.
                        Your plumbing is fixed right — or we make it right.
                    </p>
                </div>
            </div>

            <div class="text-center mt-10 md:mt-14">
                <a href="tel:{{ domain_phone_raw() }}"
                   class="inline-flex items-center gap-2 sm:gap-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800
                          text-white font-bold text-base sm:text-lg md:text-xl py-3 sm:py-4 px-6 sm:px-8 md:px-10 rounded-full
                          shadow-xl shadow-blue-500/20 transition-all hover:scale-105">
                    📞 Call Now — Book a Plumber Today
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- TRUST STATS COUNTER --}}
    {{-- ============================================ --}}
    <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-gradient-to-r from-blue-500 to-blue-600">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center">
                <div class="text-white">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-2">20+</div>
                    <div class="text-blue-100 text-xs sm:text-sm font-medium">Years Experience</div>
                </div>
                <div class="text-white">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-2">50K+</div>
                    <div class="text-blue-100 text-xs sm:text-sm font-medium">Jobs Completed</div>
                </div>
                <div class="text-white">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-2">500+</div>
                    <div class="text-blue-100 text-xs sm:text-sm font-medium">Cities Served</div>
                </div>
                <div class="text-white">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-1 sm:mb-2">99%</div>
                    <div class="text-blue-100 text-xs sm:text-sm font-medium">Satisfaction</div>
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
                <div class="bg-slate-50 rounded-xl sm:rounded-2xl p-5 sm:p-6 md:p-8 border border-slate-200">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-4 sm:mb-6 flex items-center gap-2 sm:gap-3">
                        🏆 Certifications
                    </h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">✅</div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">Licensed & Bonded</div>
                                <div class="text-xs sm:text-sm text-slate-500">State licensed plumbers</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">📋</div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">Fully Insured</div>
                                <div class="text-xs sm:text-sm text-slate-500">$2M liability coverage</div>
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
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg sm:rounded-xl flex items-center justify-center text-xl">🔧</div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">EPA Compliant</div>
                                <div class="text-xs sm:text-sm text-slate-500">Environmentally responsible</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl sm:rounded-2xl p-5 sm:p-6 md:p-8 text-white">
                    <h3 class="text-lg sm:text-xl font-bold mb-4 sm:mb-6 flex items-center gap-2 sm:gap-3">
                        💯 Our Guarantee
                    </h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="text-blue-400 text-lg sm:text-xl">✓</span>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Same-Day Service</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Call before noon for same-day</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="text-blue-400 text-lg sm:text-xl">✓</span>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Upfront Pricing</div>
                                <div class="text-slate-400 text-xs sm:text-sm">No hidden fees or surprises</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="text-blue-400 text-lg sm:text-xl">✓</span>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Work Guaranteed</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Full warranty on all work</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="text-blue-400 text-lg sm:text-xl">✓</span>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">24/7 Emergency</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Available anytime, day or night</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="text-blue-400 text-lg sm:text-xl">✓</span>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Licensed & Insured</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Full liability coverage</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-slate-700">
                        <a href="tel:{{ domain_phone_raw() }}"
                           class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg sm:rounded-xl transition-all text-sm sm:text-base">
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
                    Why Choose Our Plumbing Service?
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-400 max-w-2xl mx-auto">
                    We're not just another plumbing company — we're your
                    <strong class="text-white">trusted plumbing partner</strong>
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @php
                    $features = [
                        ['icon' => '🚚', 'title' => 'Same-Day Service', 'desc' => 'Call before noon and get a plumber out today. We understand plumbing emergencies can\'t wait.'],
                        ['icon' => '✅', 'title' => 'Licensed & Insured', 'desc' => 'Every plumber is fully licensed, bonded, and insured for your complete peace of mind.'],
                        ['icon' => '💰', 'title' => 'Upfront Pricing', 'desc' => 'No hidden fees, no surprise charges. The price we quote is the price you pay.'],
                        ['icon' => '🔧', 'title' => 'Expert Technicians', 'desc' => 'Highly trained, experienced plumbers who know how to get the job done right the first time.'],
                        ['icon' => '🕐', 'title' => '24/7 Emergency Service', 'desc' => 'We never sleep. Available 24 hours a day, 7 days a week for any plumbing emergency.'],
                        ['icon' => '⭐', 'title' => 'Satisfaction Guaranteed', 'desc' => 'We stand behind our work. If you\'re not happy, we\'ll make it right — guaranteed.'],
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
                    Don't just take our word for it — hear from real customers who used our plumbing services
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @php
                    $testimonials = [
                        [
                            'name' => 'James Rodriguez',
                            'role' => 'Homeowner',
                            'location' => 'Houston, TX',
                            'text' => 'Water heater went out on a Sunday. Called Plumbing Pro and they had someone at my door within 2 hours. Fast, professional, and the price was exactly what they quoted. Highly recommend!',
                            'rating' => 5,
                            'service' => 'Water Heater Service'
                        ],
                        [
                            'name' => 'Emily Thompson',
                            'role' => 'Property Manager',
                            'location' => 'Dallas, TX',
                            'text' => 'We manage 50+ properties and Plumbing Pro handles all our plumbing needs. Their response time is incredible and their work is always top-notch. The billing is transparent and consistent.',
                            'rating' => 5,
                            'service' => 'Commercial Service'
                        ],
                        [
                            'name' => 'Mike Kowalski',
                            'role' => 'Restaurant Owner',
                            'location' => 'Phoenix, AZ',
                            'text' => 'Our kitchen drain backed up during dinner rush. Plumbing Pro was there in 30 minutes and had it cleared in under an hour. Saved our busy Friday night. True professionals!',
                            'rating' => 5,
                            'service' => 'Emergency Service'
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
                        <p class="text-slate-600 mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base">{!! $testimonial['text'] !!}</p>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm sm:text-lg">
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
                    <a href="https://search.google.com/search?q=Plumbing+Pro+reviews" target="_blank" rel="noopener noreferrer" 
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
    <section id="pricing" class="py-10 sm:py-12 md:py-16 lg:py-20 px-3 sm:px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-8 sm:mb-10 md:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-3 sm:mb-4">
                    Transparent Plumbing Pricing — No Hidden Fees
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    Every job is unique, but we guarantee <strong>upfront pricing</strong> before any work begins.
                    Call for a free, no-obligation quote.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 md:p-8 text-center hover:shadow-lg transition-all">
                    <div class="text-4xl sm:text-5xl mb-4">🪠</div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2">Drain Cleaning</h3>
                    <p class="text-slate-500 text-sm mb-4">Professional drain cleaning with camera inspection available</p>
                    <ul class="text-sm text-slate-600 space-y-2 mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Free estimate</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Same-day service</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> No overtime charges</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Work guaranteed</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25">
                        📞 Get Free Quote
                    </a>
                </div>

                <div class="bg-white border-2 border-blue-400 rounded-2xl p-5 sm:p-6 md:p-8 text-center hover:shadow-lg transition-all relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-extrabold px-4 py-1.5 rounded-full shadow-lg">
                        🔧 MOST COMMON
                    </div>
                    <div class="text-4xl sm:text-5xl mb-4 mt-2">🔧</div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2">Pipe & Leak Repair</h3>
                    <p class="text-slate-500 text-sm mb-4">Fast, reliable pipe repairs for any type of plumbing issue</p>
                    <ul class="text-sm text-slate-600 space-y-2 mb-6 text-left">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Free inspection</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Emergency service</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Senior discounts</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Warranty included</li>
                    </ul>
                    <a href="tel:{{ domain_phone_raw() }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-blue-500/25">
                        📞 Get Free Quote
                    </a>
                </div>
            </div>

            <div class="text-center mt-8 sm:mt-10">
                <p class="text-slate-500 text-sm sm:text-base mb-4">Pricing varies by location and job complexity.</p>
                <a href="tel:{{ domain_phone_raw() }}"
                   class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold py-3 px-6 rounded-xl transition-all">
                    📞 Call {{ domain_phone_display() }} for a Custom Quote
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- FINAL CTA --}}
    {{-- ============================================ --}}
    <section class="py-12 md:py-16 lg:py-20 px-3 sm:px-4 bg-gradient-to-r from-blue-600 to-blue-700">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4">
                Ready to Fix Your Plumbing Issues?
            </h2>
            <p class="text-blue-100 text-base sm:text-lg md:text-xl mb-6 sm:mb-8 max-w-2xl mx-auto">
                Don't let plumbing problems wait. Call us now for fast, professional service.
            </p>
            <a href="tel:{{ domain_phone_raw() }}"
               class="inline-flex items-center gap-3 bg-white text-blue-700 hover:bg-blue-50 font-bold text-lg sm:text-xl md:text-2xl py-4 px-8 sm:px-10 md:px-12 rounded-full shadow-2xl shadow-blue-900/30 transition-all hover:scale-105">
                <span class="text-2xl">📞</span>
                {{ domain_phone_display() }}
            </a>
            <p class="text-blue-200 text-xs sm:text-sm mt-4">24/7 Emergency Service • Free Estimates • Upfront Pricing</p>
        </div>
    </section>

@endsection
