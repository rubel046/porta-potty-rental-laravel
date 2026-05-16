@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'Porta Potty Rental for Sports Events & Tournaments | PottyDirect')
@section('meta_description', 'Rent porta potties for sports tournaments, tailgates, marathons & sporting events. High-capacity solutions starting at $89/day. Same-day delivery available.')
@section('canonical', route('sports-event.page'))
@push('schema')
@php
$breadcrumbSchema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Sports Event Porta Potty Rental', 'item' => route('sports-event.page')]]];
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        ["@type" => "Question", "name" => "How many porta potties do I need for a sports tournament?", "acceptedAnswer" => ["@type" => "Answer", "text" => "For sports tournaments with players, coaches, and spectators, plan 1 unit per 50 attendees for events under 4 hours. For all-day tournaments, increase to 1 per 25. Add 20-30% more if alcohol is served at concessions."]],
        ["@type" => "Question", "name" => "Do you deliver porta potties to sports stadiums and fields?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, we deliver to sports stadiums, fields, golf courses, racetracks, and any sporting venue accessible by truck. Most locations include free delivery within 50 miles of our service centers."]],
        ["@type" => "Question", "name" => "Can I rent luxury restroom trailers for VIP events at sporting events?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes! Luxury restroom trailers ($500-$2,500/day) are perfect for VIP areas, corporate suites, and premium seating sections. They feature climate control, porcelain fixtures, and elegant interiors."]],
        ["@type" => "Question", "name" => "What is the best porta potty for a tailgate party?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Deluxe flushable units ($150-$275/day) are ideal for tailgate parties. They offer a flushing toilet, hand sink with running water, and interior lighting — much better for pre-game festivities than standard units."]],
        ["@type" => "Question", "name" => "How far in advance should I book porta potties for a sporting event?", "acceptedAnswer" => ["@type" => "Answer", "text" => "We recommend booking 1-2 weeks in advance for most sporting events. For major tournaments, championship games, and events expected to draw large crowds, book 3-4 weeks ahead. Same-day delivery is available for last-minute needs."]],
        ["@type" => "Question", "name" => "Do you offer ADA-compliant porta potties for sporting events?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes. ADA-compliant units are required for public sporting events under the Americans with Disabilities Act. We provide units with 60-inch wide doors, grab bars, and spacious interiors."]],
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
@section('content')
<section class="relative py-16 md:py-20 overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-slate-400 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3 sm:w-4 sm:h-4" />
            <span class="text-white">Sports Event Rental</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-400 text-sm font-bold px-4 py-2 rounded-full mb-5"><x-icon name="star" class="w-4 h-4" />SPORTS EVENT RENTAL</div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">Porta Potty Rental for <span class="text-emerald-400">Sports Events & Tournaments</span></h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">High-capacity portable restroom solutions for sports tournaments, tailgates, marathons, and all types of sporting events. Starting at $89/day.</p>
        <a href="tel:{{ domain_phone_raw() }}" data-tracking-label="sports-hero" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl md:text-2xl font-bold py-4 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-6 h-6" />{{ domain_phone_display() }}</a>
        <p class="mt-4 text-sm text-slate-400">Same-day delivery · No hidden fees · Volume pricing available</p>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Sports Events We Cover</h2>
        <div class="grid md:grid-cols-4 gap-4">
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="users" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Tournaments</h3>
                <p class="text-xs text-slate-500">Soccer, baseball, lacrosse, basketball tournaments</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="truck" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Tailgates</h3>
                <p class="text-xs text-slate-500">Pre-game parking lot parties and fan zones</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="lightning" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Marathons</h3>
                <p class="text-xs text-slate-500">Road races, triathlons, and endurance events</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="building" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Stadium Events</h3>
                <p class="text-xs text-slate-500">Outdoor concerts, championship games, festivals</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="map-pin" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Golf Tournaments</h3>
                <p class="text-xs text-slate-500">Pro-ams, charity events, country club outings</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="home" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Recreation Leagues</h3>
                <p class="text-xs text-slate-500">Youth sports, adult leagues, community games</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="sparkles" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">VIP Areas</h3>
                <p class="text-xs text-slate-500">Corporate suites, premium seating, hospitality tents</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="cube" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Outdoor Expos</h3>
                <p class="text-xs text-slate-500">Sporting goods shows, fitness expos, fan fests</p>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-6 text-center">Quantity Guide for Sporting Events</h2>
        <p class="text-slate-600 mb-8 text-center">Use this quick reference to estimate how many porta potties you need for your event.</p>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="text-left p-4 font-bold">Event Size</th>
                        <th class="p-4 font-bold text-center">Standard Units</th>
                        <th class="p-4 font-bold text-center">Add Deluxe Units</th>
                        <th class="p-4 font-bold text-center">ADA Units</th>
                        <th class="p-4 font-bold text-center">Luxury Trailer</th>
                    </tr>
                </thead>
                <tbody>
@php $sportQtyData = [['Small (50-100 people)', '2-3', '0-1', '1', 'Optional'], ['Medium (100-500 people)', '4-10', '2-4', '1-2', 'Recommended'], ['Large (500-1,000 people)', '10-20', '5-10', '2-3', '1'], ['Tournament (1,000-5,000)', '20-80', '10-30', '4-8', '2-3'], ['Major Event (5,000+)', '80+', '30+', '10+', '4+']]; @endphp
@foreach($sportQtyData as $row)<tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors @if($loop->even) bg-slate-50/50 @endif"><td class="p-4 font-medium text-slate-800">{{ $row[0] }}</td><td class="p-4 text-center font-bold text-emerald-600">{{ $row[1] }}</td><td class="p-4 text-center text-slate-700">{{ $row[2] }}</td><td class="p-4 text-center text-slate-700">{{ $row[3] }}</td><td class="p-4 text-center text-slate-700">{{ $row[4] }}</td></tr>@endforeach
                </tbody>
            </table>
        </div>
        <p class="text-sm text-slate-500 mt-4">Note: Add 20-30% more units if alcohol is served. Use our <a href="{{ route('calculator') }}" class="text-emerald-600 hover:underline font-medium">units calculator</a> for an exact recommendation.</p>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Why PottyDirect for Your Sporting Event</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="truck" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Reliable Delivery & Pickup</h3>
                <p class="text-slate-600 text-sm">We deliver units before the first game and pick them up after the final whistle. Our team handles setup, placement, and teardown so you can focus on running the event.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="currency-dollar" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Volume Discounts</h3>
                <p class="text-slate-600 text-sm">Save up to 35% on large orders. Sporting events typically need 10-100+ units, and we offer tiered pricing for 5+, 11+, and 20+ units. Every dollar saved goes back to your event budget.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="clock" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Same-Day Delivery</h3>
                <p class="text-slate-600 text-sm">Need units fast? Order by 2PM local time for same-day delivery. We understand that sporting events can have last-minute needs, and we are ready to help.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="accessibility" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">ADA Compliance</h3>
                <p class="text-slate-600 text-sm">All public sporting events must provide ADA-compliant restroom facilities. We include accessible units in every event order and ensure proper placement and signage.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="sparkles" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">VIP & Premium Options</h3>
                <p class="text-slate-600 text-sm">Impress sponsors and VIP guests with luxury restroom trailers featuring climate control, porcelain fixtures, and elegant interiors. Perfect for corporate suites and hospitality areas.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="shield-check" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Event Servicing</h3>
                <p class="text-slate-600 text-sm">Multi-day tournaments and events get scheduled servicing to keep units clean and stocked throughout. We coordinate with your event staff to minimize disruption.</p>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Sports Event FAQs</h2>
        <div class="space-y-3">
@php $faqs = [['q' => 'How many porta potties do I need for a sports tournament?', 'a' => 'For sports tournaments with players, coaches, and spectators, plan 1 unit per 50 attendees for events under 4 hours. For all-day tournaments, increase to 1 per 25. Add 20-30% more if alcohol is served at concessions.'], ['q' => 'Do you deliver porta potties to sports stadiums and fields?', 'a' => 'Yes, we deliver to sports stadiums, fields, golf courses, racetracks, and any sporting venue accessible by truck. Most locations include free delivery within 50 miles of our service centers.'], ['q' => 'Can I rent luxury restroom trailers for VIP events at sporting events?', 'a' => 'Yes! Luxury restroom trailers ($500-$2,500/day) are perfect for VIP areas, corporate suites, and premium seating sections. They feature climate control, porcelain fixtures, and elegant interiors.'], ['q' => 'What is the best porta potty for a tailgate party?', 'a' => 'Deluxe flushable units ($150-$275/day) are ideal for tailgate parties. They offer a flushing toilet, hand sink with running water, and interior lighting — much better for pre-game festivities than standard units.'], ['q' => 'How far in advance should I book for a sporting event?', 'a' => 'We recommend booking 1-2 weeks in advance for most sporting events. For major tournaments and championship games, book 3-4 weeks ahead. Same-day delivery is available for last-minute needs.'], ['q' => 'Do you offer ADA-compliant porta potties for sporting events?', 'a' => 'Yes. ADA-compliant units are required for public sporting events under the ADA. We provide units with 60-inch wide doors, grab bars, and spacious interiors.']]; @endphp
@foreach($faqs as $faq)<details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group"><summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-emerald-600 transition list-none"><span>{{ $faq['q'] }}</span><span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center">+</span></summary><div class="px-5 pb-5 text-slate-600 leading-relaxed"><p>{{ $faq['a'] }}</p></div></details>@endforeach
        </div>
    </div>
</section>
@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50"><div class="max-w-5xl mx-auto"><h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2><div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">@foreach($testimonials as $t)<div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all"><div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">@for($i = 0; $i < $t['rating']; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor</div><p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p><div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100"><div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div><div><p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>@if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif</div></div></div>@endforeach</div></div></section>
@endif
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center"><div class="max-w-3xl mx-auto"><h2 class="text-3xl md:text-4xl font-bold mb-4">Get ready for game day</h2><p class="text-lg text-slate-400 mb-8">Call for a free quote on porta potties for your sporting event. Volume pricing available.</p><a href="tel:{{ domain_phone_raw() }}" data-tracking-label="sports-final" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a></div></section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100"><div class="max-w-4xl mx-auto text-center"><h2 class="text-2xl font-bold text-slate-800 mb-6">Related Resources</h2><div class="flex flex-wrap justify-center gap-4"><a href="{{ route('festival') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Festival Rentals</a><a href="{{ route('comparison') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Unit Comparison</a><a href="{{ route('calculator') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Units Calculator</a><a href="{{ route('faq') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">FAQ</a><a href="{{ route('blog.index') }}?category=sports" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Sports Blog</a><a href="{{ route('pricing') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Pricing</a></div></div></section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50"><a href="tel:{{ domain_phone_raw() }}" data-tracking-label="sports-mobile-cta" class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30"><x-icon name="phone" class="w-6 h-6" /><span>Book Sports Event Units</span></a></div><div class="h-20 md:hidden"></div>
@endsection