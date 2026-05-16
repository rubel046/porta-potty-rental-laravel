@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', $servicePage->meta_title)
@section('meta_description', $servicePage->seo_description)
@section('canonical', url($servicePage->slug))
@section('phone_display', $servicePage->phone_display)
@section('phone_raw', $servicePage->phone_raw)

@push('schema')
@php
$domain = \App\Models\Domain::current();
$serviceLabel = $domain?->getServiceTypeLabel($servicePage->service_type) ?? 'Porta Potty Rental';
$priceRanges = config('service_pricing.ranges', []);
$priceRange = $priceRanges[$servicePage->service_type] ?? config('service_pricing.fallback');
$pricingEnabled = (bool) config('service_pricing.enabled', false);

$breadcrumbSchema = [
    "@context" => "https://schema.org",
    "@type" => "BreadcrumbList",
    "itemListElement" => [
        ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => route('home')],
        ["@type" => "ListItem", "position" => 2, "name" => $city->state->name, "item" => state_page_url($city->state->slug)],
        ["@type" => "ListItem", "position" => 3, "name" => $city->name, "item" => url($servicePage->slug)]
    ]
];

$serviceSchema = [
    "@context" => "https://schema.org",
    "@type" => "Service",
    "serviceType" => $serviceLabel,
    "name" => "{$serviceLabel} in {$city->name}, {$city->state->code}",
    "description" => $servicePage->seo_description,
    "datePublished" => optional($servicePage->created_at)->toIso8601String(),
    "dateModified" => optional($servicePage->updated_at)->toIso8601String(),
    "provider" => [
        "@type" => "LocalBusiness",
        "name" => $domain?->business_name ?? "Potty Direct",
        "telephone" => $servicePage->phone_raw,
    ],
    "areaServed" => [
        "@type" => "City",
        "name" => $city->name,
        "containedInPlace" => [
            "@type" => "State",
            "name" => $city->state->name,
        ],
    ],
    "offers" => $pricingEnabled ? [
        "@type" => "AggregateOffer",
        "priceCurrency" => config('service_pricing.unit', 'USD'),
        "lowPrice" => (string) $priceRange['low'],
        "highPrice" => (string) $priceRange['high'],
        "availability" => "https://schema.org/InStock",
    ] : null,
];
$serviceSchema = array_filter($serviceSchema);

// Speakable: tells voice assistants which parts of the page to read for
// queries like "porta potty rental near me". Points at H1 and the FAQ section.
$speakableSchema = [
    "@context" => "https://schema.org",
    "@type" => "WebPage",
    "url" => url($servicePage->slug),
    "speakable" => [
        "@type" => "SpeakableSpecification",
        "cssSelector" => ["h1", "#faq"],
    ],
];

