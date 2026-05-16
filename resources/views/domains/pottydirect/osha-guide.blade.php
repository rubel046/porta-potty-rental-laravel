@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'OSHA Porta Potty Requirements Chart | Potty Direct')
@section('meta_description', 'Complete guide to OSHA porta potty requirements per number of employees. OSHA 1926.51 toilet ratio chart. Construction site compliance guide. Free calculator.')
@section('canonical', route('osha.guide'))
@push('schema')
@php
$breadcrumbSchema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'OSHA Porta Potty Requirements', 'item' => route('osha.guide')]]];
$howToSchema = [
    "@context" => "https://schema.org",
    "@type" => "HowTo",
    "name" => "How to Meet OSHA Porta Potty Requirements for Construction Sites",
    "description" => "Step-by-step guide to determining the right number of porta potties for OSHA compliance on construction sites.",
    "step" => [
        ["@type" => "HowToStep", "position" => 1, "name" => "Count your workers", "itemListElement" => ["@type" => "HowToDirection", "text" => "Determine the maximum number of workers on site per shift. OSHA requires 1 toilet per 20 workers."]],
        ["@type" => "HowToStep", "position" => 2, "name" => "Calculate minimum units", "itemListElement" => ["@type" => "HowToDirection", "text" => "Divide your worker count by 20 and round up. For 50 workers: 50/20 = 2.5, round up to 3 toilets minimum."]],
        ["@type" => "HowToStep", "position" => 3, "name" => "Add ADA units", "itemListElement" => ["@type" => "HowToDirection", "text" => "For public-facing job sites, include at least 1 ADA-compliant unit per 20 standard units."]],
        ["@type" => "HowToStep", "position" => 4, "name" => "Schedule servicing", "itemListElement" => ["@type" => "HowToDirection", "text" => "Arrange weekly servicing for cleaning, pumping, and restocking. More frequent for large sites."]],
        ["@type" => "HowToStep", "position" => 5, "name" => "Call Potty Direct", "itemListElement" => ["@type" => "HowToDirection", "text" => "Call Potty Direct for free quote, delivery, and setup. We handle OSHA compliance documentation."]]
    ]
];
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        ["@type" => "Question", "name" => "What is the OSHA requirement for porta potties per employee?", "acceptedAnswer" => ["@type" => "Answer", "text" => "OSHA standard 1926.51(c) requires at least 1 toilet per 20 employees. For 20 or fewer workers: 1 toilet. 21-40 workers: 2 toilets. 41-60: 3. 61-80: 4. 81-100: 5. Over 100: 1 additional per 20 workers."]],
        ["@type" => "Question", "name" => "Does OSHA require separate toilets for men and women on construction sites?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes, if both men and women work on site. You can meet this by providing separate units labeled for each gender, or by providing individual locking units that can be used by either gender."]],
        ["@type" => "Question", "name" => "Are ADA porta potties required on construction sites?", "acceptedAnswer" => ["@type" => "Answer", "text" => "While not always required by OSHA specifically, ADA-compliant units are recommended for construction sites and are legally required for public-facing job sites or when employees with disabilities work on site."]],
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($howToSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
@section('content')
<section class="relative py-16 md:py-20 overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-slate-400 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3 sm:w-4 sm:h-4" />
            <span class="text-white">OSHA Porta Potty Requirements</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-red-500/15 text-red-400 text-sm font-bold px-4 py-2 rounded-full mb-5"><x-icon name="shield-check" class="w-4 h-4" />OSHA COMPLIANCE GUIDE</div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">OSHA Porta Potty Requirements <span class="text-red-400">Per Number of Employees</span></h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">Complete guide to OSHA 1926.51 toilet requirements for construction sites. Includes ratio chart, compliance tips, and free calculator.</p>
        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-6">OSHA Toilet Ratio Chart</h2>
        <p class="text-slate-600 mb-8">OSHA standard 1926.51(c) requires employers to provide toilet facilities for employees. Here's the required ratio:</p>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm mb-10">
            <table class="w-full text-sm"><thead><tr class="bg-slate-900 text-white"><th class="text-left p-4 font-bold">Number of Workers</th><th class="p-4 font-bold text-center">Minimum Toilets Required</th><th class="p-4 font-bold text-center">Recommended (with ADA)</th></tr></thead>
            <tbody>@php $oshaData = [[1,20,1,2],[21,40,2,3],[41,60,3,4],[61,80,4,5],[81,100,5,6],[101,120,6,7],[121,140,7,8],[141,160,8,9],[161,180,9,10],[181,200,10,12]]; @endphp
            @foreach($oshaData as $row)<tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors @if($loop->even) bg-slate-50/50 @endif"><td class="p-4 font-medium text-slate-800">{{ $row[0 ] }}-{{ $row[1] }} workers</td><td class="p-4 text-center font-bold text-emerald-600">{{ $row[2] }}</td><td class="p-4 text-center text-slate-600">{{ $row[3] }}</td></tr>@endforeach</tbody></table>
        </div>
        <p class="text-sm text-slate-500">Note: Over 200 workers, add 1 toilet per 20 additional workers. Separate facilities required for each gender.</p>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50"><div class="max-w-4xl mx-auto"><h2 class="text-3xl font-bold text-slate-800 mb-6">How to Meet OSHA Porta Potty Requirements</h2>
<div class="grid md:grid-cols-2 gap-6">@php $steps = [['title' => 'Count Your Workforce', 'desc' => 'Determine max workers per shift. OSHA requires 1 toilet per 20 employees.'], ['title' => 'Calculate Minimum Units', 'desc' => 'Divide worker count by 20, round up. Add extra for comfort and productivity.'], ['title' => 'Include ADA Units', 'desc' => 'At least 1 ADA unit recommended per job site for compliance and accessibility.'], ['title' => 'Schedule Regular Servicing', 'desc' => 'Weekly servicing is the minimum. Large sites need more frequent cleaning.']]; @endphp
@foreach($steps as $i => $step)<div class="bg-white border border-slate-200 rounded-xl p-6 flex gap-4"><div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-emerald-600">{{ $i+1 }}</div><div><h3 class="font-bold text-slate-800 mb-1">{{ $step['title'] }}</h3><p class="text-slate-600 text-sm">{{ $step['desc'] }}</p></div></div>@endforeach</div></div></section>
<section class="py-12 md:py-16 px-4 bg-white"><div class="max-w-3xl mx-auto"><h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">OSHA Porta Potty FAQs</h2>
<div class="space-y-3">@php $faqs = [['q' => 'What is OSHA 1926.51?', 'a' => 'OSHA 1926.51 is the sanitation standard for construction sites. It requires employers to provide clean toilet facilities, handwashing stations, and drinking water for workers.'], ['q' => 'Does OSHA require handwashing stations with porta potties?', 'a' => 'Yes. OSHA 1926.51(f) requires handwashing facilities be provided and maintained. We offer portable handwashing stations that pair with any porta potty rental.'], ['q' => 'Can I get fined for not having enough porta potties on a construction site?', 'a' => 'Yes. OSHA can issue citations and fines for non-compliance with sanitation standards. Fines range from $1,000 to $13,000 per violation. Using our calculator ensures compliance.']]; @endphp @foreach($faqs as $faq)<details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group"><summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-red-600 transition list-none"><span>{{ $faq['q'] }}</span><span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-red-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center">+</span></summary><div class="px-5 pb-5 text-slate-600 leading-relaxed"><p>{{ $faq['a'] }}</p></div></details>@endforeach</div></div></section>
@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50"><div class="max-w-5xl mx-auto"><h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2><div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">@foreach($testimonials as $t)<div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all"><div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">@for($i = 0; $i < $t['rating']; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor</div><p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p><div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100"><div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div><div><p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>@if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif</div></div></div>@endforeach</div></div></section>
@endif
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center"><div class="max-w-3xl mx-auto"><h2 class="text-3xl md:text-4xl font-bold mb-4">Stay OSHA compliant — call us today</h2><p class="text-lg text-slate-400 mb-8">Get the right number of porta potties for your construction site. Free quote, same-day delivery.</p><a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a></div></section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100"><div class="max-w-4xl mx-auto text-center"><h2 class="text-2xl font-bold text-slate-800 mb-6">Related Resources</h2><div class="flex flex-wrap justify-center gap-4"><a href="{{ route('construction.landing') }}" class="bg-slate-100 hover:bg-red-100 text-slate-700 hover:text-red-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Construction Rental</a><a href="{{ route('comparison') }}" class="bg-slate-100 hover:bg-red-100 text-slate-700 hover:text-red-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Unit Comparison</a><a href="{{ route('pricing') }}" class="bg-slate-100 hover:bg-red-100 text-slate-700 hover:text-red-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Pricing Guide</a><a href="{{ route('calculator') }}" class="bg-slate-100 hover:bg-red-100 text-slate-700 hover:text-red-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Units Calculator</a><a href="{{ route('how-many.page') }}" class="bg-slate-100 hover:bg-red-100 text-slate-700 hover:text-red-700 font-medium px-5 py-2.5 rounded-full transition text-sm">How Many Units</a><a href="{{ route('faq') }}" class="bg-slate-100 hover:bg-red-100 text-slate-700 hover:text-red-700 font-medium px-5 py-2.5 rounded-full transition text-sm">FAQ</a></div></div></section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50"><a href="tel:{{ domain_phone_raw() }}" class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30"><x-icon name="phone" class="w-6 h-6" /><span>Call for OSHA Compliance</span></a></div><div class="h-20 md:hidden"></div>
@endsection