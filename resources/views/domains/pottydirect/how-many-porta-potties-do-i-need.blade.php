@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', 'How Many Porta Potties Do I Need? — Event & Construction Guide | PottyDirect')
@section('meta_description', 'Determine exactly how many porta potties you need for your event or construction site. OSHA rules, event guides, and reference tables. Call for same-day delivery.')
@section('canonical', route('how-many.page'))

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {"@type": "Question", "name": "How many porta potties do I need for 100 guests?", "acceptedAnswer": {"@type": "Answer", "text": "For 100 guests at a 4-hour event, you need 2 standard porta potties. If alcohol is served, add 1 more for a total of 3. For an all-day event (8+ hours), plan for 3-4 units."}},
        {"@type": "Question", "name": "How many porta potties are required for a construction site?", "acceptedAnswer": {"@type": "Answer", "text": "OSHA requires 1 toilet per 20 workers for construction sites. A site with 40 workers needs at least 2 units. For 100 workers, you need 5 units. Additional units recommended for larger sites."}},
        {"@type": "Question", "name": "What is the formula for calculating porta potty needs?", "acceptedAnswer": {"@type": "Answer", "text": "For events: 1 unit per 50 guests for 4 hours. Add 20% if alcohol is served. For construction: 1 unit per 20 workers per OSHA standards. For longer events, increase by 50%."}},
        {"@type": "Question", "name": "Do I need more porta potties for a wedding than a regular party?", "acceptedAnswer": {"@type": "Answer", "text": "Weddings typically need more units because guests stay longer and dress up. For 100 wedding guests, plan 3-4 units or a luxury restroom trailer. Consider deluxe units or trailers for better guest experience."}},
        {"@type": "Question", "name": "How many porta potties for a music festival with 1,000 attendees?", "acceptedAnswer": {"@type": "Answer", "text": "For 1,000 festival attendees, you need 20-25 standard porta potties minimum. Add hand washing stations every 4 units. For multi-day festivals, increase to 30 units and arrange daily servicing."}}
    ]
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}"},
        {"@type": "ListItem", "position": 2, "name": "How Many Porta Potties", "item": "{{ route('how-many.page') }}"}
    ]
}
</script>
@endpush

@section('content')

