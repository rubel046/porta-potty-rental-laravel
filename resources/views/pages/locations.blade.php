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
            {{-- Search --}}
            <div class="mb-8">
                <div class="relative max-w-md mx-auto">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="citySearch" placeholder="Search for your city..."
                           value="{{ $search ?? '' }}"
                           class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex flex-wrap justify-center gap-6 mb-12 text-sm">
                <div class="flex items-center gap-2 text-slate-600">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">🏛️</span>
                    <span><strong class="text-slate-800">{{ $states->count() }}</strong> States</span>
                </div>
                <div class="flex items-center gap-2 text-slate-600">
                    <span class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">🏙️</span>
                    <span><strong class="text-slate-800">{{ $states->sum(fn($s) => $s->cities->count()) }}</strong> Cities</span>
                </div>
                <div class="flex items-center gap-2 text-slate-600">
                    <span class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center text-amber-600">🚚</span>
                    <span>Same-Day Delivery</span>
                </div>
            </div>

            @foreach($states->sortBy('name') as $state)
                @if($state->cities->isNotEmpty())
                    <div class="mb-10 state-group" data-state="{{ strtolower($state->name) }}">
                        <div class="flex items-center gap-3 mb-5">
                            <a href="{{ route('state.page', $state->slug) }}"
                               class="text-2xl font-bold text-slate-800 hover:text-emerald-600 transition flex items-center gap-2 group">
                                <span class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white text-lg">
                                    {{ substr($state->code, 0, 1) }}
                                </span>
                                {{ $state->name }}
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            <span class="text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                                {{ $state->cities->count() }} {{ Str::plural('city', $state->cities->count()) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                            @foreach($state->cities->sortBy('name') as $city)
                                @php $cityPage = $city->getServicePage('general'); @endphp
                                @if($cityPage)
                                    <a href="{{ url($cityPage->slug) }}"
                                       class="bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300
                                     hover:text-emerald-700 px-4 py-3 rounded-xl
                                     text-sm font-medium text-slate-700
                                     transition-all shadow-sm hover:shadow-md flex flex-col group city-item"
                                       data-city="{{ strtolower($city->name) }}">
                                        <span class="flex items-center gap-2">
                                            <span class="w-6 h-6 bg-slate-100 group-hover:bg-emerald-100 rounded-full flex items-center justify-center text-xs">
                                                📍
                                            </span>
                                            <span>{{ $city->name }}</span>
                                        </span>
                                        @if($city->population)
                                            <span class="text-xs text-slate-400 mt-1 pl-8">{{ number_format($city->population) }} pop</span>
                                        @endif
                                    </a>
                                @else
                                    <div class="bg-slate-50 border border-slate-100 px-4 py-3 rounded-xl text-sm text-slate-400 flex flex-col">
                                        <span class="flex items-center gap-2">
                                            <span class="w-6 h-6 bg-slate-100 rounded-full flex items-center justify-center text-xs">📍</span>
                                            {{ $city->name }}
                                        </span>
                                        @if($city->population)
                                            <span class="text-xs mt-1 pl-8">{{ number_format($city->population) }} pop</span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- No Results Message --}}
            <div id="noResults" class="hidden text-center py-12">
                <div class="text-5xl mb-4">🔍</div>
                <p class="text-slate-500 text-lg">No cities found matching your search.</p>
                <p class="text-slate-400 text-sm mt-2">Try a different city name or browse by state above.</p>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('citySearch').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            const groups = document.querySelectorAll('.state-group');
            let hasResults = false;

            groups.forEach(group => {
                const cities = group.querySelectorAll('.city-item');
                let groupHasResults = false;

                cities.forEach(city => {
                    const cityName = city.dataset.city;
                    if (cityName.includes(search) || search === '') {
                        city.style.display = 'flex';
                        groupHasResults = true;
                    } else {
                        city.style.display = 'none';
                    }
                });

                group.style.display = groupHasResults ? 'block' : 'none';
                if (groupHasResults) hasResults = true;
            });

            document.getElementById('noResults').classList.toggle('hidden', hasResults || search === '');
        });

        // Auto-search on page load if URL has q param
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const query = urlParams.get('q');
            if (query) {
                const searchInput = document.getElementById('citySearch');
                const event = new Event('input');
                searchInput.value = query;
                searchInput.dispatchEvent(event);
            }
        });
    </script>

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
