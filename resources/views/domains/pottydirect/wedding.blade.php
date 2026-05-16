@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'Wedding Porta Potty Rental | Luxury Trailers')
@section('meta_description', 'Wedding porta potty rental with elegant luxury restroom trailers & ADA options. Starting at $89/day with free delivery. Call for a free quote.')
@section('canonical', route('wedding'))
@push('schema')
@php
$breadcrumbSchema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Wedding Porta Potty Rental', 'item' => route('wedding')]]];
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        ["@type" => "Question", "name" => "How many porta potties do I need per wedding guest?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Plan 1 unit per 50 guests for events under 4 hours, 1 per 25 for longer events. Add 20% if alcohol is served. Luxury trailers count as 2-3 standard units. Call us for a custom recommendation."]],
        ["@type" => "Question", "name" => "Can I get a luxury restroom trailer for my outdoor wedding?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes! Luxury restroom trailers are perfect for outdoor weddings. They include climate control, porcelain toilets, vanity sinks, and can be set up anywhere accessible by truck."]],
        ["@type" => "Question", "name" => "How far in advance should I book wedding porta potties?", "acceptedAnswer" => ["@type" => "Answer", "text" => "We recommend booking 2-4 weeks in advance for weddings, especially during peak season (May-October). However, we can often accommodate last-minute bookings — call us."]],
        ["@type" => "Question", "name" => "Do you deliver wedding porta potties nationwide?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, we serve all 50 states. Delivery is included in the rental price for most locations within our service areas. Same-day delivery available for orders before 2PM."]],
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
@section('content')
<section class="relative py-16 md:py-20 overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIxIiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDMiLz48L3N2Zz4=')] opacity-50"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-slate-400 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3 sm:w-4 sm:h-4" />
            <span class="text-white">Wedding Porta Potty Rental</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-pink-500/15 text-pink-400 text-sm font-bold px-4 py-2 rounded-full mb-5">
            <x-icon name="sparkles" class="w-4 h-4" />
            WEDDING PORTA POTTY RENTAL
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">Luxury Restroom Trailers for Your <span class="text-pink-400">Perfect Wedding Day</span></h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">Elegant, climate-controlled restroom trailers that match your wedding style. Starting at <strong class="text-white">$89/day</strong> for standard units.</p>
        <div class="flex flex-wrap justify-center gap-3 text-sm text-slate-300 mb-8">
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="truck" class="w-4 h-4 text-emerald-400" />Free Delivery</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="sparkles" class="w-4 h-4 text-emerald-400" />Luxury Options</span>
            <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-4 py-2 rounded-full"><x-icon name="currency-dollar" class="w-4 h-4 text-emerald-400" />No Hidden Fees</span>
        </div>
        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}
        </a>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-6 text-center">Wedding Restroom Options</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">
                <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center mb-4"><x-icon name="sparkles" class="w-6 h-6 text-pink-600" /></div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">Luxury Trailer</h3>
                <p class="text-emerald-600 font-bold text-xl mb-3">$500 – $2,500/day</p>
                <p class="text-slate-600 text-sm">Climate-controlled with flushing toilets, sinks, mirrors, and elegant interiors. The ultimate wedding upgrade.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4"><x-icon name="water-drop" class="w-6 h-6 text-emerald-600" /></div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">Deluxe Flushable</h3>
                <p class="text-emerald-600 font-bold text-xl mb-3">$150 – $275/day</p>
                <p class="text-slate-600 text-sm">Flushing toilet with hand sink, mirror, and lighting. Perfect balance of comfort and value.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-lg transition">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4"><x-icon name="accessibility" class="w-6 h-6 text-blue-600" /></div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">ADA Accessible</h3>
                <p class="text-emerald-600 font-bold text-xl mb-3">$125 – $250/day</p>
                <p class="text-slate-600 text-sm">Wheelchair-accessible units with grab bars and spacious interior. Required for many venues.</p>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-6 text-center">Why Choose Potty Direct for Your Wedding</h2>
        <p class="text-slate-600 text-lg text-center mb-10 max-w-2xl mx-auto">Your wedding day deserves the best. Here is why hundreds of couples trust us for their big day.</p>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="sparkles" class="w-5 h-5 text-pink-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Elegant Luxury Trailers</h3>
                <p class="text-slate-600 text-sm">Our climate-controlled luxury restroom trailers feature porcelain toilets, vanity sinks, mirrors, and elegant interiors that complement any wedding theme. Guests will barely know they are in a portable restroom.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="truck" class="w-5 h-5 text-pink-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Reliable Delivery & Setup</h3>
                <p class="text-slate-600 text-sm">We deliver and set up your units before guests arrive, and pick them up after the last dance. Our team handles everything so you can focus on your special day.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="currency-dollar" class="w-5 h-5 text-pink-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Transparent Pricing</h3>
                <p class="text-slate-600 text-sm">No hidden fees, no surprise charges. We provide a clear, itemized quote upfront. Our wedding packages include delivery, setup, servicing, and pickup in one simple price.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="users" class="w-5 h-5 text-pink-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Expert Guidance</h3>
                <p class="text-slate-600 text-sm">Not sure how many units you need or which type works best for your venue? Our team walks you through every decision. Use our <a href="{{ route('calculator') }}" class="text-emerald-600 hover:underline font-medium">units calculator</a> for a quick estimate.</p>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Wedding Restroom Planning Tips</h2>
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-8 h-8 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center font-bold text-sm">1</span>
                    <h3 class="font-bold text-slate-800">Estimate guest count accurately</h3>
                </div>
                <p class="text-slate-600 text-sm">For a 4-hour wedding reception, plan one restroom stall per 50 guests. If your wedding is outdoors and alcohol is served, increase to one per 25 guests. Remember that luxury trailer stalls are more spacious and each stall can serve 2-3x more guests than a standard unit.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-8 h-8 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center font-bold text-sm">2</span>
                    <h3 class="font-bold text-slate-800">Consider venue access</h3>
                </div>
                <p class="text-slate-600 text-sm">Make sure your venue has truck access for delivery. Luxury trailers require more space for setup. We will work with your venue coordinator to identify the ideal placement that keeps restrooms convenient but out of sight from ceremony photos.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-8 h-8 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center font-bold text-sm">3</span>
                    <h3 class="font-bold text-slate-800">Book during peak season early</h3>
                </div>
                <p class="text-slate-600 text-sm">Wedding season runs May through October. Luxury restroom trailers are in high demand during these months. We recommend booking 2-4 weeks in advance. For last-minute weddings, call us — we often have availability even during peak season.</p>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Wedding Porta Potty FAQs</h2>
        @php $faqs = [['q' => 'How many porta potties do I need per wedding guest?', 'a' => 'Plan 1 unit per 50 guests for events under 4 hours, 1 per 25 for longer events. Add 20% if alcohol is served. Luxury trailers count as 2-3 standard units. Use our free calculator to get an exact number.'], ['q' => 'Can I get a luxury restroom trailer for my outdoor wedding?', 'a' => 'Yes! Luxury restroom trailers are perfect for outdoor weddings. They include climate control, porcelain toilets, vanity sinks, and can be set up anywhere accessible by truck.'], ['q' => 'How far in advance should I book wedding porta potties?', 'a' => 'We recommend booking 2-4 weeks in advance for weddings, especially during peak season (May-October). However, we can often accommodate last-minute bookings — call us.'], ['q' => 'Do you deliver wedding porta potties nationwide?', 'a' => 'Yes, we serve all 50 states. Delivery is included in the rental price for most locations within our service areas.']]; @endphp
        <div class="space-y-3">@foreach($faqs as $faq)<details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group"><summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-pink-600 transition list-none"><span>{{ $faq['q'] }}</span><span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-pink-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center">+</span></summary><div class="px-5 pb-5 text-slate-600 leading-relaxed"><p>{{ $faq['a'] }}</p></div></details>@endforeach</div>
    </div>
