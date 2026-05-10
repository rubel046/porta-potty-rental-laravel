@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@section('title', 'Professional Plumber | 24/7 Emergency Plumbing & Drain Cleaning | Plumbing Pro')
@section('meta_description', 'Need a plumber near you? Plumbing Pro offers 24/7 emergency plumbing, drain cleaning, water heater repair, leak detection & sewer service. Call '.domain_phone_display().' for same-day service!')
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

$homeReviews = [
    ["@type" => "Review", "itemReviewed" => ["@type" => "Plumber", "@id" => $url . "#business", "name" => $domain?->business_name ?? "Plumbing Pro"], "reviewRating" => ["@type" => "Rating", "ratingValue" => "5", "bestRating" => "5"], "author" => ["@type" => "Person", "name" => "James Rodriguez"], "reviewBody" => "Water heater went out on a Sunday. Called Plumbing Pro and they had someone at my door within 2 hours. Fast, professional, and the price was exactly what they quoted. Highly recommend!"],
    ["@type" => "Review", "itemReviewed" => ["@type" => "Plumber", "@id" => $url . "#business", "name" => $domain?->business_name ?? "Plumbing Pro"], "reviewRating" => ["@type" => "Rating", "ratingValue" => "5", "bestRating" => "5"], "author" => ["@type" => "Person", "name" => "Emily Thompson"], "reviewBody" => "We manage 50+ properties and Plumbing Pro handles all our plumbing needs. Their response time is incredible and their work is always top-notch."],
    ["@type" => "Review", "itemReviewed" => ["@type" => "Plumber", "@id" => $url . "#business", "name" => $domain?->business_name ?? "Plumbing Pro"], "reviewRating" => ["@type" => "Rating", "ratingValue" => "5", "bestRating" => "5"], "author" => ["@type" => "Person", "name" => "Mike Kowalski"], "reviewBody" => "Our kitchen drain backed up during dinner rush. Plumbing Pro was there in 30 minutes and had it cleared in under an hour. True professionals!"]
];
@endphp
<script type="application/ld+json">{!! json_encode($businessSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($homeReviews, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@php
$phoneDisplay = domain_phone_display();
$phoneRaw = domain_phone_raw();
@endphp

@section('content')

    {{-- ============================================ --}}
    {{-- HERO SECTION -- premium, trust-first, CTA above fold --}}
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

    <section class="relative min-h-[480px] sm:min-h-[560px] md:min-h-[700px] flex items-center overflow-hidden bg-slate-900">
        <div class="absolute inset-0">
            <div class="w-full h-full bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/80 to-slate-900/60"></div>
            <div class="absolute top-20 right-0 w-64 sm:w-96 h-64 sm:h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-0 w-48 sm:w-72 h-48 sm:h-72 bg-orange-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white to-transparent z-10"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 md:py-28 w-full z-20">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-red-500/15 backdrop-blur-sm border border-red-400/30 text-red-300 text-xs sm:text-sm px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-4 sm:mb-6">
                    <span class="relative flex w-2 h-2">
                        <span class="absolute inline-flex w-full h-full bg-red-400 rounded-full opacity-75 animate-ping"></span>
                        <span class="relative inline-flex w-2 h-2 bg-red-400 rounded-full"></span>
                    </span>
                    <span class="font-semibold">24/7 Emergency Service Available</span>
                    <span class="hidden sm:inline text-red-300/70">· Call Now</span>
                </div>

                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-4 sm:mb-6 leading-tight">
                    Emergency Plumber & Same-Day<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-300 text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl">
                        Plumbing Services — Available 24/7
                    </span>
                </h1>

                <p class="text-base sm:text-lg md:text-xl text-slate-300 mb-6 sm:mb-8 leading-relaxed max-w-xl">
                    Need a <strong class="text-white">plumber near you</strong> for emergency repairs, drain cleaning, or water heater installation?
                    We provide <strong class="text-white">professional plumbing services</strong> with upfront pricing and same-day availability across the USA.
                </p>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 mb-8 sm:mb-10">
                    <a href="tel:{{ $phoneRaw }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500 text-white text-xl sm:text-2xl md:text-3xl font-extrabold py-4 sm:py-5 px-8 sm:px-10 rounded-full shadow-2xl shadow-orange-500/30 transition-all hover:scale-105 hover:shadow-orange-500/50 active:scale-95 ring-2 ring-orange-400/30"
                       data-tracking-label="home-hero">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <span>{{ $phoneDisplay }}</span>
                    </a>
                    <a href="{{ route('locations') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white text-base sm:text-lg font-semibold py-3 sm:py-4 px-6 sm:px-8 rounded-full transition-all hover:scale-105 active:scale-95">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0z"/>
                            <path d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        </svg>
                        <span>Find Your City</span>
                    </a>
                </div>

                <div class="flex flex-wrap items-center gap-x-4 sm:gap-x-8 gap-y-2.5 text-xs sm:text-sm">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <div class="flex text-amber-400">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <span class="text-slate-300 font-semibold">4.9/5</span>
                        <span class="text-slate-500">(500+ reviews)</span>
                    </div>
                    <span class="hidden sm:inline text-slate-600">|</span>
                    <span class="inline-flex items-center gap-1.5 text-slate-300">
                        <svg class="w-4 h-4 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Licensed & Insured
                    </span>
                    <span class="hidden sm:inline text-slate-600">|</span>
                    <span class="inline-flex items-center gap-1.5 text-slate-300">
                        <svg class="w-4 h-4 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Upfront Pricing
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-5 sm:mt-7 pt-5 sm:pt-6 border-t border-white/10 text-xs text-slate-500">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/></svg>
                        BBB A+ Rated
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/></svg>
                        $2M Insured
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/></svg>
                        50K+ Jobs Done
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- TRUST BADGE STRIP -- 5 columns, premium dark --}}
    {{-- ============================================ --}}
    <section class="bg-slate-900 border-t border-slate-800 py-6 sm:py-8 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 sm:gap-6">
                <div class="flex flex-col items-center text-center gap-2 py-2">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-blue-400 font-bold text-sm">20+ Years</div>
                        <div class="text-slate-500 text-xs">Experience</div>
                    </div>
                </div>
                <div class="flex flex-col items-center text-center gap-2 py-2">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-emerald-400 font-bold text-sm">50K+ Jobs</div>
                        <div class="text-slate-500 text-xs">Completed</div>
                    </div>
                </div>
                <div class="flex flex-col items-center text-center gap-2 py-2">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-amber-400 font-bold text-sm">4.9/5 Stars</div>
                        <div class="text-slate-500 text-xs">500+ Reviews</div>
                    </div>
                </div>
                <div class="flex flex-col items-center text-center gap-2 py-2">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-purple-400 font-bold text-sm">BBB A+</div>
                        <div class="text-slate-500 text-xs">Accredited</div>
                    </div>
                </div>
                <div class="flex flex-col items-center text-center gap-2 py-2 col-span-2 md:col-span-1">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-orange-400 font-bold text-sm">24/7 Support</div>
                        <div class="text-slate-500 text-xs">Always Available</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- SERVICE OPTIONS -- premium cards with SVG icons --}}
    {{-- ============================================ --}}
    <section id="services" class="py-14 sm:py-20 lg:py-24 px-4 sm:px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10 sm:mb-14">
                <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-xs sm:text-sm font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-3 sm:mb-4">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Our Services
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 mb-3 sm:mb-4">
                    Professional Plumbing Services for Every Need
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    From <strong>emergency repairs</strong> to <strong>full installations</strong> —
                    we have the right plumbing solution for every home and business.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                {{-- Emergency Plumbing --}}
                <div class="group relative bg-white border-2 border-red-200 rounded-2xl p-5 sm:p-6 text-center hover:shadow-xl hover:shadow-red-100/50 transition-all duration-300 overflow-visible">
                    <div class="absolute -top-3 sm:-top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-extrabold px-3 sm:px-4 py-1.5 rounded-full shadow-lg shadow-red-500/40 z-10 whitespace-nowrap">
                        <span class="relative flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                            24/7 Available
                        </span>
                    </div>
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-5 mt-2 shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Emergency Plumbing</h3>
                    <p class="text-slate-500 text-sm mb-3 leading-relaxed">24/7 emergency service for burst pipes, sewer backups, gas leaks, and any urgent plumbing issue.</p>
                    <ul class="text-sm text-slate-600 space-y-1.5 mb-4 sm:mb-5 text-left">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Burst pipe emergency</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Sewage backup</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> No hot water</li>
                    </ul>
                    <a href="tel:{{ $phoneRaw }}" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-400 hover:to-red-500 text-white font-bold py-2.5 sm:py-3 px-4 rounded-xl transition-all shadow-lg shadow-red-500/25 hover:shadow-red-500/40 active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>Call Now — 24/7</span>
                    </a>
                </div>

                {{-- Drain Cleaning --}}
                <div class="group relative bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 text-center hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-5 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Drain Cleaning</h3>
                    <p class="text-slate-500 text-sm mb-3 leading-relaxed">Professional drain cleaning with camera inspection to clear clogs and keep your pipes flowing.</p>
                    <ul class="text-sm text-slate-600 space-y-1.5 mb-4 sm:mb-5 text-left">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Hydro jetting service</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Drain snaking & augering</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Camera inspection</li>
                    </ul>
                    <a href="tel:{{ $phoneRaw }}" class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 sm:py-3 px-4 rounded-xl transition-all shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- Water Heater --}}
                <div class="group relative bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 text-center hover:border-orange-200 hover:shadow-xl hover:shadow-orange-100/50 transition-all duration-300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-5 shadow-lg shadow-orange-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            <path d="M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Water Heater Service</h3>
                    <p class="text-slate-500 text-sm mb-3 leading-relaxed">Installation, repair, and maintenance for tank and tankless water heaters. Same-day service available.</p>
                    <ul class="text-sm text-slate-600 space-y-1.5 mb-4 sm:mb-5 text-left">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Tankless water heaters</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Traditional tank repair</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Annual maintenance</li>
                    </ul>
                    <a href="tel:{{ $phoneRaw }}" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500 text-white font-bold py-2.5 sm:py-3 px-4 rounded-xl transition-all shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- Sewer Line --}}
                <div class="group relative bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 text-center hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-700 to-blue-800 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-5 shadow-lg shadow-blue-700/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Sewer Line Repair</h3>
                    <p class="text-slate-500 text-sm mb-3 leading-relaxed">Complete sewer line services from video inspection to trenchless repair and full replacement.</p>
                    <ul class="text-sm text-slate-600 space-y-1.5 mb-4 sm:mb-5 text-left">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Trenchless repair</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Video inspection</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Emergency service</li>
                    </ul>
                    <a href="tel:{{ $phoneRaw }}" class="w-full inline-flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-bold py-2.5 sm:py-3 px-4 rounded-xl transition-all shadow-lg shadow-blue-700/25 hover:shadow-blue-700/40 active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- Leak Detection --}}
                <div class="group relative bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 text-center hover:border-cyan-200 hover:shadow-xl hover:shadow-cyan-100/50 transition-all duration-300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-5 shadow-lg shadow-cyan-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            <path d="M12 2v4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Leak Detection</h3>
                    <p class="text-slate-500 text-sm mb-3 leading-relaxed">Advanced leak detection technology to find hidden water leaks without damaging your property.</p>
                    <ul class="text-sm text-slate-600 space-y-1.5 mb-4 sm:mb-5 text-left">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Electronic detection</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Thermal imaging</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Slab leak detection</li>
                    </ul>
                    <a href="tel:{{ $phoneRaw }}" class="w-full inline-flex items-center justify-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2.5 sm:py-3 px-4 rounded-xl transition-all shadow-lg shadow-cyan-600/25 hover:shadow-cyan-600/40 active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                {{-- General Plumbing --}}
                <div class="group relative bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 text-center hover:border-purple-200 hover:shadow-xl hover:shadow-purple-100/50 transition-all duration-300">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-5 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">General Plumbing</h3>
                    <p class="text-slate-500 text-sm mb-3 leading-relaxed">Full-service residential and commercial plumbing from minor faucet repairs to full repiping.</p>
                    <ul class="text-sm text-slate-600 space-y-1.5 mb-4 sm:mb-5 text-left">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Faucet & toilet repair</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Gas line services</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Sump pump install</li>
                    </ul>
                    <a href="tel:{{ $phoneRaw }}" class="w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 sm:py-3 px-4 rounded-xl transition-all shadow-lg shadow-purple-600/25 hover:shadow-purple-600/40 active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>Get Free Quote</span>
                    </a>
                </div>
            </div>

            <div class="text-center mt-8 sm:mt-10">
                <a href="{{ route('services') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold text-sm sm:text-base transition-colors">
                    <span>View All Services</span>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- MID-PAGE EMERGENCY BANNER --}}
    {{-- ============================================ --}}
    <section class="bg-gradient-to-r from-red-600 to-red-500 text-white py-3 sm:py-4 px-4">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-6 text-center sm:text-left text-sm sm:text-base">
            <div class="flex items-center gap-2">
                <span class="relative flex w-3 h-3">
                    <span class="absolute inline-flex w-full h-full bg-white rounded-full opacity-75 animate-ping"></span>
                    <span class="relative inline-flex w-3 h-3 bg-white rounded-full"></span>
                </span>
                <span class="font-extrabold">Plumbing Emergency?</span>
            </div>
            <span class="text-red-100">We're available 24/7 for burst pipes, sewer backups, and gas leaks.</span>
            <a href="tel:{{ $phoneRaw }}" class="inline-flex items-center gap-2 bg-white text-red-600 font-extrabold px-4 sm:px-5 py-2 sm:py-2.5 rounded-full hover:bg-red-50 transition-all hover:scale-105 shadow-lg whitespace-nowrap text-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <span>Call Now — 24/7</span>
            </a>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- HOW IT WORKS --}}
    {{-- ============================================ --}}
    <section class="py-14 sm:py-20 lg:py-24 px-4 sm:px-6 bg-gradient-to-b from-white to-slate-50">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-10 sm:mb-14">
                <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-xs sm:text-sm font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-3 sm:mb-4">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Simple Process
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 mb-3 sm:mb-4">
                    How It Works in <span class="text-blue-600">3 Simple Steps</span>
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    Professional plumbing service has never been easier — 24/7 emergency service available
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-12 lg:gap-16">
                <div class="text-center relative">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl sm:text-3xl font-extrabold mx-auto mb-5 sm:mb-6 shadow-xl shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3">Call or Book Online</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                        Describe your <strong>plumbing issue</strong> — we'll give you an upfront, transparent quote with no hidden fees.
                    </p>
                    <div class="hidden sm:block absolute top-10 -right-6 lg:-right-8 text-slate-300">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>

                <div class="text-center relative">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl sm:text-3xl font-extrabold mx-auto mb-5 sm:mb-6 shadow-xl shadow-blue-500/30">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3">We Arrive & Fix It</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                        Our licensed plumber arrives on time, diagnoses the issue, and gets it fixed right. <strong>Same-day emergency service</strong> available.
                    </p>
                    <div class="hidden sm:block absolute top-10 -right-6 lg:-right-8 text-slate-300">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl sm:text-3xl font-extrabold mx-auto mb-5 sm:mb-6 shadow-xl shadow-blue-500/30">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 sm:mb-3">Peace of Mind</h3>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                        We guarantee our work with full warranty coverage. Your plumbing is fixed right — or we make it right.
                    </p>
                </div>
            </div>

            <div class="text-center mt-10 sm:mt-14">
                <a href="tel:{{ $phoneRaw }}"
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold text-base sm:text-lg md:text-xl py-3.5 sm:py-4 px-8 sm:px-10 rounded-full shadow-xl shadow-blue-600/30 transition-all hover:scale-105 active:scale-95">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>Call Now — Book a Plumber Today</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- STATS COUNTER --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 px-4 sm:px-6 bg-gradient-to-r from-blue-600 to-blue-700">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center">
                <div class="text-white">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-1 sm:mb-2">20+</div>
                    <div class="text-blue-200 text-xs sm:text-sm font-medium">Years Experience</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-1 sm:mb-2">50K+</div>
                    <div class="text-blue-200 text-xs sm:text-sm font-medium">Jobs Completed</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-1 sm:mb-2">500+</div>
                    <div class="text-blue-200 text-xs sm:text-sm font-medium">Cities Served</div>
                </div>
                <div class="text-white">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-1 sm:mb-2">99%</div>
                    <div class="text-blue-200 text-xs sm:text-sm font-medium">Satisfaction</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- CERTIFICATIONS & GUARANTEES --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20 px-4 sm:px-6 bg-white">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-10 sm:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-3 sm:mb-4">
                    Licensed, Insured & Guaranteed
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    We stand behind every job with our <strong>100% satisfaction guarantee</strong>
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                <div class="bg-slate-50 rounded-2xl p-6 sm:p-8 border border-slate-200">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-5 sm:mb-6 flex items-center gap-2 sm:gap-3">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Certifications & Credentials
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">Licensed & Bonded</div>
                                <div class="text-xs sm:text-sm text-slate-500">State licensed master plumbers</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">Fully Insured</div>
                                <div class="text-xs sm:text-sm text-slate-500">$2M liability + workers' comp</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">BBB A+ Rated</div>
                                <div class="text-xs sm:text-sm text-slate-500">Better Business Bureau accredited</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 text-sm sm:text-base">EPA Compliant</div>
                                <div class="text-xs sm:text-sm text-slate-500">Environmentally responsible</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 sm:p-8 text-white">
                    <h3 class="text-lg sm:text-xl font-bold mb-5 sm:mb-6 flex items-center gap-2 sm:gap-3">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Our Guarantee to You
                    </h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Same-Day Service</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Call before noon — we'll be there today</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Upfront Pricing</div>
                                <div class="text-slate-400 text-xs sm:text-sm">No hidden fees — the quote is the price</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Work Guaranteed</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Full warranty on parts and labor</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">24/7 Emergency</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Available anytime, day or night</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            <div>
                                <div class="font-semibold text-sm sm:text-base">Licensed & Insured</div>
                                <div class="text-slate-400 text-xs sm:text-sm">Full liability and bond coverage</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 sm:mt-8 pt-5 sm:pt-6 border-t border-slate-700">
                        <a href="tel:{{ $phoneRaw }}" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2.5 sm:py-3 px-5 sm:px-6 rounded-xl transition-all text-sm sm:text-base hover:scale-105 active:scale-95">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>Call for Free Quote</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- WHY CHOOSE US --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20 px-4 sm:px-6 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10 sm:mb-14">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-3 sm:mb-4">
                    Why Choose Plumbing Pro?
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-400 max-w-2xl mx-auto">
                    We're not just another plumbing company — we're your <strong class="text-white">trusted plumbing partner</strong>
                </p>
            </div>

            @php
                $features = [
                    ['icon' => 'truck', 'title' => 'Same-Day Service', 'desc' => 'Call before noon and get a plumber out today. We understand plumbing emergencies can\'t wait.'],
                    ['icon' => 'shield', 'title' => 'Licensed & Insured', 'desc' => 'Every plumber is fully licensed, bonded, and insured for your complete peace of mind.'],
                    ['icon' => 'currency-dollar', 'title' => 'Upfront Pricing', 'desc' => 'No hidden fees, no surprise charges. The price we quote is the price you pay.'],
                    ['icon' => 'wrench', 'title' => 'Expert Technicians', 'desc' => 'Highly trained, experienced plumbers who know how to get the job done right the first time.'],
                    ['icon' => 'clock', 'title' => '24/7 Emergency Service', 'desc' => 'We never sleep. Available 24 hours a day, 7 days a week for any plumbing emergency.'],
                    ['icon' => 'star', 'title' => 'Satisfaction Guaranteed', 'desc' => 'We stand behind our work. If you\'re not happy, we\'ll make it right — guaranteed.'],
                ];

                $svgIcons = [
                    'truck' => '<path d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                    'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
                    'currency-dollar' => '<path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'wrench' => '<path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                    'clock' => '<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'star' => '<path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($features as $feature)
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-5 sm:p-6 hover:bg-white/10 hover:border-white/20 transition-all duration-300 group">
                        <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                {!! $svgIcons[$feature['icon']] !!}
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- TESTIMONIALS --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20 px-4 sm:px-6 bg-slate-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10 sm:mb-14">
                <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 text-xs sm:text-sm font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-3 sm:mb-4">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Trusted by 50,000+ Customers
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 mb-3 sm:mb-4">
                    What Our Customers Say
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    Real reviews from real customers who used our plumbing services
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
                        ],
                    ];
                @endphp

                @foreach($testimonials as $testimonial)
                    <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-lg shadow-slate-200/50 border border-slate-100 hover:shadow-xl transition-all">
                        <div class="flex items-center gap-0.5 mb-3 sm:mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <div class="text-xs text-blue-600 font-semibold mb-2">{{ $testimonial['service'] }}</div>
                        <p class="text-slate-600 mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base">{{ $testimonial['text'] }}</p>
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

        </div>
    </section>

    {{-- ============================================ --}}
    {{-- PRICING TABLE --}}
    {{-- ============================================ --}}
    <section id="pricing" class="py-12 sm:py-16 lg:py-20 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10 sm:mb-14">
                <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-xs sm:text-sm font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-full mb-3 sm:mb-4">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Transparent Pricing
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 mb-3 sm:mb-4">
                    Upfront Pricing — No Hidden Fees
                </h2>
                <p class="text-sm sm:text-base md:text-lg text-slate-500 max-w-2xl mx-auto">
                    Every job is unique, but we guarantee <strong>upfront pricing</strong> before any work begins.
                    Call for a free, no-obligation quote.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 text-center hover:shadow-lg transition-all">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2">Drain Cleaning</h3>
                    <p class="text-slate-500 text-sm mb-4">Professional drain cleaning with camera inspection available</p>
                    <ul class="text-sm text-slate-600 space-y-2 mb-6 text-left">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Free estimate</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Same-day service</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> No overtime charges</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Work guaranteed</li>
                    </ul>
                    <a href="tel:{{ $phoneRaw }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>Get Free Quote</span>
                    </a>
                </div>

                <div class="bg-white border-2 border-blue-400 rounded-2xl p-6 sm:p-8 text-center hover:shadow-lg transition-all relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-extrabold px-4 py-1.5 rounded-full shadow-lg whitespace-nowrap">
                        MOST COMMON SERVICE
                    </div>
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4 mt-2">
                        <svg class="w-7 h-7 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2">Pipe & Leak Repair</h3>
                    <p class="text-slate-500 text-sm mb-4">Fast, reliable pipe repairs for any type of plumbing issue</p>
                    <ul class="text-sm text-slate-600 space-y-2 mb-6 text-left">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Free inspection</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Emergency service</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Warranty included</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg> Senior discounts</li>
                    </ul>
                    <a href="tel:{{ $phoneRaw }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-400 hover:to-orange-500 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>Get Free Quote</span>
                    </a>
                </div>
            </div>

            <div class="text-center mt-8 sm:mt-10">
                <p class="text-slate-500 text-sm sm:text-base mb-4">Pricing varies by location and job complexity.</p>
                <a href="tel:{{ $phoneRaw }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold py-3 px-6 rounded-xl transition-all hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>Call {{ $phoneDisplay }} for a Custom Quote</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- FINAL CTA --}}
    {{-- ============================================ --}}
    <section class="py-14 sm:py-20 lg:py-24 px-4 sm:px-6 bg-gradient-to-r from-blue-600 to-blue-700">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-4">
                Ready to Fix Your Plumbing Issues?
            </h2>
            <p class="text-blue-100 text-base sm:text-lg md:text-xl mb-6 sm:mb-8 max-w-2xl mx-auto">
                Don't let plumbing problems wait. Call us now for <strong class="text-white">fast, professional service</strong>.
            </p>
            <a href="tel:{{ $phoneRaw }}"
               class="inline-flex items-center gap-3 bg-white text-blue-700 hover:bg-blue-50 font-extrabold text-lg sm:text-xl md:text-2xl py-4 px-8 sm:px-10 md:px-12 rounded-full shadow-2xl shadow-blue-900/30 transition-all hover:scale-105 active:scale-95">
                <svg class="w-6 h-6 sm:w-7 sm:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <span>{{ $phoneDisplay }}</span>
            </a>
            <p class="text-blue-200 text-xs sm:text-sm mt-4">24/7 Emergency Service · Free Estimates · Upfront Pricing</p>
        </div>
    </section>

@endsection
