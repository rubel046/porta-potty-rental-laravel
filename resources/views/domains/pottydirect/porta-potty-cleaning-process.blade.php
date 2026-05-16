@extends(\App\Models\Domain::getLayoutPathStatic())
@section('title', 'Porta Potty Cleaning Process: What We Do Before Every Delivery | PottyDirect')
@section('meta_description', 'Learn about our hospital-grade porta potty cleaning process. Deep sanitization, odor control, and restocking. Every unit is cleaned before delivery — guaranteed.')
@section('canonical', route('cleaning-process.page'))
@push('schema')
@php
$breadcrumbSchema = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Cleaning Process', 'item' => route('cleaning-process.page')]]];
$howToSchema = [
    "@context" => "https://schema.org",
    "@type" => "HowTo",
    "name" => "How We Clean and Sanitize Every Porta Potty",
    "description" => "Our 7-step hospital-grade porta potty cleaning process ensures every unit is thoroughly sanitized, deodorized, and restocked before delivery.",
    "step" => [
        ["@type" => "HowToStep", "position" => 1, "name" => "Personal Protective Equipment", "itemListElement" => ["@type" => "HowToDirection", "text" => "Our technicians suit up in full PPE including gloves, safety goggles, and protective suits before handling any unit."]],
        ["@type" => "HowToStep", "position" => 2, "name" => "Waste Removal and Tank Pumping", "itemListElement" => ["@type" => "HowToDirection", "text" => "We pump out all waste from the holding tank using our specialized vacuum trucks. Tanks are fully emptied and inspected for damage."]],
        ["@type" => "HowToStep", "position" => 3, "name" => "High-Pressure Interior Wash", "itemListElement" => ["@type" => "HowToDirection", "text" => "Every surface inside the unit is pressure washed with a hospital-grade disinfectant solution at high pressure to remove all residue."]],
        ["@type" => "HowToStep", "position" => 4, "name" => "Hand Scrubbing and Detail Cleaning", "itemListElement" => ["@type" => "HowToDirection", "text" => "All surfaces are hand-scrubbed including walls, floor, toilet seat, urinal, door handles, and latches using antimicrobial cleaners."]],
        ["@type" => "HowToStep", "position" => 5, "name" => "Deodorizing and Odor Control", "itemListElement" => ["@type" => "HowToDirection", "text" => "We apply industrial-grade deodorizer to the holding tank and interior surfaces. A long-lasting odor control system is installed."]],
        ["@type" => "HowToStep", "position" => 6, "name" => "Restocking Supplies", "itemListElement" => ["@type" => "HowToDirection", "text" => "Each unit is restocked with fresh toilet paper rolls, hand sanitizer, and deodorizer. Deluxe and luxury units get soap and paper towels."]],
        ["@type" => "HowToStep", "position" => 7, "name" => "Final Inspection and Quality Check", "itemListElement" => ["@type" => "HowToDirection", "text" => "Our quality control team inspects every unit inside and out. Units failing inspection go back for recleaning before delivery."]]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($howToSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
@section('content')
<section class="relative py-16 md:py-20 overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <nav class="flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-slate-400 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3 sm:w-4 sm:h-4" />
            <span class="text-white">Cleaning Process</span>
        </nav>
        <div class="inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-400 text-sm font-bold px-4 py-2 rounded-full mb-5"><x-icon name="shield-check" class="w-4 h-4" />CLEANING PROCESS</div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">Porta Potty Cleaning Process: <span class="text-emerald-400">Hospital-Grade Sanitization</span></h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">Every unit is professionally cleaned, sanitized, and inspected before delivery. Here is exactly how we do it.</p>
        <a href="tel:{{ domain_phone_raw() }}" data-tracking-label="cleaning-hero" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl md:text-2xl font-bold py-4 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-6 h-6" />{{ domain_phone_display() }}</a>
        <p class="mt-4 text-sm text-slate-400">Clean units delivered to your site — guaranteed</p>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4 text-center">Our 7-Step Cleaning Process</h2>
        <p class="text-lg text-slate-600 mb-10 text-center">Every porta potty goes through this rigorous cleaning protocol before and after every rental.</p>
        <div class="space-y-6">
@php $steps = [['num' => '01', 'title' => 'Personal Protective Equipment', 'desc' => 'Our cleaning technicians begin by donning full personal protective equipment including chemical-resistant gloves, safety goggles, protective suits, and steel-toe boots. Safety is our top priority, both for our team and for your guests. Our technicians are trained in OSHA-compliant handling procedures and hazardous material protocols.'], ['num' => '02', 'title' => 'Waste Removal and Tank Pumping', 'desc' => 'Using our specialized vacuum trucks, we fully pump out all waste from the holding tank. The tank is then inspected for cracks, leaks, or damage. Any damaged units are taken out of service immediately for repair. We log every pumping for quality assurance and regulatory compliance.'], ['num' => '03', 'title' => 'High-Pressure Interior Wash', 'doc' => 'Every interior surface is pressure washed using a hospital-grade disinfectant solution. Our pressure washers reach 2,000+ PSI to remove all residue, stains, and biological material from walls, ceilings, floors, and fixtures. The solution we use is EPA-registered and effective against a broad spectrum of pathogens including E. coli, Salmonella, and Staph.'], ['num' => '04', 'title' => 'Hand Scrubbing and Detail Cleaning', 'desc' => 'After pressure washing, every surface is hand-scrubbed by our technicians. This includes detailed attention to high-touch areas: door handles, latches, toilet seats, urinals, dispensers, and light switches. We use antimicrobial scrub brushes and microfiber cloths to ensure no surface is overlooked. Corners, crevices, and hinges receive extra attention.'], ['num' => '05', 'title' => 'Deodorizing and Odor Control', 'desc' => 'We apply a commercial-grade bio-enzymatic deodorizer to both the holding tank and all interior surfaces. This eliminates odors at the molecular level rather than simply masking them. A time-release odor control system is installed in every unit to maintain freshness between service visits. Our deodorizers are non-toxic and environmentally friendly.'], ['num' => '06', 'title' => 'Restocking Supplies', 'desc' => 'Every unit is fully restocked with fresh supplies: toilet paper rolls (at least 2 per unit), hand sanitizer refills, and deodorizer blocks. Deluxe flushable units receive hand soap and paper towels. Luxury trailers receive premium amenities including hand lotion, air freshener, and extra paper products.'], ['num' => '07', 'title' => 'Final Inspection and Quality Check', 'desc' => 'Before any unit is cleared for delivery, it undergoes a final quality control inspection. Our QC team checks: cleanliness of all surfaces, proper function of door latch and vent, adequate supply levels, deodorizer effectiveness, and overall appearance. Units that do not pass inspection are sent back for recleaning. Only units that pass are loaded for delivery.']; @endphp
@foreach($steps as $i => $step)
<div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 hover:shadow-lg transition-all">
    <div class="flex items-start gap-4 md:gap-6">
        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center flex-shrink-0">
            <span class="text-2xl font-extrabold text-emerald-600">{{ $step['num'] }}</span>
        </div>
        <div class="flex-1">
            <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $step['title'] }}</h3>
            <p class="text-slate-600 leading-relaxed">{{ $step['desc'] ?? $step['doc'] }}</p>
        </div>
    </div>
</div>
@endforeach
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-slate-800 mb-6">What We Use — Quality Cleaning Products</h2>
        <p class="text-slate-600 mb-8">We only use EPA-registered cleaning and sanitizing products that meet healthcare facility standards.</p>
        <div class="grid md:grid-cols-3 gap-6 text-left">
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-3"><x-icon name="badge-check" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">EPA-Registered Disinfectants</h3>
                <p class="text-slate-600 text-sm">Hospital-grade disinfectants effective against COVID-19, Norovirus, E. coli, and other pathogens. All products carry EPA registration numbers.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-3"><x-icon name="badge-check" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Bio-Enzymatic Deodorizers</h3>
                <p class="text-slate-600 text-sm">Advanced bio-enzymatic formulas that break down odor-causing bacteria at the molecular level. Non-toxic and environmentally safe.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-3"><x-icon name="badge-check" class="w-5 h-5 text-emerald-600" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Industrial-Grade Equipment</h3>
                <p class="text-slate-600 text-sm">Commercial pressure washers, specialized vacuum trucks, and professional-grade cleaning tools designed for portable sanitation.</p>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-white">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-slate-800 mb-4">Our Quality Guarantee</h2>
        <p class="text-slate-600 mb-8">We stand behind every cleaning with a 100% satisfaction guarantee.</p>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-emerald-200 rounded-full flex items-center justify-center mx-auto mb-4"><x-icon name="check-circle" class="w-6 h-6 text-emerald-700" /></div>
                <h3 class="font-bold text-slate-800 mb-2">Clean or Free</h3>
                <p class="text-slate-600 text-sm">If a unit is not up to your standards, we will reclean it or replace it at no charge.</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-4"><x-icon name="clock" class="w-6 h-6 text-blue-700" /></div>
                <h3 class="font-bold text-slate-800 mb-2">On-Time Delivery</h3>
                <p class="text-slate-600 text-sm">We guarantee on-time delivery and setup. If we are late, your first week is on us.</p>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center mx-auto mb-4"><x-icon name="shield-check" class="w-6 h-6 text-amber-700" /></div>
                <h3 class="font-bold text-slate-800 mb-2">24/7 Support</h3>
                <p class="text-slate-600 text-sm">Having an issue? Our support team is available 24/7 to address any concerns.</p>
            </div>
        </div>
    </div>
</section>
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Cleaning Process FAQs</h2>
        <div class="space-y-3">
@php $faqs = [['q' => 'How often are porta potties cleaned?', 'a' => 'All standard rentals include weekly servicing, which covers cleaning, pumping, sanitizing, and restocking supplies. Events and high-traffic locations can request more frequent service. Luxury trailers may require daily servicing during events.'], ['q' => 'Do you clean the porta potties before delivery?', 'a' => 'Yes. Every unit goes through our full 7-step cleaning process before being delivered to any customer. We never deliver a unit that has not been fully cleaned, sanitized, and inspected.'], ['q' => 'What cleaning products do you use?', 'a' => 'We use EPA-registered hospital-grade disinfectants that are effective against a broad spectrum of pathogens. All our cleaning products are safe for use around people, pets, and the environment when used as directed.'], ['q' => 'Is the cleaning process environmentally friendly?', 'a' => 'Yes. We use bio-enzymatic deodorizers and environmentally responsible cleaning agents. Our waste disposal process follows all federal, state, and local environmental regulations. We are committed to sustainable sanitation practices.'], ['q' => 'Can I request extra cleaning for my event?', 'a' => 'Absolutely. We offer daily servicing for events and high-traffic locations. Additional cleaning can be arranged at the time of booking. We recommend daily servicing for large events with heavy usage.'], ['q' => 'What happens if a porta potty gets dirty during my rental?', 'a' => 'Call us anytime. We offer emergency servicing and can send a technician to clean, restock, or address any issues. Our 24/7 support team is always available.']; @endphp
@foreach($faqs as $faq)<details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group"><summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-emerald-600 transition list-none"><span>{{ $faq['q'] }}</span><span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center">+</span></summary><div class="px-5 pb-5 text-slate-600 leading-relaxed"><p>{{ $faq['a'] }}</p></div></details>@endforeach
        </div>
    </div>
</section>
@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-10 sm:py-12 md:py-16 px-3 sm:px-4 bg-slate-50"><div class="max-w-5xl mx-auto"><h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-8 sm:mb-10">What Our Customers Say</h2><div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">@foreach($testimonials as $t)<div class="bg-white border border-slate-200 p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all"><div class="flex items-center gap-0.5 text-amber-400 mb-3 sm:mb-4" aria-label="{{ $t['rating'] }} out of 5 stars">@for($i = 0; $i < $t['rating']; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor</div><p class="text-slate-700 mb-4 italic leading-relaxed text-sm sm:text-base">"{{ $t['content'] }}"</p><div class="flex items-center gap-3 pt-3 sm:pt-4 border-t border-slate-100"><div class="w-8 h-8 sm:w-9 sm:h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs sm:text-sm">{{ substr($t['customer_name'], 0, 1) }}</div><div><p class="font-bold text-sm text-slate-800">{{ $t['customer_name'] }}</p>@if(!empty($t['customer_title']))<p class="text-xs text-slate-500">{{ $t['customer_title'] }}</p>@endif</div></div></div>@endforeach</div></div></section>
@endif
<section class="py-16 md:py-20 px-4 bg-slate-900 text-white text-center"><div class="max-w-3xl mx-auto"><h2 class="text-3xl md:text-4xl font-bold mb-4">Experience the cleanest porta potties in the industry</h2><p class="text-lg text-slate-400 mb-8">Order today and get a professionally cleaned unit delivered to your site.</p><a href="tel:{{ domain_phone_raw() }}" data-tracking-label="cleaning-final" class="inline-flex items-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-2xl font-bold py-5 px-10 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] min-h-[44px]"><x-icon name="phone" class="w-7 h-7" />{{ domain_phone_display() }}</a></div></section>
<section class="py-12 md:py-16 px-4 bg-white border-t border-slate-100"><div class="max-w-4xl mx-auto text-center"><h2 class="text-2xl font-bold text-slate-800 mb-6">Related Resources</h2><div class="flex flex-wrap justify-center gap-4"><a href="{{ route('faq') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">FAQ</a><a href="{{ route('types-guide.page') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Types Guide</a><a href="{{ route('osha.guide') }}" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">OSHA Requirements</a><a href="{{ route('blog.index') }}?category=cleaning" class="bg-slate-100 hover:bg-emerald-100 text-slate-700 hover:text-emerald-700 font-medium px-5 py-2.5 rounded-full transition text-sm">Cleaning Blog</a></div></div></section>
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50"><a href="tel:{{ domain_phone_raw() }}" data-tracking-label="cleaning-mobile-cta" class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30"><x-icon name="phone" class="w-6 h-6" /><span>Order Clean Units Now</span></a></div><div class="h-20 md:hidden"></div>
@endsection