</section>
@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            @foreach($testimonials as $t)
            <div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all">
                <div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">
                    @for($i = 0; $i < $t['rating']; $i++)
                    <x-icon name="star" class="w-4 h-4 fill-current" />
                    @endfor
                </div>
                <p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p>
                <div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div>
                    <div>
                        <p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>
                        @if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Make your wedding unforgettable</h2>
        <p class="text-lg text-slate-400 mb-8">Call for a free quote on luxury restroom trailers for your special day.</p>
        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100"><div class="max-w-4xl mx-auto text-center"><h2 class="text-2xl font-bold text-slate-800 mb-6">Related Resources</h2><div class="flex flex-wrap justify-center gap-4"><a href="{{ route('comparison') }}" class="bg-slate-100 hover:bg-pink-100 text-slate-700 hover:text-pink-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Unit Comparison</a><a href="{{ route('pricing') }}" class="bg-slate-100 hover:bg-pink-100 text-slate-700 hover:text-pink-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Pricing Guide</a><a href="{{ route('calculator') }}" class="bg-slate-100 hover:bg-pink-100 text-slate-700 hover:text-pink-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Units Calculator</a><a href="{{ route('festival') }}" class="bg-slate-100 hover:bg-pink-100 text-slate-700 hover:text-pink-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Festival Rentals</a><a href="{{ route('restroom-trailer.page') }}" class="bg-slate-100 hover:bg-pink-100 text-slate-700 hover:text-pink-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Restroom Trailers</a><a href="{{ route('faq') }}" class="bg-slate-100 hover:bg-pink-100 text-slate-700 hover:text-pink-700 font-medium px-5 py-2.5 rounded-full transition text-sm">FAQ</a></div></div></section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50"><a href="tel:{{ domain_phone_raw() }}" class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30"><x-icon name="phone" class="w-6 h-6" /><span>Book Wedding Restrooms — Call Now</span></a></div>
<div class="h-20 md:hidden"></div>
@endsection