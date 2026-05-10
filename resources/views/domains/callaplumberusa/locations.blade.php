@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', 'Plumber Near You | Find Local Plumbing Services in Your City')
@section('meta_description', 'Find a plumber near you. We serve hundreds of cities across the USA with 24/7 emergency plumbing, drain cleaning, water heater repair & more. Call '.domain_phone_display().' for a free quote.')
@section('canonical', route('locations'))

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();
$businessName = $domain?->business_name ?? 'Plumbing Pro';

$plumberSchema = [
    "@context" => "https://schema.org",
    "@type" => "Plumber",
    "@id" => $url . "#business",
    "name" => $businessName,
    "description" => "Plumbing services across the USA. Emergency plumbing available 24/7.",
    "url" => $url,
    "telephone" => $phone,
    "priceRange" => "$$",
    "areaServed" => [
        "@type" => "Country",
        "name" => "United States"
    ],
    "openingHoursSpecification" => [
        [
            "@type" => "OpeningHoursSpecification",
            "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            "opens" => "00:00",
            "closes" => "23:59"
        ]
    ]
];

$websiteSchema = [
    "@context" => "https://schema.org",
    "@type" => "WebSite",
    "@id" => $url . "#website",
    "url" => $url,
    "name" => $businessName . " - Plumbing Services",
    "potentialAction" => [
        "@type" => "SearchAction",
        "target" => $url . "/locations?q={search_term_string}",
        "query-input" => "required name=search_term_string"
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($plumberSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
    {{-- Hero --}}
    <section class="relative py-12 sm:py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900"></div>
        <div class="absolute inset-0 opacity-10 desktop-only">
            
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[250px] sm:w-[350px] md:w-[500px] h-[250px] sm:h-[350px] md:h-[500px] bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-3 sm:px-6 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-4 sm:mb-5">
                Plumber Locations — Find a Plumber Near You
            </h1>
            <p class="text-base sm:text-lg md:text-xl text-slate-300 max-w-xl mx-auto mb-5 sm:mb-6">
                Find a trusted plumber in your city. Emergency plumbing services available 24/7 across the United States.
            </p>
            <div class="mt-5 sm:mt-6 flex flex-wrap justify-center gap-2 sm:gap-3 text-xs sm:text-sm text-slate-300">
                <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-3 sm:px-4 py-1.5 sm:py-2 rounded-full"><x-icon name="clock" class="w-3.5 h-3.5 text-blue-400" />24/7 Emergency</span>
                <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-3 sm:px-4 py-1.5 sm:py-2 rounded-full"><x-icon name="currency-dollar" class="w-3.5 h-3.5 text-blue-400" />Upfront Pricing</span>
                <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur px-3 sm:px-4 py-1.5 sm:py-2 rounded-full"><x-icon name="shield-check" class="w-3.5 h-3.5 text-blue-400" />Licensed & Insured</span>
            </div>
        </div>
    </section>

    {{-- FEATURED CITIES (Popular) --}}
    @php
        $featuredCities = \App\Models\City::where('is_active', true)
            ->whereHas('domainCities', fn($q) => $q->where('domain_id', $domain->id)->where('status', true))
            ->with(['servicePages' => fn($q) => $q->where('service_type', 'emergency')->where('is_published', true)->where('domain_id', $domain?->id ?? 0)])
            ->orderByDesc('priority')
            ->orderByDesc('population')
            ->limit(12)
            ->get();
    @endphp
    @if($featuredCities->isNotEmpty())
        <section class="py-8 sm:py-10 px-3 sm:px-4 bg-gradient-to-r from-orange-50 to-blue-50 border-b border-orange-100">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-5 sm:mb-6">
                    <span class="inline-flex items-center gap-2 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-full mb-3">
                        Most Popular
                    </span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-800">Top Requested Locations</h2>
                    <p class="text-sm text-slate-500">These cities have the highest demand for plumbing services</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 sm:gap-3">
                    @foreach($featuredCities as $city)
                        @php $cityPage = $city->servicePages->first(); @endphp
                        @if($cityPage)
                            <a href="{{ url($cityPage->slug) }}"
                               class="bg-white hover:bg-orange-50 border border-slate-200 hover:border-orange-300
                                      rounded-xl p-3 sm:p-4 text-center transition-all hover:shadow-lg group">
                                
                                <div class="font-semibold text-slate-800 text-sm sm:text-base group-hover:text-orange-700">{{ $city->name }}</div>
                                <div class="text-xs text-slate-400">{{ $city->state->code ?? '' }}</div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ZIP CODE & CITY SEARCH --}}
    <section class="py-8 sm:py-10 md:py-12 px-3 sm:px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-8">
                {{-- City Search --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search by City Name</label>
                    <div class="relative">
                        <svg class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="citySearch" placeholder="e.g. Houston, Austin, Dallas..."
                               value="{{ $search ?? '' }}"
                               class="w-full pl-10 sm:pl-12 pr-10 py-2.5 sm:py-3 rounded-lg sm:rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all text-sm sm:text-base">
                        <button type="button" id="clearCitySearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Zip Code Search --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search by Zip Code</label>
                    <div class="relative">
                        <svg class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        <input type="text" id="zipSearch" placeholder="e.g. 77001, 90210..."
                               maxlength="5"
                               class="w-full pl-10 sm:pl-12 pr-10 py-2.5 sm:py-3 rounded-lg sm:rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all text-sm sm:text-base">
                        <button type="button" id="clearZipSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <script>
                const citySearchInput = document.getElementById('citySearch');
                const clearCityBtn = document.getElementById('clearCitySearch');
                
                citySearchInput.addEventListener('input', function(e) {
                    const search = e.target.value.toLowerCase();
                    clearCityBtn.classList.toggle('hidden', search === '');
                    filterCities();
                });

                clearCityBtn.addEventListener('click', function() {
                    citySearchInput.value = '';
                    clearCityBtn.classList.add('hidden');
                    citySearchInput.focus();
                    filterCities();
                });

                const zipSearchInput = document.getElementById('zipSearch');
                const clearZipBtn = document.getElementById('clearZipSearch');
                
                zipSearchInput.addEventListener('input', function(e) {
                    const zip = e.target.value.replace(/\D/g, '');
                    e.target.value = zip;
                    clearZipBtn.classList.toggle('hidden', zip === '');
                    filterCities();
                });

                clearZipBtn.addEventListener('click', function() {
                    zipSearchInput.value = '';
                    clearZipBtn.classList.add('hidden');
                    zipSearchInput.focus();
                    filterCities();
                });

                function filterCities() {
                    const citySearch = citySearchInput.value.toLowerCase();
                    const zipSearch = zipSearchInput.value;
                    const groups = document.querySelectorAll('.state-group');
                    let hasResults = false;

                    groups.forEach(group => {
                        const cities = group.querySelectorAll('.city-item');
                        let groupHasResults = false;

                        cities.forEach(city => {
                            const cityName = city.dataset.city;
                            const cityZip = city.dataset.zip || '';
                            const matchesCity = cityName.includes(citySearch) || citySearch === '';
                            const matchesZip = cityZip.includes(zipSearch) || zipSearch === '';
                            
                            if (matchesCity && matchesZip) {
                                city.style.display = 'flex';
                                groupHasResults = true;
                            } else {
                                city.style.display = 'none';
                            }
                        });

                        group.style.display = groupHasResults ? 'block' : 'none';
                        if (groupHasResults) hasResults = true;
                    });

                    document.getElementById('noResults').classList.toggle('hidden', hasResults || (citySearch === '' && zipSearch === ''));
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const query = urlParams.get('q');
                    if (query) {
                        citySearchInput.value = query;
                        clearCityBtn.classList.remove('hidden');
                        filterCities();
                    }
                });
            </script>

            {{-- Stats --}}
            <div class="flex flex-wrap justify-center gap-4 sm:gap-6 mb-8 sm:mb-12 text-xs sm:text-sm">
                <div class="flex items-center gap-2 text-slate-600">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                    <span><strong class="text-slate-800">{{ $states->sum(fn($s) => $s->cities->count()) }}</strong> Plumbers Available</span>
                </div>
                <div class="flex items-center gap-2 text-slate-600">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/></svg></span>
                    <span><strong class="text-slate-800">{{ $states->count() }}</strong> States Served</span>
                </div>
                <div class="flex items-center gap-2 text-slate-600">
                    <span class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                    <span>24/7 Emergency Service</span>
                </div>
            </div>

            @foreach($states->sortBy('name') as $state)
                <div class="mb-8 sm:mb-10 state-group" data-state="{{ strtolower($state->name) }}">
                        <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-5">
                            <a href="{{ state_page_url($state->slug) }}"
                               class="text-xl sm:text-2xl font-bold text-slate-800 hover:text-orange-600 transition flex items-center gap-2 group">
                                <span class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg sm:rounded-xl flex items-center justify-center text-white text-sm sm:text-lg">
                                    {{ substr($state->code, 0, 1) }}
                                </span>
                                <span class="hidden sm:inline">{{ $state->name }}</span>
                                <span class="sm:hidden">{{ $state->code }}</span>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                            <span class="text-xs sm:text-sm text-slate-500 bg-slate-100 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full">
                                {{ $state->cities->count() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-3">
                            @foreach($state->cities->sortBy('name') as $city)
                                @php $cityPage = $city->getServicePage('emergency'); @endphp
                                @if($cityPage)
                                    <a href="{{ url($cityPage->slug) }}"
                                       class="bg-white hover:bg-orange-50 border border-slate-200 hover:border-orange-300
                                     hover:text-orange-700 px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl
                                     text-xs sm:text-sm font-medium text-slate-700
                                     transition-all shadow-sm hover:shadow-md flex flex-col group city-item"
                                       data-city="{{ strtolower($city->name) }}"
                                       data-zip="{{ $city->zip_codes ?? '' }}">
                                        <span class="flex items-center gap-1.5 sm:gap-2">
                                            <span class="w-5 h-5 sm:w-6 sm:h-6 bg-slate-100 group-hover:bg-orange-100 rounded-full flex items-center justify-center text-[10px] sm:text-xs">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                            </span>
                                            <span>{{ $city->name }}</span>
                                        </span>
                                        @if($city->population)
                                            <span class="text-[10px] sm:text-xs text-slate-400 mt-0.5 sm:mt-1 pl-6 sm:pl-8">{{ number_format($city->population) }}</span>
                                        @endif
                                    </a>
                                @else
                                    <div class="bg-slate-50 border border-slate-100 px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl text-xs sm:text-sm text-slate-400 flex flex-col">
                                        <span class="flex items-center gap-1.5 sm:gap-2">
                                            <span class="w-5 h-5 sm:w-6 sm:h-6 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center"><svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                            {{ $city->name }}
                                        </span>
                                        @if($city->population)
                                            <span class="text-[10px] sm:text-xs mt-0.5 sm:mt-1 pl-6 sm:pl-8">{{ number_format($city->population) }}</span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
            @endforeach

            {{-- No Results Message --}}
            <div id="noResults" class="hidden text-center py-8 sm:py-12">
                <svg class="w-12 h-12 sm:w-14 sm:h-14 mx-auto mb-3 sm:mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-slate-500 text-base sm:text-lg">No cities found matching your search.</p>
                <p class="text-slate-400 text-sm mt-2">Try a different city name or browse by state above.</p>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 md:py-20 px-4 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-blue-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Don't See Your City?</h2>
            <p class="text-xl text-slate-400 mb-8">
                We're expanding! Call us — we may still serve your area with emergency plumbing.
            </p>
            <a href="tel:{{ domain_phone_raw() }}"
               data-tracking-label="locations-final"
               class="inline-flex items-center gap-3 bg-orange-500 hover:bg-orange-400
                      text-white text-2xl md:text-3xl font-bold
                      py-4 px-10 rounded-full shadow-2xl shadow-orange-500/40 ring-4 ring-orange-400/30
                      transition-all hover:scale-[1.02] min-h-[44px]">
                <x-icon name="phone" class="w-7 h-7" />
                {{ domain_phone_display() }}
            </a>
            <p class="mt-6 text-slate-400 text-sm">24/7 emergency service · Free estimate</p>
        </div>
    </section>

    {{-- Mobile Sticky CTA --}}
    <div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
        <a href="tel:{{ domain_phone_raw() }}"
           class="flex items-center justify-center gap-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-orange-500/40 ring-4 ring-orange-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            <span>Call Now — Free Estimate</span>
        </a>
    </div>
    <div class="h-20 md:hidden"></div>

    {{-- SERVICE COVERAGE MAP --}}
    <section class="py-10 sm:py-12 px-3 sm:px-4 bg-slate-50 border-t border-slate-200">
        <div class="max-w-4xl mx-auto text-center">
            <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-4 sm:mb-6 flex items-center justify-center gap-2">
                Our Service Coverage
            </h3>
            <div class="relative bg-white rounded-2xl shadow-lg border border-slate-200 p-4 sm:p-6">
                <svg viewBox="0 0 960 600" class="w-full h-auto max-h-[300px]" preserveAspectRatio="xMidYMid meet">
                    <path d="M833 295 L815 290 L795 295 L770 290 L750 285 L730 280 L710 275 L685 270 L665 265 L645 260 L625 255 L605 250 L585 245 L565 240 L545 235 L525 230 L505 225 L485 220 L465 215 L445 210 L425 205 L405 200 L385 195 L365 190 L345 185 L325 180 L305 175 L285 170 L265 165 L245 160 L225 155 L205 150 L185 145 L165 140 L145 135 L125 130 L105 125 L85 120"
                          fill="none" stroke="#e2e8f0" stroke-width="3"/>
                    <ellipse cx="200" cy="280" rx="80" ry="60" fill="#FF6B35" opacity="0.3"/>
                    <ellipse cx="450" cy="200" rx="120" ry="70" fill="#FF6B35" opacity="0.3"/>
                    <ellipse cx="700" cy="250" rx="100" ry="60" fill="#FF6B35" opacity="0.3"/>
                    <ellipse cx="500" cy="400" rx="150" ry="80" fill="#FF6B35" opacity="0.3"/>
                    <ellipse cx="800" cy="350" rx="80" ry="50" fill="#FF6B35" opacity="0.3"/>
                </svg>
                <p class="text-sm text-slate-500 mt-4">
                    Serving <strong class="text-slate-700">500+ cities</strong> across the United States
                </p>
            </div>
        </div>
    </section>
@endsection
