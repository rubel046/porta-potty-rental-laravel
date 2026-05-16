@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', 'Porta Potty Rental for Parties — Affordable Event Restrooms | PottyDirect')
@section('meta_description', 'Need porta potties for your party? Starting at $89/day with same-day delivery. Birthday parties, backyard BBQs, graduation parties & more. Book in 5 minutes.')
@section('canonical', route('party.page'))

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {"@type": "Question", "name": "How many porta potties do I need for a party?", "acceptedAnswer": {"@type": "Answer", "text": "For a 4-hour party, plan 1 porta potty per 50 guests. Add 20% more if alcohol is served. For a backyard BBQ with 50 guests, 1-2 standard units is typically sufficient."}},
        {"@type": "Question", "name": "How much does a porta potty for a party cost?", "acceptedAnswer": {"@type": "Answer", "text": "Standard porta potty rentals start at $89/day. Deluxe units with hand washing stations start at $150/day. Weekly rates available for multi-day events."}},
        {"@type": "Question", "name": "Can I get a luxury restroom trailer for a party?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. Luxury restroom trailers with climate control, flushing toilets, and sinks are available for upscale parties and events. Rates start at $500/day."}},
        {"@type": "Question", "name": "Do I need a permit for a porta potty at my party?", "acceptedAnswer": {"@type": "Answer", "text": "If the porta potty is on private property (your backyard), no permit is needed. For public spaces, street placement, or parks, a permit may be required. We can help with permitting."}},
        {"@type": "Question", "name": "How far in advance should I book a porta potty for a party?", "acceptedAnswer": {"@type": "Answer", "text": "We recommend booking 3-5 days in advance for best availability. Same-day delivery is available when you order before 2PM. Call for last-minute needs."}},
        {"@type": "Question", "name": "What comes with a party porta potty rental?", "acceptedAnswer": {"@type": "Answer", "text": "Delivery, setup, toilet paper, hand sanitizer, and pickup are included. Deluxe units include a hand sink with running water. Weekly servicing included for longer rentals."}}
    ]
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}"},
        {"@type": "ListItem", "position": 2, "name": "Party Porta Potty Rental", "item": "{{ route('party.page') }}"}
    ]
}
</script>
@endpush

@section('content')

<section class="relative py-16 md:py-20 overflow-hidden bg-gradient-to-br from-pink-600 via-rose-600 to-pink-700">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;><g fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.1&quot;><path d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/></g></g></svg>')"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 text-xs sm:text-sm text-rose-200 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3" />
            <span class="text-white">Party Porta Potty Rental</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-white/15 text-white text-sm font-bold px-4 py-2 rounded-full mb-5 backdrop-blur">
            <x-icon name="sparkles" class="w-4 h-4" />
            PARTIES & CELEBRATIONS
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5 leading-tight">
            Porta Potty Rental for Parties
            <span class="block text-2xl md:text-3xl mt-3 text-rose-200">Clean, Affordable Restrooms for Your Celebration</span>
        </h1>
        <p class="text-lg md:text-xl text-rose-100 max-w-2xl mx-auto mb-8">
            From backyard BBQs to milestone birthdays, get clean porta potties delivered to your party.
            Starting at just <strong class="text-white">$89/day</strong> with same-day delivery.
        </p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="party-hero"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl md:text-2xl font-bold py-4 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-6 h-6" />
            {{ domain_phone_display() }}
        </a>
        <p class="mt-4 text-sm text-rose-200">Free quote · No hidden fees · Answered in 15 seconds</p>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-10">Perfect for Every Party Type</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @php
            $partyTypes = [
                ['icon' => 'sparkles', 'name' => 'Birthday Parties', 'desc' => 'Kids birthdays, adult celebrations, milestone parties. Keep guests comfortable with clean restrooms.'],
                ['icon' => 'users', 'name' => 'Backyard BBQs', 'desc' => 'Summer cookouts, family reunions, block parties. No more running inside every 10 minutes.'],
                ['icon' => 'building', 'name' => 'Graduation Parties', 'desc' => 'Celebrate the graduate without worrying about bathroom capacity. Deluxe units available.'],
                ['icon' => 'heart', 'name' => 'Anniversary Parties', 'desc' => 'Elegant restroom trailers for milestone anniversary celebrations and vow renewals.'],
                ['icon' => 'home', 'name' => 'Housewarming Parties', 'desc' => 'Moving in? Keep porta potties outside so guests don\'t track mud through your new home.'],
                ['icon' => 'calendar', 'name' => 'Holiday Gatherings', 'desc' => '4th of July, Labor Day, Memorial Day. Outdoor celebrations need outdoor restrooms.'],
            ];
            @endphp
            @foreach($partyTypes as $pt)
            <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-lg transition-all text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-rose-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <x-icon name="{{ $pt['icon'] }}" class="w-6 h-6 text-white" />
                </div>
                <h3 class="font-bold text-slate-800 mb-2">{{ $pt['name'] }}</h3>
                <p class="text-sm text-slate-600">{{ $pt['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Best Party Porta Potties for Your Event</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @php
            $partyUnits = [
                ['icon' => 'building', 'name' => 'Standard Units', 'price' => '$89/day', 'desc' => 'Perfect for casual backyard parties and BBQs. Includes sanitizer dispenser.', 'popular' => true],
                ['icon' => 'water-drop', 'name' => 'Deluxe Flushable', 'price' => '$150/day', 'desc' => 'Flushing toilet with hand sink. Great for graduations and milestone parties.', 'popular' => false],
                ['icon' => 'sparkles', 'name' => 'Luxury Trailers', 'price' => '$500/day', 'desc' => 'Upscale restroom trailers for anniversary parties and elegant events.', 'popular' => false],
            ];
            @endphp
            @foreach($partyUnits as $pu)
            <div class="bg-white border @if($pu['popular']) border-amber-300 ring-2 ring-amber-200 @else border-slate-200 @endif rounded-2xl p-6 hover:shadow-xl transition-all">
                @if($pu['popular'])
                <div class="text-xs font-bold bg-amber-500 text-white px-3 py-1 rounded-full inline-block mb-3">MOST POPULAR</div>
                @endif
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-4">
                    <x-icon name="{{ $pu['icon'] }}" class="w-6 h-6 text-emerald-600" />
                </div>
                <h3 class="font-bold text-lg text-slate-800 mb-1">{{ $pu['name'] }}</h3>
                <div class="text-2xl font-extrabold text-emerald-600 mb-3">{{ $pu['price'] }}</div>
                <p class="text-sm text-slate-600 mb-4">{{ $pu['desc'] }}</p>
                <a href="tel:{{ domain_phone_raw() }}"
                   data-tracking-label="party-unit-{{ strtolower(str_replace(' ', '-', $pu['name'])) }}"
                   class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-400 text-white font-bold text-sm px-4 py-2.5 rounded-full transition-all hover:scale-105 min-h-[44px]">
                    <x-icon name="phone" class="w-4 h-4" />
                    Book for My Party
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-4 text-center">Party Porta Potty FAQs</h2>
        <p class="text-center text-slate-600 mb-8 max-w-2xl mx-auto">Everything you need to know about renting porta potties for your party or celebration.</p>
        @php
        $partyFaqs = [
            ['q' => 'How many porta potties do I need for 50 guests at a party?', 'a' => 'For 50 guests at a 4-hour party, 1-2 standard porta potties is sufficient. If serving alcohol, add an extra unit. For an all-day event (8+ hours), plan for 2 units.'],
            ['q' => 'Can a porta potty be placed in my backyard?', 'a' => 'Yes. We can place porta potties on grass, gravel, or pavement as long as there is a clear access path (at least 6 feet wide) for delivery. We protect surfaces with boards if needed.'],
            ['q' => 'How far in advance should I book for a party?', 'a' => 'We recommend booking 3-5 days before your event. Same-day delivery is available for orders placed before 2PM local time. Call for last-minute availability.'],
            ['q' => 'What is the best porta potty option for a kids birthday party?', 'a' => 'Standard units are great for kids parties. They are clean, functional, and affordable. For extra comfort, consider a deluxe unit with a hand sink so kids can wash up.'],
            ['q' => 'Do you deliver to parks or public venues for parties?', 'a' => 'Yes, we deliver to public parks, community centers, and rented venues. You may need a permit from the local parks department for public property. We can advise on requirements.'],
        ];
        @endphp
        <div class="space-y-3">
            @foreach($partyFaqs as $faq)
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
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Why Choose PottyDirect for Party Rentals</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $whyParty = [
                ['icon' => 'truck', 'title' => 'Delivery Before Guests Arrive', 'desc' => 'We schedule delivery around your party timeline. Your porta potties arrive and are set up before the first guest arrives, and we pick up after the party ends.'],
                ['icon' => 'sparkles', 'title' => 'Clean, Well-Maintained Units', 'desc' => 'Every unit is thoroughly cleaned, sanitized, and stocked before delivery. Deluxe units include hand sinks with running water for a premium guest experience.'],
                ['icon' => 'users', 'title' => 'Flexible Capacity Options', 'desc' => 'From a single standard unit for a backyard BBQ to multiple luxury trailers for a milestone birthday — we have the right solution for any party size.'],
                ['icon' => 'currency-dollar', 'title' => 'Affordable Pricing Starting at $89', 'desc' => 'Party porta potty rentals start at just $89/day with no hidden fees. Volume discounts available for larger parties. Same-day delivery when you order before 2PM.'],
            ];
            @endphp
            @foreach($whyParty as $w)
            <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-lg transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
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
            $relatedParty = [
                ['icon' => 'currency-dollar', 'title' => 'Porta Potty Cost Guide', 'url' => route('cost.page'), 'desc' => 'Complete pricing breakdown'],
                ['icon' => 'calculator', 'title' => 'Units Calculator', 'url' => route('calculator'), 'desc' => 'How many for your guest count'],
                ['icon' => 'building', 'title' => 'Compare Unit Types', 'url' => route('comparison'), 'desc' => 'Standard vs Deluxe vs Luxury'],
                ['icon' => 'sparkles', 'title' => 'Wedding Rentals', 'url' => route('wedding'), 'desc' => 'Luxury options for big events'],
                ['icon' => 'music', 'title' => 'Festival Rentals', 'url' => route('festival'), 'desc' => 'Volume solutions for large events'],
                ['icon' => 'book-open', 'title' => 'Complete Rental Guide', 'url' => route('pillar.page'), 'desc' => 'Everything about porta potty rental'],
            ];
            @endphp
            @foreach($relatedParty as $r)
            <a href="{{ $r['url'] }}" class="bg-white border border-slate-200 rounded-xl p-5 hover:shadow-lg transition-all group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                        <x-icon name="{{ $r['icon'] }}" class="w-5 h-5" />
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-rose-600 transition">{{ $r['title'] }}</h3>
                </div>
                <p class="text-sm text-slate-600">{{ $r['desc'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Expert Tips for Party Porta Potty Rentals</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $tipsParty = [
                ['icon' => 'calendar', 'title' => 'Book 3-5 Days in Advance', 'desc' => 'While same-day delivery is available, booking 3-5 days ahead guarantees the best selection of unit types and delivery time slots. Weekends book up fast during summer party season.'],
                ['icon' => 'map-pin', 'title' => 'Plan Your Placement Carefully', 'desc' => 'Place porta potties on firm, level ground with a clear 6-foot access path for delivery. Keep them away from food serving areas but close enough to the party for convenience.'],
                ['icon' => 'users', 'title' => 'Add Extra Units If Serving Alcohol', 'desc' => 'Alcohol increases restroom usage by 20-30%. If you\'re serving beer, wine, or cocktails at your party, add at least one extra unit to prevent long lines.'],
                ['icon' => 'water-drop', 'title' => 'Consider Deluxe Units for Guest Comfort', 'desc' => 'Deluxe units with flushing toilets and hand sinks are worth the upgrade for parties. Guests appreciate the running water for hand washing, especially at food-centered events.'],
            ];
            @endphp
            @foreach($tipsParty as $t)
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
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

<section class="py-16 md:py-20 px-4 bg-gradient-to-br from-rose-600 to-rose-700 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Make Your Party Unforgettable</h2>
        <p class="text-lg text-rose-100 mb-3">Clean, reliable porta potties — delivered to your party before the guests arrive.</p>
        <p class="text-rose-200 mb-8 text-sm">Free quote · No hidden fees · Same-day delivery available</p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="party-final"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl md:text-3xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7" />
            {{ domain_phone_display() }}
        </a>
    </div>
</section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <x-icon name="phone" class="w-6 h-6" />
        <span>Call Now — Book Your Party Porta Potty</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection
