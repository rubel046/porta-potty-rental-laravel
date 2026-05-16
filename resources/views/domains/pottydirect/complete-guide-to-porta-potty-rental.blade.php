@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'Complete Guide to Porta Potty Rental | Potty Direct')
@section('meta_description', 'Ultimate guide to porta potty rental: pricing, types, OSHA requirements, quantity calculator, delivery timelines & regulations. Free personalized quote.')
@section('canonical', route('pillar.page'))

@push('schema')
@php
$pillarSchema = [
    "@context" => "https://schema.org",
    "@type" => "Article",
    "headline" => "Complete Guide to Porta Potty Rental 2026",
    "description" => "The ultimate guide covering porta potty types, pricing, OSHA compliance, quantity calculations, delivery logistics, and local regulations.",
    "author" => ["@type" => "Organization", "name" => "Potty Direct"],
    "datePublished" => "2026-01-15",
    "dateModified" => date('Y-m-d'),
    "mainEntityOfPage" => ["@type" => "WebPage", "@id" => route('pillar.page')],
];
@endphp
<script type="application/ld+json">{!! json_encode($pillarSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@php
$pillarBreadcrumb = [
    "@context" => "https://schema.org",
    "@type" => "BreadcrumbList",
    "itemListElement" => [
        ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => route('home')],
        ["@type" => "ListItem", "position" => 2, "name" => "Complete Guide to Porta Potty Rental", "item" => route('pillar.page')]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($pillarBreadcrumb, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
<section class="relative py-16 md:py-20 overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-slate-400 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3 sm:w-4 sm:h-4" />
            <span class="text-white">Complete Guide to Porta Potty Rental</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-400 text-sm font-bold px-4 py-2 rounded-full mb-5">
            <x-icon name="document" class="w-4 h-4" />2026 COMPLETE GUIDE
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5 leading-tight">
            The Complete Guide to <span class="text-emerald-400">Porta Potty Rental</span>
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">
            Everything you need to know about renting portable toilets — types, pricing, quantities, rules, and local regulations — all in one place.
        </p>
        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}
        </a>
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="prose prose-lg max-w-none prose-headings:text-slate-800 prose-headings:font-bold prose-h2:text-2xl prose-h2:mt-12 prose-h2:mb-5 prose-h2:border-b prose-h2:border-slate-200 prose-h2:pb-3 prose-p:text-slate-600 prose-p:leading-relaxed prose-a:text-emerald-600 prose-strong:text-slate-800 prose-ul:list-disc prose-li:text-slate-600">

            <p class="lead text-xl text-slate-700">Renting a porta potty seems straightforward — and it is — but getting the right unit in the right quantity at the right price takes a bit of knowledge. This guide covers everything from unit types to permit rules, so you can book with confidence.</p>

            <h2 id="types">Porta Potty Types: Which One Do You Need?</h2>
            <p>Portable restrooms range from basic units to full luxury trailers. Here's what each type offers and when to choose it.</p>

            <h3>Standard Porta Potties</h3>
            <p>The workhorse of the industry. A standard unit includes a non-splash urinal, ventilation, toilet paper holder, and hand sanitizer. Best for construction sites, short events, and job sites where function matters more than frills. Starting at $89/day.</p>

            <h3>Deluxe Flushable Units</h3>
            <p>A step up from standard — features a flushing toilet, hand sink with running water, interior mirror, and lighting. Ideal for weddings, private parties, and corporate events. Starting at $150/day.</p>

            <h3>ADA Compliant Units</h3>
            <p>Wheelchair-accessible with a 60-inch door, interior grab bars, non-slip flooring, and spacious interior. Required by law for public events and most construction sites over a certain size. Starting at $125/day.</p>

            <h3>Luxury Restroom Trailers</h3>
            <p>Climate-controlled trailers with porcelain flush toilets, vanity sinks, LED lighting, and separate men's/women's sides. Perfect for VIP events, weddings, and film productions. Starting at $500/day.</p>

            <h3>Handwashing Stations</h3>
            <p>Portable sinks with soap, paper towels, and fresh water. Required by health codes for food service events. Can be paired with any unit type. Starting at $50/day.</p>

            <p>Still unsure? <a href="{{ route('comparison') }}">Compare all porta potty types side by side →</a> or call {{ domain_phone_display() }} for a free consultation.</p>

            <h2 id="pricing">Porta Potty Rental Prices: What to Expect</h2>
            <p>Pricing varies by unit type, rental duration, quantity, and delivery location. Here are typical daily rates across the industry:</p>

            <div class="overflow-x-auto my-6">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white">
                            <th class="p-3 text-left font-bold">Unit Type</th>
                            <th class="p-3 text-center font-bold">Daily Rate</th>
                            <th class="p-3 text-center font-bold">Weekly Rate</th>
                            <th class="p-3 text-center font-bold">Monthly Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t border-slate-200"><td class="p-3 font-medium">Standard</td><td class="p-3 text-center">$89–$175</td><td class="p-3 text-center">$445–$875</td><td class="p-3 text-center">$1,335–$2,625</td></tr>
                        <tr class="border-t border-slate-200 bg-slate-50"><td class="p-3 font-medium">Deluxe Flushable</td><td class="p-3 text-center">$150–$275</td><td class="p-3 text-center">$750–$1,375</td><td class="p-3 text-center">$3,000–$5,500</td></tr>
                        <tr class="border-t border-slate-200"><td class="p-3 font-medium">ADA Accessible</td><td class="p-3 text-center">$125–$250</td><td class="p-3 text-center">$625–$1,250</td><td class="p-3 text-center">$2,500–$5,000</td></tr>
                        <tr class="border-t border-slate-200 bg-slate-50"><td class="p-3 font-medium">Luxury Trailer</td><td class="p-3 text-center">$500–$2,500</td><td class="p-3 text-center">$2,500–$12,500</td><td class="p-3 text-center">$10,000–$50,000</td></tr>
                        <tr class="border-t border-slate-200"><td class="p-3 font-medium">Handwash Station</td><td class="p-3 text-center">$50–$100</td><td class="p-3 text-center">$250–$500</td><td class="p-3 text-center">$750–$1,500</td></tr>
                    </tbody>
                </table>
            </div>

            <p>Most rental companies offer volume discounts for 10+ units and long-term rental discounts for monthly bookings. <a href="{{ route('pricing') }}">View our complete pricing guide →</a></p>

            <h2 id="quantity">How Many Porta Potties Do You Need?</h2>
            <p>Getting the right quantity matters — too few and you'll have long lines and sanitation issues, too many and you're wasting money. Use these guidelines:</p>

            <h3>Construction Sites (OSHA Standard 1926.51)</h3>
            <p>OSHA requires at least 1 toilet seat per 20 workers for projects under 5 days, and 1 per 10 workers for longer projects. For 50 workers on a 6-month job: minimum 5 units.</p>

            <h3>Events (Based on Attendance)</h3>
            <ul>
                <li>Under 100 guests: 2 standard units + 1 ADA</li>
                <li>100–500 guests: 4–6 units + 2 ADA</li>
                <li>500–1,000 guests: 8–12 units + 2 ADA</li>
                <li>1,000+ guests: 1 unit per 100 guests + 1 ADA per 10 units</li>
            </ul>

            <h3>Weddings</h3>
            <p>For a 4-hour reception with 150 guests, plan on 3–4 standard units or 2 deluxe units plus 1 ADA. Add luxury trailers if you want an upscale experience.</p>

            <h3>Festivals (Multi-Day Events)</h3>
            <p>Festivals need more units per person due to extended hours and alcohol consumption. Plan on 1 unit per 75 attendees for multi-day events.</p>

            <p>Use our <a href="{{ route('calculator') }}">interactive units calculator →</a> to get a precise recommendation for your specific event or job site.</p>

            <h2 id="osha">OSHA Porta Potty Requirements</h2>
            <p>The Occupational Safety and Health Administration (OSHA) has specific rules about portable toilets on job sites. Here's what you need to know:</p>

            <ul>
                <li><strong>Sanitation Standard (1926.51):</strong> Employers must provide toilet facilities for all employees. At least 1 toilet seat per 20 workers for short-term projects, 1 per 10 for long-term.</li>
                <li><strong>Accessibility:</strong> Toilets must be within a 10-minute walk of the work area.</li>
                <li><strong>Sanitation:</strong> Units must be serviced regularly to maintain sanitary conditions.</li>
                <li><strong>Handwashing:</strong> Employers must provide handwashing facilities if employees handle hazardous substances.</li>
                <li><strong>ADA Compliance:</strong> Public-facing construction zones must include ADA-compliant units.</li>
            </ul>

            <p>For a full breakdown, see our <a href="{{ route('osha.guide') }}">OSHA porta potty requirements guide →</a></p>

            <h2 id="delivery">Delivery, Setup & Service</h2>
            <p>Here's what to expect when you order portable restrooms:</p>

            <ul>
                <li><strong>Lead Time:</strong> Most companies offer same-day delivery for orders placed before 2 PM. Next-day is standard.</li>
                <li><strong>Placement:</strong> Units need a flat, dry surface accessible by truck. Avoid soft ground, steep slopes, or obstructed access.</li>
                <li><strong>Servicing:</strong> Standard rental includes weekly cleaning and restocking. Events may require daily servicing depending on attendance.</li>
                <li><strong>Pickup:</strong> The rental company handles pickup at the end of your rental period. Notify them 24–48 hours in advance.</li>
            </ul>

            <h2 id="regulations">Local Regulations & Permits</h2>
            <p>Some cities and counties require permits for portable toilet placement, especially on public property or for large events. Here's what to check:</p>

            <ul>
                <li><strong>City Permits:</strong> Many municipalities require a temporary structure permit for portable restrooms on public property.</li>
                <li><strong>Health Department:</strong> Events serving food may need health department approval of sanitation plans.</li>
                <li><strong>Event Permits:</strong> Large events often have sanitation requirements built into their event permit application.</li>
                <li><strong>Homeowners Associations:</strong> Some HOAs restrict portable toilet placement in residential neighborhoods.</li>
            </ul>

            <p>Your rental company should handle most permitting. Always ask before you book.</p>

            <h2 id="tips">Rental Tips & Best Practices</h2>
            <ul>
                <li><strong>Book early:</strong> Peak season (May–October) fills up fast. Book 2–4 weeks ahead for events, 1 week for construction.</li>
                <li><strong>Order extra:</strong> It's better to have one too many than not enough. Add 1–2 units as a buffer.</li>
                <li><strong>Ask about volume discounts:</strong> Many companies offer 10–20% off for 10+ units.</li>
                <li><strong>Check the fine print:</strong> Confirm what's included — delivery, setup, servicing, and pickup should all be itemized.</li>
                <li><strong>Request ADA units:</strong> Even if not legally required, ADA units are appreciated by elderly guests and parents with strollers.</li>
            </ul>

        </div>
    </div>
</section>

{{-- Cluster Links --}}
<section class="py-12 md:py-16 px-4 bg-slate-50 border-t border-slate-200">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-8 text-center">Related Guides</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('comparison') }}" class="bg-white rounded-xl p-5 border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all group">
                <span class="text-sm font-bold text-emerald-600 uppercase tracking-wide">Compare Types</span>
                <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mt-1">Standard vs Deluxe vs Luxury</h3>
                <p class="text-sm text-slate-500 mt-1">Side-by-side feature comparison</p>
            </a>
            <a href="{{ route('pricing') }}" class="bg-white rounded-xl p-5 border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all group">
                <span class="text-sm font-bold text-emerald-600 uppercase tracking-wide">Pricing</span>
                <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mt-1">Full Pricing Guide</h3>
                <p class="text-sm text-slate-500 mt-1">Daily, weekly & monthly rates</p>
            </a>
            <a href="{{ route('calculator') }}" class="bg-white rounded-xl p-5 border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all group">
                <span class="text-sm font-bold text-emerald-600 uppercase tracking-wide">Calculator</span>
                <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mt-1">Units Quantity Calculator</h3>
                <p class="text-sm text-slate-500 mt-1">Get your exact count</p>
            </a>
            <a href="{{ route('osha.guide') }}" class="bg-white rounded-xl p-5 border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all group">
                <span class="text-sm font-bold text-emerald-600 uppercase tracking-wide">OSHA Guide</span>
                <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mt-1">OSHA Compliance Requirements</h3>
                <p class="text-sm text-slate-500 mt-1">Job site sanitation rules</p>
            </a>
            <a href="{{ route('wedding') }}" class="bg-white rounded-xl p-5 border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all group">
                <span class="text-sm font-bold text-emerald-600 uppercase tracking-wide">Weddings</span>
                <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mt-1">Wedding Porta Potty Guide</h3>
                <p class="text-sm text-slate-500 mt-1">Luxury options for your big day</p>
            </a>
            <a href="{{ route('festival') }}" class="bg-white rounded-xl p-5 border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all group">
                <span class="text-sm font-bold text-emerald-600 uppercase tracking-wide">Festivals</span>
                <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition mt-1">Festival & Event Rentals</h3>
                <p class="text-sm text-slate-500 mt-1">Volume pricing for large events</p>
            </a>
        </div>
    </div>
</section>

{{-- FAQ Section --}}
<section class="py-12 md:py-16 px-4 bg-white" id="faq">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-8 text-center">Frequently Asked Questions</h2>
        @php
            $pillarFaqs = [
                ['q' => 'How much does a porta potty rental cost?', 'a' => 'Standard porta potties start at $89/day, deluxe flushable units at $150/day, ADA units at $125/day, and luxury trailers at $500/day. Weekly and monthly discounts are available. Call for a precise quote.'],
                ['q' => 'How many porta potties do I need for 100 guests?', 'a' => 'For 100 guests at a 4-hour event, plan on 2–3 standard units plus 1 ADA unit. If alcohol is served, add 1–2 more units. Use our units calculator for exact recommendations.'],
                ['q' => 'How far in advance should I book a porta potty?', 'a' => 'For construction sites, 1 week is usually sufficient. For weddings and events, book 2–4 weeks ahead, especially during peak season (May–October). Same-day delivery is available for urgent orders placed before 2 PM.'],
                ['q' => 'Do I need a permit for a porta potty?', 'a' => 'Some cities require permits for porta potties on public property. Your rental company typically handles this. For private property (backyard, construction site), permits are rarely needed. Always ask your rental company.'],
                ['q' => 'What is OSHA\'s rule for porta potties on construction sites?', 'a' => 'OSHA standard 1926.51 requires at least 1 toilet seat per 20 workers for short-term projects and 1 per 10 for long-term projects. Toilets must be within a 10-minute walk of the work area.'],
                ['q' => 'How often are porta potties serviced?', 'a' => 'Standard rental includes weekly servicing (cleaning, restocking supplies, waste removal). High-traffic events may require daily servicing. Luxury trailers typically include daily servicing.'],
                ['q' => 'What\'s included in a standard porta potty rental?', 'a' => 'Delivery, setup, initial supplies (toilet paper, hand sanitizer), periodic servicing, and pickup at the end of your rental period. Some companies charge extra for delivery beyond a certain distance.'],
                ['q' => 'Can I get same-day porta potty delivery?', 'a' => 'Yes, most rental companies offer same-day delivery for orders placed before 2 PM local time. Call {{ domain_phone_display() }} to check availability in your area.'],
            ];
        @endphp
        <div class="space-y-3">
            @foreach($pillarFaqs as $index => $faq)
            <details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group scroll-mt-24">
                <summary class="flex justify-between items-center p-4 sm:p-5 cursor-pointer font-semibold text-slate-800 hover:text-emerald-600 transition list-none text-sm sm:text-base">
                    <h3 class="text-sm sm:text-base font-semibold m-0 flex-1">{{ $faq['q'] }}</h3>
                    <span aria-hidden="true" class="text-xl sm:text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500 transition-all duration-300 ml-2 sm:ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-emerald-100 w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center">+</span>
                </summary>
                <div class="px-4 sm:px-5 pb-4 sm:pb-5 text-slate-600 leading-relaxed text-sm sm:text-base">
                    {{ $faq['a'] }}
                </div>
            </details>
            @endforeach
        </div>
    </div>
</section>

@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50"><div class="max-w-5xl mx-auto"><h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2><div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">@foreach($testimonials as $t)<div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all"><div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">@for($i = 0; $i < $t['rating']; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor</div><p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p><div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100"><div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div><div><p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>@if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif</div></div></div>@endforeach</div></div></section>
@endif

{{-- Final CTA --}}
<section class="py-16 md:py-24 px-4 bg-slate-900 text-white text-center relative overflow-hidden">
    <div class="relative max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-4 text-balance">
            Ready to rent? Let's find the right porta potty for you
        </h2>
        <p class="text-lg text-slate-400 mb-8 max-w-xl mx-auto">
            Free quote · No hidden fees · Same-day delivery available
        </p>
        <a href="tel:{{ domain_phone_raw() }}"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl md:text-3xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7 md:w-8 md:h-8" />
            {{ domain_phone_display() }}
        </a>
        <p class="mt-6 text-slate-400 text-sm">Answered in under 15 seconds by a real person.</p>
    </div>
</section>
@endsection
