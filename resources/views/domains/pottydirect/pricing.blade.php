@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', 'Porta Potty Rental Pricing | Starting at $89/day')
@section('meta_description', 'Transparent porta potty rental pricing: Standard from $89/day, Deluxe $150/day, ADA $125/day. No hidden fees, same-day delivery, weekly servicing included.')
@section('canonical', route('pricing'))

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();
$breadcrumbSchema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Pricing', 'item' => route('pricing')]]];
$localBusinessSchema = [
    "@context" => "https://schema.org",
    "@type" => "LocalBusiness",
    "name" => "Potty Direct",
    "description" => "Affordable portable restroom rental service across the USA. Standard units from $89/day. Same-day delivery available.",
    "url" => $url,
    "telephone" => $phone,
    "priceRange" => "$",
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
        "name" => "Porta Potty Rental Pricing",
        "itemListElement" => [
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Standard Porta Potty Rental — From $89/day"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Deluxe Flushable Unit Rental — From $150/day"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "ADA Accessible Unit Rental — From $125/day"]],
            ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Luxury Restroom Trailer Rental — From $500/day"]],
        ]
    ]
];
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        [
            "@type" => "Question",
            "name" => "How much does a standard porta potty rental cost?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Standard porta potty rentals start at $89/day or $445/week. This includes delivery, weekly servicing, and pickup. Volume discounts are available for 5+ units."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Do you offer volume discounts for multiple porta potties?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Yes! Rent 5-10 units and save 15%, 11-20 units save 25%, and 20+ units save up to 35%. Long-term rentals (30+ days) also qualify for additional discounts."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "What is included in the rental price?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Every rental includes: delivery to your location, professional setup, weekly servicing (cleaning, pumping, sanitizing, restocking toilet paper and hand sanitizer), and pickup when your rental ends. No hidden fees."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Is same-day delivery available and does it cost extra?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Same-day delivery is available when you order before 2 PM local time. Standard delivery is included in the rental price. Remote locations may have a small delivery surcharge."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "How much does a luxury restroom trailer cost for a wedding?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "Luxury restroom trailers range from $500 to $2,500 per day depending on size, features, and amenities. They include climate control, porcelain fixtures, and running water."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "How many porta potties do I need for my event and how much will it cost?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "For events, a general rule is 1 porta potty per 50 guests for a 4-hour event. For construction sites, OSHA requires 1 toilet per 20 workers. Use our free calculator to estimate your needs and call for pricing."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Do you charge extra for servicing or cleaning?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "No. Weekly servicing — including cleaning, pumping, sanitizing, and restocking — is included in the standard rental price. Additional servicing can be arranged at a nominal fee."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "Is there a minimum rental period?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "We offer daily, weekly, and monthly rentals. There is no minimum rental period for standard units. Event rentals can be as short as 1 day. Long-term rentals (30+ days) get the best rates."
            ]
        ]
    ]
];
$productSchemas = [];
foreach ($pricingInfo as $info) {
    $low = $info['daily_label'] ?? '';
    $productSchemas[] = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $info['title'] . ' — ' . $info['short_title'],
        'description' => $info['description'],
        'category' => 'Portable Toilet Rental',
        'offers' => [
            '@type' => 'AggregateOffer',
            'priceCurrency' => 'USD',
            'lowPrice' => preg_match('/\$?([\d,]+)/', $low, $m) ? str_replace(',', '', $m[1]) : '89',
            'offerCount' => '1',
            'availability' => 'https://schema.org/InStock',
            'url' => route('pricing') . '#price-' . $info['key'],
        ],
    ];
}
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($localBusinessSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@foreach($productSchemas as $ps)
<script type="application/ld+json">{!! json_encode($ps, JSON_UNESCAPED_SLASHES) !!}</script>
@endforeach
@endpush

@section('content')

{{-- Trust Banner --}}
<div class="bg-slate-900 text-white py-3">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-center gap-3 md:gap-5 text-center md:text-left text-xs sm:text-sm">
        <div class="flex items-center gap-2">
            <x-icon name="star" class="w-4 h-4 text-amber-400" />
            <span class="font-semibold">4.9/5 (2,000+ Reviews)</span>
        </div>
        <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
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
        <span class="text-slate-800 font-medium">Pricing</span>
    </div>
</nav>

{{-- Hero --}}
<section class="relative py-16 md:py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-amber-500/10 rounded-full blur-3xl"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <div class="inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-400 text-sm font-bold px-4 py-2 rounded-full mb-5">
            <x-icon name="currency-dollar" class="w-4 h-4" />
            TRANSPARENT PRICING — NO HIDDEN FEES
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
            Porta Potty Rental Pricing <span class="text-emerald-400">Starting at $89/day</span>
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">
            Transparent pricing with <strong class="text-white">no hidden fees</strong>.
            All rentals include delivery, setup, weekly servicing, and pickup.
        </p>
        <div class="flex flex-wrap justify-center gap-3 text-sm text-slate-300">
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="currency-dollar" class="w-4 h-4 text-emerald-400" />No Hidden Fees</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="phone" class="w-4 h-4 text-emerald-400" />Free Quotes</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="truck" class="w-4 h-4 text-emerald-400" />Same-Day Delivery</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="cube" class="w-4 h-4 text-emerald-400" />Volume Discounts</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="sparkles" class="w-4 h-4 text-emerald-400" />Weekly Servicing</span>
        </div>
    </div>
</section>

{{-- Price Comparison Table --}}
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Compare Porta Potty Rental Prices</h2>
            <p class="text-lg text-slate-600">All prices include delivery, servicing, and pickup — no hidden fees</p>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="text-left p-4 md:p-5 font-bold">Unit Type</th>
                        <th class="p-4 md:p-5 font-bold text-center">Daily Rate</th>
                        <th class="p-4 md:p-5 font-bold text-center hidden sm:table-cell">Weekly Rate</th>
                        <th class="p-4 md:p-5 font-bold text-center hidden lg:table-cell">Monthly Rate</th>
                        <th class="p-4 md:p-5 font-bold text-center hidden md:table-cell">Best For</th>
                        <th class="p-4 md:p-5 font-bold text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pricingInfo as $i => $info)
                    <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors @if(($info['popular'] ?? false)) bg-amber-50/50 @endif" id="price-{{ $info['key'] }}">
                        <td class="p-4 md:p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                    <x-icon name="{{ $info['icon'] }}" class="w-5 h-5" />
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800">
                                        {{ $info['title'] }}
                                        @if($info['popular'] ?? false)
                                            <span class="text-xs bg-amber-500 text-white px-2 py-0.5 rounded-full ml-1">POPULAR</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-400">per unit</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 md:p-5 text-center">
                            <div class="font-extrabold text-emerald-600 text-base md:text-lg">{{ $info['daily_label'] }}</div>
                            <div class="text-xs text-slate-400">per day</div>
                        </td>
                        <td class="p-4 md:p-5 text-center hidden sm:table-cell">
                            <div class="font-bold text-slate-800">{{ $info['weekly_label'] }}</div>
                            <div class="text-xs text-slate-400">7 days</div>
                        </td>
                        <td class="p-4 md:p-5 text-center hidden lg:table-cell">
                            <div class="font-bold text-slate-800">{{ $info['monthly_label'] }}</div>
                            <div class="text-xs text-slate-400">30 days</div>
                        </td>
                        <td class="p-4 md:p-5 text-center hidden md:table-cell">
                            <span class="text-xs text-slate-600">{{ $info['best_for'] }}</span>
                        </td>
                        <td class="p-4 md:p-5 text-center">
                            <a href="tel:{{ domain_phone_raw() }}"
                               data-tracking-label="pricing-table-{{ $info['key'] }}"
                               class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-400 text-white font-bold text-xs px-3 py-2 rounded-full transition-all hover:scale-105 min-h-[44px] whitespace-nowrap">
                                <x-icon name="phone" class="w-3.5 h-3.5" />
                                <span>Book {{ $info['short_title'] }}</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-center text-sm text-slate-500">
            <x-icon name="check-circle" class="w-4 h-4 text-emerald-500 inline" /> Prices vary by location, quantity, and rental duration. Call for exact pricing in your city.
        </div>
    </div>
</section>

{{-- What's Included Section --}}
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <span class="inline-block text-emerald-600 font-semibold text-sm tracking-wider uppercase mb-3">Every Rental Includes</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">What's Included in Your Rental</h2>
            <p class="text-lg text-slate-600">No surprises. Every rental comes with these standard inclusions.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <x-icon name="truck" class="w-6 h-6" />
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Delivery & Setup</h3>
                <p class="text-sm text-slate-600">Professional delivery and placement included. Same-day delivery available when you order before 2 PM.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <x-icon name="sparkles" class="w-6 h-6" />
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Weekly Servicing</h3>
                <p class="text-sm text-slate-600">Cleaning, pumping, sanitizing, and restocking of toilet paper and hand sanitizer every week.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <x-icon name="shield-check" class="w-6 h-6" />
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Pickup & Removal</h3>
                <p class="text-sm text-slate-600">When your rental ends, we pick up and remove the units. No hidden disposal fees.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <x-icon name="currency-dollar" class="w-6 h-6" />
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Flat-Rate Pricing</h3>
                <p class="text-sm text-slate-600">The price we quote is the price you pay. No fuel surcharges, no service fees, no hidden costs.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <x-icon name="phone" class="w-6 h-6" />
                </div>
                <h3 class="font-bold text-slate-800 mb-2">24/7 Customer Support</h3>
                <p class="text-sm text-slate-600">Real humans answer every call in under 30 seconds. Emergency service available 24/7.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                    <x-icon name="document" class="w-6 h-6" />
                </div>
                <h3 class="font-bold text-slate-800 mb-2">OSHA Compliance</h3>
                <p class="text-sm text-slate-600">All standard units meet OSHA requirements for construction sites. Compliance documentation available.</p>
            </div>
        </div>
    </div>
</section>

{{-- Payment & Cancellation --}}
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Payment, Deposit & Cancellation</h2>
            <p class="text-slate-600">Simple terms. No surprises.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4"><x-icon name="currency-dollar" class="w-6 h-6" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Payment Methods</h3>
                <p class="text-sm text-slate-600">We accept credit cards (Visa, MC, Amex, Discover), debit cards, ACH bank transfers, and company checks. Payment due at delivery for new customers.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4"><x-icon name="shield-check" class="w-6 h-6" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Deposit Policy</h3>
                <p class="text-sm text-slate-600">No deposit required for standard daily and weekly rentals. Long-term projects (30+ days) may require a 25% deposit. Established accounts get net-30 terms.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4"><x-icon name="clock" class="w-6 h-6" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Cancellation Policy</h3>
                <p class="text-sm text-slate-600">Cancel 48+ hours before delivery for full refund. Late cancellations may incur a small delivery truck fee. Same-day orders are non-cancellable once dispatched.</p>
            </div>
        </div>
    </div>
</section>

{{-- Volume Discounts --}}
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-8 md:p-12 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Save with Volume Discounts</h2>
            <p class="text-lg text-emerald-100 mb-8 max-w-2xl mx-auto">Rent 5+ units and save up to 20%. Rent 20+ units and save up to 35%. Long-term rentals (30+ days) get additional discounts.</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="text-3xl font-extrabold mb-1">5-10</div>
                    <div class="text-sm text-emerald-200">Units</div>
                    <div class="text-2xl font-bold text-amber-300 mt-2">Save 15%</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="text-3xl font-extrabold mb-1">11-20</div>
                    <div class="text-sm text-emerald-200">Units</div>
                    <div class="text-2xl font-bold text-amber-300 mt-2">Save 25%</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="text-3xl font-extrabold mb-1">20+</div>
                    <div class="text-sm text-emerald-200">Units</div>
                    <div class="text-2xl font-bold text-amber-300 mt-2">Save 35%</div>
                </div>
            </div>
            <a href="tel:{{ domain_phone_raw() }}"
               data-tracking-label="pricing-volume"
               class="inline-flex items-center gap-3 bg-white text-emerald-600 font-bold text-lg py-4 px-8 rounded-full shadow-xl hover:bg-amber-50 transition-all min-h-[44px]">
                <x-icon name="phone" class="w-5 h-5" />
                Call for Volume Pricing
            </a>
        </div>
    </div>
</section>

{{-- Pricing Factors --}}
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Factors That Affect Your Quote</h2>
            <p class="text-slate-600">Understanding pricing helps you get the best value for your project</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            @foreach($factors as $factor)
                <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all">
                    <h3 class="font-bold text-slate-800 mb-2">{{ $factor['title'] }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $factor['description'] }}</p>
                </div>
            @endforeach
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
                    'q' => 'How much does a standard porta potty rental cost?',
                    'a' => 'Standard porta potty rentals start at $89/day or $445/week. This includes delivery, setup, weekly servicing (cleaning, pumping, restocking), and pickup. Prices vary by location and quantity — call for an exact quote.',
                ],
                [
                    'q' => 'Do you offer volume discounts for multiple porta potties?',
                    'a' => 'Yes! Rent 5-10 units and save 15%, 11-20 units save 25%, and 20+ units save up to 35%. Long-term rentals (30+ days) also qualify for additional discounts. Call us to discuss your project.',
                ],
                [
                    'q' => 'What is included in the rental price?',
                    'a' => 'Every rental includes: delivery to your location, professional setup, weekly servicing (cleaning, pumping, sanitizing, restocking toilet paper and hand sanitizer), and pickup when your rental ends. No hidden fees — the price we quote is the price you pay.',
                ],
                [
                    'q' => 'Is same-day delivery available and does it cost extra?',
                    'a' => 'Same-day delivery is available when you order before 2 PM local time. Standard delivery is included in the rental price. Remote locations may have a small delivery surcharge.',
                ],
                [
                    'q' => 'How much does a luxury restroom trailer cost for a wedding?',
                    'a' => 'Luxury restroom trailers range from $500 to $2,500 per day depending on the size and features. A typical wedding trailer includes climate control, flushing toilets, vanity sinks, and elegant interiors. Call for event-specific pricing.',
                ],
                [
                    'q' => 'How many porta potties do I need for my event and how much will it cost?',
                    'a' => 'For events, a general rule is 1 porta potty per 50 guests for a 4-hour event. For construction sites, OSHA requires 1 toilet per 20 workers. Use our free calculator to estimate your needs and call for pricing.',
                ],
                [
                    'q' => 'Do you charge extra for servicing or cleaning?',
                    'a' => 'No. Weekly servicing — including cleaning, pumping, sanitizing, and restocking — is included in the standard rental price. Additional servicing can be arranged at a nominal fee.',
                ],
                [
                    'q' => 'Is there a minimum rental period?',
                    'a' => 'We offer daily, weekly, and monthly rentals. There is no minimum rental period for standard units. Event rentals can be as short as 1 day. Long-term rentals (30+ days) get the best rates.',
                ],
            ];
        @endphp

        <div class="space-y-3">
            @foreach($pricingFaqs as $faq)
                <details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
                    <summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-emerald-600 transition list-none">
                        <span>{{ $faq['q'] }}</span>
                        <span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-emerald-100 w-8 h-8 rounded-full flex items-center justify-center">+</span>
                    </summary>
                    <div class="px-5 pb-5 text-slate-600 leading-relaxed">
                        <p>{!! str_replace(
                        'Use our free calculator',
                        '<a href="'.route('calculator').'" class="text-emerald-600 hover:underline font-medium">Use our free calculator</a>',
                        $faq['a']
                    ) !!}</p>
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-balance">Get your free, no-obligation quote</h2>
        <p class="text-lg text-slate-400 mb-3">
            Call us for a <strong class="text-white">free quote</strong> tailored to your project. We'll help you find the best pricing for porta potty rental.
        </p>
        <p class="text-slate-400 mb-8 text-sm">
            Serving construction sites, events, weddings, and more across the USA.
        </p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="pricing-final"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400
                  text-white text-2xl md:text-3xl font-bold py-5 px-10
                  rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30
                  transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7 md:w-8 md:h-8" />
            {{ domain_phone_display() }}
        </a>
        <p class="mt-6 text-slate-400 text-sm">No obligation · No hidden fees · Answered in under 30 seconds</p>
    </div>
</section>

{{-- Navigation to other pages --}}
<section class="py-8 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto flex flex-wrap justify-center gap-4 text-sm">
        <a href="{{ route('home') }}" class="text-amber-600 hover:text-amber-700 font-medium">← Back to Home</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('calculator') }}" class="text-amber-600 hover:text-amber-700 font-medium">Units Calculator</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('pillar.page') }}" class="text-amber-600 hover:text-amber-700 font-medium">Complete Guide</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('services') }}" class="text-amber-600 hover:text-amber-700 font-medium">View All Services</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('locations') }}" class="text-amber-600 hover:text-amber-700 font-medium">Find Your City</a>
    </div>
</section>

{{-- Mobile Sticky CTA --}}
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        <span>Call Now — Starting at $89/day</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection