@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'Municipal & Government Porta Potty Rental | PottyDirect')
@section('meta_description', 'Municipal porta potty rentals for parks, public events, emergency response & government projects. GSA-compliant. Volume pricing available. Call for quote.')
@section('canonical', route('municipal.page'))
@push('schema')
@php
$breadcrumbSchema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Municipal Porta Potty Rental', 'item' => route('municipal.page')]]];
$faqSchema = [
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => [
        ["@type" => "Question", "name" => "Does PottyDirect work with municipal and government agencies?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes. We contract with city, county, state, and federal government agencies. We understand government procurement processes, provide required documentation, and offer net-30 billing terms for qualified agencies."]],
        ["@type" => "Question", "name" => "Can you handle large-volume municipal contracts?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Absolutely. We manage contracts of all sizes from single-unit park placements to 500+ unit deployments for large-scale public events and emergency response. Our fleet and logistics network handle nationwide delivery."]],
        ["@type" => "Question", "name" => "What documentation do you provide for government contracts?", "acceptedAnswer" => ["@type" => "Answer", "text" => "We provide W-9 forms, certificates of insurance, OSHA compliance documentation, service logs, itemized invoices, and any additional paperwork required by your procurement department."]],
        ["@type" => "Question", "name" => "Do you offer net-30 billing for municipalities?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes. We offer net-30 terms for qualified municipal and government agencies. We also accept purchase orders (POs) and government credit cards."]],
        ["@type" => "Question", "name" => "Can you provide porta potties for emergency response situations?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes. We maintain a dedicated emergency response fleet that can be deployed within hours. We work with FEMA, state emergency management agencies, and local responders for disaster relief, wildfire support, and emergency shelter operations."]],
        ["@type" => "Question", "name" => "Do you service porta potties in public parks regularly?", "acceptedAnswer" => ["@type" => "Answer", "text" => "Yes. We offer recurring service contracts for public parks, rest areas, and recreational facilities. Our scheduled servicing ensures units are always clean, stocked, and operational. Monthly reporting included."]],
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
            <span class="text-white">Municipal Rental</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-blue-500/15 text-blue-400 text-sm font-bold px-4 py-2 rounded-full mb-5"><x-icon name="building" class="w-4 h-4" />MUNICIPAL SOLUTIONS</div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">Municipal & Government <span class="text-blue-400">Porta Potty Rental</span></h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">Reliable sanitation solutions for parks, public events, emergency response, and government facilities. GSA-compliant. Volume pricing available.</p>
        <a href="tel:{{ domain_phone_raw() }}" data-tracking-label="municipal-hero" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl md:text-2xl font-bold py-4 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-6 h-6" />{{ domain_phone_display() }}</a>
        <p class="mt-4 text-sm text-slate-400">Net-30 billing available · Government POs accepted · Nationwide service</p>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Government Applications</h2>
        <p class="text-slate-600 text-center mb-8">From public parks to emergency response — we provide sanitation solutions for every municipal need.</p>
        <div class="grid md:grid-cols-4 gap-4">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="map-pin" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Public Parks</h3>
                <p class="text-xs text-slate-500">Year-round servicing for city and county parks</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="users" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Public Events</h3>
                <p class="text-xs text-slate-500">Concerts, parades, farmers markets, festivals</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="lightning" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Emergency Response</h3>
                <p class="text-xs text-slate-500">Rapid deployment for shelters, wildfire support, disasters</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="building" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Govt Facilities</h3>
                <p class="text-xs text-slate-500">Maintenance yards, depots, temporary offices</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="construction" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Public Works</h3>
                <p class="text-xs text-slate-500">Road construction, infrastructure projects, maintenance</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="home" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Recreation Areas</h3>
                <p class="text-xs text-slate-500">Campgrounds, boat ramps, trailheads, picnic areas</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="accessibility" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">ADA Compliance</h3>
                <p class="text-xs text-slate-500">Accessible units for all public facilities and events</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center hover:shadow-md transition">
                <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3"><x-icon name="truck" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Special Events</h3>
                <p class="text-xs text-slate-500">Grand openings, dedications, holiday celebrations</p>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Why Municipalities Choose PottyDirect</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="document" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Government-Ready Documentation</h3>
                <p class="text-slate-600 text-sm">We understand government procurement. We provide W-9 forms, certificates of insurance (up to $5M general liability), OSHA compliance documentation, service logs, and itemized invoices tailored to your agency's requirements. Our billing team is experienced with municipal purchase order systems.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="currency-dollar" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Flexible Billing & Contracts</h3>
                <p class="text-slate-600 text-sm">Net-30 billing terms available for qualified government agencies. We accept purchase orders (POs), government credit cards, and can set up recurring billing for long-term contracts. No prepayment required for established municipal accounts.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="truck" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Nationwide Fleet Coverage</h3>
                <p class="text-slate-600 text-sm">Our logistics network covers all 50 states with local service centers for rapid deployment. Whether you need a single unit at a trailhead or 500 units for a presidential visit, we have the fleet capacity and logistics expertise to deliver on time.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="clock" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Emergency Rapid Response</h3>
                <p class="text-slate-600 text-sm">Our dedicated emergency response team is available 24/7/365. We maintain a standby fleet for rapid deployment to natural disasters, emergency shelters, wildfire staging areas, and humanitarian missions. We have supported FEMA and state emergency management agencies in multiple deployments.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="shield-check" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Compliance & Safety</h3>
                <p class="text-slate-600 text-sm">All units meet OSHA, ADA, and local health department requirements. We provide compliance documentation, service records, and can coordinate with your health inspectors. Our safety record and insurance coverage meet strict government contractor requirements.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4"><x-icon name="badge-check" class="w-5 h-5 text-blue-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Dedicated Account Management</h3>
                <p class="text-slate-600 text-sm">Every municipal contract receives a dedicated account manager who serves as your single point of contact. Your account manager coordinates deliveries, servicing, billing, and emergency requests. No call centers, no runaround — just direct access to your team.</p>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-6 text-center">How Our Municipal Contract Process Works</h2>
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold flex-shrink-0 mt-1">1</div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Submit your requirements</h3>
                    <p class="text-slate-600">Call or email us with your agency's needs — number of units, unit types, locations, service frequency, and contract duration. We will also need your procurement requirements (PO system, billing terms, insurance requirements).</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold flex-shrink-0 mt-1">2</div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Receive a detailed proposal</h3>
                    <p class="text-slate-600">We prepare a comprehensive proposal with pricing, service schedules, unit specifications, compliance documentation samples, insurance certificates, and contract terms. We can align our proposal format with your RFP requirements.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold flex-shrink-0 mt-1">3</div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Deployment and ongoing service</h3>
                    <p class="text-slate-600">Once approved, we deploy units on your schedule. Your dedicated account manager handles all ongoing servicing, reporting, and coordination. Monthly service reports and invoices are provided automatically.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Municipal Porta Potty FAQs</h2>
        <div class="space-y-3">
