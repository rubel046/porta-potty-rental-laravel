@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', 'Emergency Porta Potty Rental — 24/7 Rapid Response | PottyDirect')
@section('meta_description', 'Need an emergency porta potty? 24/7 rapid response for pipe bursts, disasters, construction emergencies & job site needs. Same-day delivery. Call now for immediate dispatch.')
@section('canonical', route('emergency.page'))

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {"@type": "Question", "name": "How fast can you deliver an emergency porta potty?", "acceptedAnswer": {"@type": "Answer", "text": "We offer 24/7 emergency response with delivery in as little as 2-4 hours. Same-day delivery is guaranteed for orders before 2PM. Call for immediate dispatch to your location."}},
        {"@type": "Question", "name": "Do you provide emergency porta potty service on weekends?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. Our emergency service operates 24/7 including weekends and holidays. Real humans answer every call. No voicemail, no call centers — just direct dispatch."}},
        {"@type": "Question", "name": "What types of emergencies do you cover?", "acceptedAnswer": {"@type": "Answer", "text": "Pipe bursts, natural disasters, construction site emergencies, special event overflow, power outages affecting plumbing, remote work site needs, and any situation requiring immediate portable sanitation."}},
        {"@type": "Question", "name": "How much does emergency porta potty delivery cost?", "acceptedAnswer": {"@type": "Answer", "text": "Emergency delivery includes standard rental pricing starting at $89/day plus a rapid response fee. Call for exact pricing based on your location and urgency. No hidden fees."}},
        {"@type": "Question", "name": "Can you deliver to remote or disaster-affected areas?", "acceptedAnswer": {"@type": "Answer", "text": "Yes. We deliver to remote locations, disaster zones, and areas with limited access. Our drivers are experienced in navigating challenging conditions."}}
    ]
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}"},
        {"@type": "ListItem", "position": 2, "name": "Emergency Porta Potty", "item": "{{ route('emergency.page') }}"}
    ]
}
</script>
@endpush

@section('content')

<section class="relative py-16 md:py-20 overflow-hidden bg-gradient-to-br from-red-700 via-red-600 to-rose-700">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;><g fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.1&quot;><path d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/></g></g></svg>')"></div>
    <div class="absolute top-10 left-10 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 text-xs sm:text-sm text-rose-200 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3" />
            <span class="text-white">Emergency Porta Potty Rental</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-amber-500 text-white text-sm font-bold px-4 py-2 rounded-full mb-5 animate-pulse">
            <x-icon name="lightning" class="w-4 h-4" />
            24/7 EMERGENCY SERVICE
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5 leading-tight">
            Emergency Porta Potty Rental
            <span class="block text-2xl md:text-3xl mt-3 text-amber-300">24/7 Rapid Response — Delivered in Hours</span>
        </h1>
        <p class="text-lg md:text-xl text-rose-100 max-w-2xl mx-auto mb-8">
            Pipe burst? Disaster struck? Need immediate sanitation? We dispatch porta potties 24/7.
            Real humans answer every call. <strong class="text-white">Immediate response guaranteed.</strong>
        </p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="emergency-hero"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl md:text-2xl font-bold py-4 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px] animate-pulse">
            <x-icon name="phone" class="w-6 h-6" />
            {{ domain_phone_display() }}
        </a>
        <div class="mt-6 flex flex-wrap justify-center gap-4 text-sm text-rose-200">
            <span class="inline-flex items-center gap-1.5"><x-icon name="check-circle" class="w-4 h-4 text-green-300" />24/7 Service</span>
            <span class="inline-flex items-center gap-1.5"><x-icon name="check-circle" class="w-4 h-4 text-green-300" />Same-Day Delivery</span>
            <span class="inline-flex items-center gap-1.5"><x-icon name="check-circle" class="w-4 h-4 text-green-300" />No Hidden Fees</span>
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-6">Emergency Scenarios We Cover</h2>
        <p class="text-center text-slate-600 mb-10 max-w-2xl mx-auto">Any situation requiring immediate portable sanitation. We respond 24/7/365.</p>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $scenarios = [
                ['icon' => 'wrench', 'title' => 'Pipe Bursts', 'desc' => 'Water main breaks or pipe bursts that make indoor plumbing unusable. Emergency units delivered immediately.'],
                ['icon' => 'water-drop', 'title' => 'Plumbing Failures', 'desc' => 'Commercial or residential plumbing failures. Keep your business running or family comfortable.'],
                ['icon' => 'building', 'title' => 'Construction Emergencies', 'desc' => 'Site expansions, worker surges, or unexpected OSHA requirements. Additional units delivered same-day.'],
                ['icon' => 'users', 'title' => 'Event Overflow', 'desc' => 'Unexpected crowd sizes at events. Additional units dispatched to prevent long lines and complaints.'],
                ['icon' => 'fire', 'title' => 'Natural Disasters', 'desc' => 'Hurricanes, floods, wildfires, and storms. Emergency sanitation for shelters and response teams.'],
                ['icon' => 'truck', 'title' => 'Remote Work Sites', 'desc' => 'Unexpected site openings, pipeline work, or field operations needing immediate sanitation solutions.'],
            ];
            @endphp
            @foreach($scenarios as $s)
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 hover:shadow-lg transition-all">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4">
                    <x-icon name="{{ $s['icon'] }}" class="w-6 h-6 text-red-600" />
                </div>
                <h3 class="font-bold text-slate-800 mb-2">{{ $s['title'] }}</h3>
                <p class="text-sm text-slate-600">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-slate-900 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <div class="text-6xl mb-4">📞</div>
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Have an Emergency Right Now?</h2>
        <p class="text-lg text-slate-300 mb-2">We're standing by. Real humans answer in under 15 seconds.</p>
        <p class="text-slate-400 mb-8 text-sm">24 hours a day · 7 days a week · 365 days a year</p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="emergency-urgent"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl md:text-3xl font-bold py-5 px-12 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7" />
            {{ domain_phone_display() }}
        </a>
        <p class="mt-4 text-slate-500 text-sm">No voicemail · No call centers · Direct dispatch</p>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-4 text-center">Emergency Rental FAQs</h2>
        <p class="text-center text-slate-600 mb-8 max-w-2xl mx-auto">Quick answers about our emergency porta potty rental service and rapid response times.</p>
        @php
        $emergencyFaqs = [
            ['q' => 'How quickly can you deliver in an emergency?', 'a' => 'Our emergency response team aims for delivery within 2-4 hours of your call. For orders placed before 2PM, same-day delivery is guaranteed. Call for immediate dispatch.'],
            ['q' => 'Do you charge more for emergency delivery?', 'a' => 'Emergency delivery includes standard rental pricing plus a rapid response fee. We are transparent about pricing — no hidden fees or surprise charges. Call for an exact quote.'],
            ['q' => 'Can you deliver to a disaster area or emergency shelter?', 'a' => 'Yes. We coordinate with emergency management teams to deliver sanitation solutions to disaster areas, shelters, and relief operations. Volume discounts available for large-scale responses.'],
            ['q' => 'What if I need multiple units urgently?', 'a' => 'We maintain large regional inventories for emergency situations. Call and tell us your needs — we can dispatch 50+ units within 24 hours for most locations.'],
            ['q' => 'Do you offer emergency servicing for existing rentals?', 'a' => 'Yes. If you have existing rentals and need emergency servicing (unexpected overflow, damage, or additional cleaning), call our 24/7 line. We respond immediately.'],
        ];
        @endphp
        <div class="space-y-3">
            @foreach($emergencyFaqs as $faq)
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

