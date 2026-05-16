@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', 'Porta Potty Rental Services | Potty Direct')
@section('meta_description', 'Nationwide porta potty rental services. Standard, Deluxe, ADA & luxury restroom trailers available. Same-day delivery across the USA. Call now!')
@section('canonical', route('services'))

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();

$serviceSchema = [
    "@context" => "https://schema.org",
    "@type" => "LocalBusiness",
    "name" => "Potty Direct",
    "description" => "Porta potty rental services nationwide. Standard, Deluxe, ADA, Luxury units available.",
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
    "hasOfferCatalog" => [
        "@type" => "OfferCatalog",
        "name" => "Porta Potty Rental Services",
        "itemListElement" => [
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Standard Portable Restroom Rental"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Deluxe Flushable Unit"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "ADA Accessible Unit"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Luxury Restroom Trailer"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Portable Shower Unit"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Mobile Restroom Trailer"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "VIP Event Restroom"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Construction Site Package"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Portable Urinal Station"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Hand Wash Trailer"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Temporary Fencing"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "High-Rise Construction Toilet"]]
        ]
    ],
    "aggregateRating" => ($reviewCount ?? 0) > 0 ? [
        "@type" => "AggregateRating",
        "ratingValue" => (string) ($reviewRating ?? 4.9),
        "reviewCount" => (string) ($reviewCount ?? 0),
        "bestRating" => "5"
    ] : null
];
$serviceSchema = array_filter($serviceSchema);

$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        [
            "@type" => "Question",
            "name" => "What type of porta potty rental do I need for a construction site?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Standard portable toilet units are ideal for construction sites. They are OSHA compliant, budget-friendly, and include weekly servicing. For larger job sites, we offer construction site packages with volume discounts."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "What porta potty rental is best for weddings?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Deluxe flushable units with hand washing stations are perfect for weddings. For upscale events, luxury restroom trailers offer climate control, porcelain fixtures, and elegant interiors that guests expect."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Do you offer ADA accessible porta potty rental?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Yes, we offer ADA accessible portable restroom units with extra-wide doors, interior grab bars, non-slip flooring, and spacious interiors. They are required for many public events."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "How much does porta potty rental cost?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Pricing varies by location, quantity, rental duration, and unit type. Call us for a personalized quote tailored to your specific needs."
            ]
        ]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@php
$breadcrumbSchema = [
    "@context" => "https://schema.org",
    "@type" => "BreadcrumbList",
    "itemListElement" => [
        ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => route('home')],
        ["@type" => "ListItem", "position" => 2, "name" => "Services", "item" => route('services')]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

    {{-- Trust Banner --}}
    <div class="bg-slate-900 text-white py-3">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-center gap-3 md:gap-5 text-center md:text-left text-xs sm:text-sm">
            @if(($reviewCount ?? 0) > 0)
                <div class="flex items-center gap-2">
                    <x-icon name="star" class="w-4 h-4 text-amber-400" />
                    <span class="font-semibold">{{ number_format($reviewRating ?? 4.9, 1) }}/5 ({{ $reviewCount }}+ Reviews)</span>
                </div>
                <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
            @endif
            <span class="inline-flex items-center gap-1.5"><x-icon name="shield-check" class="w-4 h-4 text-emerald-400" />Licensed &amp; Insured</span>
            <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
            <span class="inline-flex items-center gap-1.5"><x-icon name="truck" class="w-4 h-4 text-emerald-400" />Same-Day Delivery</span>
            <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
            <span class="inline-flex items-center gap-1.5"><x-icon name="currency-dollar" class="w-4 h-4 text-emerald-400" />No Hidden Fees</span>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <nav class="bg-slate-100 border-b border-slate-200 py-2.5 px-4">
        <div class="max-w-6xl mx-auto flex items-center gap-1.5 text-xs sm:text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-emerald-600 transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3" />
            <span class="text-slate-800 font-medium">Services</span>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative py-16 md:py-20 overflow-hidden bg-slate-900">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5 tracking-tight text-balance">
                Porta Potty Rental Services
            </h1>
            <p class="text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto mb-8">
                From basic <strong class="text-white">construction site portable toilets</strong> to <strong class="text-white">luxury restroom trailers</strong> — a rental solution for every need and budget.
            </p>
            <div class="flex flex-wrap justify-center gap-3 text-sm text-slate-300">
                <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="truck" class="w-4 h-4 text-emerald-400" />Same-Day Delivery</span>
                <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="sparkles" class="w-4 h-4 text-emerald-400" />Weekly Servicing</span>
                <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="shield-check" class="w-4 h-4 text-emerald-400" />OSHA Compliant</span>
            </div>
        </div>
    </section>

    {{-- Quick Navigation --}}
    <section class="py-5 px-4 bg-slate-50 border-b border-slate-200">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-wrap justify-center gap-x-5 gap-y-2 text-sm">
                <a href="#standard" class="text-slate-600 hover:text-emerald-600 font-medium transition">Standard</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#deluxe" class="text-slate-600 hover:text-emerald-600 font-medium transition">Deluxe Flushable</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#ada" class="text-slate-600 hover:text-emerald-600 font-medium transition">ADA Accessible</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#luxury" class="text-slate-600 hover:text-emerald-600 font-medium transition">Luxury Trailers</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#shower" class="text-slate-600 hover:text-emerald-600 font-medium transition">Portable Showers</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#mobile" class="text-slate-600 hover:text-emerald-600 font-medium transition">Mobile Trailers</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#vip" class="text-slate-600 hover:text-emerald-600 font-medium transition">VIP Restrooms</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#construction" class="text-slate-600 hover:text-emerald-600 font-medium transition">Construction Packages</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#portable-urinal" class="text-slate-600 hover:text-emerald-600 font-medium transition">Urinal Stations</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#handwash-trailer" class="text-slate-600 hover:text-emerald-600 font-medium transition">Hand Wash Trailers</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#temporary-fencing" class="text-slate-600 hover:text-emerald-600 font-medium transition">Fencing</a>
                <span class="text-slate-300" aria-hidden="true">·</span>
                <a href="#highrise" class="text-slate-600 hover:text-emerald-600 font-medium transition">High-Rise</a>
            </div>
        </div>
    </section>

    {{-- Introduction Content --}}
    <section class="py-12 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4">Complete Porta Potty Rental Solutions</h2>
            <p class="text-lg text-slate-600 leading-relaxed mb-6">
                Whether you need <strong>porta potty rental for construction sites</strong>, <strong>outdoor events</strong>, 
                <strong>weddings</strong>, or <strong>VIP gatherings</strong>, we offer the widest selection of portable toilet rentals 
                in the USA. Our fleet includes standard units, deluxe flushable, ADA accessible, luxury restroom trailers, 
                portable showers, and specialized packages.
            </p>
            <p class="text-slate-600 leading-relaxed">
                Every rental includes <strong>same-day delivery</strong>, <strong>weekly servicing</strong>, and <strong>OSHA compliance</strong>. 
                Call now to speak with a sanitation expert who will help you choose the right porta potty rental for your project.
            </p>
        </div>
    </section>

    {{-- Events We Serve --}}
    <section class="py-12 md:py-16 px-4 bg-gradient-to-r from-amber-500 to-amber-600">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">We Serve All Types of Events & Occasions</h2>
            <p class="text-lg text-amber-100 mb-8">No matter what you're planning, we have the right porta potty rental solution</p>

            <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-left">
                    @php
                        $events = [
                            'Weddings', 'Birthday Parties', 'Festivals',
                            'Concerts', 'Sports Events', 'Corporate Events',
                            'Graduations', 'Camping', 'Fairs & Carnivals',
                            'Trade Shows', 'Home Renovation', 'Construction Sites',
                            'Beach Events', 'Outdoor Gatherings', 'Casino Events',
                            'Race Tracks', 'Film Sets', 'Road Work',
                            'Agricultural Events', 'Community Events',
                        ];
                    @endphp

                    @foreach($events as $event)
                        <div class="flex items-center gap-2 text-white font-medium">
                            <x-icon name="check" class="w-4 h-4 text-emerald-400 flex-shrink-0" />
                            <span>{{ $event }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="text-amber-200 mt-6">Plus many more! Call us to discuss your specific event needs.</p>
        </div>
    </section>

    {{-- Service Types --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Our Porta Potty Rental Options</h2>
                <p class="text-lg text-slate-600">Choose from our complete range of portable toilet rentals</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                @foreach($serviceTypes as $type)
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden
                                hover:shadow-xl hover:border-amber-300 transition-all duration-300 group" id="{{ $type['key'] }}">
                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-slate-800 to-slate-900 p-6 text-white">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                        <div class="flex items-center gap-4 mb-3">
                                            <div class="w-14 h-14 rounded-xl bg-emerald-500/15 text-emerald-300 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                                                <x-icon name="{{ $type['icon'] }}" class="w-7 h-7" />
                                            </div>
                                            <div>
                                                <h2 class="text-xl font-bold">{{ $type['name'] }}</h2>
                                                @php $svcPrice = $priceRanges[$type['key']] ?? null; @endphp
                                                @if($pricingEnabled && $svcPrice)
                                                    <span class="inline-block mt-1 text-xs bg-emerald-500/20 text-emerald-300 font-bold px-2 py-0.5 rounded-full">From ${{ number_format($svcPrice['low']) }}/day</span>
                                                @endif
                                            </div>
                                        </div>
                                    <p class="text-slate-300 text-sm leading-relaxed">
                                        {{ $type['description'] }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="tel:{{ domain_phone_raw() }}"
                                       data-tracking-label="services-card-inline"
                                       class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white font-semibold py-2 px-4 rounded-lg transition-all whitespace-nowrap min-h-[44px]">
                                        <x-icon name="phone" class="w-4 h-4" />
                                        Get Quote
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            {{-- Features --}}
                            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Features Included</h3>
                            <ul class="space-y-3 mb-6">
                                @foreach($type['features'] as $feature)
                                    <li class="flex items-center gap-3 text-slate-600">
                                        <span class="w-5 h-5 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-amber-600 text-xs">✓</span>
                                        </span>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>

                            {{-- Best For --}}
                            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-3">Best For</h3>
                            <div class="flex flex-wrap gap-2 mb-6">
                                @foreach($type['best_for'] as $use)
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm">
                                        {{ $use }}
                                    </span>
                                @endforeach
                            </div>

                            {{-- CTA --}}
                            <a href="tel:{{ domain_phone_raw() }}"
                               data-tracking-label="services-card-cta"
                               class="flex items-center justify-center gap-2 w-full bg-amber-500 hover:bg-amber-400 text-white font-bold py-3 px-6 rounded-full transition-all shadow-lg shadow-amber-500/25 hover:scale-[1.02] min-h-[44px]">
                                <x-icon name="phone" class="w-4 h-4" />
                                Get Quote for {{ $type['short_name'] }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Add-Ons --}}
    <section class="py-12 md:py-16 px-4 bg-slate-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-slate-800 mb-4">Additional Porta Potty Rental Services</h2>
                <p class="text-slate-500">Enhance your portable toilet rental with these additional services</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($addOns as $addon)
                    <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-lg hover:border-emerald-300 transition-all">
                        <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                            <x-icon name="{{ $addon['icon'] }}" class="w-6 h-6" />
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">{{ $addon['name'] }}</h3>
                        <p class="text-sm text-slate-500 mb-4 leading-relaxed">{{ $addon['description'] }}</p>
                        <a href="tel:{{ domain_phone_raw() }}" data-tracking-label="services-addon" class="inline-flex items-center gap-1.5 text-emerald-600 font-semibold hover:text-emerald-700 min-h-[44px]">
                            <x-icon name="phone" class="w-4 h-4" />
                            Call for pricing
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How to Choose --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-slate-800 mb-4">How to Choose the Right Portable Toilet</h2>
                <p class="text-slate-500">Not sure which porta potty rental service is right for your needs? Here's a quick guide</p>
            </div>

            <div class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <x-icon name="building" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Need basic facilities for workers?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Standard units</strong> are perfect for construction sites, job sites, and any situation where you need functional, no-frills restroom facilities. OSHA compliant and budget-friendly.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <x-icon name="water-drop" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Hosting an outdoor event or wedding?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Deluxe flushable units</strong> with hand washing stations are ideal for weddings, private parties, and corporate events where guests expect more comfort and amenities.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <x-icon name="accessibility" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Public event or need ADA compliance?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>ADA accessible units</strong> are required for many public events and provide wheelchair access. They also offer more room for anyone who needs it.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <x-icon name="sparkles" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">VIP event or high-profile gathering?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Luxury restroom trailers</strong> offer the amenities of indoor restrooms — climate control, running water, elegant interiors. Perfect for film sets, weddings, and executive events.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-5 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <x-icon name="building" class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Large construction project?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Construction site packages</strong> include multiple units, weekly servicing, OSHA documentation, and volume discounts. Ideal for large job sites, high-rise projects, and road work.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SEO Content Section --}}
    <section class="py-12 md:py-16 px-4 bg-slate-50">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl p-8 md:p-12 shadow-sm border border-slate-200">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">Why Choose Our Porta Potty Rental Service?</h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex gap-3">
                        <x-icon name="truck" class="w-6 h-6 text-emerald-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">Fast Delivery</h3>
                            <p class="text-slate-600 text-sm">Same-day porta potty delivery available in most areas when you call before 2 PM.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <x-icon name="sparkles" class="w-6 h-6 text-emerald-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">Reliable Servicing</h3>
                            <p class="text-slate-600 text-sm">Weekly cleaning, pumping, and restocking included with every rental.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <x-icon name="shield-check" class="w-6 h-6 text-emerald-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">OSHA Compliant</h3>
                            <p class="text-slate-600 text-sm">All standard units meet OSHA requirements for construction sites.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <x-icon name="currency-dollar" class="w-6 h-6 text-emerald-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">Transparent Pricing</h3>
                            <p class="text-slate-600 text-sm">No hidden fees. The price we quote is the price you pay.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <x-icon name="check-circle" class="w-6 h-6 text-emerald-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">Licensed &amp; Insured</h3>
                            <p class="text-slate-600 text-sm">Fully licensed and insured for your peace of mind.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <x-icon name="map-pin" class="w-6 h-6 text-emerald-500 flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 class="font-bold text-slate-800 mb-1">Nationwide Service</h3>
                            <p class="text-slate-600 text-sm">Serving all 50 states with local delivery and service.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200">
                    <p class="text-slate-600 leading-relaxed">
                        As one of the leading porta potty rental companies in the USA, we understand that every project has unique requirements. 
                        Whether you're renting porta potties for a <strong>construction site</strong>, <strong>outdoor event</strong>, 
                        <strong>wedding</strong>, or <strong>commercial project</strong>, our team is ready to help you find the perfect solution.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Get Custom Quote --}}
    <section class="py-14 md:py-20 px-4 bg-white border-t border-slate-100">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3">Get your custom quote</h2>
            <p class="text-slate-600 mb-7">Every project is unique. Call for pricing based on your specific needs.</p>
            <a href="tel:{{ domain_phone_raw() }}"
               data-tracking-label="services-custom-quote"
               class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white font-bold text-lg py-4 px-8 rounded-full shadow-xl shadow-amber-500/30 hover:scale-105 transition-all min-h-[44px]">
                <x-icon name="phone" class="w-5 h-5" />
                {{ domain_phone_display() }}
            </a>
            <p class="text-slate-500 text-sm mt-4">No obligation · Same-day delivery available</p>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center relative overflow-hidden">
        <div class="relative max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 text-balance">Book your delivery now — only a 5-minute call</h2>
            <p class="text-lg text-slate-400 mb-3 max-w-xl mx-auto">
                Order by 2PM for <strong class="text-white">same-day delivery</strong> starting at just <strong class="text-emerald-400">$89/day</strong>. No hidden fees.
            </p>
            <p class="text-slate-400 mb-8 text-sm">
                Serving construction sites, events, weddings, and more across the USA.
            </p>
            <a href="tel:{{ domain_phone_raw() }}"
               data-tracking-label="services-final"
               class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400
                      text-white text-2xl md:text-3xl font-bold py-5 px-10
                      rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30
                      transition-all hover:scale-105 min-h-[44px]">
                <x-icon name="phone" class="w-7 h-7 md:w-8 md:h-8" />
                {{ domain_phone_display() }}
            </a>
            <p class="mt-6 text-slate-400 text-sm">No obligation · Same-day delivery available</p>
        </div>
    </section>

    {{-- Quick Links --}}
    <section class="py-8 px-4 bg-slate-50">
        <div class="max-w-4xl mx-auto flex flex-wrap justify-center gap-6 text-sm">
            <a href="{{ route('home') }}" class="text-amber-600 hover:text-amber-700 font-medium">← Back to Home</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('pricing') }}" class="text-amber-600 hover:text-amber-700 font-medium">View Pricing</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('locations') }}" class="text-amber-600 hover:text-amber-700 font-medium">Find Your City</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('blog.index') }}" class="text-amber-600 hover:text-amber-700 font-medium">Blog</a>
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
