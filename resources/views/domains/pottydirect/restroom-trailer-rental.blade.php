@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', 'Luxury Restroom Trailer Rental | PottyDirect')
@section('meta_description', 'Rent luxury restroom trailers for weddings, corporate events & VIP occasions. Climate-controlled, flushing toilets, running water. Starting at $500/day.')
@section('canonical', route('restroom-trailer.page'))

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {"@type": "Question", "name": "How much does it cost to rent a restroom trailer?", "acceptedAnswer": {"@type": "Answer", "text": "Restroom trailer rental costs range from $500 to $2,500 per day depending on size, features, and location. A standard 2-stall luxury trailer with A/C starts around $895 per day."}},
        {"@type": "Question", "name": "What is included in a restroom trailer rental?", "acceptedAnswer": {"@type": "Answer", "text": "Climate control (A/C and heat), flushing porcelain toilets, running water sinks, mirrors, LED lighting, separate men's and women's sides, fresh water system, and weekly servicing."}},
        {"@type": "Question", "name": "Do restroom trailers need electricity and water hookup?", "acceptedAnswer": {"@type": "Answer", "text": "Most luxury restroom trailers require a standard 110v electrical outlet and a garden hose water connection. Some larger models have onboard generators and water tanks for off-grid use."}},
        {"@type": "Question", "name": "How many people can a restroom trailer accommodate?", "acceptedAnswer": {"@type": "Answer", "text": "A 2-stall trailer comfortably serves 100-150 guests. A 4-stall trailer serves 200-300 guests. For larger events, multiple trailers or additional standard units can supplement."}},
        {"@type": "Question", "name": "Are restroom trailers better than porta potties for weddings?", "acceptedAnswer": {"@type": "Answer", "text": "For upscale weddings, restroom trailers provide a significantly better guest experience with flushing toilets, running water, climate control, and elegant interiors. They match the aesthetic of high-end venues."}}
    ]
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}"},
        {"@type": "ListItem", "position": 2, "name": "Restroom Trailer Rental", "item": "{{ route('restroom-trailer.page') }}"}
    ]
}
</script>
@endpush

@section('content')

<section class="relative py-16 md:py-20 overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;><g fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.05&quot;><path d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/></g></g></svg>')"></div>
    <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 text-xs sm:text-sm text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3" />
            <span class="text-white">Restroom Trailer Rental</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-400 text-sm font-bold px-4 py-2 rounded-full mb-5">
            <x-icon name="sparkles" class="w-4 h-4" />
            PREMIUM RESTROOM TRAILERS
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5 leading-tight">Luxury Restroom Trailer Rental<span class="block text-2xl md:text-3xl mt-3 text-emerald-400">Elegant Bathrooms for Your Event</span></h1>
        <p class="text-lg md:text-xl text-slate-300 max-w-2xl mx-auto mb-8">
            Climate-controlled, flushing toilets, running water. Starting at <strong class="text-white">$500/day</strong>.
            Perfect for weddings, corporate events, and VIP occasions.
        </p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="trailer-hero"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl md:text-2xl font-bold py-4 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-6 h-6" />
            {{ domain_phone_display() }}
        </a>
        <p class="mt-4 text-sm text-slate-400">Free quote · No hidden fees · Delivery & setup included</p>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-10">Our Restroom Trailer Options</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @php
            $trailers = [
                ['icon' => 'building', 'name' => '2-Stall Standard', 'capacity' => 'Up to 100 guests', 'price' => '$500 – $895', 'features' => 'Climate control, 2 private stalls, sink, mirror, LED lighting', 'popular' => true],
                ['icon' => 'building', 'name' => '4-Stall Deluxe', 'capacity' => 'Up to 200 guests', 'price' => '$895 – $1,500', 'features' => 'Separate men/women sides, 4 stalls, A/C, heat, running water, vanity', 'popular' => false],
                ['icon' => 'sparkles', 'name' => '8-Stall VIP', 'capacity' => 'Up to 400 guests', 'price' => '$1,500 – $2,500', 'features' => 'Full handicap access, premium finishes, attendant station, music system', 'popular' => false],
            ];
            @endphp
            @foreach($trailers as $t)
            <div class="bg-white border @if($t['popular']) border-amber-300 ring-2 ring-amber-100 @else border-slate-200 @endif rounded-2xl p-6 hover:shadow-xl transition-all">
                @if($t['popular'])
                <div class="text-xs font-bold bg-amber-500 text-white px-3 py-1 rounded-full inline-block mb-3">MOST POPULAR</div>
                @endif
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center mb-4">
                    <x-icon name="{{ $t['icon'] }}" class="w-6 h-6 text-white" />
                </div>
                <h3 class="font-bold text-lg text-slate-800">{{ $t['name'] }}</h3>
                <p class="text-xs text-slate-500 mb-1">{{ $t['capacity'] }}</p>
                <div class="text-2xl font-extrabold text-emerald-600 my-3">{{ $t['price'] }}<span class="text-sm text-slate-400 font-normal">/day</span></div>
                <p class="text-sm text-slate-600 mb-4">{{ $t['features'] }}</p>
                <a href="tel:{{ domain_phone_raw() }}"
                   data-tracking-label="trailer-{{ strtolower(str_replace(' ', '-', $t['name'])) }}"
                   class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-400 text-white font-bold text-sm px-4 py-2.5 rounded-full transition-all hover:scale-105 min-h-[44px]">
                    <x-icon name="phone" class="w-4 h-4" />
                    Reserve This Trailer
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-6 text-center">Restroom Trailer vs Porta Potty: Which Is Right?</h2>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="text-left p-4 font-bold">Feature</th>
                        <th class="p-4 font-bold text-center">Restroom Trailer</th>
                        <th class="p-4 font-bold text-center">Standard Porta Potty</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $comparisons = [
                        ['feature' => 'Cost per day', 'trailer' => '$500 – $2,500', 'potty' => '$89 – $175'],
                        ['feature' => 'Flushing toilet', 'trailer' => '✅ Yes', 'potty' => '❌ No (standard)'],
                        ['feature' => 'Running water sink', 'trailer' => '✅ Yes', 'potty' => '❌ No'],
                        ['feature' => 'Climate control', 'trailer' => '✅ A/C & heat', 'potty' => '❌ No'],
                        ['feature' => 'Lighting', 'trailer' => '✅ LED interior', 'potty' => '✅ Basic'],
                        ['feature' => 'Capacity per unit', 'trailer' => 'Up to 8 stalls', 'potty' => '1 stall'],
                        ['feature' => 'Best for', 'trailer' => 'Upscale events, weddings', 'potty' => 'Construction, casual events'],
                    ];
                    @endphp
                    @foreach($comparisons as $c)
                    <tr class="border-t border-slate-100">
                        <td class="p-4 font-medium text-slate-800">{{ $c['feature'] }}</td>
                        <td class="p-4 text-center text-emerald-600 font-medium">{{ $c['trailer'] }}</td>
                        <td class="p-4 text-center text-slate-600">{{ $c['potty'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-4 text-center">Restroom Trailer FAQs</h2>
        <p class="text-center text-slate-600 mb-8 max-w-2xl mx-auto">Common questions about luxury restroom trailer rentals, answered by our experts.</p>
        @php
        $trailerFaqs = [
            ['q' => 'How much does a restroom trailer cost for a wedding?', 'a' => 'Wedding restroom trailers typically cost $895-$1,500 per day for a 2-4 stall unit with climate control. Price depends on location, rental duration, and features. Call for a wedding-specific quote.'],
            ['q' => 'What size restroom trailer do I need for 150 guests?', 'a' => 'A 2-stall restroom trailer comfortably serves 100-150 guests. For 150 guests, a 4-stall trailer provides extra comfort and shorter wait times. We recommend the larger option for evening events.'],
            ['q' => 'Do you deliver and set up restroom trailers?', 'a' => 'Yes. Delivery, setup, and pickup are included in the rental price. We handle all logistics — you just enjoy your event. Delivery requires a clear path at least 12 feet wide with 14 feet vertical clearance.'],
            ['q' => 'Can a restroom trailer be placed on grass?', 'a' => 'Yes, as long as the ground is firm and level. For soft ground or after rain, we may use plywood or gravel to prevent sinking. We assess the site before delivery.'],
            ['q' => 'What happens if the restroom trailer runs out of water?', 'a' => 'Our luxury trailers have large fresh water tanks (50-100 gallons) that last through most events. For multi-day events, we schedule regular servicing to refill water and pump waste tanks.'],
        ];
        @endphp
        <div class="space-y-3">
            @foreach($trailerFaqs as $faq)
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
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Why Choose PottyDirect for Restroom Trailers</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $whyTrailer = [
                ['icon' => 'sparkles', 'title' => 'Premium Guest Experience', 'desc' => 'Our luxury trailers feature porcelain flushing toilets, running water sinks, LED mirrors, climate control, and elegant interiors. Your guests will never know they\'re in a portable restroom.'],
                ['icon' => 'building', 'title' => 'Wide Range of Sizes', 'desc' => 'From intimate 2-stall trailers for small weddings to 8-stall VIP units for large galas — we have the perfect trailer for your event capacity and budget.'],
                ['icon' => 'truck', 'title' => 'Full-Service Delivery & Setup', 'desc' => 'We handle everything — delivery, leveling, water fill, power connection, and final setup. Your trailer arrives ready to use. We also handle weekly servicing and pickup.'],
                ['icon' => 'star', 'title' => 'Event-Ready Presentation', 'desc' => 'Restroom trailers are pressure-washed, detailed, and inspected before every delivery. We take pride in delivering trailers that look as good as they function.'],
            ];
            @endphp
            @foreach($whyTrailer as $w)
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
            $relatedTrailer = [
                ['icon' => 'building', 'title' => 'Compare Trailer vs Standard', 'url' => route('comparison'), 'desc' => 'Trailers vs porta potties side-by-side'],
                ['icon' => 'sparkles', 'title' => 'Wedding Trailer Rentals', 'url' => route('wedding'), 'desc' => 'Luxury trailers for your big day'],
                ['icon' => 'music', 'title' => 'Event & Festival Rentals', 'url' => route('festival'), 'desc' => 'Volume trailer solutions'],
                ['icon' => 'currency-dollar', 'title' => 'Complete Pricing Guide', 'url' => route('pricing'), 'desc' => 'All trailer pricing tiers'],
                ['icon' => 'calculator', 'title' => 'Units Calculator', 'url' => route('calculator'), 'desc' => 'Calculate your trailer needs'],
                ['icon' => 'book-open', 'title' => 'Complete Rental Guide', 'url' => route('pillar.page'), 'desc' => 'Everything about porta potty rental'],
            ];
            @endphp
            @foreach($relatedTrailer as $r)
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
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Expert Tips for Restroom Trailer Rentals</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $tipsTrailer = [
                ['icon' => 'calendar', 'title' => 'Book 2-4 Weeks Ahead for Weddings', 'desc' => 'Restroom trailers are in high demand during wedding season (April-October). Book at least 2-4 weeks in advance to secure the trailer size and features you need for your date.'],
                ['icon' => 'map-pin', 'title' => 'Prepare Your Site Properly', 'desc' => 'Trailers require a level surface and a 12-foot-wide access path with 14-foot vertical clearance. Firm ground is essential — soft lawns may need plywood track mats. We can advise on placement.'],
                ['icon' => 'users', 'title' => 'Size Up for Guest Comfort', 'desc' => 'A 2-stall trailer serves 100 guests adequately. For 150+ guests, upgrade to a 4-stall trailer. Guests at upscale events expect no lines — oversizing slightly is worth the investment.'],
                ['icon' => 'lightning', 'title' => 'Plan for Power & Water', 'desc' => 'Most trailers need a standard 110v outlet and garden hose connection. Some models have onboard generators and water tanks for venues without hookups. Discuss site logistics with our team.'],
            ];
            @endphp
            @foreach($tipsTrailer as $t)
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

<section class="py-16 md:py-20 px-4 bg-gradient-to-br from-amber-500 to-orange-500 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Make Your Event Unforgettable</h2>
        <p class="text-lg text-amber-100 mb-3">Premium restroom trailers delivered to your venue. Your guests will thank you.</p>
        <p class="text-amber-200 mb-8 text-sm">Free quote · Delivery & setup included · Weekly servicing</p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="trailer-final"
           class="inline-flex items-center gap-3 bg-slate-900 hover:bg-slate-800 text-white text-2xl md:text-3xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-slate-900/40 ring-4 ring-white/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7" />
            {{ domain_phone_display() }}
        </a>
    </div>
</section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <x-icon name="phone" class="w-6 h-6" />
        <span>Book a Restroom Trailer — {{ domain_phone_display() }}</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection
