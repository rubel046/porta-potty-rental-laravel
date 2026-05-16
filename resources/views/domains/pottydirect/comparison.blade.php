@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'Standard vs Deluxe vs Luxury Porta Potty Guide')
@section('meta_description', 'Compare standard, deluxe & luxury porta potties. Features, pricing, and best-use scenarios for each unit type. Free consultation available.')
@section('canonical', route('comparison'))
@push('schema')
@php
$breadcrumbSchema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Porta Potty Comparison', 'item' => route('comparison')]]];
$productSchema = [
    "@context" => "https://schema.org",
    "@type" => "ItemList",
    "name" => "Porta Potty Comparison Guide",
    "description" => "Compare standard, deluxe, and luxury porta potty options to find the right fit for your needs.",
    "itemListElement" => [
        ["@type" => "ListItem", "position" => 1, "item" => ["@type" => "Product", "name" => "Standard Porta Potty", "description" => "Basic OSHA-compliant portable toilet starting at $89/day.", "offers" => ["@type" => "Offer", "price" => "89", "priceCurrency" => "USD", "priceValidUntil" => "2027-12-31", "availability" => "https://schema.org/InStock"]]],
        ["@type" => "ListItem", "position" => 2, "item" => ["@type" => "Product", "name" => "Deluxe Flushable Unit", "description" => "Premium flushable portable toilet with hand sink starting at $150/day.", "offers" => ["@type" => "Offer", "price" => "150", "priceCurrency" => "USD", "priceValidUntil" => "2027-12-31", "availability" => "https://schema.org/InStock"]]],
        ["@type" => "ListItem", "position" => 3, "item" => ["@type" => "Product", "name" => "Luxury Restroom Trailer", "description" => "Climate-controlled luxury restroom trailer with porcelain fixtures starting at $500/day.", "offers" => ["@type" => "Offer", "price" => "500", "priceCurrency" => "USD", "priceValidUntil" => "2027-12-31", "availability" => "https://schema.org/InStock"]]],
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
@section('content')
<section class="relative py-16 md:py-20 overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-slate-400 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3 sm:w-4 sm:h-4" />
            <span class="text-white">Porta Potty Comparison</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-blue-500/15 text-blue-400 text-sm font-bold px-4 py-2 rounded-full mb-5"><x-icon name="building" class="w-4 h-4" />COMPARISON GUIDE</div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">Standard vs Deluxe vs Luxury <span class="text-blue-400">Porta Potty</span></h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">Not sure which porta potty type is right for you? Compare features, pricing, and best-use scenarios to make the right choice.</p>
        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Compare Porta Potty Types</h2>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
            <table class="w-full text-sm"><thead><tr class="bg-slate-900 text-white"><th class="text-left p-4 font-bold">Feature</th><th class="p-4 font-bold text-center">Standard</th><th class="p-4 font-bold text-center">Deluxe Flushable</th><th class="p-4 font-bold text-center">Luxury Trailer</th></tr></thead>
            <tbody>
            <tr class="border-t border-slate-100"><td class="p-4 font-medium">Price (per day)</td><td class="p-4 text-center"><span class="font-bold text-emerald-600">$89 – $175</span></td><td class="p-4 text-center"><span class="font-bold text-emerald-600">$150 – $275</span></td><td class="p-4 text-center"><span class="font-bold text-emerald-600">$500 – $2,500</span></td></tr>
            <tr class="border-t border-slate-100 bg-slate-50/50"><td class="p-4 font-medium">Flushing Toilet</td><td class="p-4 text-center text-slate-400">No</td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td></tr>
            <tr class="border-t border-slate-100"><td class="p-4 font-medium">Hand Sink</td><td class="p-4 text-center text-slate-400">No</td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td></tr>
            <tr class="border-t border-slate-100 bg-slate-50/50"><td class="p-4 font-medium">Climate Control</td><td class="p-4 text-center text-slate-400">No</td><td class="p-4 text-center text-slate-400">No</td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td></tr>
            <tr class="border-t border-slate-100"><td class="p-4 font-medium">Porcelain Fixtures</td><td class="p-4 text-center text-slate-400">No</td><td class="p-4 text-center text-slate-400">No</td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td></tr>
            <tr class="border-t border-slate-100 bg-slate-50/50"><td class="p-4 font-medium">Interior Lighting</td><td class="p-4 text-center text-slate-400">No</td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td></tr>
            <tr class="border-t border-slate-100"><td class="p-4 font-medium">OSHA Compliant</td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td></tr>
            <tr class="border-t border-slate-100 bg-slate-50/50"><td class="p-4 font-medium">ADA Option</td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td><td class="p-4 text-center"><span class="text-emerald-600">✓</span></td></tr>
            <tr class="border-t border-slate-100"><td class="p-4 font-medium">Best For</td><td class="p-4 text-center text-xs">Construction, Short Events, Job Sites</td><td class="p-4 text-center text-xs">Weddings, Parties, Corporate Events</td><td class="p-4 text-center text-xs">VIP Events, Weddings, Film Sets</td></tr>
            </tbody></table>
        </div>
        <div class="mt-8 grid md:grid-cols-3 gap-6">
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center"><div class="w-14 h-14 bg-slate-200 rounded-xl flex items-center justify-center mx-auto mb-4"><x-icon name="building" class="w-7 h-7 text-slate-600" /></div><h3 class="font-bold text-lg mb-2">Standard</h3><p class="text-emerald-600 font-bold text-xl mb-3">$89 – $175/day</p><p class="text-slate-600 text-sm mb-4">Basic, functional, OSHA-compliant. Perfect for construction sites and work zones. Includes weekly servicing.</p><a href="https://pottydirect.com/pricing#price-standard" class="text-emerald-600 font-semibold hover:underline text-sm">View pricing →</a></div>
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 text-center ring-2 ring-emerald-400"><div class="w-14 h-14 bg-emerald-200 rounded-xl flex items-center justify-center mx-auto mb-4"><x-icon name="water-drop" class="w-7 h-7 text-emerald-600" /></div><span class="text-xs bg-emerald-500 text-white px-2 py-0.5 rounded-full mb-2 inline-block">MOST POPULAR</span><h3 class="font-bold text-lg mb-2">Deluxe</h3><p class="text-emerald-600 font-bold text-xl mb-3">$150 – $275/day</p><p class="text-slate-600 text-sm mb-4">Flushing toilet with sink, mirror, and lighting. Ideal for weddings and private events.</p><a href="https://pottydirect.com/pricing#price-deluxe" class="text-emerald-600 font-semibold hover:underline text-sm">View pricing →</a></div>
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center"><div class="w-14 h-14 bg-amber-200 rounded-xl flex items-center justify-center mx-auto mb-4"><x-icon name="sparkles" class="w-7 h-7 text-amber-600" /></div><h3 class="font-bold text-lg mb-2">Luxury Trailer</h3><p class="text-emerald-600 font-bold text-xl mb-3">$500 – $2,500/day</p><p class="text-slate-600 text-sm mb-4">Climate-controlled with porcelain fixtures. The ultimate restroom experience for VIP events.</p><a href="https://pottydirect.com/pricing#price-luxury" class="text-emerald-600 font-semibold hover:underline text-sm">View pricing →</a></div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Detailed Comparison by Use Case</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-slate-50 rounded-2xl p-6">
                <h3 class="font-bold text-xl text-slate-800 mb-3">Standard Units</h3>
                <p class="text-slate-600 mb-4">Best for construction sites, job sites, and short-term events where basic functionality is all you need. Standard units are OSHA-compliant and include:</p>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Ventilated holding tank</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Urinal and toilet seat</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Toilet paper dispenser</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Weekly servicing included</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Most affordable option at $89-$175/day</li>
                </ul>
            </div>
            <div class="bg-emerald-50 rounded-2xl p-6 ring-2 ring-emerald-400">
                <span class="text-xs bg-emerald-500 text-white px-3 py-1 rounded-full mb-3 inline-block">BEST VALUE</span>
                <h3 class="font-bold text-xl text-slate-800 mb-3">Deluxe Units</h3>
                <p class="text-slate-600 mb-4">The perfect middle ground for weddings, parties, and corporate events. Deluxe units provide a more comfortable experience with:</p>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Flushing toilet mechanism</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Hand sink with soap dispenser</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Mirror and interior lighting</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Large interior footprint</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Premium feel at $150-$275/day</li>
                </ul>
            </div>
            <div class="bg-amber-50 rounded-2xl p-6">
                <h3 class="font-bold text-xl text-slate-800 mb-3">Luxury Trailers</h3>
                <p class="text-slate-600 mb-4">For VIP events, upscale weddings, and film productions where image matters. Luxury trailers deliver an experience comparable to indoor restrooms:</p>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Climate-controlled interior</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Porcelain flushing toilets</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Vanity sinks with running water</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Full-length mirrors and lighting</li>
                    <li class="flex items-start gap-2"><x-icon name="check-circle" class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" />Premium experience at $500-$2,500/day</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-6 text-center">How to Choose the Right Porta Potty</h2>
        <div class="space-y-6">
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold flex-shrink-0 mt-1">1</div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Consider your event type</h3>
                    <p class="text-slate-600">Construction sites and work zones only need standard OSHA-compliant units. Weddings and upscale events benefit from deluxe or luxury options. Festivals and large gatherings often mix standard and deluxe units to balance budget and guest experience.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold flex-shrink-0 mt-1">2</div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Calculate the right quantity</h3>
                    <p class="text-slate-600">A common rule of thumb is one unit per 50 guests for events under 4 hours. For longer events or when alcohol is served, increase to one per 25 guests. Each luxury trailer stall counts as 2-3 standard units. Use our <a href="{{ route('calculator') }}" class="text-emerald-600 hover:underline font-medium">units calculator</a> for an exact recommendation.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold flex-shrink-0 mt-1">3</div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Factor in accessibility requirements</h3>
                    <p class="text-slate-600">ADA-compliant units are required for public events and many venues. Building codes and permit requirements may also dictate the number and type of units you need. Our <a href="{{ route('osha.guide') }}" class="text-emerald-600 hover:underline font-medium">OSHA requirements guide</a> covers all regulations.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50"><div class="max-w-3xl mx-auto text-center"><h2 class="text-3xl font-bold text-slate-800 mb-4">Not sure which is right for you?</h2><p class="text-slate-600 mb-8">Call us for a free consultation. We'll help you choose the perfect porta potty for your needs.</p><a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl font-bold py-4 px-8 rounded-full shadow-xl shadow-amber-500/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-5 h-5" />{{ domain_phone_display() }}</a></div></section>
@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50"><div class="max-w-5xl mx-auto"><h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2><div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">@foreach($testimonials as $t)<div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all"><div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">@for($i = 0; $i < $t['rating']; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor</div><p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p><div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100"><div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div><div><p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>@if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif</div></div></div>@endforeach</div></div></section>
@endif
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center"><div class="max-w-3xl mx-auto"><h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to order? Call now</h2><p class="text-lg text-slate-400 mb-8">Free quote, same-day delivery, no hidden fees.</p><a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a></div></section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100"><div class="max-w-4xl mx-auto text-center"><h2 class="text-2xl font-bold text-slate-800 mb-6">Related Resources</h2><div class="flex flex-wrap justify-center gap-4"><a href="{{ route('pricing') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Full Pricing Guide</a><a href="{{ route('osha.guide') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">OSHA Requirements</a><a href="{{ route('calculator') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Units Calculator</a><a href="{{ route('cost.page') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Cost Guide</a><a href="{{ route('construction.landing') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Construction Rental</a><a href="{{ route('faq') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">FAQ</a></div></div></section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50"><a href="tel:{{ domain_phone_raw() }}" class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30"><x-icon name="phone" class="w-6 h-6" /><span>Call for Help Choosing</span></a></div><div class="h-20 md:hidden"></div>
@endsection