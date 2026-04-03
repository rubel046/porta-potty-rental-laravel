@extends('layouts.app')

@section('title', 'Porta Potty Rental Pricing | Competitive Rates | Potty Direct')
@section('meta_description', 'Porta potty rental pricing information. Get competitive rates on standard, deluxe, ADA, and luxury portable toilet rentals. Call for a personalized quote — no hidden fees!')

@push('schema')
@verbatim
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "How is porta potty rental pricing calculated?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Pricing is based on several factors: the type of unit you need, how many units, how long you need them, and your location. Call us for a personalized quote."
            }
        },
        {
            "@type": "Question",
            "name": "Do you offer volume discounts?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes! We offer competitive pricing for large orders. The more units you rent, the better value you receive. Call us to discuss your project."
            }
        },
        {
            "@type": "Question",
            "name": "What is included in the rental price?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Our rental prices include delivery, setup, weekly servicing, and pickup. No hidden fees — the price we quote is the price you pay."
            }
        }
    ]
}
</script>
@endverbatim
@endpush

@section('content')

{{-- Trust Banner --}}
<div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white py-3">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-center gap-4 text-center md:text-left">
        <div class="flex items-center gap-2">
            <span>⭐</span>
            <span class="font-semibold">4.9/5 Rating from 500+ Reviews</span>
        </div>
        <span class="text-emerald-100">|</span>
        <span>🏢 BBB A+ Rated</span>
        <span class="text-emerald-100">|</span>
        <span>🏗️ 25+ Years Experience</span>
        <span class="text-emerald-100">|</span>
        <span>🚚 Same-Day Delivery</span>
    </div>
</div>

{{-- Hero --}}
<section class="relative py-16 md:py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 right-20 text-[180px]">💰</div>
    </div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
            Porta Potty Rental Pricing
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">
            Transparent pricing with <strong class="text-white">no hidden fees</strong>. 
            Get competitive rates on all portable toilet rental options. Call for your personalized quote.
        </p>
        <div class="flex flex-wrap justify-center gap-4 text-sm text-slate-300">
            <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">💰 No Hidden Fees</span>
            <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">📞 Free Quotes</span>
            <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">🚚 Same-Day Delivery</span>
            <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">📦 Volume Discounts</span>
        </div>
    </div>
</section>

{{-- Intro --}}
<section class="py-12 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4">How Our Pricing Works</h2>
        <p class="text-lg text-slate-600 leading-relaxed mb-6">
            We believe in <strong>transparent pricing</strong> — the quote you receive is the price you pay. 
            Our porta potty rental rates are competitive and based on your specific needs. 
            Whether you need one unit or fifty, we'll work to find the best solution for your budget.
        </p>
        <p class="text-slate-600 leading-relaxed">
            Every rental includes <strong>delivery, setup, weekly servicing, and pickup</strong>. 
            Call us at <a href="tel:{{ phone_raw() }}" class="text-emerald-600 font-semibold hover:underline">{{ phone_display() }}</a> for a free, personalized quote.
        </p>
    </div>
</section>

{{-- Pricing Options --}}
<section class="py-12 md:py-16 px-4 bg-slate-50">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Rental Options & What's Included</h2>
            <p class="text-lg text-slate-600">Choose the right portable toilet rental for your needs</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pricingInfo as $info)
                <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-xl hover:border-emerald-200 transition-all duration-300">
                    <div class="text-4xl mb-4">{{ $info['icon'] }}</div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $info['title'] }}</h3>
                    <p class="text-slate-600 text-sm mb-4 leading-relaxed">{{ $info['description'] }}</p>
                    
                    <div class="mb-4">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Best For</span>
                        <p class="text-sm text-slate-600">{{ $info['best_for'] }}</p>
                    </div>

                    <div class="mb-6">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Includes</span>
                        <ul class="mt-2 space-y-1">
                            @foreach($info['includes'] as $include)
                                <li class="flex items-center gap-2 text-sm text-slate-600">
                                    <span class="text-emerald-500">✓</span> {{ $include }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <a href="tel:{{ phone_raw() }}"
                       class="block w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                              text-white text-center font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-emerald-500/20">
                        📞 {{ $info['cta'] }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Pricing Factors --}}
<section class="py-12 md:py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Factors That Affect Your Quote</h2>
            <p class="text-slate-600">Understanding pricing helps you get the best value</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            @foreach($factors as $factor)
                <div class="bg-white border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all">
                    <h3 class="font-bold text-slate-800 mb-2">{{ $factor['title'] }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $factor['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Why Choose Us --}}
<section class="py-12 md:py-16 px-4 bg-gradient-to-r from-emerald-500 to-emerald-600">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Why Our Pricing Stands Out</h2>
        
        <div class="grid md:grid-cols-2 gap-6 mt-8">
            <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-left">
                <div class="text-3xl mb-3">💰</div>
                <h3 class="font-bold text-white mb-2">No Hidden Fees</h3>
                <p class="text-emerald-100 text-sm">The price we quote is the price you pay. No surprise charges on your final bill.</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-left">
                <div class="text-3xl mb-3">📦</div>
                <h3 class="font-bold text-white mb-2">Volume Discounts</h3>
                <p class="text-emerald-100 text-sm">Rent more and save more. We offer competitive pricing for large orders.</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-left">
                <div class="text-3xl mb-3">📅</div>
                <h3 class="font-bold text-white mb-2">Flexible Terms</h3>
                <p class="text-emerald-100 text-sm">Daily, weekly, monthly options. Choose what works best for your project.</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-left">
                <div class="text-3xl mb-3">🧹</div>
                <h3 class="font-bold text-white mb-2">Servicing Included</h3>
                <p class="text-emerald-100 text-sm">Weekly cleaning, pumping, and restocking at no extra cost.</p>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-12 md:py-16 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">Frequently Asked Questions About Pricing</h2>
            <p class="text-slate-600">Get answers to common pricing questions</p>
        </div>

        @php
            $pricingFaqs = [
                [
                    'q' => 'How is porta potty rental pricing calculated?',
                    'a' => 'Your quote is calculated based on several factors: the type of unit you need (standard, deluxe, luxury), how many units you require, how long you need them, and your location. Call us for a personalized quote tailored to your specific needs.',
                ],
                [
                    'q' => 'Do you offer volume discounts?',
                    'a' => 'Yes! We offer competitive pricing for large orders. Whether you need 5 units or 50, we work to provide the best value. Contact us to discuss your project and we\'ll find a solution that fits your budget.',
                ],
                [
                    'q' => 'What is included in the rental price?',
                    'a' => 'Our rental prices include delivery to your location, professional setup, weekly servicing (cleaning, pumping, sanitizing, and restocking), and pickup when you\'re done. There are no hidden fees — what we quote is what you pay.',
                ],
                [
                    'q' => 'Do you offer long-term rental discounts?',
                    'a' => 'Yes! We offer discounted rates for monthly and long-term rentals. The longer you rent, the more you save. Contact us to discuss your project timeline and we\'ll find the best pricing option.',
                ],
                [
                    'q' => 'Is delivery included in the price?',
                    'a' => 'Yes, delivery and setup are included in our rental pricing. We offer same-day delivery in most areas when you call before 2 PM. Delivery distance may affect pricing for very remote locations.',
                ],
            ];
        @endphp

        <div class="space-y-3">
            @foreach($pricingFaqs as $faq)
                <details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
                    <summary class="flex justify-between items-center p-5 cursor-pointer font-semibold text-slate-800 hover:text-emerald-600 transition list-none">
                        <span>{{ $faq['q'] }}</span>
                        <span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500 transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-emerald-100 w-8 h-8 rounded-full flex items-center justify-center">+</span>
                    </summary>
                    <div class="px-5 pb-5 text-slate-600 leading-relaxed">
                        <p>{{ $faq['a'] }}</p>
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 md:py-20 px-4 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 text-[200px]">📞</div>
    </div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

    <div class="relative max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Get Your Free Quote Today</h2>
        <p class="text-xl text-slate-400 mb-4">
            Call us for a <strong class="text-white">free, no-obligation quote</strong>. 
            We'll help you find the best pricing for your needs.
        </p>
        <p class="text-slate-300 mb-8">
            Serving construction sites, events, weddings, and more across the USA
        </p>
        <a href="tel:{{ phone_raw() }}"
           class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                  text-white text-3xl md:text-4xl font-bold py-5 px-14
                  rounded-full shadow-2xl shadow-emerald-500/40
                  transition-all hover:scale-105 animate-pulse">
            📞 {{ phone_display() }}
        </a>
        <p class="mt-6 text-slate-400 text-sm">Mon-Sat 7AM-8PM • No Obligation • No Hidden Fees</p>
    </div>
</section>

{{-- Navigation to other pages --}}
<section class="py-8 px-4 bg-slate-50">
    <div class="max-w-4xl mx-auto flex flex-wrap justify-center gap-4 text-sm">
        <a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">← Back to Home</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('services') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">View All Services</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('locations') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Find Your City</a>
        <span class="text-slate-300">|</span>
        <a href="{{ route('blog.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Blog</a>
    </div>
</section>

@endsection