@php $faqs = [['q' => 'Does PottyDirect work with municipal and government agencies?', 'a' => 'Yes. We contract with city, county, state, and federal government agencies. We understand government procurement processes, provide required documentation, and offer net-30 billing terms for qualified agencies.'], ['q' => 'Can you handle large-volume municipal contracts?', 'a' => 'Absolutely. We manage contracts of all sizes from single-unit park placements to 500+ unit deployments for large-scale public events and emergency response. Our fleet and logistics network handle nationwide delivery.'], ['q' => 'What documentation do you provide for government contracts?', 'a' => 'We provide W-9 forms, certificates of insurance, OSHA compliance documentation, service logs, itemized invoices, and any additional paperwork required by your procurement department.'], ['q' => 'Do you offer net-30 billing for municipalities?', 'a' => 'Yes. We offer net-30 terms for qualified municipal and government agencies. We also accept purchase orders (POs) and government credit cards.'], ['q' => 'Can you provide porta potties for emergency response situations?', 'a' => 'Yes. We maintain a dedicated emergency response fleet that can be deployed within hours. We work with FEMA, state emergency management agencies, and local responders for disaster relief, wildfire support, and emergency shelter operations.'], ['q' => 'Do you service porta potties in public parks regularly?', 'a' => 'Yes. We offer recurring service contracts for public parks, rest areas, and recreational facilities. Our scheduled servicing ensures units are always clean, stocked, and operational. Monthly reporting included.']]; @endphp
@foreach($faqs as $faq)<details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group"><summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-blue-600 transition list-none"><span>{{ $faq['q'] }}</span><span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-blue-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center">+</span></summary><div class="px-5 pb-5 text-slate-600 leading-relaxed"><p>{{ $faq['a'] }}</p></div></details>@endforeach
        </div>
    </div>
</section>
@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50"><div class="max-w-5xl mx-auto"><h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2><div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">@foreach($testimonials as $t)<div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all"><div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">@for($i = 0; $i < $t['rating']; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor</div><p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p><div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100"><div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div><div><p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>@if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif</div></div></div>@endforeach</div></div></section>
@endif
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center"><div class="max-w-3xl mx-auto"><h2 class="text-3xl md:text-4xl font-bold mb-4">Serve your community better</h2><p class="text-lg text-slate-400 mb-8">Call us to discuss your municipal sanitation needs. Government pricing, net-30 terms, nationwide coverage.</p><a href="tel:{{ domain_phone_raw() }}" data-tracking-label="municipal-final" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a></div></section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100"><div class="max-w-4xl mx-auto text-center"><h2 class="text-2xl font-bold text-slate-800 mb-6">Related Resources</h2><div class="flex flex-wrap justify-center gap-4"><a href="{{ route('emergency.page') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Emergency Rental</a><a href="{{ route('construction.landing') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Construction Rental</a><a href="{{ route('osha.guide') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">OSHA Requirements</a><a href="{{ route('pricing') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Pricing</a><a href="{{ route('faq') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">FAQ</a><a href="{{ route('calculator') }}" class="bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Units Calculator</a></div></div></section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50"><a href="tel:{{ domain_phone_raw() }}" data-tracking-label="municipal-mobile-cta" class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30"><x-icon name="phone" class="w-6 h-6" /><span>Call for Government Pricing</span></a></div><div class="h-20 md:hidden"></div>
@endsection