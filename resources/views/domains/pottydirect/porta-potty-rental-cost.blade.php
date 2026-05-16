@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', 'Porta Potty Rental Cost Guide 2026 | PottyDirect')
@section('meta_description', 'Complete guide to porta potty costs: Standard from $89/day, Deluxe $150/day, ADA $125/day, Luxury from $500/day. No hidden fees. Real pricing.')
@section('canonical', route('cost.page'))

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {"@type": "Question", "name": "How much does a standard porta potty cost per day?", "acceptedAnswer": {"@type": "Answer", "text": "Standard porta potty rentals cost $89 to $175 per day in most markets. Weekly rates range from $445 to $875. All rentals include delivery, setup, weekly servicing, and pickup."}},
        {"@type": "Question", "name": "What is the cheapest porta potty rental option?", "acceptedAnswer": {"@type": "Answer", "text": "Standard porta potty units are the most affordable option starting at $89/day. They are OSHA compliant and include weekly servicing. Volume discounts available for 5+ units."}},
        {"@type": "Question", "name": "How much does a luxury restroom trailer cost per day?", "acceptedAnswer": {"@type": "Answer", "text": "Luxury restroom trailers range from $500 to $2,500 per day depending on size, amenities, and location. A typical 2-stall wedding trailer averages $895-$1,500 per day."}},
        {"@type": "Question", "name": "Are there hidden fees in porta potty rental pricing?", "acceptedAnswer": {"@type": "Answer", "text": "No. PottyDirect offers transparent flat-rate pricing with no hidden fees, fuel surcharges, or service upcharges. The price quoted is the price you pay. Call for a guaranteed quote."}},
        {"@type": "Question", "name": "Do you get a discount for renting multiple porta potties?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. Volume discounts apply at 5+ units (save 15%), 11+ units (save 25%), and 20+ units (save up to 35%). Long-term rentals of 30+ days get additional savings."}},
        {"@type": "Question", "name": "What is included in the porta potty rental price?", "acceptedAnswer": {"@type": "Answer", "text": "Every rental includes: delivery, professional setup, weekly servicing (cleaning, pumping, sanitizing, restocking supplies), and pickup. No extra charges."}}
    ]
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}"},
        {"@type": "ListItem", "position": 2, "name": "Pricing", "item": "{{ route('pricing') }}"},
        {"@type": "ListItem", "position": 3, "name": "Porta Potty Rental Cost", "item": "{{ route('cost.page') }}"}
    ]
}
</script>
@endpush

@section('content')

<section class="relative py-16 md:py-20 overflow-hidden bg-slate-900">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
    <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 text-xs sm:text-sm text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3" />
            <a href="{{ route('pricing') }}" class="hover:text-white transition">Pricing</a>
            <x-icon name="chevron-right" class="w-3 h-3" />
            <span class="text-white">Porta Potty Cost</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-400 text-sm font-bold px-4 py-2 rounded-full mb-5">
            <x-icon name="currency-dollar" class="w-4 h-4" />
            TRANSPARENT PRICING
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5 leading-tight">
            How Much Does a Porta Potty Cost?
            <span class="block text-emerald-400 text-2xl md:text-3xl mt-3">2026 Pricing Guide</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-300 max-w-2xl mx-auto mb-8">
            Complete breakdown of porta potty rental prices. Standard units from <strong class="text-white">$89/day</strong>.
            No hidden fees. Same-day delivery available.
        </p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="cost-hero"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl md:text-2xl font-bold py-4 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-6 h-6" />
            {{ domain_phone_display() }}
        </a>
        <p class="mt-4 text-sm text-slate-400">Real humans answer in under 15 seconds</p>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Porta Potty Rental Cost by Unit Type</h2>
            <p class="text-lg text-slate-600">All prices include delivery, weekly servicing, and pickup</p>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="text-left p-4 md:p-5 font-bold">Unit Type</th>
                        <th class="p-4 md:p-5 font-bold text-center">Daily Rate</th>
                        <th class="p-4 md:p-5 font-bold text-center hidden sm:table-cell">Weekly Rate</th>
                        <th class="p-4 md:p-5 font-bold text-center hidden lg:table-cell">Monthly Rate</th>
                        <th class="p-4 md:p-5 font-bold text-center">Call to Book</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $costRows = [
                        ['icon' => 'building', 'name' => 'Standard Porta Potty', 'daily' => '$89 – $175', 'weekly' => '$445 – $875', 'monthly' => '$1,335 – $2,625', 'popular' => true],
                        ['icon' => 'water-drop', 'name' => 'Deluxe Flushable Unit', 'daily' => '$150 – $275', 'weekly' => '$750 – $1,375', 'monthly' => '$2,250 – $4,125', 'popular' => false],
                        ['icon' => 'accessibility', 'name' => 'ADA Accessible Unit', 'daily' => '$125 – $250', 'weekly' => '$625 – $1,250', 'monthly' => '$1,875 – $3,750', 'popular' => false],
                        ['icon' => 'sparkles', 'name' => 'Luxury Restroom Trailer', 'daily' => '$500 – $2,500', 'weekly' => '$2,500 – $12,500', 'monthly' => '$7,500 – $37,500', 'popular' => false],
                        ['icon' => 'shower', 'name' => 'Portable Shower Unit', 'daily' => '$150 – $400', 'weekly' => '$750 – $2,000', 'monthly' => '$2,250 – $6,000', 'popular' => false],
                        ['icon' => 'building', 'name' => 'Construction Site Package', 'daily' => 'From $89/unit', 'weekly' => 'Volume pricing', 'monthly' => 'Up to 40% off', 'popular' => false],
                    ];
                    @endphp
                    @foreach($costRows as $row)
                    <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors @if($row['popular']) bg-amber-50/50 @endif">
                        <td class="p-4 md:p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <x-icon name="{{ $row['icon'] }}" class="w-5 h-5" />
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800">
                                        {{ $row['name'] }}
                                        @if($row['popular'])
                                            <span class="text-xs bg-amber-500 text-white px-2 py-0.5 rounded-full ml-1">MOST POPULAR</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 md:p-5 text-center font-extrabold text-emerald-600 text-base md:text-lg">{{ $row['daily'] }}</td>
                        <td class="p-4 md:p-5 text-center hidden sm:table-cell font-bold text-slate-800">{{ $row['weekly'] }}</td>
                        <td class="p-4 md:p-5 text-center hidden lg:table-cell font-bold text-slate-800">{{ $row['monthly'] }}</td>
                        <td class="p-4 md:p-5 text-center">
                            <a href="tel:{{ domain_phone_raw() }}"
                               data-tracking-label="cost-table-{{ strtolower(str_replace(' ', '-', $row['name'])) }}"
                               class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-400 text-white font-bold text-xs px-4 py-2.5 rounded-full transition-all hover:scale-105 min-h-[44px]">
                                <x-icon name="phone" class="w-3.5 h-3.5" />
                                Get This Price
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 grid md:grid-cols-3 gap-4">
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 text-center">
                <div class="text-3xl font-extrabold text-emerald-600 mb-1">$89</div>
                <div class="text-sm text-slate-600">Lowest daily rate</div>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center">
                <div class="text-3xl font-extrabold text-blue-600 mb-1">35%</div>
                <div class="text-sm text-slate-600">Max volume discount</div>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-center">
                <div class="text-3xl font-extrabold text-amber-600 mb-1">40%</div>
                <div class="text-sm text-slate-600">Long-term savings</div>
            </div>
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Factors That Affect Porta Potty Cost</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $costFactors = [
                ['title' => 'Number of Units', 'desc' => 'More units = lower per-unit price. Volume discounts start at 5 units. Rent 20+ units and save up to 35%.'],
                ['title' => 'Rental Duration', 'desc' => 'Longer rentals get better rates. Monthly rentals save up to 40% compared to daily rates. Weekly servicing included.'],
                ['title' => 'Unit Type', 'desc' => 'Standard units start at $89/day. Deluxe, ADA, and luxury trailers have higher rates due to added features and amenities.'],
                ['title' => 'Location & Distance', 'desc' => 'Most locations within 50 miles include free delivery. Remote areas may have a small delivery surcharge.'],
                ['title' => 'Servicing Frequency', 'desc' => 'Weekly servicing included. Events requiring daily servicing or extra cleanings may have additional costs.'],
                ['title' => 'Season & Demand', 'desc' => 'Peak season (summer) may have higher demand. Book early for best rates. Same-day delivery subject to availability.'],
            ];
            @endphp
            @foreach($costFactors as $factor)
            <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all">
                <h3 class="font-bold text-slate-800 mb-2">{{ $factor['title'] }}</h3>
                <p class="text-slate-600 text-sm leading-relaxed">{{ $factor['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-4 text-center">Frequently Asked Questions</h2>
        <p class="text-center text-slate-600 mb-8 max-w-2xl mx-auto">Still have questions about porta potty pricing? We've got answers to the most common ones below.</p>
        @php
        $costFaqs = [
            ['q' => 'How much does a standard porta potty cost vs a luxury trailer?', 'a' => 'Standard porta potties range from $89-$175/day while luxury restroom trailers range from $500-$2,500/day. The price difference reflects amenities: luxury trailers include climate control, flushing toilets, running water, and elegant interiors.'],
            ['q' => 'Is there a minimum rental period?', 'a' => 'No minimum for standard units — rent for 1 day or 1 year. Daily, weekly, and monthly rates available. Long-term rentals (30+ days) get the best per-day rates.'],
            ['q' => 'How many porta potties do I need and how much will it cost?', 'a' => 'For construction sites, OSHA requires 1 toilet per 20 workers. For events, plan 1 unit per 50 guests. A typical construction site with 20 workers costs $89-$175/day for 1 unit. Use our calculator for precise estimates.'],
            ['q' => 'Do your prices include delivery and setup?', 'a' => 'Yes. Every rental includes delivery, professional setup, weekly servicing, and pickup. The price you see is the price you pay — no hidden fees, fuel surcharges, or service upcharges.'],
            ['q' => 'Can I get a same-day porta potty delivery?', 'a' => 'Yes. Order by 2PM local time for same-day delivery subject to availability. Call to check real-time inventory in your area.'],
            ['q' => 'What payment methods do you accept?', 'a' => 'We accept all major credit cards (Visa, Mastercard, Amex), checks, and offer net-30 terms for qualified contractors.'],
        ];
        @endphp
        <div class="space-y-3">
            @foreach($costFaqs as $faq)
            <details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
                <summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-emerald-600 transition list-none">
                    <span>{{ $faq['q'] }}</span>
                    <span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-emerald-100 w-8 h-8 rounded-full flex items-center justify-center">+</span>
                </summary>
                <div class="px-5 pb-5 text-slate-600 leading-relaxed"><p>{{ $faq['a'] }}</p></div>
            </details>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Why Choose PottyDirect for Your Rental</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $whyCost = [
                ['icon' => 'currency-dollar', 'title' => 'Transparent Flat-Rate Pricing', 'desc' => 'No hidden fees, fuel surcharges, or service upcharges. The price we quote is the price you pay — guaranteed. Every rental includes delivery, setup, servicing, and pickup.'],
                ['icon' => 'badge-check', 'title' => 'Volume Discounts Up to 35%', 'desc' => 'Save 15% on 5+ units, 25% on 11+ units, and up to 35% on 20+ units. Long-term rentals of 30+ days get additional savings on top of volume pricing.'],
                ['icon' => 'truck', 'title' => 'Same-Day Delivery Available', 'desc' => 'Order by 2PM local time for same-day delivery subject to availability. Our fleet covers most metro areas with rapid dispatch and professional setup included.'],
                ['icon' => 'star', 'title' => 'Best Price Guarantee', 'desc' => 'Found a lower price from a competitor? We\'ll match or beat it. We\'re confident our pricing is the most competitive in the market — with no gimmicks or fine print.'],
            ];
            @endphp
            @foreach($whyCost as $w)
            <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-lg transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <x-icon name="{{ $w['icon'] }}" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">{{ $w['title'] }}</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $w['desc'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Related Resources</h2>
        <div class="grid md:grid-cols-3 gap-4">
            @php
            $relatedCost = [
                ['icon' => 'currency-dollar', 'title' => 'Full Pricing Guide', 'url' => route('pricing'), 'desc' => 'Complete pricing for all unit types'],
                ['icon' => 'calculator', 'title' => 'Units Calculator', 'url' => route('calculator'), 'desc' => 'Calculate how many you need'],
                ['icon' => 'building', 'title' => 'Compare Unit Types', 'url' => route('comparison'), 'desc' => 'Standard vs Deluxe vs Luxury'],
                ['icon' => 'book-open', 'title' => 'Complete Rental Guide', 'url' => route('pillar.page'), 'desc' => 'Everything about porta potty rental'],
                ['icon' => 'document', 'title' => 'Porta Potty FAQ', 'url' => route('faq'), 'desc' => 'Answers to common questions'],
                ['icon' => 'sparkles', 'title' => 'Wedding Rentals', 'url' => route('wedding'), 'desc' => 'Luxury options for events'],
            ];
            @endphp
            @foreach($relatedCost as $r)
            <a href="{{ $r['url'] }}" class="bg-white border border-slate-200 rounded-xl p-5 hover:shadow-lg transition-all group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <x-icon name="{{ $r['icon'] }}" class="w-5 h-5" />
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition">{{ $r['title'] }}</h3>
                </div>
                <p class="text-sm text-slate-600">{{ $r['desc'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Expert Tips for Saving on Porta Potty Rentals</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $tipsCost = [
                ['icon' => 'lightning', 'title' => 'Book Early for Best Rates', 'desc' => 'Rates are lowest when you book 1-2 weeks in advance. Last-minute bookings may have limited availability and higher demand pricing. Same-day delivery is available but subject to inventory.'],
                ['icon' => 'building', 'title' => 'Choose the Right Unit Type', 'desc' => 'Don\'t overpay for features you don\'t need. Standard units at $89/day are perfect for construction and casual events. Save luxury trailers for weddings and VIP events.'],
                ['icon' => 'users', 'title' => 'Maximize Volume Discounts', 'desc' => 'The sweet spot is 11+ units where you save 25%. If you need 8-10 units, consider rounding up to 11. The extra units cost less per day thanks to the volume discount tier.'],
                ['icon' => 'calendar', 'title' => 'Compare Weekly vs Daily Rates', 'desc' => 'For rentals longer than 5 days, weekly rates are almost always cheaper than daily. Monthly rates save up to 40% compared to daily pricing. Always ask about long-term discounts.'],
            ];
            @endphp
            @foreach($tipsCost as $t)
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <x-icon name="{{ $t['icon'] }}" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 mb-2">{{ $t['title'] }}</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $t['desc'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Testimonials --}}
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Get Your Exact Price in 30 Seconds</h2>
        <p class="text-lg text-slate-400 mb-3">Call now for a free, no-obligation quote tailored to your project.</p>
        <p class="text-slate-400 mb-8 text-sm">Same-day delivery · No hidden fees · Real humans answer</p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="cost-final"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl md:text-3xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7 md:w-8 md:h-8" />
            {{ domain_phone_display() }}
        </a>
    </div>
</section>

<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <x-icon name="phone" class="w-6 h-6" />
        <span>Call Now — Starting at $89/day</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection
