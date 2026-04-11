@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'About Potty Direct | Leading Porta Potty Rental Company in the USA')
@section('meta_description', 'Learn about Potty Direct — your trusted partner for affordable, clean portable toilet rentals across the United States. Same-day delivery, competitive pricing, and 25+ years of experience serving construction sites, events, and weddings.')
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
    "aggregateRating" => [
        "@type" => "AggregateRating",
        "ratingValue" => "4.9",
        "reviewCount" => "500"
    ]
];

$organizationSchema = [
    "@context" => "https://schema.org",
    "@type" => "Organization",
    "@id" => $url . "#organization",
    "name" => "Potty Direct",
    "url" => $url,
    "description" => "Leading porta potty rental company serving construction sites, events, and weddings across the USA.",
    "sameAs" => [
        "https://www.facebook.com/pottydirect",
        "https://www.twitter.com/pottydirect"
    ],
    "contactPoint" => [
        "@type" => "ContactPoint",
        "telephone" => $phone,
        "contactType" => "customer service",
        "areaServed" => "US",
        "availableLanguage" => "English"
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($localBusinessSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

    {{-- Hero --}}
    <section class="relative py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-20 text-[180px]">ℹ️</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
                About Us
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
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">🏢</div>
                    <h2 class="text-2xl font-bold text-slate-800">Who We Are</h2>
                </div>
                <p class="text-slate-600 leading-relaxed text-lg">
                    We are a nationwide portable toilet rental service dedicated to providing clean, affordable, and reliable sanitation solutions for construction sites, outdoor events, weddings, and more.
                </p>
            </div>

            {{-- Our Mission --}}
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">🎯</div>
                    <h2 class="text-2xl font-bold text-slate-800">Our Mission</h2>
                </div>
                <p class="text-slate-600 leading-relaxed text-lg">
                    To make portable sanitation simple, affordable, and hassle-free for every customer. Whether you need one unit for a backyard party or fifty for a music festival, we deliver the same level of quality and service.
                </p>
            </div>

            {{-- What Sets Us Apart --}}
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">⭐</div>
                    <h2 class="text-2xl font-bold text-slate-800">What Sets Us Apart</h2>
                </div>
                <div class="grid gap-4">
                    @php
                        $features = [
                            ['icon' => '🚚', 'title' => 'Same-Day Delivery', 'desc' => 'Call before 2 PM, get delivery today. We understand urgency.'],
                            ['icon' => '✨', 'title' => 'Spotlessly Clean Units', 'desc' => 'Every unit is professionally sanitized and inspected before delivery.'],
                            ['icon' => '💰', 'title' => 'No Hidden Fees', 'desc' => 'Transparent pricing you can trust. The price we quote is the price you pay.'],
                            ['icon' => '🕐', 'title' => 'Flexible Rental Terms', 'desc' => 'Daily, weekly, monthly options. No long-term contracts required.'],
                            ['icon' => '🌎', 'title' => 'Nationwide Coverage', 'desc' => 'Serving cities across the USA with the same great service.'],
                        ];
                    @endphp
                    @foreach($features as $feature)
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 flex items-start gap-4">
                            <div class="text-2xl">{{ $feature['icon'] }}</div>
                            <div>
                                <h3 class="font-bold text-slate-800">{{ $feature['title'] }}</h3>
                                <p class="text-slate-600 text-sm">{{ $feature['desc'] }}</p>
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
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-8 md:p-10 text-center text-white">
                <h2 class="text-2xl font-bold mb-3">Ready to Rent?</h2>
                <p class="text-emerald-100 mb-6">Call us for a free, no-obligation quote</p>
                <a href="tel:{{ domain_phone_raw() }}"
                   class="inline-flex items-center gap-3 bg-white text-emerald-600 font-bold text-2xl
                          py-4 px-10 rounded-full hover:scale-105 transition-all shadow-xl">
                    📞 {{ domain_phone_display() }}
                </a>
            </div>

            {{-- Quick Links --}}
            <div class="mt-10 pt-8 border-t border-slate-200">
                <div class="flex flex-wrap justify-center gap-6 text-sm">
                    <a href="{{ route('services') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">View All Services</a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('pricing') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">View Pricing</a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('locations') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Find Your City</a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('blog.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Blog</a>
                </div>
            </div>
        </div>
    </section>
@endsection
