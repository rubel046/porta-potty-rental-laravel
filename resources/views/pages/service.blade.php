@extends('layouts.app')

@section('title', $servicePage->seo_title)
@section('meta_description', $servicePage->seo_description)
@section('canonical', url($servicePage->slug))
@section('phone_display', $servicePage->phone_display)
@section('phone_raw', $servicePage->phone_raw)

@push('schema')
    <script type="application/ld+json">
        {!! json_encode($schemaMarkup, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @if(!empty($faqSchema))
        <script type="application/ld+json">
            {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endif
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "BreadcrumbList",
            "itemListElement": [
                {"@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}"},
                {"@@type": "ListItem", "position": 2, "name": "{{ $city->state->name }}", "item": "{{ route('state.page', $city->state->slug) }}"},
                {"@@type": "ListItem", "position": 3, "name": "{{ $city->name }}", "item": "{{ url($servicePage->slug) }}"}
            ]
        }
    </script>
@endpush

@section('content')

    {{-- Hero Section --}}
    @php
        $heroImages = [
            'hero-banner-images/11. 20260224_191225_782.webp',
            'hero-banner-images/11. 20260226_230456_870.webp',
            'hero-banner-images/14. 20260226_224730_961.webp',
            'hero-banner-images/16. 20260226_230059_253.webp',
        ];
        $randomHero = $heroImages[array_rand($heroImages)];
        $heroUrl = asset('storage/' . $randomHero);
    @endphp

    <section class="relative min-h-[500px] md:min-h-[580px] flex items-center overflow-hidden">
        {{-- Hero Background Image --}}
        <div class="absolute inset-0">
            <img src="{{ $heroUrl }}" alt="Porta potty rental in {{ $city->name }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/75 to-slate-900/60"></div>
        </div>

        {{-- Decorative --}}
        <div class="absolute top-20 right-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-20 md:py-28 w-full">
            <div class="max-w-3xl">
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-sm text-slate-300 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('state.page', $city->state->slug) }}" class="hover:text-white transition">
                        {{ $city->state->name }}
                    </a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-white">{{ $city->name }}</span>
                </nav>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5 leading-tight">
                    {{ $servicePage->h1_title }}
                </h1>

                <p class="text-xl text-slate-300 mb-8 max-w-2xl">
                    Clean, affordable portable toilets delivered to your
                    {{ $city->name }} location. Same-day delivery available.
                </p>

                {{-- CTA --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-8">
                    <a href="tel:{{ $servicePage->phone_raw }}"
                       class="w-full sm:w-auto bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700
                              text-white text-2xl md:text-3xl font-bold
                              py-4 px-10 rounded-full shadow-2xl shadow-emerald-500/30
                              transition-all hover:scale-105 flex items-center justify-center gap-3">
                        <span class="text-2xl">📞</span>
                        {{ $servicePage->phone_display }}
                    </a>
                    <a href="{{ route('locations') }}"
                       class="text-slate-300 hover:text-white text-sm font-medium transition flex items-center gap-2">
                        ← View All Locations
                    </a>
                </div>

                {{-- Trust Badges --}}
                <div class="flex flex-wrap items-center gap-5 text-sm text-slate-300">
                    <span class="flex items-center gap-1.5"><span class="text-yellow-400">⭐⭐⭐⭐⭐</span> 500+ Reviews</span>
                    <span class="text-slate-600">•</span>
                    <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Licensed & Insured</span>
                    <span class="text-slate-600">•</span>
                    <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Same-Day Delivery</span>
                    <span class="text-slate-600">•</span>
                    <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> No Hidden Fees</span>
                </div>
            </div>
        </div>

        {{-- Wave --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,50 1440,40 L1440,80 L0,80 Z" fill="white"/>
            </svg>
        </div>
    </section>

    {{-- Main Content --}}
    <article class="py-12 md:py-16 px-4">
        <div class="max-w-4xl mx-auto">
            {{-- Rendered Markdown/Content --}}
            <div class="prose prose-lg max-w-none
                        prose-headings:text-slate-800 prose-headings:font-bold
                        prose-h2:text-2xl prose-h2:mt-12 prose-h2:mb-5 prose-h2:border-b prose-h2:border-slate-200 prose-h2:pb-3
                        prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
                        prose-p:text-slate-600 prose-p:leading-relaxed prose-p:mb-5
                        prose-li:text-slate-600 prose-li:leading-relaxed
                        prose-li:mb-2
                        prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-slate-800 prose-strong:font-semibold
                        prose-blockquote:border-l-emerald-500 prose-blockquote:bg-emerald-50 prose-blockquote:rounded-r-xl
                        prose-table:text-sm
                        prose-th:bg-slate-100 prose-th:p-3 prose-th:font-semibold
                        prose-td:p-4 prose-td:border prose-td:border-slate-100
                        prose-img:rounded-xl prose-img:shadow-lg">
                {!! Str::markdown($servicePage->content) !!}
            </div>

            {{-- Mid-Content CTA --}}
            <div class="my-12 bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-8 md:p-10 text-center">
                <h3 class="text-2xl font-bold text-white mb-3">
                    Ready to Get a Quote?
                </h3>
                <p class="text-slate-400 mb-6">
                    Call now for instant pricing on porta potty rental in {{ $city->name }}
                </p>
                <a href="tel:{{ $servicePage->phone_raw }}"
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                          text-white font-bold text-xl py-4 px-10 rounded-full
                          transition-all hover:scale-105 shadow-xl shadow-emerald-500/30">
                    📞 {{ $servicePage->phone_display }}
                </a>
                <p class="text-sm text-slate-400 mt-4">
                    Mon-Sat 7AM-8PM • Same-Day Delivery Available
                </p>
            </div>
        </div>
    </article>

    {{-- Testimonials --}}
    @if($testimonials->isNotEmpty())
        <section class="py-12 md:py-16 px-4 bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    What {{ $city->name }} Customers Say
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($testimonials as $testimonial)
                        <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm hover:shadow-lg transition-all">
                            <div class="text-yellow-400 mb-4">
                                @for($i = 0; $i < $testimonial->rating; $i++)⭐@endfor
                            </div>
                            <p class="text-slate-700 mb-4 italic leading-relaxed">
                                "{{ $testimonial->content }}"
                            </p>
                            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ substr($testimonial->customer_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-800">{{ $testimonial->customer_name }}</p>
                                    @if($testimonial->customer_title)
                                        <p class="text-xs text-slate-500">{{ $testimonial->customer_title }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQs --}}
    @if($faqs->isNotEmpty())
        <section class="py-12 md:py-16 px-4">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    Frequently Asked Questions — {{ $city->name }}, {{ $city->state->code }}
                </h2>
                <div class="space-y-3">
                    @foreach($faqs as $faq)
                        <details class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
                            <summary class="flex justify-between items-center p-5 cursor-pointer
                                    font-semibold text-slate-800 hover:text-emerald-600 transition list-none">
                                <span>{{ $faq->question }}</span>
                                <span class="text-2xl text-slate-400 group-open:rotate-45 group-open:text-emerald-500
                                     transition-all duration-300 ml-4 flex-shrink-0 bg-slate-100 group-hover:bg-emerald-100 w-8 h-8 rounded-full flex items-center justify-center">+</span>
                            </summary>
                            <div class="px-5 pb-5 text-slate-600 leading-relaxed">
                                {{ $faq->answer }}
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Other Services in This City --}}
    @if($otherServices->isNotEmpty())
        <section class="py-12 md:py-16 px-4 bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    Other Services in {{ $city->name }}
                </h2>
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach($otherServices as $service)
                        <a href="{{ url($service->slug) }}"
                           class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg hover:border-emerald-300
                          transition-all text-center group border border-slate-200">
                            <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition">
                                {{ $service->service_type_label }}
                            </h3>
                            <p class="text-sm text-slate-400 mt-1">in {{ $city->name }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Nearby Cities --}}
    @if($nearbyCityPages->isNotEmpty())
        <section class="py-12 md:py-16 px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    Porta Potty Rental Near {{ $city->name }}
                </h2>
                <div class="flex flex-wrap justify-center gap-3">
                    @foreach($nearbyCityPages as $nearbyCity)
                        @php $nearbyPage = $nearbyCity->getServicePage('general'); @endphp
                        @if($nearbyPage)
                            <a href="{{ url($nearbyPage->slug) }}"
                               class="bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300
                              px-5 py-3 rounded-xl text-sm font-medium text-slate-700
                              hover:text-emerald-700 transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                                📍 {{ $nearbyCity->name }}, {{ $nearbyCity->state->code }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Related Blog Posts --}}
    @if($relatedPosts->isNotEmpty())
        <section class="py-12 md:py-16 px-4 bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                    Helpful Resources
                </h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $post)
                        <a href="{{ $post->url }}"
                           class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden group border border-slate-200">
                            <div class="h-32 bg-gradient-to-br from-blue-100 to-emerald-50 flex items-center justify-center text-4xl">
                                🚽
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-slate-800 group-hover:text-emerald-600
                                   transition mb-2 line-clamp-2">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-sm text-slate-500 flex items-center gap-1.5">
                                    📖 {{ $post->reading_time_text }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Final CTA --}}
    <section class="py-16 md:py-24 px-4 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-10 left-10 text-[200px]">🚽</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-5xl font-extrabold mb-5">
                Get Your Porta Potty Delivered in {{ $city->name }} Today
            </h2>
            <p class="text-xl text-slate-400 mb-10">
                Free quote • No hidden fees • Same-day delivery available
            </p>
            <a href="tel:{{ $servicePage->phone_raw }}"
               class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                      text-white text-3xl md:text-4xl font-bold py-5 px-14
                      rounded-full shadow-2xl shadow-emerald-500/40
                      transition-all hover:scale-105 animate-pulse">
                📞 {{ $servicePage->phone_display }}
            </a>
            <p class="mt-6 text-slate-400 text-sm">Mon-Sat 7AM-8PM • Operators Standing By</p>
        </div>
    </section>
@endsection