// Review schema intentionally omitted. Testimonials displayed on the page are
// AI-generated examples and must not be marked up as Review/AggregateRating
// until a real-reviews pipeline (GBP sync, verified on-site reviews) exists.
$reviewSchema = null;
@endphp
<script type="application/ld+json">{!! json_encode($schemaMarkup, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@if(!empty($faqSchema))
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($speakableSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@if($reviewSchema)
<script type="application/ld+json">{!! json_encode($reviewSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endif
@endpush

@section('content')

    {{-- Hero Section --}}
    @php
        // Get domain prefix from URL directly (no DB query)
        $host = request()->getHost();
        $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host); // "pottydirect.com" → "pottydirect"

        // Fallback for local development
        if ($prefix === 'localhost' || !Storage::disk('public')->exists($prefix . '/hero-banner-images')) {
            $prefix = 'pottydirect';
        }

        $heroImages = Cache::remember("hero_images_{$prefix}", 3600, function () use ($prefix) {
            return collect(Storage::disk('public')->files($prefix . '/hero-banner-images'))
                ->filter(fn($f) => in_array(pathinfo($f, PATHINFO_EXTENSION), ['webp', 'jpg', 'jpeg', 'png']))
                ->values()
                ->all();
        });
        $randomHero = !empty($heroImages) ? $heroImages[array_rand($heroImages)] : $prefix . '/hero-banner-images/default.webp';
        $heroUrl = asset('storage/' . $randomHero);
    @endphp

    <section class="relative min-h-[450px] sm:min-h-[500px] md:min-h-[580px] flex items-center overflow-hidden">
        {{-- Hero Background Image with responsive picture element and WebP support --}}
        <div class="absolute inset-0">
            <picture>
                <source srcset="{{ $heroUrl }}" type="image/webp" media="(min-width: 1200px)">
                <source srcset="{{ $heroUrl }}-768w" type="image/webp" media="(min-width: 768px)">
                <source srcset="{{ $heroUrl }}-480w" type="image/webp" media="(max-width: 767px)">
                <img src="{{ $heroUrl }}" alt="Porta potty rental in {{ $city->name }}"
                     class="w-full h-full object-cover"
                     width="1920" height="1080" loading="eager" fetchpriority="high" decoding="async">
            </picture>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/75 to-slate-900/60"></div>
        </div>

        {{-- Decorative --}}
        <div class="absolute top-16 sm:top-20 right-4 sm:right-10 w-32 sm:w-48 md:w-64 h-32 sm:h-48 md:h-64 bg-amber-500/10 rounded-full blur-3xl desktop-only"></div>

        <div class="relative max-w-7xl mx-auto px-3 sm:px-6 py-12 sm:py-16 md:py-28 w-full">
            <div class="max-w-2xl md:max-w-3xl">
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-slate-300 mb-4 sm:mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ state_page_url($city->state->slug) }}" class="hover:text-white transition">
                        {{ $city->state->name }}
                    </a>
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white">{{ $city->name }}</span>
                </nav>

                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold text-white mb-4 sm:mb-5 leading-tight">
                    {{ $servicePage->h1_title }}
                </h1>

                <p class="text-base sm:text-lg md:text-xl text-slate-300 mb-3 sm:mb-4 max-w-xl">
                    Clean, affordable portable toilets delivered to your
                    {{ $city->name }} location. Same-day delivery available.
                </p>

                <p class="text-xs text-slate-400 mb-5 sm:mb-7">
                    <time datetime="{{ $servicePage->updated_at?->toIso8601String() }}">
                        Last updated {{ $servicePage->updated_at?->format('F j, Y') }}
                    </time>
                </p>

                {{-- CTA --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                    <a href="tel:{{ $servicePage->phone_raw }}"
                       data-tracking-label="service-hero"
                       class="w-full sm:w-auto bg-amber-500 hover:bg-amber-400
                              text-lg sm:text-xl md:text-2xl font-bold
                              py-3 sm:py-4 px-6 sm:px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30
                              transition-all hover:scale-[1.02] flex items-center justify-center gap-2 sm:gap-3 min-h-[44px]">
                        <x-icon name="phone" class="w-5 h-5 sm:w-6 sm:h-6" />
                        {{ $servicePage->phone_display }}
                    </a>
                    <a href="{{ route('locations') }}"
                       class="text-slate-300 hover:text-white text-sm font-medium transition flex items-center gap-2 min-h-[44px]">
                        ← View All Locations
                    </a>
                </div>

                {{-- Price Badge --}}
                @if($pricingEnabled && isset($priceRange['low']))
                <div class="inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-300 text-sm font-bold px-4 py-2 rounded-full mb-5 sm:mb-7">
                    <x-icon name="currency-dollar" class="w-4 h-4" />
                    From ${{ number_format($priceRange['low']) }}/day
                </div>
                @endif

                {{-- Trust microcopy + social proof --}}
                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 mb-4 sm:mb-5">
                    <p class="text-sm sm:text-base text-emerald-300 font-semibold flex items-center gap-2">
                        <x-icon name="check-circle" class="w-4 h-4 flex-shrink-0" />
                        Answered in under 30 seconds by a real person
                    </p>
                    <p class="text-sm text-amber-300 flex items-center gap-1.5">
                        <x-icon name="lightning" class="w-4 h-4" />
                        Only a few units left for this week
                    </p>
                </div>

                {{-- Trust Badges - Simplified on mobile --}}
                <div class="flex flex-wrap items-center gap-x-4 sm:gap-x-5 gap-y-2 text-xs sm:text-sm text-slate-300">
                    @if(($reviewCount ?? 0) > 0)
                        <span class="flex items-center gap-1.5">
                            <x-icon name="star" class="w-4 h-4 text-amber-400" />
                            {{ number_format($reviewRating ?? 4.9, 1) }}/5 ({{ $reviewCount }}+ Reviews)
                        </span>
                        <span class="text-slate-600 hidden xsm:block" aria-hidden="true">·</span>
                    @endif
                    <span class="flex items-center gap-1.5"><x-icon name="shield-check" class="w-4 h-4 text-emerald-400" /><span class="hidden sm:inline">Licensed &amp; Insured</span><span class="sm:hidden">Licensed</span></span>
                    <span class="text-slate-600 hidden xsm:block" aria-hidden="true">·</span>
                    <span class="flex items-center gap-1.5"><x-icon name="truck" class="w-4 h-4 text-emerald-400" /><span class="hidden sm:inline">Same-Day Delivery</span><span class="sm:hidden">Same-Day</span></span>
                    <span class="text-slate-600 hidden xsm:block" aria-hidden="true">·</span>
                    <span class="flex items-center gap-1.5"><x-icon name="currency-dollar" class="w-4 h-4 text-emerald-400" /><span class="hidden sm:inline">No Hidden Fees</span><span class="sm:hidden">No Fees</span></span>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <article class="py-10 sm:py-12 md:py-16 px-3 sm:px-4">
        <div class="max-w-4xl mx-auto">
            {{-- Rendered Markdown/Content --}}
            <div class="prose prose-lg max-w-none
                        prose-headings:text-slate-800 prose-headings:font-bold
                        prose-h2:text-xl sm:text-2xl prose-h2:mt-10 sm:prose-h2:mt-12 prose-h2:mb-4 sm:prose-h2:mb-5 prose-h2:border-b prose-h2:border-slate-200 prose-h2:pb-2 sm:prose-h2:pb-3
                        prose-h3:text-lg sm:text-xl prose-h3:mt-6 sm:prose-h3:mt-8 prose-h3:mb-2 sm:prose-h3:mb-3
                        prose-p:text-slate-600 prose-p:leading-relaxed prose-p:mb-4 sm:prose-p:mb-5
                        prose-li:text-slate-600 prose-li:leading-relaxed
                        prose-li:mb-1 sm:prose-li:mb-2
                        prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-slate-800 prose-strong:font-semibold
                        prose-blockquote:border-l-emerald-500 prose-blockquote:bg-emerald-50 prose-blockquote:rounded-r-xl
                        prose-table:text-sm
                        prose-th:bg-slate-100 prose-th:p-2 sm:prose-th:p-3 prose-th:font-semibold
                        prose-td:p-3 sm:prose-td:p-4 prose-td:border prose-td:border-slate-100
                        prose-img:rounded-xl prose-img:shadow-lg prose-img:mx-auto">
                {!! Str::markdown($servicePage->content) !!}
            </div>

            {{-- Mid-Content CTA --}}
            <div class="my-10 sm:my-12 bg-gradient-to-r from-slate-800 to-slate-900 rounded-xl sm:rounded-2xl p-6 sm:p-8 md:p-10 text-center">
                <h3 class="text-xl sm:text-2xl font-bold text-white mb-3">
                    Order by 2PM for Same-Day Delivery in {{ $city->name }}
                </h3>
                <p class="text-slate-400 mb-5 sm:mb-6">
                    Call for instant pricing — only a 5-minute call to get your porta potty delivered.
                </p>
                <a href="tel:{{ $servicePage->phone_raw }}"
                   data-tracking-label="service-midpage"
                   class="inline-flex items-center gap-2 sm:gap-3 bg-amber-500 hover:bg-amber-400
                          text-white font-bold text-base sm:text-xl py-3 sm:py-4 px-6 sm:px-10 rounded-full
                          transition-all hover:scale-[1.02] shadow-xl shadow-amber-500/30 ring-4 ring-amber-400/30 min-h-[44px]">
                    <x-icon name="phone" class="w-5 h-5 sm:w-6 sm:h-6" />
                    {{ $servicePage->phone_display }}
                </a>
                <div class="flex flex-wrap justify-center gap-x-4 gap-y-2 mt-5 text-xs sm:text-sm text-slate-400">
                    <span class="inline-flex items-center gap-1.5"><x-icon name="check" class="w-4 h-4 text-emerald-400" />Licensed &amp; Insured</span>
                    <span class="inline-flex items-center gap-1.5"><x-icon name="check" class="w-4 h-4 text-emerald-400" />Same-Day Delivery</span>
                    <span class="inline-flex items-center gap-1.5"><x-icon name="check" class="w-4 h-4 text-emerald-400" />No Hidden Fees</span>
                </div>
            </div>
        </div>
    </article>

    {{-- Phone-only CTA — no forms, just a call button --}}
    <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50">
        <div class="max-w-2xl mx-auto text-center">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-3">
                    Call for a Free Quote
                </h2>
                <p class="text-slate-500 mb-6">
                    Real person answers in under 30 seconds. No forms, no waiting.
                </p>
                <div x-data="{
                    units: 0,
                    init() {
                        let base = 2 + Math.floor(Math.random() * 6);
                        let hour = new Date().getHours();
                        if (hour >= 6 && hour <= 14) base += 3;
                        if (hour >= 17 || hour <= 5) base = Math.max(1, base - 2);
                        this.units = base;
                    }
                }" class="flex items-center justify-center gap-2 mb-6 text-sm">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-emerald-700 font-semibold">
                        <span x-text="units"></span> units available for delivery in <strong>{{ $city->name }}</strong>
                    </span>
                </div>
                <a href="tel:{{ $servicePage->phone_raw }}"
                   data-tracking-label="service-phone-cta"
                   class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold text-xl py-4 px-8 rounded-full shadow-lg shadow-amber-500/30 transition-all hover:scale-[1.02] min-h-[44px]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    {{ $servicePage->phone_display }}
                </a>
                <p class="text-xs text-slate-400 mt-4">No spam, no forms — just a real person ready to help.</p>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    @if($testimonials->isNotEmpty())
        <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">
                    What {{ $city->name }} Customers Say
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    @foreach($testimonials as $testimonial)
                        <div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all">
                            <div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $testimonial->rating }} out of 5 stars">
                                @for($i = 0; $i < $testimonial->rating; $i++)
                                    <x-icon name="star" class="w-4 h-4 fill-current" />
                                @endfor
                            </div>
                            <p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">
                                {!! $testimonial->content !!}
                            </p>
                            <div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100">
                                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">
                                    {{ substr($testimonial->customer_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-800">{{ $testimonial->customer_name }}</p>
                                    @if($testimonial->customer_title)
                                        <p class="text-xs text-slate-500">{{ $testimonial->customer_title }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Review Generation CTA --}}
    <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-white">
        <div class="max-w-2xl mx-auto text-center">
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <x-icon name="star" class="w-8 h-8 text-amber-500" />
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-800 mb-3">
                Love our service in {{ $city->name }}?
            </h2>
            <p class="text-slate-600 mb-6 max-w-md mx-auto">
                Help others find reliable porta potty rentals. Share your experience and help us grow.
            </p>
            <a href="https://search.google.com/local/reviews?q={{ urlencode($domain?->business_name ?? 'Potty Direct') }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white font-bold py-3 px-6 rounded-full transition hover:scale-[1.02] min-h-[44px] shadow-lg">
                <x-icon name="star" class="w-5 h-5" />
                Write a Review
            </a>
            <p class="text-xs text-slate-400 mt-3">Takes less than 2 minutes</p>
        </div>
    </section>

    {{-- FAQs --}}
    @if($faqs->isNotEmpty())
        <section id="faq" class="py-10 sm:py-12 md:py-16 px-3 sm:px-4">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">
                    FAQs — {{ $city->name }}, {{ $city->state->code }}
                </h2>
                <div class="space-y-3">
                    @foreach($faqs as $faq)
                        @php
                            $faqId = 'faq-' . ($faq->id ?? \Illuminate\Support\Str::slug(\Illuminate\Support\Str::limit($faq->question, 50, '')));
                        @endphp
                        <details id="{{ $faqId }}" class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group scroll-mt-24">
                            <summary class="flex justify-between items-center p-4 sm:p-5 cursor-pointer
                                    font-semibold text-slate-800 hover:text-emerald-600 transition list-none text-sm sm:text-base">
                                <h3 class="text-sm sm:text-base font-semibold m-0 flex-1">{{ $faq->question }}</h3>
                                <span aria-hidden="true" class="text-xl sm:text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500
                                     transition-all duration-300 ml-2 sm:ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-emerald-100 w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center">+</span>
                            </summary>
                            <div class="px-4 sm:px-5 pb-4 sm:pb-5 text-slate-600 leading-relaxed text-sm sm:text-base">
                                {!! $faq->answer !!}
                            </div>
                        </details>
                    @endforeach
                </div>
                <div class="mt-6 sm:mt-8 text-center">
                <a href="{{ route('pricing') }}" class="text-emerald-600 hover:text-emerald-700 font-medium transition text-sm sm:text-base min-h-[44px] inline-flex items-center">
                    View our full pricing guide →
                </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Other Services in This City --}}
    @if($otherServices->isNotEmpty())
        <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">
                    Other Services in {{ $city->name }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    @foreach($otherServices as $service)
                <a href="{{ url($service->slug) }}"
                   class="bg-white p-4 sm:p-6 rounded-xl shadow-sm hover:shadow-lg hover:border-emerald-300
                          transition-all text-center group border border-slate-200 min-h-[44px] flex flex-col justify-center">
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition text-sm sm:text-base">
                        {{ $service->service_type_label }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">in {{ $city->name }}</p>
                </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Nearby Cities --}}
    @if($nearbyCityPages->isNotEmpty())
        <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">
                    Porta Potty Rental Near {{ $city->name }}
                </h2>
                <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
                    @foreach($nearbyCityPages as $nearbyCity)
                        @php $nearbyPage = $nearbyCity->getServicePage('general'); @endphp
                        @if($nearbyPage)
                        <a href="{{ url($nearbyPage->slug) }}"
                           class="bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300
                                  px-3 sm:px-5 py-2 sm:py-3 rounded-lg sm:rounded-xl text-xs sm:text-sm font-medium text-slate-700
                                  hover:text-emerald-700 transition-all shadow-sm hover:shadow-md flex items-center gap-1.5 sm:gap-2 min-h-[44px]">
                            <x-icon name="map-pin" class="w-4 h-4 text-emerald-500 flex-shrink-0" />
                            {{ $nearbyCity->name }}, {{ $nearbyCity->state->code }}
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Neighborhoods We Serve --}}
    @if($neighborhoods->isNotEmpty())
        <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">
                    Neighborhoods We Serve in {{ $city->name }}
                </h2>
                <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
                    @foreach($neighborhoods as $neighborhood)
                        @php $nbPage = $neighborhood->servicePages->first(); @endphp
                        @if($nbPage)
                            <a href="{{ url('neighborhoods/' . $nbPage->slug) }}"
                               class="bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300
                                      px-3 sm:px-5 py-2 sm:py-3 rounded-lg sm:rounded-xl text-xs sm:text-sm font-medium text-slate-700
                                      hover:text-emerald-700 transition-all shadow-sm hover:shadow-md flex items-center gap-1.5 sm:gap-2 min-h-[44px]">
                                <x-icon name="map-pin" class="w-4 h-4 text-emerald-500 flex-shrink-0" />
                                {{ $neighborhood->name }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Related Blog Posts --}}
    @if($relatedPosts->isNotEmpty())
        <section class="py-12 md:py-16 px-4 bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    Helpful Resources
                </h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $post)
                        <a href="{{ $post->url }}"
                           class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group border border-slate-200">
                            <div class="h-32 overflow-hidden bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center">
                                 @if($post->featured_image_url)
                                     <img src="{{ $post->featured_image_url }}"
                                         alt="{{ $post->title }}"
                                         loading="lazy"
                                         decoding="async"
                                         width="400"
                                         height="128"
                                         class="w-full h-full object-cover">
                                @else
                                    <x-icon name="home" class="w-10 h-10 text-emerald-400" />
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-slate-800 group-hover:text-emerald-600
                                       transition mb-2 line-clamp-2">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-sm text-slate-500 flex items-center gap-1.5">
                                    📖 {{ $post->reading_time_text }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Helpful Guides Section --}}
    <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-white">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">
                Helpful Guides for {{ $city->name }} Residents
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <a href="{{ route('cost.page') }}"
                   class="bg-white border border-slate-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-lg transition-all group">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-3">
                        <x-icon name="currency-dollar" class="w-5 h-5 text-emerald-600" />
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mb-1">Porta Potty Rental Cost</h3>
                    <p class="text-xs sm:text-sm text-slate-500">See pricing factors and get an estimate</p>
                </a>
                <a href="{{ route('party.page') }}"
                   class="bg-white border border-slate-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-lg transition-all group">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mb-3">
                        <x-icon name="sparkles" class="w-5 h-5 text-pink-500" />
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mb-1">Rental for Parties</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Perfect solutions for your celebration</p>
                </a>
                <a href="{{ route('emergency.page') }}"
                   class="bg-white border border-slate-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-lg transition-all group">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mb-3">
                        <x-icon name="lightning" class="w-5 h-5 text-red-500" />
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mb-1">Emergency Rentals</h3>
                    <p class="text-xs sm:text-sm text-slate-500">24/7 rapid response when you need it most</p>
                </a>
                <a href="{{ route('restroom-trailer.page') }}"
                   class="bg-white border border-slate-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-lg transition-all group">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                        <x-icon name="sparkles" class="w-5 h-5 text-purple-500" />
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mb-1">Restroom Trailers</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Premium trailers for upscale events</p>
                </a>
                <a href="{{ route('how-many.page') }}"
                   class="bg-white border border-slate-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-lg transition-all group">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                        <x-icon name="calculator" class="w-5 h-5 text-blue-600" />
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mb-1">How Many Do I Need?</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Quick reference for events & job sites</p>
                </a>
                <a href="{{ route('calculator') }}"
                   class="bg-white border border-slate-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-lg transition-all group">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mb-3">
                        <x-icon name="calculator" class="w-5 h-5 text-amber-600" />
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mb-1">Units Calculator</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Estimate how many units you need</p>
                </a>
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="py-16 md:py-24 px-4 bg-slate-900 text-white text-center relative overflow-hidden">
        <div class="relative max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4 text-balance">
                Get your porta potty delivered in {{ $city->name }} today
            </h2>
            <p class="text-lg text-slate-400 mb-8 max-w-xl mx-auto">
                Free quote · No hidden fees · Same-day delivery available
            </p>
            <a href="tel:{{ $servicePage->phone_raw }}"
               data-tracking-label="service-final"
               class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400
                      text-white text-2xl md:text-3xl font-bold py-5 px-10
                      rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30
                      transition-all hover:scale-[1.02] min-h-[44px]">
                <x-icon name="phone" class="w-7 h-7 md:w-8 md:h-8" />
                {{ $servicePage->phone_display }}
            </a>
            <p class="mt-6 text-slate-400 text-sm">Answered in under 30 seconds by a real person.</p>
        </div>
    </section>

    {{-- Instant Price Calculator --}}
    <section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-white">
        <div class="max-w-2xl mx-auto">
            <x-instant-price cityName="{{ $city->name }}" />
        </div>
    </section>
@endsection
