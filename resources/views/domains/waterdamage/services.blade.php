@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@section('title', 'Porta Potty Rental Services | Standard, Deluxe & Luxury Units | Potty Direct')
@section('meta_description', 'Porta potty rental services nationwide. Standard, Deluxe flushable, ADA accessible, Luxury restroom trailers, portable showers & more. Same-day delivery available across the USA. Call for a free quote!')
@section('canonical', route('services'))

@push('schema')
@php
$url = url('/');
$phone = phone_raw();

$serviceSchema = [
    "@context" => "https://schema.org",
    "@type" => "LocalBusiness",
    "@id" => $url . "#business",
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
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Construction Site Package"]]
        ]
    ],
    "aggregateRating" => [
        "@type" => "AggregateRating",
        "ratingValue" => "4.9",
        "reviewCount" => "500"
    ]
];

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
@endpush

@section('content')

    {{-- Trust Banner --}}
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white py-3">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-center gap-4 text-center md:text-left">
            <div class="flex items-center gap-2">
                <span>⭐</span>
                <span class="font-semibold">4.9/5 Rating from 500+ Reviews</span>
            </div>
            <span class="text-emerald-100">|</span>
            <span>🏢 BBB A+ Rated</span>
            <span class="text-emerald-100">|</span>
            <span>🏗️ 25+ Years Experience</span>
            <span class="text-emerald-100">|</span>
            <span>🇺🇸 50,000+ Customers</span>
        </div>
    </div>

    {{-- Hero --}}
    <section class="relative py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-20 text-[180px]">🚽</div>
            <div class="absolute bottom-10 left-10 text-[120px]">🚿</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
                Porta Potty Rental Services
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">
                From basic <strong class="text-white">construction site portable toilets</strong> to <strong class="text-white">luxury restroom trailers</strong>,
                we have the perfect rental solution for every need and budget
            </p>
            <div class="flex flex-wrap justify-center gap-4 text-sm text-slate-300">
                <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">🚚 Same-Day Delivery</span>
                <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">🧹 Weekly Servicing</span>
                <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">✅ OSHA Compliant</span>
                <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">🏢 BBB A+ Rated</span>
            </div>
        </div>
    </section>

    {{-- Quick Navigation --}}
    <section class="py-6 px-4 bg-slate-50 border-b border-slate-200">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-wrap justify-center gap-4 text-sm">
                <a href="#standard" class="text-emerald-600 hover:text-emerald-700 font-medium">🚻 Standard Units</a>
                <span class="text-slate-300">|</span>
                <a href="#deluxe" class="text-emerald-600 hover:text-emerald-700 font-medium">🚿 Deluxe Flushable</a>
                <span class="text-slate-300">|</span>
                <a href="#ada" class="text-emerald-600 hover:text-emerald-700 font-medium">♿ ADA Accessible</a>
                <span class="text-slate-300">|</span>
                <a href="#luxury" class="text-emerald-600 hover:text-emerald-700 font-medium">✨ Luxury Trailers</a>
                <span class="text-slate-300">|</span>
                <a href="#shower" class="text-emerald-600 hover:text-emerald-700 font-medium">🚿 Portable Showers</a>
                <span class="text-slate-300">|</span>
                <a href="#mobile" class="text-emerald-600 hover:text-emerald-700 font-medium">🚐 Mobile Trailers</a>
                <span class="text-slate-300">|</span>
                <a href="#vip" class="text-emerald-600 hover:text-emerald-700 font-medium">👔 VIP Restrooms</a>
                <span class="text-slate-300">|</span>
                <a href="#construction" class="text-emerald-600 hover:text-emerald-700 font-medium">🏗️ Construction Packages</a>
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
    <section class="py-12 md:py-16 px-4 bg-gradient-to-r from-emerald-500 to-emerald-600">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">We Serve All Types of Events & Occasions</h2>
            <p class="text-lg text-emerald-100 mb-8">No matter what you're planning, we have the right porta potty rental solution</p>

            <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-left">
                    @php
                        $events = [
                            '💒 Weddings', '🎉 Birthday Parties', '🎪 Festivals',
                            '🎤 Concerts', '⚽ Sports Events', '🏛️ Corporate Events',
                            '🎓 Graduations', '🏕️ Camping', '🎭 Fairs & Carnivals',
                            '⛺ Trade Shows', '🏠 Home Renovation', '🛠️ Construction Sites',
                            '🏖️ Beach Events', '🌲 Outdoor Gatherings', '🎰 Casino Events',
                            '🏇 Race Tracks', '🎰 Movie Sets', '🚧 Road Work',
                            '🌾 Agricultural Events', '🎪 Community Events'
                        ];
                    @endphp

                    @foreach($events as $event)
                        <div class="flex items-center gap-2 text-white font-medium">
                            <span>{{ $event }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="text-emerald-200 mt-6">Plus many more! Call us to discuss your specific event needs.</p>
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
                                hover:shadow-xl hover:border-emerald-200 transition-all duration-300 group" id="{{ $type['key'] }}">
                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-slate-800 to-slate-900 p-6 text-white">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="text-4xl group-hover:scale-110 transition-transform duration-300">
                                            {{ $type['icon'] }}
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-bold">{{ $type['name'] }}</h2>
                                        </div>
                                    </div>
                                    <p class="text-slate-300 text-sm leading-relaxed">
                                        {{ $type['description'] }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="tel:{{ phone_raw() }}"
                                       class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2 px-4 rounded-lg transition-all whitespace-nowrap">
                                        📞 Get Quote
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
                                        <span class="w-5 h-5 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-emerald-600 text-xs">✓</span>
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
                            <a href="tel:{{ phone_raw() }}"
                               class="block w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                                      text-white text-center font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-emerald-500/20">
                                📞 Get Quote for {{ $type['short_name'] }}
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
                    <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-lg hover:border-emerald-200 transition-all">
                        <div class="text-4xl mb-4">{{ $addon['icon'] }}</div>
                        <h3 class="font-bold text-slate-800 mb-2">{{ $addon['name'] }}</h3>
                        <p class="text-sm text-slate-500 mb-4 leading-relaxed">{{ $addon['description'] }}</p>
                        <a href="tel:{{ phone_raw() }}" class="text-emerald-600 font-semibold hover:underline">📞 Call for pricing</a>
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
                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-6 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">🚻</div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Need basic facilities for workers?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Standard units</strong> are perfect for construction sites, job sites, and any situation where you need functional, no-frills restroom facilities. OSHA compliant and budget-friendly.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-6 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">🚿</div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Hosting an outdoor event or wedding?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Deluxe flushable units</strong> with hand washing stations are ideal for weddings, private parties, and corporate events where guests expect more comfort and amenities.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-6 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-emerald-100 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">♿</div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">Public event or need ADA compliance?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>ADA accessible units</strong> are required for many public events and provide wheelchair access. They also offer more room for anyone who needs it.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-6 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-amber-100 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">✨</div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">VIP event or high-profile gathering?</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            <strong>Luxury restroom trailers</strong> offer the amenities of indoor restrooms — climate control, running water, elegant interiors. Perfect for film sets, weddings, and executive events.
                        </p>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-start gap-6 hover:shadow-md transition">
                    <div class="w-16 h-16 bg-cyan-100 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">🏗️</div>
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
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">🚚 Fast Delivery</h3>
                        <p class="text-slate-600 text-sm">Same-day porta potty delivery available in most areas when you call before 2 PM.</p>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">🧹 Reliable Servicing</h3>
                        <p class="text-slate-600 text-sm">Weekly cleaning, pumping, and restocking included with every rental.</p>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">✅ OSHA Compliant</h3>
                        <p class="text-slate-600 text-sm">All standard units meet OSHA requirements for construction sites.</p>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">💰 Transparent Pricing</h3>
                        <p class="text-slate-600 text-sm">No hidden fees. The price we quote is the price you pay.</p>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">🏢 Licensed & Insured</h3>
                        <p class="text-slate-600 text-sm">Fully licensed and insured for your peace of mind.</p>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">🇺🇸 Nationwide Service</h3>
                        <p class="text-slate-600 text-sm">Serving all 50 states with local delivery and service.</p>
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
    <section class="py-12 md:py-16 px-4 bg-gradient-to-r from-emerald-500 to-emerald-600">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Get Your Custom Quote</h2>
            <p class="text-emerald-100 mb-8">Every project is unique. Call us for personalized pricing based on your specific needs.</p>
            <a href="tel:{{ phone_raw() }}"
               class="inline-flex items-center gap-3 bg-white text-emerald-600 font-bold text-lg py-4 px-8 rounded-full shadow-xl hover:scale-105 transition-all">
                📞 {{ phone_display() }}
            </a>
            <p class="text-emerald-200 text-sm mt-4">No obligation • Same-day delivery available</p>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 md:py-20 px-4 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-10 left-10 text-[200px]">🚽</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-xl text-slate-400 mb-4">
                Call us for a <strong class="text-white">free quote</strong> and we'll help you choose the right portable toilets for your needs
            </p>
            <p class="text-slate-300 mb-8">
                Serving construction sites, events, weddings, and more across the USA
            </p>
            <a href="tel:{{ phone_raw() }}"
               class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                      text-white text-3xl md:text-4xl font-bold py-5 px-14
                      rounded-full shadow-2xl shadow-emerald-500/40
                      transition-all hover:scale-105 animate-pulse">
                📞 {{ phone_display() }}
            </a>
            <p class="mt-6 text-slate-400 text-sm">Mon-Sat 7AM-8PM • No Obligation Quote</p>
        </div>
    </section>

    {{-- Quick Links --}}
    <section class="py-8 px-4 bg-slate-50">
        <div class="max-w-4xl mx-auto flex flex-wrap justify-center gap-6 text-sm">
            <a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">← Back to Home</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('pricing') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">View Pricing</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('locations') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Find Your City</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('blog.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Blog</a>
        </div>
    </section>
@endsection