<section class="py-12 md:py-16 px-4">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Why Choose PottyDirect for Emergency Rentals</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $whyEmergency = [
                ['icon' => 'lightning', 'title' => '24/7 Rapid Response Team', 'desc' => 'Our emergency team is on call 24 hours a day, 7 days a week. Real humans answer every call — no voicemail, no call centers, no automated menus. Direct dispatch.'],
                ['icon' => 'truck', 'title' => 'Delivery in as Little as 2-4 Hours', 'desc' => 'We prioritize emergency requests and dispatch immediately. For most metro areas, delivery happens within 2-4 hours of your call. Same-day guaranteed before 2PM.'],
                ['icon' => 'map-pin', 'title' => 'All-Terrain Delivery Capability', 'desc' => 'Our trucks are equipped for challenging conditions — disaster zones, remote sites, rough terrain, and areas with limited road access. We get units where they\'re needed most.'],
                ['icon' => 'users', 'title' => 'Large-Scale Emergency Capacity', 'desc' => 'Maintaining regional inventory for emergencies means we can dispatch 50+ units within 24 hours. Ideal for disaster relief, emergency shelters, and large-scale response operations.'],
            ];
            @endphp
            @foreach($whyEmergency as $w)
            <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-lg transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0">
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
            $relatedEmergency = [
                ['icon' => 'currency-dollar', 'title' => 'Porta Potty Cost Guide', 'url' => route('cost.page'), 'desc' => 'Pricing for all unit types'],
                ['icon' => 'building', 'title' => 'Construction Rentals', 'url' => route('construction.landing'), 'desc' => 'OSHA-compliant solutions'],
                ['icon' => 'shield', 'title' => 'OSHA Requirements Guide', 'url' => route('osha.guide'), 'desc' => 'Compliance for job sites'],
                ['icon' => 'calculator', 'title' => 'Units Calculator', 'url' => route('calculator'), 'desc' => 'How many units you need'],
                ['icon' => 'book-open', 'title' => 'Complete Rental Guide', 'url' => route('pillar.page'), 'desc' => 'Everything about porta potty rental'],
                ['icon' => 'document', 'title' => 'Porta Potty FAQ', 'url' => route('faq'), 'desc' => 'Answers to common questions'],
            ];
            @endphp
            @foreach($relatedEmergency as $r)
            <a href="{{ $r['url'] }}" class="bg-white border border-slate-200 rounded-xl p-5 hover:shadow-lg transition-all group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                        <x-icon name="{{ $r['icon'] }}" class="w-5 h-5" />
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-red-600 transition">{{ $r['title'] }}</h3>
                </div>
                <p class="text-sm text-slate-600">{{ $r['desc'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Expert Tips for Emergency Porta Potty Rentals</h2>
        <div class="grid md:grid-cols-2 gap-6">
            @php
            $tipsEmergency = [
                ['icon' => 'phone', 'title' => 'Call Immediately — Don\'t Wait', 'desc' => 'When sanitation is needed urgently, every hour counts. Call us as soon as you identify the need. Our dispatchers can often have a unit on the road within 30 minutes of your call.'],
                ['icon' => 'map-pin', 'title' => 'Have Your Location Details Ready', 'desc' => 'Speed up dispatch by having your exact address, access instructions, and any site restrictions ready. For remote areas, GPS coordinates help us find you faster.'],
                ['icon' => 'users', 'title' => 'Know Your Approximate Unit Count', 'desc' => 'Estimating how many units you need helps us dispatch the right truck on the first trip. For emergency shelters, plan 1 unit per 20 people. For work sites, 1 per 20 workers per OSHA.'],
                ['icon' => 'truck', 'title' => 'Clear a Path for Delivery', 'desc' => 'Ensure there\'s at least a 6-foot-wide access path to the placement area. Move vehicles, debris, or obstacles if possible. This saves precious minutes during emergency delivery.'],
            ];
            @endphp
            @foreach($tipsEmergency as $t)
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
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

<section class="py-16 md:py-20 px-4 bg-gradient-to-br from-red-700 to-rose-700 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Need Emergency Sanitation Now?</h2>
        <p class="text-lg text-rose-100 mb-3">We're standing by 24/7. Real humans answer every call in under 15 seconds.</p>
        <p class="text-rose-200 mb-8 text-sm">Immediate dispatch · No voicemail · All-terrain delivery</p>
        <a href="tel:{{ domain_phone_raw() }}"
           data-tracking-label="emergency-new-cta"
           class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl md:text-3xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]">
            <x-icon name="phone" class="w-7 h-7" />
            {{ domain_phone_display() }}
        </a>
        <p class="mt-4 text-rose-300 text-sm">24/7 dispatch · Direct to driver · No hidden fees</p>
    </div>
</section>

@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50"><div class="max-w-5xl mx-auto"><h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2><div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">@foreach($testimonials as $t)<div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all"><div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">@for($i = 0; $i < $t['rating']; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor</div><p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p><div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100"><div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div><div><p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>@if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif</div></div></div>@endforeach</div></div></section>
@endif<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-red-500 to-rose-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-red-500/40 ring-4 ring-red-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <x-icon name="phone" class="w-6 h-6" />
        <span>Emergency? Call {{ domain_phone_display() }}</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection
