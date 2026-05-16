@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'Porta Potty Rental FAQ | Expert Answers')
@section('meta_description', 'Find answers to common porta potty rental questions: pricing, quantity needed, delivery times, ADA compliance, servicing, and more. Call for a free quote.')
@section('canonical', route('faq'))
@push('schema')
@php
$breadcrumbSchema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'FAQ', 'item' => route('faq')]]];
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        ["@type" => "Question", "name" => "How much does a porta potty rental cost?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Standard units start at $89/day or $445/week. Deluxe flushable units start at $150/day. ADA accessible units start at $125/day. Luxury restroom trailers range from $500-$2,500/day. Volume discounts available for 5+ units."]],
        ["@type" => "Question", "name" => "How many porta potties do I need?", "acceptedAnswer" => ["@type" => "Answer", "text" => "For events under 4 hours: 1 unit per 50 guests. For events over 4 hours: 1 unit per 25 guests. For construction sites: OSHA requires 1 toilet per 20 workers. Add 20% if alcohol is served."]],
        ["@type" => "Question", "name" => "Do you offer same-day delivery?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes! Order before 2 PM local time for same-day delivery. Standard delivery is included in the rental price. Remote locations may have a small surcharge."]],
        ["@type" => "Question", "name" => "What is included in the rental price?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Delivery, professional setup, weekly servicing (cleaning, pumping, sanitizing, restocking toilet paper and hand sanitizer), and pickup. No hidden fees."]],
        ["@type" => "Question", "name" => "Do you offer ADA-compliant porta potties?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes. Our ADA units feature 60\" wide doors, grab bars, non-slip flooring, lowered seats, and 90\" ceilings — meeting all federal accessibility standards."]],
        ["@type" => "Question", "name" => "Can I rent luxury restroom trailers?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes! Luxury trailers with climate control, flushing toilets, sinks, and elegant interiors start at $500/day. Perfect for weddings, VIP events, and film productions."]],
        ["@type" => "Question", "name" => "How often are the porta potties serviced?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Weekly servicing is included in every standard rental. We clean, pump, sanitize, and restock supplies. Additional servicing can be arranged for events."]],
        ["@type" => "Question", "name" => "Is there a minimum rental period?", "acceptedAnswer" => ["@type" => "Answer", "text" => "No minimum. We offer daily, weekly, and monthly rentals. Event rentals can be as short as 1 day. Long-term rentals get the best rates."]],
        ["@type" => "Question", "name" => "Do you offer volume discounts?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes! 5-10 units: save 15%. 11-20 units: save 25%. 20+ units: save up to 35%. Long-term rentals (30+ days) get additional discounts."]],
        ["@type" => "Question", "name" => "What areas do you serve?", "acceptedAnswer" => ["@type" => "Answer", "text" => "We serve all 50 states with local delivery and servicing. Check our locations page to find service in your city."]],
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
            <span class="text-white">FAQ</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-400 text-sm font-bold px-4 py-2 rounded-full mb-5"><x-icon name="check-circle" class="w-4 h-4" />FAQ</div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">Porta Potty Rental <span class="text-emerald-400">FAQ</span></h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">Find answers to the most common questions about porta potty rental pricing, delivery, servicing, and more.</p>
        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Frequently Asked Questions</h2>
        <div class="space-y-3">
@php
$faqs = [
    ['q' => 'How much does a porta potty rental cost?', 'a' => 'Standard units start at $89/day or $445/week. Deluxe flushable units start at $150/day. ADA accessible units start at $125/day. Luxury restroom trailers range from $500-$2,500/day. Volume discounts available for 5+ units.'],
    ['q' => 'How many porta potties do I need?', 'a' => 'For events under 4 hours: 1 unit per 50 guests. For events over 4 hours: 1 unit per 25 guests. For construction sites: OSHA requires 1 toilet per 20 workers. Add 20% if alcohol is served. Use our free calculator tool for an exact number.'],
    ['q' => 'Do you offer same-day delivery?', 'a' => 'Yes! Order before 2 PM local time for same-day delivery. Standard delivery is included in the rental price. Remote locations may have a small surcharge.'],
    ['q' => 'What is included in the rental price?', 'a' => 'Delivery, professional setup, weekly servicing (cleaning, pumping, sanitizing, restocking toilet paper and hand sanitizer), and pickup. No hidden fees.'],
    ['q' => 'Do you offer ADA-compliant porta potties?', 'a' => 'Yes. Our ADA units feature 60" wide doors, grab bars, non-slip flooring, lowered seats, and 90" ceilings — meeting all federal accessibility standards.'],
    ['q' => 'Can I rent luxury restroom trailers?', 'a' => 'Yes! Luxury trailers with climate control, flushing toilets, sinks, and elegant interiors start at $500/day. Perfect for weddings, VIP events, and film productions.'],
    ['q' => 'How often are the porta potties serviced?', 'a' => 'Weekly servicing is included in every standard rental. We clean, pump, sanitize, and restock supplies. Additional servicing can be arranged for events.'],
    ['q' => 'Is there a minimum rental period?', 'a' => 'No minimum. We offer daily, weekly, and monthly rentals. Event rentals can be as short as 1 day. Long-term rentals get the best rates.'],
    ['q' => 'Do you offer volume discounts?', 'a' => 'Yes! 5-10 units: save 15%. 11-20 units: save 25%. 20+ units: save up to 35%. Long-term rentals (30+ days) get additional discounts.'],
    ['q' => 'What areas do you serve?', 'a' => 'We serve all 50 states with local delivery and servicing. Check our locations page to find service in your city.'],
];
@endphp
@foreach($faqs as $faq)
<details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group"><summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-emerald-600 transition list-none"><span>{{ $faq['q'] }}</span><span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center">+</span></summary><div class="px-5 pb-5 text-slate-600 leading-relaxed"><p>{{ $faq['a'] }}</p></div></details>
@endforeach
</div></div></section>
@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50"><div class="max-w-5xl mx-auto"><h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2><div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">@foreach($testimonials as $t)<div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all"><div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">@for($i = 0; $i < $t['rating']; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor</div><p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p><div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100"><div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div><div><p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>@if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif</div></div></div>@endforeach</div></div></section>
@endif
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center"><div class="max-w-3xl mx-auto"><h2 class="text-3xl md:text-4xl font-bold mb-4">Still have questions?</h2><p class="text-lg text-slate-400 mb-8">Call us — a real person will answer in under 30 seconds.</p><a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a></div></section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100"><div class="max-w-4xl mx-auto text-center"><h2 class="text-2xl font-bold text-slate-800 mb-6">Related Resources</h2><div class="flex flex-wrap justify-center gap-4"><a href="{{ route('pricing') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Full Pricing Guide</a><a href="{{ route('comparison') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Unit Comparison</a><a href="{{ route('calculator') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Units Calculator</a><a href="{{ route('cost.page') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Cost Guide</a><a href="{{ route('osha.guide') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">OSHA Requirements</a><a href="{{ route('pillar.page') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Complete Guide</a></div></div></section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50"><a href="tel:{{ domain_phone_raw() }}" class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30"><x-icon name="phone" class="w-6 h-6" /><span>Call for Answers</span></a></div><div class="h-20 md:hidden"></div>
@endsection