<section class="relative py-16 md:py-20 overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-600 to-teal-700">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;><g fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.1&quot;><path d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/></g></g></svg>')"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 text-xs sm:text-sm text-emerald-200 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3" />
            <span class="text-white">How Many Porta Potties?</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-white/15 text-white text-sm font-bold px-4 py-2 rounded-full mb-5 backdrop-blur">
            <x-icon name="calculator" class="w-4 h-4" />
            FREE PLANNING GUIDE
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5 leading-tight">
            How Many Porta Potties Do I Need?
            <span class="block text-2xl md:text-3xl mt-3 text-emerald-200">OSHA Requirements + Event Guide + Quick Reference Tables</span>
        </h1>
        <p class="text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto mb-8">
            Whether you're planning an event or managing a construction site, get the exact number.
            No math needed. <strong class="text-white">Free consultation included.</strong>
        </p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="howmany-hero"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl md:text-2xl font-bold py-4 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-6 h-6" />
            {{ domain_phone_display() }}
        </a>
        <p class="mt-4 text-sm text-emerald-200">Free expert consultation · No obligation</p>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-6">Quick Reference: How Many Porta Potties You Need</h2>
        <p class="text-center text-slate-600 mb-8">Simple formulas for events and construction sites</p>

        <div class="grid md:grid-cols-2 gap-8 mb-10">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 border border-blue-200">
                <h3 class="text-2xl font-bold text-slate-800 mb-4">🎉 For Events</h3>
                <div class="space-y-4">
                    @php
                    $eventRows = [
                        ['guests' => '50', '4hr' => '1-2', '8hr' => '2', 'alcohol' => '2'],
                        ['guests' => '100', '4hr' => '2', '8hr' => '3', 'alcohol' => '3-4'],
                        ['guests' => '200', '4hr' => '4', '8hr' => '5-6', 'alcohol' => '5-6'],
                        ['guests' => '500', '4hr' => '10', '8hr' => '12-15', 'alcohol' => '12-14'],
                        ['guests' => '1000', '4hr' => '20', '8hr' => '25-30', 'alcohol' => '25-28'],
                    ];
                    @endphp
                    <table class="w-full text-sm bg-white rounded-xl overflow-hidden">
                        <thead>
                            <tr class="bg-blue-600 text-white">
                                <th class="p-3 text-left font-bold">Guests</th>
                                <th class="p-3 font-bold">4-Hour Event</th>
                                <th class="p-3 font-bold">All Day (8hr+)</th>
                                <th class="p-3 font-bold">With Alcohol</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventRows as $row)
                            <tr class="border-t border-blue-100">
                                <td class="p-3 font-bold text-slate-800">{{ $row['guests'] }}</td>
                                <td class="p-3 text-center">{{ $row['4hr'] }}</td>
                                <td class="p-3 text-center">{{ $row['8hr'] }}</td>
                                <td class="p-3 text-center">{{ $row['alcohol'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-8 border border-amber-200">
                <h3 class="text-2xl font-bold text-slate-800 mb-4">🏗️ For Construction Sites</h3>
                <div class="space-y-4">
                    @php
                    $constRows = [
                        ['workers' => '10', 'units' => '1', 'note' => 'OSHA minimum'],
                        ['workers' => '20', 'units' => '1', 'note' => 'Minimum per OSHA'],
                        ['workers' => '50', 'units' => '3', 'note' => 'Recommended: 4'],
                        ['workers' => '100', 'units' => '5', 'note' => 'Recommended: 7'],
                        ['workers' => '200', 'units' => '10', 'note' => 'With hand wash stations'],
                    ];
                    @endphp
                    <table class="w-full text-sm bg-white rounded-xl overflow-hidden">
                        <thead>
                            <tr class="bg-amber-600 text-white">
                                <th class="p-3 text-left font-bold">Workers</th>
                                <th class="p-3 font-bold">Required</th>
                                <th class="p-3 font-bold">Recommendation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($constRows as $row)
                            <tr class="border-t border-amber-100">
                                <td class="p-3 font-bold text-slate-800">{{ $row['workers'] }}</td>
                                <td class="p-3 text-center">{{ $row['units'] }}</td>
                                <td class="p-3 text-center">{{ $row['note'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 rounded-2xl p-8 text-center">
            <h3 class="text-2xl font-bold text-white mb-3">Need help calculating for your specific situation?</h3>
            <p class="text-slate-400 mb-6">We'll help you determine the exact number of units — free, no obligation.</p>
            <a href="tel:{{ domain_phone_raw() }}"
               data-tracking-label="howmany-cta"
               class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white font-bold text-lg py-3 px-8 rounded-full shadow-lg shadow-amber-500/30 transition hover:scale-[1.02] min-h-[44px]">
                <x-icon name="phone" class="w-5 h-5" />
                Call for Expert Advice: {{ domain_phone_display() }}
            </a>
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-6">OSHA Porta Potty Requirements</h2>
        <p class="text-slate-600 mb-6">Federal OSHA standard 1926.51 requires employers to provide sanitation facilities for construction workers.</p>
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <ul class="space-y-3 text-sm text-slate-600">
                <li class="flex items-start gap-3"><span class="font-bold text-emerald-600 flex-shrink-0">•</span> <strong>20 or fewer workers:</strong> 1 toilet required</li>
                <li class="flex items-start gap-3"><span class="font-bold text-emerald-600 flex-shrink-0">•</span> <strong>20 or more workers:</strong> 1 toilet per 20 workers (e.g., 40 workers = 2 toilets)</li>
                <li class="flex items-start gap-3"><span class="font-bold text-emerald-600 flex-shrink-0">•</span> <strong>200+ workers:</strong> 1 toilet per 40 workers</li>
                <li class="flex items-start gap-3"><span class="font-bold text-emerald-600 flex-shrink-0">•</span> <strong>Hand washing:</strong> OSHA requires hand washing facilities on sites with running water or hand sanitizer</li>
                <li class="flex items-start gap-3"><span class="font-bold text-emerald-600 flex-shrink-0">•</span> <strong>ADA access:</strong> At least one accessible unit required for sites with 20+ workers</li>
            </ul>
        </div>
        <div class="mt-6 text-center">
            <a href="{{ route('osha.guide') }}" class="text-emerald-600 hover:text-emerald-700 font-medium transition">View full OSHA compliance guide →</a>
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-4 text-center">Planning FAQs</h2>
        <p class="text-center text-slate-600 mb-8 max-w-2xl mx-auto">Common questions about calculating porta potty quantities for events and construction sites.</p>
        @php
        $planningFaqs = [
            ['q' => 'What happens if I don\'t rent enough porta potties?', 'a' => 'Too few units leads to long lines, unsanitary conditions, and unhappy guests or workers. For construction sites, OSHA can fine employers for inadequate sanitation. For events, guests remember the bathroom situation.'],
            ['q' => 'Should I rent extra porta potties just in case?', 'a' => 'Yes. It\'s always better to have 1-2 extra units than to run short. The cost of an extra standard unit ($89/day) is minimal compared to the inconvenience of insufficient restrooms.'],
            ['q' => 'How do luxury restroom trailers affect the count?', 'a' => 'Luxury trailers with multiple stalls serve more people. A 2-stall trailer handles about the same as 2-3 standard units. A 4-stall trailer handles 4-5 standard units worth of capacity.'],
            ['q' => 'Do I need hand washing stations too?', 'a' => 'OSHA requires hand washing facilities on construction sites. For events, hand washing stations are highly recommended — especially for food events. We offer portable sink rentals starting at $75/week.'],
            ['q' => 'How many units for a construction site with 150 workers?', 'a' => 'OSHA requires 1 toilet per 20 workers for sites with 20-200 workers. For 150 workers, you need 8 units minimum. We recommend 10 units for optimal efficiency. Add hand wash stations every 4 units.'],
        ];
        @endphp
        <div class="space-y-3">
            @foreach($planningFaqs as $faq)
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

@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50"><div class="max-w-5xl mx-auto"><h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2><div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">@foreach($testimonials as $t)<div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all"><div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">@for($i = 0; $i < $t['rating']; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor</div><p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p><div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100"><div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div><div><p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>@if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif</div></div></div>@endforeach</div></div></section>
@endif

<section class="py-12 md:py-16 px-4">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Why Choose PottyDirect for Quantity Planning</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $whyHowMany = [
                ['icon' => 'users', 'title' => 'Expert Guidance Free of Charge', 'desc' => 'Not sure how many you need? Call us for a free consultation. Our team calculates your exact needs based on guest count, duration, event type, and alcohol service — no obligation.'],
                ['icon' => 'calculator', 'title' => 'Industry-Standard Formulas', 'desc' => 'We follow OSHA standards for construction sites and established event industry guidelines for gatherings. Our recommendations are backed by decades of combined industry experience.'],
                ['icon' => 'building', 'title' => 'OSHA Compliance Expertise', 'desc' => 'Stay compliant with federal OSHA 1926.51 sanitation requirements. We know the exact ratios for construction sites of any size and can help you avoid costly fines.'],
                ['icon' => 'truck', 'title' => 'Flexible Scaling for Any Event', 'desc' => 'Whether you need 1 unit or 100, we have the inventory and logistics to deliver. Our team helps you scale up or down based on your final guest count and event duration.'],
            ];
            @endphp
            @foreach($whyHowMany as $w)
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
            $relatedHowMany = [
                ['icon' => 'calculator', 'title' => 'Interactive Calculator', 'url' => route('calculator'), 'desc' => 'Calculate units for your event'],
                ['icon' => 'shield', 'title' => 'OSHA Compliance Guide', 'url' => route('osha.guide'), 'desc' => 'Full OSHA requirements explained'],
                ['icon' => 'building', 'title' => 'Construction Rentals', 'url' => route('construction.landing'), 'desc' => 'OSHA-compliant site solutions'],
                ['icon' => 'currency-dollar', 'title' => 'Porta Potty Cost Guide', 'url' => route('cost.page'), 'desc' => 'Pricing for every unit type'],
                ['icon' => 'book-open', 'title' => 'Complete Rental Guide', 'url' => route('pillar.page'), 'desc' => 'Everything about porta potty rental'],
                ['icon' => 'document', 'title' => 'Porta Potty FAQ', 'url' => route('faq'), 'desc' => 'Answers to common questions'],
            ];
            @endphp
            @foreach($relatedHowMany as $r)
            <a href="{{ $r['url'] }}" class="bg-white border border-slate-200 rounded-xl p-5 hover:shadow-lg transition-all group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
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
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Expert Tips for Calculating Porta Potty Needs</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $tipsHowMany = [
                ['icon' => 'lightning', 'title' => 'Always Round Up, Never Down', 'desc' => 'It\'s always better to have one extra unit than one too few. An extra standard unit costs only $89/day — far less than the inconvenience of long lines or OSHA violations.'],
                ['icon' => 'users', 'title' => 'Factor in Event Duration', 'desc' => 'For events longer than 4 hours, increase your unit count by 50%. An all-day festival needs significantly more units than a 2-hour ceremony to maintain hygiene and comfort.'],
                ['icon' => 'water-drop', 'title' => 'Account for Alcohol Service', 'desc' => 'Alcohol increases restroom usage by 20-30%. Add 1 extra unit for every 50 guests if you\'re serving alcohol. For open-bar events, consider adding 40% more capacity.'],
                ['icon' => 'sparkles', 'title' => 'Consider Luxury Trailers in Your Count', 'desc' => 'A 2-stall luxury restroom trailer replaces 2-3 standard units. For upscale events, trailers provide a better guest experience while serving the same capacity as multiple standard units.'],
            ];
            @endphp
            @foreach($tipsHowMany as $t)
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
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

<section class="py-16 md:py-20 px-4 bg-gradient-to-br from-emerald-600 to-teal-600 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Get a Personalized Recommendation</h2>
        <p class="text-lg text-emerald-100 mb-3">Call and tell us about your project. We'll tell you exactly what you need.</p>
        <p class="text-emerald-200 mb-8 text-sm">Free consultation · No obligation · Same-day delivery available</p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="howmany-final"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl md:text-3xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7" />
            {{ domain_phone_display() }}
        </a>
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100"><div class="max-w-4xl mx-auto text-center"><h2 class="text-2xl font-bold text-slate-800 mb-6">Related Resources</h2><div class="flex flex-wrap justify-center gap-4"><a href="{{ route('pricing') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Pricing Guide</a><a href="{{ route('calculator') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Units Calculator</a><a href="{{ route('comparison') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Unit Comparison</a><a href="{{ route('osha.guide') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">OSHA Guide</a><a href="{{ route('construction.landing') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Construction Rental</a><a href="{{ route('faq') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">FAQ</a></div></div></section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-emerald-500/40 ring-4 ring-emerald-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <x-icon name="phone" class="w-6 h-6" />
        <span>Get Your Free Estimate — {{ domain_phone_display() }}</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection
