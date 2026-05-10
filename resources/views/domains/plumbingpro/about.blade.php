@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title')
About {{ $domain?->business_name ?? 'Plumbing Pro' }} | Leading {{ $domain?->primary_service ?? 'Plumbing Services' }} Company in the USA
@endsection
@section('meta_description')
Learn about {{ $domain?->business_name ?? 'Plumbing Pro' }} — your trusted partner for professional plumbing services across the United States. Emergency service, transparent pricing, and 25+ years of experience serving homes and businesses.
@endsection
@section('canonical', route('about'))

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();

$localBusinessSchema = [
    "@context" => "https://schema.org",
    "@type" => "Plumber",
    "@id" => $url . "#business",
    "name" => $domain?->business_name ?? 'Plumbing Pro',
    "description" => "Your trusted partner for professional plumbing services across the United States. Emergency service, transparent pricing, and 25+ years of experience.",
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

$organizationSchema = [
    "@context" => "https://schema.org",
    "@type" => "Organization",
    "@id" => $url . "#organization",
    "name" => $domain?->business_name ?? 'Plumbing Pro',
    "url" => $url,
    "description" => "Leading plumbing company serving homes and businesses across the USA with expert repairs, installations, and emergency services.",
    "sameAs" => [
        "https://www.facebook.com/plumbingpro",
        "https://www.twitter.com/plumbingpro"
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

        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
                About Us
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">
                Your trusted plumbing partner across the United States
            </p>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-3xl mx-auto">
            {{-- Who We Are --}}
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                        <x-icon name="building" class="w-5 h-5" />
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">Who we are</h2>
                </div>
                <p class="text-slate-600 leading-relaxed text-lg">
                    We're a nationwide plumbing service dedicated to expert repairs, installations, and maintenance for residential and commercial properties. From emergency pipe repairs to full bathroom remodels, we deliver quality workmanship you can trust.
                </p>
            </div>

            {{-- Our Mission --}}
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                        <x-icon name="shield-check" class="w-5 h-5" />
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">Our mission</h2>
                </div>
                <p class="text-slate-600 leading-relaxed text-lg">
                    Make plumbing simple, affordable, and hassle-free. Whether you need a leaky faucet fixed or a complete sewer line replacement, you get the same quality and service.
                </p>
            </div>

            {{-- What Sets Us Apart --}}
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                        <x-icon name="sparkles" class="w-5 h-5" />
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800">What sets us apart</h2>
                </div>
                <div class="grid gap-4">
                    @php
                        $features = [
                            ['icon' => 'truck',            'title' => 'Emergency service',       'desc' => 'Available 24/7 for urgent plumbing issues. We arrive fast when you need us most.'],
                            ['icon' => 'sparkles',         'title' => 'Licensed & insured',       'desc' => 'Every technician is fully licensed, bonded, and background-checked for your peace of mind.'],
                            ['icon' => 'currency-dollar',  'title' => 'Upfront pricing',          'desc' => 'Transparent pricing you can trust. The price we quote is the price you pay.'],
                            ['icon' => 'clock',            'title' => 'Satisfaction guaranteed',  'desc' => 'We stand behind every job. If you are not happy, we make it right.'],
                            ['icon' => 'map-pin',          'title' => 'Nationwide coverage',      'desc' => 'Serving cities across the USA with the same great service.'],
                        ];
                    @endphp
                    @foreach($features as $feature)
                        <div class="bg-white border border-slate-200 rounded-xl p-5 flex items-start gap-4 hover:shadow-md transition">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
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

            {{-- Stats --}}
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-8 md:p-10 mb-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-400">15K+</div>
                        <div class="text-slate-400 text-sm mt-1">Jobs Completed</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-400">25+</div>
                        <div class="text-slate-400 text-sm mt-1">Years Experience</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-400">500+</div>
                        <div class="text-slate-400 text-sm mt-1">Cities Served</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-blue-400">98%</div>
                        <div class="text-slate-400 text-sm mt-1">Satisfaction Rate</div>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="bg-slate-900 rounded-2xl p-8 md:p-10 text-center text-white">
                <h2 class="text-2xl font-bold mb-3">Need a plumber?</h2>
                <p class="text-slate-400 mb-6">Call us for a free estimate.</p>
                <a href="tel:{{ domain_phone_raw() }}"
                   data-tracking-label="about-cta"
                   class="inline-flex items-center gap-3 bg-orange-500 hover:bg-orange-400 text-white font-bold text-xl md:text-2xl py-4 px-8 rounded-full shadow-xl shadow-orange-500/30 ring-4 ring-orange-400/30 hover:scale-105 transition-all min-h-[44px]">
                    <x-icon name="phone" class="w-6 h-6" />
                    {{ domain_phone_display() }}
                </a>
            </div>

            {{-- Quick Links --}}
            <div class="mt-10 pt-8 border-t border-slate-200">
                <div class="flex flex-wrap justify-center gap-x-6 gap-y-3 text-sm">
                    <a href="{{ route('services') }}" class="text-blue-600 hover:text-blue-700 font-medium">View All Services</a>
                    <span class="text-slate-300" aria-hidden="true">·</span>
                    <a href="{{ route('pricing') }}" class="text-blue-600 hover:text-blue-700 font-medium">View Pricing</a>
                    <span class="text-slate-300" aria-hidden="true">·</span>
                    <a href="{{ route('locations') }}" class="text-blue-600 hover:text-blue-700 font-medium">Find Your City</a>
                    <span class="text-slate-300" aria-hidden="true">·</span>
                    <a href="{{ route('blog.index') }}" class="text-orange-600 hover:text-orange-700 font-medium">Blog</a>
                </div>
            </div>
        </div>
    </section>

{{-- Mobile Sticky CTA --}}
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-orange-500/40 ring-4 ring-orange-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        <span>Call Now — Free Estimate</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection
