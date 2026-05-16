@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'Complete Guide to Porta Potty Types | Standard, Deluxe, ADA & Luxury | PottyDirect')
@section('meta_description', 'Complete guide to porta potty types: Standard ($89/day), Deluxe ($150/day), ADA ($125/day), Luxury ($500/day). Compare features and find the right unit for your needs.')
@section('canonical', route('types-guide.page'))
@push('schema')
@php
$breadcrumbSchema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Porta Potty Types Guide', 'item' => route('types-guide.page')]]];
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        ["@type" => "Question", "name" => "What is the difference between a standard and deluxe porta potty?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Standard porta potties ($89-$175/day) are basic units with a non-splash urinal, ventilation, and hand sanitizer. Deluxe units ($150-$275/day) add a flushing toilet, hand sink with running water, interior mirror, and better ventilation — ideal for weddings and upscale events."]],
        ["@type" => "Question", "name" => "What features does an ADA porta potty have?", "acceptedAnswer" => ["@type" => "Answer", "text" => "ADA-compliant porta potties ($125-$250/day) feature a 60-inch wide door for wheelchair access, interior grab bars, non-slip flooring, lowered seat height, and a spacious interior with 90-inch ceilings. They meet all federal ADA requirements."]],
        ["@type" => "Question", "name" => "How much does a luxury restroom trailer cost?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Luxury restroom trailers range from $500 to $2,500 per day. They include climate control (A/C and heat), porcelain flush toilets, vanity sinks with running water, LED lighting, and separate men's and women's sides. Perfect for VIP events and weddings."]],
        ["@type" => "Question", "name" => "Are standard porta potties OSHA compliant?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes. Standard porta potties meet all OSHA 1926.51 requirements for construction sites. They include a non-splash urinal, ventilation, anti-slip floor, hand sanitizer dispenser, and toilet paper holder. Weekly servicing is included in every rental."]],
        ["@type" => "Question", "name" => "Which porta potty type is best for a wedding?", "acceptedAnswer" => ["@type" => "Answer", "text" => "For weddings, luxury restroom trailers ($500-$2,500/day) provide the best guest experience with climate control and porcelain fixtures. Deluxe units ($150-$275/day) offer a great mid-range option with flushing toilets and sinks. Standard units work for budget-friendly or casual outdoor weddings."]],
        ["@type" => "Question", "name" => "What is the most affordable porta potty option?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Standard porta potties at $89-$175/day are the most affordable option. They are OSHA-compliant, include weekly servicing, and work well for construction sites, short events, and budget-conscious projects. Volume discounts are available for 5+ units."]],
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
            <span class="text-white">Porta Potty Types Guide</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-blue-500/15 text-blue-400 text-sm font-bold px-4 py-2 rounded-full mb-5"><x-icon name="book-open" class="w-4 h-4" />COMPLETE GUIDE</div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">Complete Guide to <span class="text-blue-400">Porta Potty Types</span></h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">Compare Standard, Deluxe, ADA, and Luxury options. Find the perfect unit for your event, construction site, or project.</p>
        <a href="tel:{{ domain_phone_raw() }}" data-tracking-label="types-guide-hero" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl md:text-2xl font-bold py-4 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-6 h-6" />{{ domain_phone_display() }}</a>
        <p class="mt-4 text-sm text-slate-400">Free consultation — real humans answer in under 15 seconds</p>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4 text-center">Porta Potty Types Comparison Table</h2>
        <p class="text-lg text-slate-600 mb-8 text-center">Side-by-side comparison of all porta potty types with features, pricing, and best use cases.</p>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="text-left p-4 md:p-5 font-bold">Feature</th>
                        <th class="p-4 md:p-5 font-bold text-center">Standard</th>
                        <th class="p-4 md:p-5 font-bold text-center">Deluxe</th>
                        <th class="p-4 md:p-5 font-bold text-center">ADA Accessible</th>
                        <th class="p-4 md:p-5 font-bold text-center">Luxury Trailer</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-slate-100"><td class="p-4 font-medium">Price Per Day</td><td class="p-4 text-center font-bold text-emerald-600">$89 – $175</td><td class="p-4 text-center font-bold text-emerald-600">$150 – $275</td><td class="p-4 text-center font-bold text-emerald-600">$125 – $250</td><td class="p-4 text-center font-bold text-emerald-600">$500 – $2,500</td></tr>
                    <tr class="border-t border-slate-100 bg-slate-50/50"><td class="p-4 font-medium">Flushing Toilet</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-emerald-600">✓</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-emerald-600">✓</td></tr>
                    <tr class="border-t border-slate-100"><td class="p-4 font-medium">Hand Sink</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-emerald-600">✓</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-emerald-600">✓</td></tr>
                    <tr class="border-t border-slate-100 bg-slate-50/50"><td class="p-4 font-medium">Climate Control</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-emerald-600">✓</td></tr>
                    <tr class="border-t border-slate-100"><td class="p-4 font-medium">Wheelchair Access</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-emerald-600">✓</td><td class="p-4 text-center text-emerald-600">✓</td></tr>
                    <tr class="border-t border-slate-100 bg-slate-50/50"><td class="p-4 font-medium">Interior Lighting</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-emerald-600">✓</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-emerald-600">✓</td></tr>
                    <tr class="border-t border-slate-100"><td class="p-4 font-medium">Porcelain Fixtures</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-slate-400">&mdash;</td><td class="p-4 text-center text-emerald-600">✓</td></tr>
                    <tr class="border-t border-slate-100 bg-slate-50/50"><td class="p-4 font-medium">OSHA Compliant</td><td class="p-4 text-center text-emerald-600">✓</td><td class="p-4 text-center text-emerald-600">✓</td><td class="p-4 text-center text-emerald-600">✓</td><td class="p-4 text-center text-emerald-600">✓</td></tr>
                    <tr class="border-t border-slate-100"><td class="p-4 font-medium">Weekly Servicing</td><td class="p-4 text-center text-emerald-600">✓</td><td class="p-4 text-center text-emerald-600">✓</td><td class="p-4 text-center text-emerald-600">✓</td><td class="p-4 text-center text-emerald-600">✓</td></tr>
                    <tr class="border-t border-slate-100 bg-slate-50/50"><td class="p-4 font-medium">Best For</td><td class="p-4 text-center text-xs">Construction, Job Sites, Short Events</td><td class="p-4 text-center text-xs">Weddings, Parties, Corporate Events</td><td class="p-4 text-center text-xs">Public Events, ADA Compliance</td><td class="p-4 text-center text-xs">VIP Events, Weddings, Film Sets</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Which Unit Is Right for You?</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mb-4"><x-icon name="building" class="w-6 h-6 text-slate-600" /></div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">Standard &mdash; For Construction & Work Sites</h3>
                <p class="text-slate-600 text-sm">Best for construction sites, work zones, and short-term events where basic functionality is all you need. OSHA compliant with weekly servicing included. Most budget-friendly option at $89-$175/day.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4"><x-icon name="water-drop" class="w-6 h-6 text-emerald-600" /></div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">Deluxe &mdash; For Events & Guest Comfort</h3>
                <p class="text-slate-600 text-sm">Ideal for weddings, private parties, corporate events, and any occasion where guest comfort is important. Features a flushing toilet, hand sink, mirror, and lighting. $150-$275/day.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4"><x-icon name="accessibility" class="w-6 h-6 text-blue-600" /></div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">ADA &mdash; For Accessibility Compliance</h3>
                <p class="text-slate-600 text-sm">Required for public events and many venues. Features a 60-inch wide door, grab bars, non-slip flooring, and spacious interior. Meets all federal ADA requirements. $125-$250/day.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4"><x-icon name="sparkles" class="w-6 h-6 text-amber-600" /></div>
                <h3 class="font-bold text-lg text-slate-800 mb-2">Luxury Trailer &mdash; For Premium Experiences</h3>
                <p class="text-slate-600 text-sm">The ultimate restroom experience with climate control, porcelain flush toilets, vanity sinks, and elegant interiors. Perfect for VIP events, upscale weddings, and film productions. $500-$2,500/day.</p>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Detailed Breakdown by Type</h2>
        <div class="space-y-8">
            <div class="bg-slate-50 rounded-2xl p-6 md:p-8">
                <h3 class="text-2xl font-bold text-slate-800 mb-4">Standard Porta Potty ($89 – $175/day)</h3>
                <p class="text-slate-600 mb-4">The standard porta potty is the most common type of portable toilet and the backbone of the portable sanitation industry. These units are designed for functionality, durability, and compliance with OSHA regulations for construction sites and work zones. Every standard unit includes a non-splash urinal, ventilation system, anti-slip floor, hand sanitizer dispenser, and toilet paper holder. They are built to withstand heavy use at construction sites, outdoor events, and festivals. Weekly servicing is included in every rental, which covers cleaning, pumping, sanitizing, and restocking supplies. At $89 to $175 per day, standard units offer the most affordable porta potty rental option while still meeting all workplace safety requirements. Volume discounts are available for orders of 5 or more units, making them even more cost-effective for large construction projects.</p>
                <div class="grid md:grid-cols-3 gap-3">
                    <div class="bg-white border border-slate-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">OSHA Compliant</p></div>
                    <div class="bg-white border border-slate-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">Budget-Friendly</p></div>
                    <div class="bg-white border border-slate-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">Weekly Servicing</p></div>
                </div>
            </div>
            <div class="bg-emerald-50 rounded-2xl p-6 md:p-8">
                <h3 class="text-2xl font-bold text-slate-800 mb-4">Deluxe Flushable Unit ($150 – $275/day)</h3>
                <p class="text-slate-600 mb-4">Deluxe flushable porta potties represent a significant upgrade from standard units, offering a much more comfortable and sanitary user experience. The key differentiator is the flushing toilet mechanism, which provides a more familiar restroom experience. These units also include a hand sink with running water, an interior mirror, better ventilation, and interior lighting. Deluxe units are the most popular choice for weddings, private parties, corporate events, and other gatherings where guest comfort is a priority. The hand washing station encourages better hygiene, and the mirror adds a touch of convenience. At $150 to $275 per day, deluxe units strike the perfect balance between affordability and premium features. Many event planners choose a mix of standard and deluxe units to manage budgets while still providing upgraded options for guests.</p>
                <div class="grid md:grid-cols-3 gap-3">
                    <div class="bg-white border border-emerald-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">Flushing Toilet</p></div>
                    <div class="bg-white border border-emerald-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">Hand Sink</p></div>
                    <div class="bg-white border border-emerald-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">Interior Lighting</p></div>
                </div>
            </div>
            <div class="bg-blue-50 rounded-2xl p-6 md:p-8">
                <h3 class="text-2xl font-bold text-slate-800 mb-4">ADA Accessible Unit ($125 – $250/day)</h3>
                <p class="text-slate-600 mb-4">ADA-compliant porta potties are specifically designed to meet the accessibility requirements of the Americans with Disabilities Act. These units feature a 60-inch wide door that easily accommodates wheelchairs, interior grab bars for stability and transfer, non-slip flooring for safety, a lowered seat height for easier access, and a spacious interior with 90-inch ceilings providing ample maneuverability space. ADA units are required by law for many public events, government facilities, and venues that receive federal funding. They are also recommended for any event or location where individuals with disabilities may be present. The rental cost ranges from $125 to $250 per day. Having at least one ADA-compliant unit per 20 standard units is a best practice for public events and is often required by local permits. These units can also serve as standard units when ADA compliance is not strictly required, making them a versatile addition to any rental order.</p>
                <div class="grid md:grid-cols-3 gap-3">
                    <div class="bg-white border border-blue-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">Wheelchair Accessible</p></div>
                    <div class="bg-white border border-blue-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">Grab Bars</p></div>
                    <div class="bg-white border border-blue-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">ADA Compliant</p></div>
                </div>
            </div>
            <div class="bg-amber-50 rounded-2xl p-6 md:p-8">
                <h3 class="text-2xl font-bold text-slate-800 mb-4">Luxury Restroom Trailer ($500 – $2,500/day)</h3>
                <p class="text-slate-600 mb-4">Luxury restroom trailers represent the highest tier of portable sanitation. These are not porta potties in the traditional sense — they are fully featured mobile restroom facilities that rival indoor bathrooms. Every luxury trailer includes climate control (both air conditioning and heating), porcelain flush toilets, vanity sinks with running water, full-length mirrors, LED lighting, and separate men's and women's sides. The interior is finished with high-quality materials and elegant lighting fixtures. These trailers are perfect for upscale weddings, VIP events, film and television productions, corporate galas, and any occasion where image and guest experience are paramount. Pricing ranges from $500 to $2,500 per day depending on the trailer size, number of stalls, and amenities. A typical two-stall wedding trailer with a separate ADA-compliant stall averages $895 to $1,500 per day. Luxury trailers require a suitable truck-access location for delivery and setup.</p>
                <div class="grid md:grid-cols-3 gap-3">
                    <div class="bg-white border border-amber-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">Climate Controlled</p></div>
                    <div class="bg-white border border-amber-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">Porcelain Fixtures</p></div>
                    <div class="bg-white border border-amber-200 rounded-xl p-4 text-center"><x-icon name="check-circle" class="w-5 h-5 text-emerald-500 mx-auto mb-2" /><p class="text-sm font-medium text-slate-700">Separate M/F Sides</p></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Frequently Asked Questions</h2>
        <div class="space-y-3">
@php $faqs = [['q' => 'What is the difference between a standard and deluxe porta potty?', 'a' => 'Standard porta potties ($89-$175/day) are basic units with a non-splash urinal, ventilation, and hand sanitizer. Deluxe units ($150-$275/day) add a flushing toilet, hand sink with running water, interior mirror, and better ventilation — ideal for weddings and upscale events.'], ['q' => 'What features does an ADA porta potty have?', 'a' => 'ADA-compliant porta potties ($125-$250/day) feature a 60-inch wide door for wheelchair access, interior grab bars, non-slip flooring, lowered seat height, and a spacious interior with 90-inch ceilings. They meet all federal ADA requirements.'], ['q' => 'How much does a luxury restroom trailer cost?', 'a' => 'Luxury restroom trailers range from $500 to $2,500 per day. They include climate control (A/C and heat), porcelain flush toilets, vanity sinks with running water, LED lighting, and separate men\s and women\s sides. Perfect for VIP events and weddings.'], ['q' => 'Are standard porta potties OSHA compliant?', 'a' => 'Yes. Standard porta potties meet all OSHA 1926.51 requirements for construction sites. They include a non-splash urinal, ventilation, anti-slip floor, hand sanitizer dispenser, and toilet paper holder. Weekly servicing is included in every rental.'], ['q' => 'Which porta potty type is best for a wedding?', 'a' => 'For weddings, luxury restroom trailers ($500-$2,500/day) provide the best guest experience with climate control and porcelain fixtures. Deluxe units ($150-$275/day) offer a great mid-range option with flushing toilets and sinks. Standard units work for budget-friendly weddings.'], ['q' => 'What is the most affordable porta potty option?', 'a' => 'Standard porta potties at $89-$175/day are the most affordable option. They are OSHA-compliant, include weekly servicing, and work well for construction sites, short events, and budget-conscious projects. Volume discounts available for 5+ units.']]; @endphp
@foreach($faqs as $faq)<details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group"><summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-blue-600 transition list-none"><span>{{ $faq['q'] }}</span><span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-blue-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center">+</span></summary><div class="px-5 pb-5 text-slate-600 leading-relaxed"><p>{{ $faq['a'] }}</p></div></details>@endforeach
        </div>
    </div>
</section>
@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50"><div class="max-w-5xl mx-auto"><h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2><div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">@foreach($testimonials as $t)<div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all"><div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">@for($i = 0; $i < $t['rating']; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor</div><p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p><div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100"><div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div><div><p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>@if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif</div></div></div>@endforeach</div></div></section>
@endif
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center"><div class="max-w-3xl mx-auto"><h2 class="text-3xl md:text-4xl font-bold mb-4">Not sure which type you need?</h2><p class="text-lg text-slate-400 mb-8">Call us for a free consultation &mdash; we will match you with the perfect porta potty.</p><a href="tel:{{ domain_phone_raw() }}" data-tracking-label="types-guide-final" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a></div></section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100"><div class="max-w-4xl mx-auto text-center"><h2 class="text-2xl font-bold text-slate-800 mb-6">Related Resources</h2><div class="flex flex-wrap justify-center gap-4"><a href="{{ route('comparison') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Standard vs Deluxe vs Luxury</a><a href="{{ route('pricing') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Pricing Guide</a><a href="{{ route('osha.guide') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">OSHA Requirements</a><a href="{{ route('calculator') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Units Calculator</a><a href="{{ route('faq') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">FAQ</a><a href="{{ route('blog.index') }}?category=types" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Types Blog</a></div></div></section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50"><a href="tel:{{ domain_phone_raw() }}" data-tracking-label="types-guide-mobile-cta" class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30"><x-icon name="phone" class="w-6 h-6" /><span>Call for Help Choosing</span></a></div><div class="h-20 md:hidden"></div>
@endsection