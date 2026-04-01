@extends('layouts.app')

@section('title', 'Porta Potty Rental Locations | All Cities We Serve')
@section('meta_description', 'Find porta potty rental near you. We serve hundreds of cities across the USA. Same-day delivery available. Browse all locations or call for a free quote.')

@section('content')

    {{-- Hero --}}
    <section class="relative py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-20 text-[180px]">📍</div>
            <div class="absolute bottom-10 left-10 text-[120px]">🚽</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
                All Locations
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">
                Find porta potty rental in your city across the United States
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-4 text-sm text-slate-400">
                <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">🚚 Same-Day Delivery</span>
                <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">💰 No Hidden Fees</span>
                <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">✅ Licensed & Insured</span>
            </div>
        </div>
    </section>

    {{-- States & Cities --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-6xl mx-auto">
            @foreach($states as $state)
                @if($state->cities->isNotEmpty())
                    <div class="mb-12">
                        <div class="flex items-center gap-3 mb-5">
                            <a href="{{ route('state.page', $state->slug) }}"
                               class="text-2xl font-bold text-slate-800 hover:text-emerald-600 transition flex items-center gap-2 group">
                                📍 {{ $state->name }}
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            <span class="text-sm text-slate-400 bg-slate-100 px-3 py-1 rounded-full">
                                {{ $state->cities->count() }} {{ Str::plural('city', $state->cities->count()) }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @foreach($state->cities as $city)
                                @php $cityPage = $city->getServicePage('general'); @endphp
                                @if($cityPage)
                                    <a href="{{ url($cityPage->slug) }}"
                                       class="bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300
                                      hover:text-emerald-700 px-4 py-2 rounded-xl
                                      text-sm font-medium text-slate-700
                                      transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                                        <span>{{ $city->name }}</span>
                                        @if($city->population)
                                            <span class="text-xs text-slate-400">{{ number_format($city->population) }}</span>
                                        @endif
                                    </a>
                                @else
                                    <span class="bg-slate-50 border border-slate-100 px-4 py-2
                                         rounded-xl text-sm text-slate-400 flex items-center gap-2">
                                {{ $city->name }}
                                @if($city->population)
                                    <span class="text-xs">{{ number_format($city->population) }}</span>
                                @endif
                            </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 md:py-20 px-4 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-10 left-10 text-[200px]">🚽</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Don't See Your City?</h2>
            <p class="text-xl text-slate-400 mb-8">
                We're expanding! Call us — we may still serve your area.
            </p>
            <a href="tel:{{ phone_raw() }}"
               class="inline-flex items-center gap-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500
                      text-white text-2xl md:text-3xl font-bold
                      py-4 px-12 rounded-full shadow-2xl shadow-emerald-500/30
                      transition-all hover:scale-105 animate-pulse">
                📞 {{ phone_display() }}
            </a>
            <p class="mt-6 text-slate-400 text-sm">Mon-Sat 7AM-8PM • Free Quote</p>
        </div>
    </section>
@endsection
