@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', 'Free Porta Potty Calculator | How Many Units?')
@section('meta_description', 'Free porta potty calculator: Find how many portable toilets you need for your event or construction site. OSHA-compliant recommendations. Instant results.')
@section('canonical', route('calculator'))

@push('schema')
@php
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        [
            "@type" => "Question",
            "name" => "How many porta potties do I need for a construction site?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "OSHA requires a minimum of 1 toilet per 20 workers for construction sites. For example, a crew of 100 workers needs at least 5 porta potties."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "How many porta potties do I need for a wedding?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "For weddings, plan 1 porta potty per 50 guests for events under 4 hours. If alcohol is served, increase by 20%. Consider adding deluxe or ADA units for guest comfort."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "How many portable toilets do I need for an event?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "For short events (under 4 hours), 1 unit per 50 guests. For longer events, 1 unit per 25 guests. Increase by 20% if alcohol is served."
            ]
        ],
        [
            "@type" => "Question",
            "name" => "How many porta potties do I need for a festival?",
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => "For festivals and large gatherings, plan 1 unit per 50 attendees for a 4-hour event. Add more units for multi-day events or when alcohol is served."
            ]
        ]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@php
$breadcrumbSchema = [
    "@context" => "https://schema.org",
    "@type" => "BreadcrumbList",
    "itemListElement" => [
        ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => route('home')],
        ["@type" => "ListItem", "position" => 2, "name" => "Porta Potty Calculator", "item" => route('calculator')]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

{{-- Hero --}}
<section class="relative py-16 md:py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-slate-400 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3 sm:w-4 sm:h-4" />
            <span class="text-white">Porta Potty Calculator</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-400 text-sm font-bold px-4 py-2 rounded-full mb-5">
            <x-icon name="calculator" class="w-4 h-4" />
            FREE CALCULATOR TOOL
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
            How Many Porta Potties <span class="text-emerald-400">Do I Need?</span>
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">
            Use our free calculator to determine the exact number of portable toilets for your
            <strong class="text-white">construction site, event, wedding, or gathering</strong>.
            OSHA-compliant recommendations instantly.
        </p>
        <div class="flex flex-wrap justify-center gap-3 text-sm text-slate-300">
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="check-circle" class="w-4 h-4 text-emerald-400" />OSHA Compliant</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="lightning" class="w-4 h-4 text-emerald-400" />Instant Results</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="phone" class="w-4 h-4 text-emerald-400" />Free Quotes</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="currency-dollar" class="w-4 h-4 text-emerald-400" />Starting at $89/day</span>
        </div>
    </div>
</section>

{{-- Calculator Section --}}
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <x-units-calculator />
    </div>
</section>

{{-- Embed Section --}}
<section class="py-12 md:py-16 px-4 bg-slate-50 border-t border-slate-200">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <span class="inline-block text-emerald-600 font-semibold text-sm tracking-wider uppercase mb-3">Share This Tool</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Embed This Calculator on Your Site</h2>
            <p class="text-lg text-slate-600">Wedding planners, event coordinators, and construction blogs: add this free tool to your site.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span class="font-semibold text-slate-800">Copy and paste this code into your website's HTML:</span>
                </div>
                <div class="relative bg-slate-900 rounded-xl p-4 overflow-x-auto" x-data="{ copied: false }">
                    <pre class="text-sm text-slate-300 whitespace-pre-wrap break-all font-mono" id="embed-code">&lt;iframe src="{{ url('/calculator/embed') }}" width="100%" height="620" frameborder="0" scrolling="no"&gt;&lt;/iframe&gt;</pre>
                    <button @click="
                        navigator.clipboard.writeText(document.getElementById('embed-code').innerText);
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                    " class="absolute top-3 right-3 bg-white/10 hover:bg-white/20 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition" x-text="copied ? 'Copied!' : 'Copy'"></button>
                </div>
                <div class="mt-4 flex flex-wrap gap-4 text-sm text-slate-500">
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Free to use</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Automatic updates</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Mobile responsive</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>No ads</span>
                </div>
            </div>
            <div class="bg-slate-50 border-t border-slate-200 p-4">
                <p class="text-sm text-slate-600 flex items-start gap-2">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    <span>The embed includes a backlink to PottyDirect. Removing it violates our terms of service.</span>
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Guide Section --}}
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <span class="inline-block text-emerald-600 font-semibold text-sm tracking-wider uppercase mb-3">Quick Reference</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">How Many Porta Potties Do You Need?</h2>
            <p class="text-lg text-slate-600">General guidelines based on industry standards and OSHA requirements</p>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm mb-10">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="text-left p-4 font-bold">Scenario</th>
                        <th class="p-4 font-bold text-center">People</th>
                        <th class="p-4 font-bold text-center">Recommended Units</th>
                        <th class="p-4 font-bold text-center hidden sm:table-cell">OSHA Min</th>
                        <th class="p-4 font-bold text-center hidden sm:table-cell">With Alcohol</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $guideData = [
                            ['scenario' => 'Construction Site', 'people' => '20 workers', 'units' => '1', 'osha' => '1', 'alcohol' => '—'],
                            ['scenario' => 'Construction Site', 'people' => '50 workers', 'units' => '3', 'osha' => '3', 'alcohol' => '—'],
                            ['scenario' => 'Construction Site', 'people' => '100 workers', 'units' => '5', 'osha' => '5', 'alcohol' => '—'],
                            ['scenario' => 'Wedding (4 hrs)', 'people' => '100 guests', 'units' => '2', 'osha' => '—', 'alcohol' => '3'],
                            ['scenario' => 'Wedding (4 hrs)', 'people' => '200 guests', 'units' => '4', 'osha' => '—', 'alcohol' => '5'],
                            ['scenario' => 'Outdoor Event (4+ hrs)', 'people' => '100 attendees', 'units' => '4', 'osha' => '—', 'alcohol' => '5'],
                            ['scenario' => 'Music Festival', 'people' => '500 attendees', 'units' => '10', 'osha' => '—', 'alcohol' => '12'],
                            ['scenario' => 'Music Festival', 'people' => '1,000 attendees', 'units' => '20', 'osha' => '—', 'alcohol' => '24'],
                        ];
                    @endphp
                    @foreach($guideData as $row)
                    <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors @if($loop->even) bg-slate-50/50 @endif">
                        <td class="p-4 font-medium text-slate-800">{{ $row['scenario'] }}</td>
                        <td class="p-4 text-center text-slate-700">{{ $row['people'] }}</td>
                        <td class="p-4 text-center font-bold text-emerald-600">{{ $row['units'] }}</td>
                        <td class="p-4 text-center hidden sm:table-cell text-slate-600">{{ $row['osha'] }}</td>
                        <td class="p-4 text-center hidden sm:table-cell text-slate-600">{{ $row['alcohol'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200">
                <h3 class="font-bold text-lg text-slate-800 mb-3 flex items-center gap-2">
                    <x-icon name="building" class="w-5 h-5 text-emerald-500" />
                    Construction Sites
                </h3>
                <p class="text-slate-600 text-sm mb-3">OSHA standard 1926.51 requires at least 1 toilet per 20 workers. For mixed-gender crews or longer projects, more units may be needed.</p>
                <p class="text-sm font-semibold text-slate-700">Rule of thumb: 1 unit per 20 workers, minimum.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200">
                <h3 class="font-bold text-lg text-slate-800 mb-3 flex items-center gap-2">
                    <x-icon name="star" class="w-5 h-5 text-emerald-500" />
                    Weddings & Events
                </h3>
                <p class="text-slate-600 text-sm mb-3">Plan 1 unit per 50 guests for events under 4 hours, 1 per 25 for longer events. Add luxury trailers for a premium experience.</p>
                <p class="text-sm font-semibold text-slate-700">Rule of thumb: 1 unit per 50 guests, plus extras for alcohol.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200">
                <h3 class="font-bold text-lg text-slate-800 mb-3 flex items-center gap-2">
                    <x-icon name="accessibility" class="w-5 h-5 text-emerald-500" />
                    ADA Compliance
                </h3>
                <p class="text-slate-600 text-sm mb-3">At least 5% of units should be ADA accessible for public events. Most construction sites also benefit from at least one ADA unit.</p>
                <p class="text-sm font-semibold text-slate-700">Rule of thumb: 1 ADA unit per 20 standard units.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200">
                <h3 class="font-bold text-lg text-slate-800 mb-3 flex items-center gap-2">
                    <x-icon name="clock" class="w-5 h-5 text-emerald-500" />
                    Duration Matters
                </h3>
                <p class="text-slate-600 text-sm mb-3">The longer people stay, the more units you need. Events over 4 hours may need double the units. Multi-day events require weekly servicing.</p>
                <p class="text-sm font-semibold text-slate-700">Rule of thumb: Double units for 4+ hour events.</p>
            </div>
        </div>
    </div>
</section>

{{-- FAQ Section --}}
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Frequently Asked Questions</h2>
            <p class="text-slate-600">Everything you need to know about how many porta potties to rent</p>
        </div>

        @php
            $faqs = [
                ['q' => 'How many porta potties do I need per person?', 'a' => 'For construction sites, OSHA requires 1 toilet per 20 workers. For events, plan 1 per 50 guests (under 4 hours) or 1 per 25 guests (4+ hours). Adjust upward if alcohol is served.'],
                ['q' => 'What happens if I don\'t have enough porta potties?', 'a' => 'Insufficient restrooms lead to long lines, unhappy guests, and potential OSHA citations on construction sites. It\'s always better to rent extra units — unused units can be adjusted on next service.'],
                ['q' => 'Do I need ADA-accessible porta potties?', 'a' => 'ADA units are required by law for most public events and recommended for construction sites. We recommend at least 1 ADA unit per 20 standard units.'],
                ['q' => 'How many porta potties for a wedding with 150 guests?', 'a' => 'For 150 guests at a 4-hour wedding, we recommend 3 standard units plus 1 ADA unit. If alcohol is served, add 1 more. Consider deluxe or luxury units for a more upscale experience.'],
                ['q' => 'How many portable toilets for a construction site with 50 workers?', 'a' => 'OSHA requires a minimum of 3 toilets for 50 workers (1 per 20 workers). We recommend at least 3-4 units for comfort, plus 1 ADA unit.'],
                ['q' => 'Is there a minimum order quantity?', 'a' => 'No minimum order. We rent single units for small projects and events, and offer volume discounts for 5+ units. Call us for your specific needs.'],
            ];
        @endphp

        <div class="space-y-3">
            @foreach($faqs as $faq)
                <details class="bg-slate-50 border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
                    <summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-emerald-600 transition list-none">
                        <span>{{ $faq['q'] }}</span>
                        <span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-emerald-100 w-8 h-8 rounded-full flex items-center justify-center">+</span>
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
        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-balance">Ready to order your porta potties?</h2>
        <p class="text-lg text-slate-400 mb-3">
            Now that you know how many units you need, call us for a <strong class="text-white">free quote</strong>.
            Starting at just <strong class="text-emerald-400">$89/day</strong> for standard units.
        </p>
        <p class="text-slate-400 mb-8 text-sm">
            Free delivery · Weekly servicing · No hidden fees
        </p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="calculator-cta"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400
                  text-white text-2xl md:text-3xl font-bold py-5 px-10
                  rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30
                  transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7 md:w-8 md:h-8" />
            {{ domain_phone_display() }}
        </a>
        <p class="mt-6 text-slate-400 text-sm">No obligation · Free quote · Answered in under 30 seconds</p>
    </div>
</section>

{{-- Navigation --}}
<section class="py-8 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto flex flex-wrap justify-center gap-4 text-sm">
        <a href="{{ route('home') }}" class="text-amber-600 hover:text-amber-700 font-medium">← Back to Home</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('pricing') }}" class="text-amber-600 hover:text-amber-700 font-medium">View Pricing</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('services') }}" class="text-amber-600 hover:text-amber-700 font-medium">All Services</a>
    </div>
</section>

{{-- Mobile Sticky CTA --}}
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <x-icon name="calculator" class="w-6 h-6" />
        <span>Call Now — Starting at $89/day</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection