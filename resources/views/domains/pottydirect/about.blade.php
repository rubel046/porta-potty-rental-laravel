@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'About Potty Direct | US Porta Potty Rental')
@section('meta_description', 'Learn about Potty Direct — your trusted partner for affordable, clean porta potty rentals nationwide. Same-day delivery, no hidden fees, professional service.')
@section('canonical', route('about'))

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();

$localBusinessSchema = [
    "@context" => "https://schema.org",
    "@type" => "LocalBusiness",
    "@id" => $url . "#business",
    "name" => "Potty Direct",
    "description" => "Your trusted partner for affordable, clean portable toilet rentals across the United States. Same-day delivery, competitive pricing, and 25+ years of experience.",
    "url" => $url,
    "telephone" => $phone,
    "priceRange" => "$$",
    "areaServed" => [
        "@type" => "Country",
        "name" => "United States"
    ],
    "openingHoursSpecification" => [
        [
            "@type" => "OpeningHoursSpecification",
            "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            "opens" => "00:00",
            "closes" => "23:59"
        ]
    ],
    "aggregateRating" => ($reviewCount ?? 0) > 0 ? [
        "@type" => "AggregateRating",
        "ratingValue" => (string) ($reviewRating ?? 4.9),
        "reviewCount" => (string) ($reviewCount ?? 0),
        "bestRating" => "5"
    ] : null
];
$localBusinessSchema = array_filter($localBusinessSchema);

$aboutPageSchema = [
    "@context" => "https://schema.org",
    "@type" => "AboutPage",
    "name" => "About Potty Direct",
    "description" => "Leading porta potty rental company serving construction sites, events, and weddings across the USA.",
    "url" => route('about'),
    "mainEntity" => ["@id" => url('/') . "#organization"],
    "sameAs" => [
        "https://www.facebook.com/pottydirect",
        "https://www.twitter.com/pottydirect"
    ],
];
@endphp
<script type="application/ld+json">{!! json_encode($localBusinessSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($aboutPageSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@php
$breadcrumbSchema = [
    "@context" => "https://schema.org",
    "@type" => "BreadcrumbList",
    "itemListElement" => [
        ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => route('home')],
        ["@type" => "ListItem", "position" => 2, "name" => "About", "item" => route('about')]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

    {{-- Breadcrumb --}}
    <nav class="bg-slate-100 border-b border-slate-200 py-2.5 px-4">
        <div class="max-w-4xl mx-auto flex items-center gap-1.5 text-xs sm:text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-600 transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3" />
            <span class="text-slate-800 font-medium">About</span>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-10">
            
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
                About Potty Direct — Nationwide Porta Potty Rental
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">
                Your trusted portable sanitation partner across the United States
            </p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-3xl mx-auto">
            {{-- Who We Are --}}
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                        <x-icon name="building" class="w-5 h-5" />
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">Who we are</h2>
                </div>
                <p class="text-slate-600 leading-relaxed text-lg">
                    We're a nationwide portable toilet rental service dedicated to clean, affordable, reliable sanitation solutions for construction sites, outdoor events, weddings, and more.
                </p>
            </div>

            {{-- Our Mission --}}
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                        <x-icon name="shield-check" class="w-5 h-5" />
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">Our mission</h2>
                </div>
                <p class="text-slate-600 leading-relaxed text-lg">
                    Make portable sanitation simple, affordable, and hassle-free. Whether you need one unit for a backyard party or fifty for a music festival, you get the same quality and service.
                </p>
            </div>

            {{-- What Sets Us Apart --}}
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                        <x-icon name="sparkles" class="w-5 h-5" />
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">What sets us apart</h2>
                </div>
                <div class="grid gap-4">
                    @php
                        $features = [
                            ['icon' => 'truck',            'title' => 'Same-day delivery',       'desc' => 'Call before 2 PM, get delivery today. We understand urgency.'],
                            ['icon' => 'sparkles',         'title' => 'Spotlessly clean units',  'desc' => 'Every unit is professionally sanitized and inspected before delivery.'],
                            ['icon' => 'currency-dollar',  'title' => 'No hidden fees',          'desc' => 'Transparent pricing you can trust. The price we quote is the price you pay.'],
                            ['icon' => 'clock',            'title' => 'Flexible rental terms',   'desc' => 'Daily, weekly, monthly. No long-term contracts required.'],
                            ['icon' => 'map-pin',          'title' => 'Nationwide coverage',     'desc' => 'Serving cities across the USA with the same great service.'],
                        ];
                    @endphp
                    @foreach($features as $feature)
                        <div class="bg-white border border-slate-200 rounded-xl p-5 flex items-start gap-4 hover:shadow-md transition">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <x-icon name="{{ $feature['icon'] }}" class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">{{ $feature['title'] }}</h3>
                                <p class="text-slate-600 text-sm">{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Our Story --}}
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                        <x-icon name="clock" class="w-5 h-5" />
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">Our story</h2>
                </div>
                <p class="text-slate-600 leading-relaxed text-lg mb-4">
                    Potty Direct was founded to solve a simple problem: renting a porta potty shouldn't be complicated or expensive. We saw an industry where pricing was opaque, service was inconsistent, and customers were often left guessing what they'd actually pay.
                </p>
                <p class="text-slate-600 leading-relaxed text-lg mb-4">
                    Since our founding, we've grown from a small local operation into a nationwide portable sanitation provider serving over 500 cities. Our team brings decades of combined experience in construction, event management, and waste management — giving us the expertise to handle projects of any scale.
                </p>
                <p class="text-slate-600 leading-relaxed text-lg">
                    Every day, we focus on three things: <strong class="text-slate-800">transparent pricing</strong>, <strong class="text-slate-800">reliable delivery</strong>, and <strong class="text-slate-800">exceptional cleanliness</strong>. It's that simple.
                </p>
            </div>

            {{-- Our Values --}}
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                        <x-icon name="star" class="w-5 h-5" />
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">Our values</h2>
                </div>
                <div class="grid gap-4">
                    @php
                        $values = [
                            ['title' => 'Transparency First', 'desc' => 'Every price is published upfront. No hidden fees, no surprise charges, no fine print. What we quote is what you pay.'],
                            ['title' => 'Reliability Matters', 'desc' => 'We deliver on time, every time. Our fleet and logistics network ensure your units arrive when promised. If we say same-day, we mean same-day.'],
                            ['title' => 'Cleanliness Is Non-Negotiable', 'desc' => 'Every unit is pressure-washed, sanitized, and inspected before delivery. We maintain rigorous cleaning standards that exceed industry norms.'],
                            ['title' => 'Customer First', 'desc' => 'Real humans answer your call — not automated systems. We work around your schedule, not ours.'],
                        ];
                    @endphp
                    @foreach($values as $value)
                        <div class="bg-white border border-slate-200 rounded-xl p-5 flex items-start gap-4 hover:shadow-md transition">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <x-icon name="shield-check" class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">{{ $value['title'] }}</h3>
                                <p class="text-slate-600 text-sm">{{ $value['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Stats --}}
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-8 md:p-10 mb-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-emerald-400">500+</div>
                        <div class="text-slate-400 text-sm mt-1">Cities Served</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-emerald-400">50K+</div>
                        <div class="text-slate-400 text-sm mt-1">Units Delivered</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-emerald-400">4.9</div>
                        <div class="text-slate-400 text-sm mt-1">Customer Rating</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-emerald-400">24/7</div>
                        <div class="text-slate-400 text-sm mt-1">Support Available</div>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="bg-slate-900 rounded-2xl p-8 md:p-10 text-center text-white">
                <h2 class="text-2xl font-bold mb-3">Ready to rent?</h2>
                <p class="text-slate-400 mb-6">Call us for a free, no-obligation quote.</p>
                <a href="tel:{{ domain_phone_raw() }}"
                   data-tracking-label="about-cta"
                   class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white font-bold text-xl md:text-2xl py-4 px-8 rounded-full shadow-xl shadow-amber-500/30 ring-4 ring-amber-400/30 hover:scale-105 transition-all min-h-[44px]">
                    <x-icon name="phone" class="w-6 h-6" />
                    {{ domain_phone_display() }}
                </a>
            </div>

            {{-- Quick Links --}}
            <div class="mt-10 pt-8 border-t border-slate-200">
                <div class="flex flex-wrap justify-center gap-x-6 gap-y-3 text-sm">
                    <a href="{{ route('services') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">View All Services</a>
                    <span class="text-slate-300" aria-hidden="true">·</span>
                    <a href="{{ route('pricing') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">View Pricing</a>
                    <span class="text-slate-300" aria-hidden="true">·</span>
                    <a href="{{ route('locations') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Find Your City</a>
                    <span class="text-slate-300" aria-hidden="true">·</span>
                    <a href="{{ route('blog.index') }}" class="text-amber-600 hover:text-amber-700 font-medium">Blog</a>
                </div>
            </div>
        </div>
    </section>

{{-- Mobile Sticky CTA --}}
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        <span>Call Now — Free Quote</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection
