@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', 'Plumbing Service Pricing | Transparent Rates | Plumbing Pro')
@section('meta_description', 'Plumbing service pricing information. Get competitive rates on drain cleaning, pipe repair, water heater installation, sewer line repair, and emergency plumbing. Call for a free estimate — no hidden fees!')
@section('canonical', route('pricing'))

@push('schema')
@php
$domain = \App\Models\Domain::current();
$url = url('/');
$phone = domain_phone_raw();
$businessName = $domain?->business_name ?? 'Plumbing Pro';
$pricingInfo = [];
$factors = [];
$localBusinessSchema = [
    "@context" => "https://schema.org",
    "@type" => "Plumber",
    "@id" => $url . "#business",
    "name" => $businessName,
    "description" => "Professional plumbing services across the USA. 24/7 emergency service available.",
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
    ]
];
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        [
            "@type" => "Question",
            "name" => "How are plumbing service prices calculated?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Pricing is based on several factors: the type of service needed, job complexity, materials required, your location, and whether it is an emergency or scheduled visit. Call us for a free estimate."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Do you offer free estimates?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Yes! We offer free, no-obligation estimates for all plumbing services including repairs, installations, and replacements. Call us to discuss your project."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "What is included in the service price?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Our service prices include the consultation, diagnosis, labor, and standard parts. We provide upfront pricing before any work begins — no hidden fees."
            ]
        ]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($localBusinessSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
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
        <span class="inline-flex items-center gap-1.5"><x-icon name="shield-check" class="w-4 h-4 text-blue-400" />Licensed &amp; Insured</span>
        <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
        <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="w-4 h-4 text-blue-400" />24/7 Emergency Service</span>
        <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
        <span class="inline-flex items-center gap-1.5"><x-icon name="currency-dollar" class="w-4 h-4 text-blue-400" />Free Estimates</span>
    </div>
</div>

{{-- Hero --}}
<section class="relative py-16 md:py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-3xl"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
            Plumbing Service Pricing
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">
            Transparent pricing with <strong class="text-white">no hidden fees</strong>. 
            Get competitive rates on all plumbing services. Call for your free estimate.
        </p>
        <div class="flex flex-wrap justify-center gap-3 text-sm text-slate-300">
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="currency-dollar" class="w-4 h-4 text-blue-400" />Free Estimates</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="phone" class="w-4 h-4 text-blue-400" />Upfront Pricing</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="clock" class="w-4 h-4 text-blue-400" />24/7 Service</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="badge-check" class="w-4 h-4 text-blue-400" />Licensed Plumbers</span>
        </div>
    </div>
</section>

{{-- Intro --}}
<section class="py-12 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4">How Our Pricing Works</h2>
        <p class="text-lg text-slate-600 leading-relaxed mb-6">
            We believe in <strong>transparent pricing</strong> — the estimate you receive is the price you pay. 
            Our plumbing rates are competitive and based on your specific needs. 
            Whether you need a simple drain cleaning or a full sewer line replacement, we'll find the best solution for your budget.
        </p>
        <p class="text-slate-600 leading-relaxed">
            Every service includes <strong>diagnosis, labor, and standard parts</strong>. 
            Call us at <a href="tel:{{ domain_phone_raw() }}" class="text-blue-600 font-semibold hover:underline">{{ domain_phone_display() }}</a> for a free, no-obligation estimate.
        </p>
    </div>
</section>

{{-- Pricing Options --}}
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Service Options & What's Included</h2>
            <p class="text-lg text-slate-600">Choose the right plumbing service for your needs</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if(isset($pricingInfo) && count($pricingInfo) > 0)
                @foreach($pricingInfo as $info)
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-blue-300 hover:shadow-lg transition-all duration-300 group relative overflow-hidden">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <x-icon name="{{ $info['icon'] }}" class="w-6 h-6" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $info['title'] }}</h3>
                        <p class="text-slate-600 text-sm mb-4 leading-relaxed">{{ $info['description'] }}</p>

                        @if(!empty($info['price_range']))
                        <div class="mb-3">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Price Range</span>
                            <p class="text-lg font-bold text-blue-600">{{ $info['price_range'] }}</p>
                        </div>
                        @endif

                        <div class="mb-4">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Best For</span>
                            <p class="text-sm text-slate-600">{{ $info['best_for'] }}</p>
                        </div>

                        <div class="mb-6">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Includes</span>
                            <ul class="mt-2 space-y-1">
                                @foreach($info['includes'] as $include)
                                    <li class="flex items-start gap-2 text-sm text-slate-600">
                                        <x-icon name="check" class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" />
                                        <span>{{ $include }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="tel:{{ domain_phone_raw() }}"
                           data-tracking-label="pricing-card"
                           class="flex items-center justify-center gap-2 w-full bg-orange-500 hover:bg-orange-400 text-white font-bold py-3 px-6 rounded-full transition-all shadow-lg shadow-orange-500/25 hover:scale-[1.02] min-h-[44px]">
                            <x-icon name="phone" class="w-4 h-4" />
                            {{ $info['cta'] }}
                        </a>
                    </div>
                @endforeach
            @else
                {{-- Static fallback pricing cards --}}
                @php
                    $pricingCards = [
                        [
                            'icon' => 'lightning',
                            'title' => 'Drain Cleaning',
                            'price_range' => '$150 – $500',
                            'description' => 'Professional drain cleaning using hydro-jetting, motorized snaking, and video camera inspection to clear tough clogs and restore flow.',
                            'best_for' => 'Slow drains, recurring clogs, bad odors, grease buildup',
                            'includes' => [
                                'Video camera drain inspection',
                                'Hydro-jetting or motorized snaking',
                                'Clog removal and pipe flushing',
                                'Same-day service available',
                                'Preventative maintenance tips',
                            ],
                            'cta' => 'Get a Free Estimate',
                        ],
                        [
                            'icon' => 'wrench',
                            'title' => 'Pipe Repair',
                            'price_range' => '$200 – $800',
                            'description' => 'Expert pipe repair and replacement for all piping materials. We fix leaks, corrosion, and damaged sections with minimal disruption.',
                            'best_for' => 'Leaking pipes, corroded pipes, frozen pipes, slab leaks',
                            'includes' => [
                                'Accurate leak detection',
                                'Pipe patching and section replacement',
                                'Trenchless repair options',
                                'Slab leak detection and repair',
                                'Frozen pipe thawing and repair',
                            ],
                            'cta' => 'Get a Free Estimate',
                        ],
                        [
                            'icon' => 'fire',
                            'title' => 'Water Heater Services',
                            'price_range' => '$500 – $2,500',
                            'description' => 'Installation, repair, and maintenance for all water heater types including tankless, traditional, and hybrid systems.',
                            'best_for' => 'No hot water, rusty water, strange noises, leaks',
                            'includes' => [
                                'Full system diagnostics',
                                'Tank or tankless installation',
                                'Repair of existing units',
                                'Annual maintenance and flushing',
                                'Warranty on parts and labor',
                            ],
                            'cta' => 'Get a Free Estimate',
                        ],
                        [
                            'icon' => 'map',
                            'title' => 'Sewer Line Services',
                            'price_range' => '$300 – $6,000',
                            'description' => 'Complete sewer line inspection, repair, and replacement. From minor clogs to full pipe replacement using trenchless technology.',
                            'best_for' => 'Sewer backups, slow drains, yard odors, multiple drain clogs',
                            'includes' => [
                                'Video camera sewer inspection',
                                'Hydro-jetting and root removal',
                                'Trenchless pipe lining and replacement',
                                'Sewer line cleanout installation',
                                'Emergency service available',
                            ],
                            'cta' => 'Get a Free Estimate',
                        ],
                        [
                            'icon' => 'phone',
                            'title' => 'Emergency Plumbing',
                            'price_range' => 'Call for Pricing',
                            'description' => '24/7 emergency plumbing for burst pipes, gas leaks, sewage backups, and more. We respond fast to minimize damage to your property.',
                            'best_for' => 'Burst pipes, gas leaks, sewage backups, no water, flooding',
                            'includes' => [
                                '24/7 dispatch 365 days a year',
                                'Fast response — typically under 1 hour',
                                'Emergency shut-off and containment',
                                'Temporary repairs and full resolution',
                                'Insurance claim assistance',
                            ],
                            'cta' => 'Call 24/7',
                        ],
                        [
                            'icon' => 'home',
                            'title' => 'Toilet & Faucet Repair',
                            'price_range' => '$100 – $400',
                            'description' => 'Expert repair and replacement for toilets, faucets, garbage disposals, and other plumbing fixtures throughout your home.',
                            'best_for' => 'Running toilets, leaky faucets, low water pressure, fixture replacement',
                            'includes' => [
                                'Complete fixture diagnostics',
                                'Toilet repair and replacement',
                                'Faucet repair and installation',
                                'Garbage disposal repair',
                                'Water pressure testing',
                            ],
                            'cta' => 'Get a Free Estimate',
                        ],
                    ];
                @endphp

                @foreach($pricingCards as $card)
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-blue-300 hover:shadow-lg transition-all duration-300 group relative overflow-hidden">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <x-icon name="{{ $card['icon'] }}" class="w-6 h-6" />
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $card['title'] }}</h3>
                        <p class="text-slate-600 text-sm mb-4 leading-relaxed">{{ $card['description'] }}</p>

                        <div class="mb-3">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Price Range</span>
                            <p class="text-lg font-bold text-blue-600">{{ $card['price_range'] }}</p>
                        </div>

                        <div class="mb-4">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Best For</span>
                            <p class="text-sm text-slate-600">{{ $card['best_for'] }}</p>
                        </div>

                        <div class="mb-6">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Includes</span>
                            <ul class="mt-2 space-y-1">
                                @foreach($card['includes'] as $include)
                                    <li class="flex items-start gap-2 text-sm text-slate-600">
                                        <x-icon name="check" class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" />
                                        <span>{{ $include }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="tel:{{ domain_phone_raw() }}"
                           data-tracking-label="pricing-card"
                           class="flex items-center justify-center gap-2 w-full bg-orange-500 hover:bg-orange-400 text-white font-bold py-3 px-6 rounded-full transition-all shadow-lg shadow-orange-500/25 hover:scale-[1.02] min-h-[44px]">
                            <x-icon name="phone" class="w-4 h-4" />
                            {{ $card['cta'] }}
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- Pricing Factors --}}
<section class="py-12 md:py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Factors That Affect Your Estimate</h2>
            <p class="text-slate-600">Understanding pricing helps you get the best value</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            @if(isset($factors) && count($factors) > 0)
                @foreach($factors as $factor)
                    <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all">
                        <h3 class="font-bold text-slate-800 mb-2">{{ $factor['title'] }}</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $factor['description'] }}</p>
                    </div>
                @endforeach
            @else
                @php
                    $defaultFactors = [
                        [
                            'title' => 'Type of Service',
                            'description' => 'Different plumbing services have different pricing. Drain cleaning is typically more affordable than sewer line replacement or water heater installation. We provide clear pricing for every service type.',
                        ],
                        [
                            'title' => 'Job Complexity',
                            'description' => 'Simple repairs cost less than complex jobs requiring specialized equipment or extensive labor. We assess each job thoroughly and provide an accurate estimate before any work begins.',
                        ],
                        [
                            'title' => 'Materials Needed',
                            'description' => 'The cost and quality of materials affects the final price. From basic pipe fittings to premium water heaters, we use high-quality parts and provide options to fit your budget.',
                        ],
                        [
                            'title' => 'Location & Travel',
                            'description' => 'Your location affects response time and travel costs. We serve all areas with competitive rates and never add surprise travel fees. Local jobs may qualify for reduced pricing.',
                        ],
                        [
                            'title' => 'Emergency vs. Scheduled',
                            'description' => 'Emergency services (nights, weekends, holidays) may carry a premium for immediate dispatch. Scheduled visits during regular hours offer the most cost-effective rates.',
                        ],
                        [
                            'title' => 'Required Permits & Inspections',
                            'description' => 'Some plumbing work requires permits and inspections, especially major installations and replacements. We handle all permitting and include compliance costs in your estimate.',
                        ],
                    ];
                @endphp
                @foreach($defaultFactors as $factor)
                    <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all">
                        <h3 class="font-bold text-slate-800 mb-2">{{ $factor['title'] }}</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $factor['description'] }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- Why Choose Us --}}
<section class="py-14 md:py-20 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-900 mb-3 text-balance">Why our pricing stands out</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl p-6 border border-slate-200 flex gap-4">
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <x-icon name="currency-dollar" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 mb-1">No hidden fees</h3>
                    <p class="text-slate-600 text-sm">The estimate we provide is the price you pay. No surprise charges or add-on fees after the job is done.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 border border-slate-200 flex gap-4">
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <x-icon name="clock" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 mb-1">Free estimates</h3>
                    <p class="text-slate-600 text-sm">We provide free, no-obligation estimates for all services. Know the cost before any work begins.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 border border-slate-200 flex gap-4">
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <x-icon name="badge-check" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 mb-1">Licensed &amp; insured</h3>
                    <p class="text-slate-600 text-sm">Fully licensed, bonded, and insured plumbers. Work guaranteed for your peace of mind.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 border border-slate-200 flex gap-4">
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <x-icon name="shield-check" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 mb-1">Upfront pricing</h3>
                    <p class="text-slate-600 text-sm">We explain every line item in your estimate so you know exactly what you're paying for — no confusion, no surprises.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-12 md:py-16 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Frequently Asked Questions About Pricing</h2>
            <p class="text-slate-600">Get answers to common pricing questions</p>
        </div>

        @php
            $pricingFaqs = [
                [
                    'q' => 'How are plumbing service prices calculated?',
                    'a' => 'Your estimate is calculated based on several factors: the type of service needed, the complexity of the job, materials required, your location, and whether it is an emergency or scheduled visit. Call us for a free, personalized estimate tailored to your specific needs.',
                ],
                [
                    'q' => 'Do you offer free estimates?',
                    'a' => 'Yes! We provide free, no-obligation estimates for all plumbing services. Whether you need a simple drain cleaning or a full water heater installation, call us and we\'ll give you an accurate quote with no commitment required.',
                ],
                [
                    'q' => 'What is included in the service price?',
                    'a' => 'Our service prices include thorough diagnostics, professional labor, and standard parts and materials. We provide a detailed estimate before any work begins — the price we quote is the price you pay, with no hidden fees or surprises.',
                ],
                [
                    'q' => 'Do you offer discounts for seniors or veterans?',
                    'a' => 'Yes! We offer special discounts for seniors, veterans, and first-time customers. We also provide seasonal promotions. Contact us to ask about current discounts and save on your plumbing service.',
                ],
                [
                    'q' => 'Is the diagnostic fee included in the repair cost?',
                    'a' => 'If you choose us to complete the repair, the diagnostic fee is typically applied toward the total cost. We believe in fair and transparent billing. Ask about our diagnostic fee policy when you call.',
                ],
            ];
        @endphp

        <div class="space-y-3">
            @foreach($pricingFaqs as $faq)
                <details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
                    <summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-blue-600 transition list-none">
                        <span>{{ $faq['q'] }}</span>
                        <span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-blue-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-blue-100 w-8 h-8 rounded-full flex items-center justify-center">+</span>
                    </summary>
                    <div class="px-5 pb-5 text-slate-600 leading-relaxed">
                        <p>{{ $faq['a'] }}</p>
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-balance">Get your free estimate today</h2>
        <p class="text-lg text-slate-400 mb-3">
            Call us for a <strong class="text-white">free, no-obligation estimate</strong>. We'll help you find the best pricing for your plumbing needs.
        </p>
        <p class="text-slate-400 mb-8 text-sm">
            Serving residential and commercial properties across the USA. 24/7 emergency service available.
        </p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="pricing-final"
           class="inline-flex items-center gap-3 bg-orange-500 hover:bg-orange-400
                  text-white text-2xl md:text-3xl font-bold py-5 px-10
                  rounded-full shadow-2xl shadow-orange-500/40 ring-4 ring-orange-400/30
                  transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7 md:w-8 md:h-8" />
            {{ domain_phone_display() }}
        </a>
        <p class="mt-6 text-slate-400 text-sm">No obligation · No hidden fees · Free estimates</p>
    </div>
</section>

{{-- Navigation to other pages --}}
<section class="py-8 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto flex flex-wrap justify-center gap-4 text-sm">
        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Back to Home</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('services') }}" class="text-blue-600 hover:text-blue-700 font-medium">View All Services</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('locations') }}" class="text-blue-600 hover:text-blue-700 font-medium">Find Your City</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('blog.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">Blog</a>
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
