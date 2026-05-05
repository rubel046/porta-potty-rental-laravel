@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@php
    $phoneRaw = domain_phone_raw();
    $phoneDisplay = domain_phone_display();
@endphp
@php
    $domain = \App\Models\Domain::current();
     // Only show city-specific data if geo-location was successful (middleware sets session)
     $geoDetected = session('geo_detected', false);
     $showCitySpecific = $geoDetected && !empty($topCities);

     $cityName = $showCitySpecific ? $topCities[0]['name'] : 'your area';
     $stateName = $showCitySpecific ? $topCities[0]['state']['name'] : 'the USA';
     $stateCode = $showCitySpecific ? $topCities[0]['state']['code'] : 'US';
     $zipCode = $showCitySpecific ? $topCities[0]['zip_code'] : '';
     $nearbyCity1 = $showCitySpecific ? ($topCities[1]['name'] ?? '') : '';
     $nearbyCity2 = $showCitySpecific ? ($topCities[2]['name'] ?? '') : '';
     $nearbyCity3 = $showCitySpecific ? ($topCities[3]['name'] ?? '') : '';
     $county = $showCitySpecific ? $topCities[0]['county'] ?? '' : '';
     $nearbyZip1 = $showCitySpecific ? ($topCities[1]['zip_code'] ?? '') : '';

    $rating = config('reviews.rating', 4.9);
    $reviewCount = config('reviews.count') ?? \App\Models\Testimonial::where('is_active', true)->count();
    $yearsInBusiness = $domain?->founded_year ? date('Y') - $domain->founded_year : 8;
@endphp

@section('title', 'Porta Potty Rental '.$cityName.', '.$stateName.' | Same-Day Delivery | Call '.$phoneDisplay)
@section('meta_description', 'Need porta potty rental in '.$cityName.', '.$stateName.'? Same-day delivery, ADA compliant units, flat-rate pricing. 24/7 live phone support. 4.9/5 rated. Call '.$phoneDisplay.' now!')
@section('meta_keywords', 'porta potty rental near me, portable toilet rental '.$cityName.', construction porta potty, event restroom rentals, ADA compliant porta potty')
@section('canonical', url('/'))
@section('phone_raw', $phoneRaw)
@section('phone_display', $phoneDisplay)

@push('schema')
    @php
        $url = url('/');
        $phone = domain_phone_raw();
        $domain = \App\Models\Domain::current();

        $reviewRating = config('reviews.rating', 4.9);
        $reviewCount = config('reviews.count')
            ?? \App\Models\Testimonial::where('is_active', true)->count();

        $areaServed = collect($topCities ?? [])->map(fn($c) => ['@type' => 'City', 'name' => $c['name']])->toArray();
        if (empty($areaServed)) {
            $areaServed = [['@type' => 'Country', 'name' => 'United States']];
        }

        $primaryCity = !empty($topCities) ? $topCities[0] : null;
        $latitude = $primaryCity['latitude'] ?? null;
        $longitude = $primaryCity['longitude'] ?? null;
        $streetAddress = $domain?->address ?? ($primaryCity['name'] ?? null);
        $cityAddress = $primaryCity['name'] ?? null;
        $stateAddress = $primaryCity['state']['name'] ?? null;
        $stateCodeLocal = $primaryCity['state']['code'] ?? null;
        $postalCode = $primaryCity['zip_code'] ?? null;

        $businessSchema = [
            '@context' => 'https://schema.org',
            '@type' => ['LocalBusiness', 'HomeAndConstructionBusiness', 'EmergencyService'],
            '@id' => $url . '#business',
            'name' => $domain?->business_name ?? 'Potty Direct',
            'alternateName' => [$domain?->primary_service ?? 'Portable Restroom Rental', 'Porta Potty Rental ' . $cityAddress, 'Portable Toilet Rental Near Me'],
            'description' => $domain?->tagline ?? 'Portable restroom rental service across ' . $stateAddress . '. Same-day delivery available in ' . $cityAddress . ' and surrounding areas.',
            'url' => $url,
            'telephone' => $phone,
            'priceRange' => '$$-$$$',
            'image' => [$url . '/og-image.jpg', $url . '/logo.png'],
            'logo' => $url . '/logo.png',
            'photograph' => $url . '/og-image.jpg',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $streetAddress,
                'addressLocality' => $cityAddress,
                'addressRegion' => $stateCodeLocal,
                'postalCode' => $postalCode,
                'addressCountry' => 'US'
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $latitude,
                'longitude' => (float) $longitude
            ],
            'areaServed' => array_merge($areaServed, [
                ['@type' => 'State', 'name' => $stateAddress],
                ['@type' => 'Country', 'name' => 'United States']
            ]),
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    'opens' => '00:00',
                    'closes' => '23:59',
                ],
            ],
            'aggregateRating' => ($reviewCount ?? null) ? [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $reviewRating,
                'reviewCount' => (string) $reviewCount,
                'bestRating' => '5',
            ] : null,
            'contactPoint' => [[
                '@type' => 'ContactPoint',
                'telephone' => $phone,
                'contactType' => 'customer service',
                'contactOption' => ['TollFree', 'HearingImpairedSupported'],
                'areaServed' => ['US', $stateAddress],
                'availableLanguage' => ['English'],
                'hoursAvailable' => [[
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    'opens' => '00:00',
                    'closes' => '23:59',
                ]],
            ]],
        ];
        $businessSchema = array_filter($businessSchema);

        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'How fast can I get a porta potty delivered in '.$cityName.'?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Same-day delivery is guaranteed for orders placed before 2 PM local time in '.$cityName.', with 98% on-time delivery rate. Call '.$phoneDisplay.' to confirm availability.']],
                ['@type' => 'Question', 'name' => 'Are your portable toilets ADA-compliant?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes, all our fleets include ADA compliant units that meet federal accessibility standards. Mention your need when you call '.$phoneDisplay.'.']],
                ['@type' => 'Question', 'name' => 'Is there a hidden fee for porta potty rentals?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'No. We offer flat-rate, all-inclusive pricing with no hidden fees, fuel surcharges, or service upcharges. Full pricing details provided immediately when you call '.$phoneDisplay.'.']],
                ['@type' => 'Question', 'name' => 'Do you service emergency porta potty rentals?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes, we offer 24/7 emergency response for pipe bursts, disaster relief, and urgent job site needs in '.$stateName.'. Call '.$phoneDisplay.' for immediate dispatch.']]
            ]
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($businessSchema, JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush


@section('content')

    {{-- ================================================================
         STICKY MOBILE CTA
         ================================================================ --}}
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-gradient-to-r from-amber-500 to-amber-600 text-white py-3 px-4 z-50 shadow-2xl border-t border-amber-400">
        <a href="tel:{{ $phoneRaw }}" class="flex items-center justify-center gap-2 font-bold text-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            Call Now: {{ $phoneDisplay }} <span class="text-xs bg-amber-700 px-1.5 py-0.5 rounded-full ml-1">24/7</span>
        </a>
    </div>

    {{-- ================================================================
         HERO
         ================================================================ --}}
    @php
        $host = request()->getHost();
        $prefix = preg_replace('/\.[a-z]{2,}$/i', '', $host);
        if ($prefix === 'localhost' || !\Illuminate\Support\Facades\Storage::disk('public')->exists($prefix . '/hero-banner-images')) {
            $prefix = 'pottydirect';
        }
        $heroImages = \Illuminate\Support\Facades\Cache::remember("hero_images_{$prefix}", 3600, function () use ($prefix) {
            return collect(\Illuminate\Support\Facades\Storage::disk('public')->files($prefix . '/hero-banner-images'))
                ->filter(fn($f) => in_array(pathinfo($f, PATHINFO_EXTENSION), ['webp', 'jpg', 'jpeg', 'png']))
                ->values()
                ->all();
        });
        $randomHero = !empty($heroImages) ? $heroImages[array_rand($heroImages)] : $prefix . '/hero-banner-images/default.webp';
        $heroUrl = asset('storage/' . $randomHero);
    @endphp

    <section class="relative min-h-[420px] sm:min-h-[480px] md:min-h-[560px] flex items-center overflow-hidden bg-slate-900">
        <div class="absolute inset-0">
            <img src="{{ $heroUrl }}" alt="Portable toilet rental for construction and events"
                 class="w-full h-full object-cover opacity-40"
                 width="1920" height="1080"
                 loading="eager" fetchpriority="high" decoding="async">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 via-slate-900/75 to-slate-900/50"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 md:py-20 w-full">
            <div class="max-w-3xl">
                <div class="flex justify-start mb-4">
                    <div class="bg-yellow-400 text-yellow-900 text-sm font-bold px-3 py-1 rounded-full flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ $rating }}/5 ({{ $reviewCount }}+ Reviews)
                    </div>
                </div>

                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-3 sm:mb-4 leading-[1.1] tracking-tight text-balance">
                    Porta Potty Rental in {{ $cityName }}, {{ $stateName }}
                    <span class="block text-emerald-400 text-xl sm:text-2xl md:text-3xl lg:text-4xl mt-2 font-bold">
                        Fast, Clean, Reliable — Same-Day Delivery
                    </span>
                </h1>

                <p class="text-base sm:text-lg text-slate-300 mb-5 sm:mb-7 max-w-xl leading-relaxed">
                    Portable toilet rental near me just got easier. We deliver sanitized, OSHA-compliant
                    porta potties to {{ $cityName }} job sites, events, and residential projects in hours, not days.
                </p>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-5 mb-3">
                    <a href="tel:{{ $phoneRaw }}"
                       data-tracking-label="home-hero"
                       class="flex items-center justify-center gap-3 bg-amber-500 hover:bg-amber-400 text-white text-xl sm:text-2xl font-bold py-4 px-7 sm:px-9 rounded-full shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] active:scale-[0.98] min-h-[44px] whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{{ $phoneDisplay }}</span>
                    </a>
                </div>

                <p class="text-sm text-slate-300 font-medium flex items-center gap-2 mb-5">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Answered in under 15 seconds by a real person — no robocalls.</span>
                </p>

                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs sm:text-sm text-slate-300">
                    <span class="inline-flex items-center gap-1.5">Background-Checked Drivers</span>
                    <span class="text-slate-600" aria-hidden="true">·</span>
                    <span class="inline-flex items-center gap-1.5">Sanitized Units Every Time</span>
                    <span class="text-slate-600" aria-hidden="true">·</span>
                    <span class="inline-flex items-center gap-1.5">Price Match Guarantee</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ================================================================
         TRUST BADGES — Licensed, Rated, Support, Guarantee, Experience
         ================================================================ --}}
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border-y border-slate-700 py-6 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-6">
                {{-- Licensed & Insured --}}
                <div class="flex flex-col items-center text-center gap-2 px-2 py-3">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-emerald-400 font-bold text-sm">Licensed & Insured</div>
                        <div class="text-slate-400 text-xs">Full Coverage</div>
                    </div>
                </div>

                {{-- Star Rated --}}
                <div class="flex flex-col items-center text-center gap-2 px-2 py-3">
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-yellow-400 font-bold text-sm">4.9/5 Star Rated</div>
                        <div class="text-slate-400 text-xs">{{ $reviewCount }}+ Reviews</div>
                    </div>
                </div>

                {{-- 24/7 Support --}}
                <div class="flex flex-col items-center text-center gap-2 px-2 py-3">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-blue-400 font-bold text-sm">24/7 Live Support</div>
                        <div class="text-slate-400 text-xs">Real Humans</div>
                    </div>
                </div>

                {{-- Satisfaction Guarantee --}}
                <div class="flex flex-col items-center text-center gap-2 px-2 py-3">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-purple-400 font-bold text-sm">100% Satisfaction</div>
                        <div class="text-slate-400 text-xs">24H Replacement</div>
                    </div>
                </div>

                {{-- Years Serving --}}
                <div class="flex flex-col items-center text-center gap-2 px-2 py-3 col-span-2 md:col-span-1">
                    <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-amber-400 font-bold text-sm">{{ $yearsInBusiness }}+ Years Serving</div>
                        <div class="text-slate-400 text-xs">{{ $stateName }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         SERVICES
         ================================================================ --}}
    <section id="services" class="py-16 sm:py-20 px-4 sm:px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-10 sm:mb-14">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Trusted by 2,000+ contractors and event planners across {{ $stateName }} with a 98% on-time delivery rate, hospital-grade sanitation, and 24/7 real-human support that answers in 15 seconds.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $homeServices = [
                        ['key' => 'construction', 'icon' => 'building', 'name' => 'Construction Porta Potties', 'blurb' => 'Heavy-duty, spill-proof units for job sites. Weekly servicing and restocking included.', 'popular' => true],
                        ['key' => 'standard', 'icon' => 'water-drop', 'name' => 'Event Portable Toilets', 'blurb' => 'Clean, fragrant units for weddings, festivals, and parties. Luxury add-ons available.', 'popular' => false],
                        ['key' => 'luxury', 'icon' => 'sparkles', 'name' => 'Luxury Restroom Trailers', 'blurb' => 'Climate-controlled, flushable units with running water. Perfect for high-end events.', 'popular' => false],
                        ['key' => 'ada', 'icon' => 'accessibility', 'name' => 'ADA Compliant Toilets', 'blurb' => 'Meets all federal accessibility standards. Required for public events and job sites.', 'popular' => false],
                        ['key' => 'shower', 'icon' => 'shower', 'name' => 'Handwashing Stations', 'blurb' => 'Portable sinks with soap, paper towels, and fresh water. Pair with any rental.', 'popular' => false],
                        ['key' => 'dumpster', 'icon' => 'truck', 'name' => 'Emergency Rentals', 'blurb' => '24/7 rapid response for disasters, pipe bursts, and urgent job site needs.', 'popular' => false],
                    ];
                @endphp

                @foreach($homeServices as $svc)
                    <div class="relative border border-gray-200 rounded-2xl p-6 hover:shadow-xl transition-all hover:-translate-y-1 bg-white">
                        @if($svc['popular'])
                            <div class="absolute -top-3 left-4 bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full">MOST POPULAR</div>
                        @endif
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($svc['icon'] === 'building')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                @elseif($svc['icon'] === 'water-drop')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                @elseif($svc['icon'] === 'sparkles')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                @elseif($svc['icon'] === 'accessibility')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                @elseif($svc['icon'] === 'shower')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0v2.5m0-2.5V14m0-2.5v-6a1.5 1.5 0 113 0v2.5M5 14h14v-2a3 3 0 00-3-3h-8a3 3 0 00-3 3v2z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                @endif
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-gray-900">{{ $svc['name'] }}</h3>
                        <p class="text-gray-600 mb-4">{{ $svc['blurb'] }}</p>
                        <a href="tel:{{ $phoneRaw }}" data-tracking-label="home-service-{{ $svc['key'] }}" class="text-emerald-600 font-bold hover:underline flex items-center gap-1">Call To Book →</a>
                    </div>
                @endforeach
             </div>

             <div class="text-center mt-10">
                 <a href="/services"
                    class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-xl transition hover:scale-[1.02] min-h-[44px]">
                     <span>View All Services</span>
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                         <path d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                     </svg>
                 </a>
              </div>
         </div>
     </section>

     {{-- ================================================================
          OCCASIONS — Portable Toilet Rental For Every Occasion
          ================================================================ --}}
     <section class="py-16 sm:py-20 px-4 sm:px-6 bg-gray-50">
         <div class="max-w-7xl mx-auto">
             <div class="text-center mb-10 sm:mb-14">
                 <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Portable Toilet Rental For Every Occasion</h2>
                 <p class="text-gray-600 max-w-2xl mx-auto">From intimate gatherings to massive festivals, we provide clean, reliable restroom solutions for every event across the USA.</p>
             </div>

             <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                 @php
                     $occasions = [
                         ['icon' => 'sparkles', 'name' => 'Weddings & Receptions', 'desc' => 'Elegant luxury trailers and pristine units that match your special day.', 'color' => 'from-pink-500 to-rose-500'],
                         ['icon' => 'music', 'name' => 'Festivals & Concerts', 'desc' => 'High-capacity solutions for music festivals, concerts, and outdoor events.', 'color' => 'from-purple-500 to-indigo-500'],
                         ['icon' => 'users', 'name' => 'Corporate Events', 'desc' => 'Professional-grade units for company picnics, conferences, and retreats.', 'color' => 'from-blue-500 to-blue-600'],
                         ['icon' => 'heart', 'name' => 'Parties & Celebrations', 'desc' => 'Birthday parties, anniversaries, graduations, and family reunions.', 'color' => 'from-rose-500 to-pink-600'],
                         ['icon' => 'truck', 'name' => 'Sports & Tailgating', 'desc' => 'Tournaments, tailgate parties, and sporting events of all sizes.', 'color' => 'from-green-500 to-emerald-600'],
                         ['icon' => 'building', 'name' => 'Construction & Job Sites', 'desc' => 'OSHA-compliant units for construction sites, renovations, and industrial projects.', 'color' => 'from-orange-500 to-amber-600'],
                         ['icon' => 'church', 'name' => 'Religious Gatherings', 'desc' => 'Perfect for church picnics, revivals, and community worship events.', 'color' => 'from-teal-500 to-cyan-600'],
                         ['icon' => 'academic-cap', 'name' => 'School & University Events', 'desc' => 'Graduation ceremonies, football games, and campus events.', 'color' => 'from-indigo-500 to-blue-600'],
                         ['icon' => 'cake', 'name' => 'Fair & Carnival', 'desc' => 'State fairs, county fairs, carnivals, and agricultural exhibitions.', 'color' => 'from-amber-500 to-orange-500'],
                         ['icon' => 'film', 'name' => 'Film & Production Sets', 'desc' => 'Movie sets, photo shoots, and television production locations.', 'color' => 'from-gray-700 to-gray-900'],
                         ['icon' => 'fire', 'name' => 'Emergency & Disaster Relief', 'desc' => 'Rapid response for hurricanes, floods, fires, and natural disasters.', 'color' => 'from-red-500 to-red-600'],
                         ['icon' => 'shower', 'name' => 'Agricultural & Farm Events', 'desc' => 'Harvest festivals, farmers markets, and agricultural exhibitions.', 'color' => 'from-lime-500 to-green-600'],
                     ];
                 @endphp

                 @foreach($occasions as $occ)
                     <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all hover:-translate-y-1 border border-gray-100">
                         <div class="w-12 h-12 bg-gradient-to-br {{ $occ['color'] }} flex items-center justify-center rounded-xl mb-4">
                             <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                 @if($occ['icon'] === 'sparkles')
                                     <path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                 @elseif($occ['icon'] === 'music')
                                     <path d="M9 19V6l12-2v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-2c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/>
                                 @elseif($occ['icon'] === 'users')
                                     <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                 @elseif($occ['icon'] === 'heart')
                                     <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                 @elseif($occ['icon'] === 'truck')
                                     <path d="M9 17h6l-6 6V3a2 2 0 012-2h8a2 2 0 012 2v14a2 2 0 01-2 2H9a2 2 0 01-2-2V7a2 2 0 012-2h2"/>
                                 @elseif($occ['icon'] === 'building')
                                     <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                 @elseif($occ['icon'] === 'church')
                                     <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                 @elseif($occ['icon'] === 'academic-cap')
                                     <path d="M10 14l2-2m0 0l2 2m-2-2v6m-4 2h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                 @elseif($occ['icon'] === 'cake')
                                     <path d="M21 15.546c-.523 1.756-1.62 3.218-3.055 4.068a12.985 12.985 0 01-6.89 1.686c-2.916 0-5.59-.74-7.34-1.936a12.296 12.296 0 01-.91-.65 3.003 3.003 0 01-1.17-1.915 5.742 5.742 0 01-.218-1.385c0-3.742 4.426-6.802 10-6.802 5.75 0 10 3.06 10 6.802 0 .552-.045 1.118-.134 1.702-.046.309-.1.613-.163.907z"/>
                                 @elseif($occ['icon'] === 'film')
                                     <path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                 @elseif($occ['icon'] === 'fire')
                                     <path d="M17.69 7.165a3 3 0 00-3.883-3.733m3.883 3.733a3 3 0 01-3.883 3.733M12 21a9 9 0 100-18 9 9 0 000 18zm0-9a3 3 0 100-6 3 3 0 000 6z"/>
                                 @else
                                     <path d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0v2.5m0-2.5V14m0-2.5v-6a1.5 1.5 0 113 0v2.5M5 14h14v-2a3 3 0 00-3-3h-8a3 3 0 00-3 3v2z"/>
                                 @endif
                             </svg>
                         </div>
                         <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $occ['name'] }}</h3>
                         <p class="text-gray-600 text-sm leading-relaxed">{{ $occ['desc'] }}</p>
                     </div>
                 @endforeach
             </div>

             <div class="text-center mt-10">
                 <a href="tel:{{ $phoneRaw }}"
                    data-tracking-label="home-occasions-cta"
                    class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white font-bold py-3 px-8 rounded-full shadow-lg shadow-amber-500/30 transition hover:scale-[1.02] min-h-[44px]">
                     <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                         <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                     </svg>
                     <span>Call {{ $phoneDisplay }} to Book Your Event</span>
                 </a>
             </div>
         </div>
      </section>

     {{-- ================================================================
          HOW TO RENT — 3 Simple Steps (Rich Design)
          ================================================================ --}}
     <section class="py-16 sm:py-24 px-4 sm:px-6 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
         {{-- Background Decoration --}}
         <div class="absolute inset-0 opacity-5">
             <div class="absolute top-10 left-10 w-72 h-72 bg-amber-500 rounded-full blur-3xl"></div>
             <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
         </div>

         <div class="max-w-6xl mx-auto relative">
             <div class="text-center mb-16">
                 <div class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 text-sm font-bold px-4 py-2 rounded-full mb-4">
                     <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                     SIMPLE PROCESS
                 </div>
                 <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4">
                     Rent a Porta Potty in <span class="text-amber-500">3 Simple Steps</span>
                 </h2>
                 <p class="text-lg text-gray-600 max-w-2xl mx-auto">Get clean, reliable restrooms delivered anywhere in {{ $stateName }} in under 15 minutes. No forms, no waiting.</p>
             </div>

             <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12 mb-16">
                 {{-- Step 1 --}}
                 <div class="relative group">
                     {{-- Connecting Line --}}
                     <div class="hidden md:block absolute top-10 left-1/2 w-full h-1 bg-gradient-to-r from-amber-200 via-emerald-200 to-blue-200 -z-10"></div>

                     <div class="relative bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2 border border-gray-100">
                         <div class="w-24 h-24 bg-gradient-to-br from-amber-400 to-orange-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-amber-500/30 group-hover:scale-110 transition-transform">
                             <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                 <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                             </svg>
                         </div>
                         <div class="absolute -top-4 -right-4 w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center font-black text-lg shadow-lg">1</div>
                         <h3 class="text-2xl font-extrabold text-gray-900 mb-3">Call Our Team</h3>
                         <p class="text-gray-600 leading-relaxed mb-4">Call <a href="tel:{{ $phoneRaw }}" class="text-amber-600 font-bold hover:underline">{{ $phoneDisplay }}</a> and tell us your event type, date, and location in {{ $cityName }}.</p>
                         <div class="flex items-center gap-2 text-sm text-gray-500">
                             <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                             <span>15-second average answer</span>
                         </div>
                     </div>
                 </div>

                 {{-- Step 2 --}}
                 <div class="relative group">
                     <div class="relative bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2 border border-gray-100">
                         <div class="w-24 h-24 bg-gradient-to-br from-emerald-400 to-teal-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                             <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                 <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                             </svg>
                         </div>
                         <div class="absolute -top-4 -right-4 w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center font-black text-lg shadow-lg">2</div>
                         <h3 class="text-2xl font-extrabold text-gray-900 mb-3">Pick Your Units</h3>
                         <p class="text-gray-600 leading-relaxed mb-4">Choose from standard, deluxe, ADA, or luxury restroom trailers. We'll recommend the right quantity.</p>
                         <div class="flex items-center gap-2 text-sm text-gray-500">
                             <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                             <span>Free expert consultation</span>
                         </div>
                     </div>
                 </div>

                 {{-- Step 3 --}}
                 <div class="relative group">
                     <div class="relative bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2 border border-gray-100">
                         <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-indigo-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-blue-500/30 group-hover:scale-110 transition-transform">
                             <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                 <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                             </svg>
                         </div>
                         <div class="absolute -top-4 -right-4 w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center font-black text-lg shadow-lg">3</div>
                         <h3 class="text-2xl font-extrabold text-gray-900 mb-3">Same-Day Delivery</h3>
                         <p class="text-gray-600 leading-relaxed mb-4">We deliver, set up, and service your units. Order by 2PM for same-day across {{ $stateName }}.</p>
                         <div class="flex items-center gap-2 text-sm text-gray-500">
                             <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                             <span>98% on-time rate</span>
                         </div>
                     </div>
                 </div>
             </div>

             {{-- Trust Bar --}}
             <div class="flex flex-wrap justify-center gap-8 mb-12">
                 <div class="flex items-center gap-2 text-gray-600">
                     <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                     <span class="font-bold text-gray-900">2,000+</span> Happy Customers
                 </div>
                 <div class="flex items-center gap-2 text-gray-600">
                     <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                     <span class="font-bold text-gray-900">Licensed</span> & Insured
                 </div>
                 <div class="flex items-center gap-2 text-gray-600">
                     <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                     <span class="font-bold text-gray-900">98%</span> On-Time Rate
                 </div>
             </div>

             <div class="text-center">
                 <a href="tel:{{ $phoneRaw }}"
                    data-tracking-label="home-3steps-cta"
                    class="inline-flex items-center gap-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white font-bold py-5 px-12 rounded-full shadow-2xl shadow-amber-500/40 transition hover:scale-[1.03] min-h-[44px] text-xl">
                     <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                         <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                     </svg>
                     <span>Call {{ $phoneDisplay }} to Get Started</span>
                 </a>
                 <p class="mt-4 text-gray-500">No forms • No waiting • Real humans answer</p>
             </div>
         </div>
     </section>

     {{-- ================================================================
          WHY CHOOSE US — Modern Card Design
         ================================================================ --}}
    <section class="py-16 sm:py-20 px-4 sm:px-6 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3">
                    Why {{ $domain?->business_name ?? 'Us' }} Is {{ $cityName }}'s Most Trusted Porta Potty Provider
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">We've built our reputation on reliability, transparency, and exceptional service.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                {{-- Card 1: Same-Day Delivery --}}
                <div class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all hover:-translate-y-1 border border-gray-100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="text-lg font-bold text-gray-900">Same-Day Guarantee</h3>
                        <span class="text-xs font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">98%</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">On-time delivery rate for orders placed before 2 PM. No delays, no excuses. Real-time tracking available.</p>
                </div>

                {{-- Card 2: Flat-Rate Pricing --}}
                <div class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all hover:-translate-y-1 border border-gray-100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="text-lg font-bold text-gray-900">Flat-Rate Pricing</h3>
                        <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">NO HIDDEN FEES</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">No hidden fees, fuel surcharges, or service upcharges. Price match guarantee available for all units.</p>
                </div>

                {{-- Card 3: Licensed & Insured --}}
                <div class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all hover:-translate-y-1 border border-gray-100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="text-lg font-bold text-gray-900">Licensed & Insured</h3>
                        <span class="text-xs font-bold bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">A+ BBB</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">Full liability coverage, workers' comp, and all local permits included. Fully compliant with state regulations.</p>
                </div>

                {{-- Card 4: 24/7 Live Support --}}
                <div class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all hover:-translate-y-1 border border-gray-100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="text-lg font-bold text-gray-900">24/7 Live Phone Support</h3>
                        <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">15s ANSWER</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">Real people answer every call in 15 seconds or less. No bots, no voicemail menus. Always available.</p>
                </div>

                {{-- Card 5: 10,000+ Units Delivered --}}
                <div class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all hover:-translate-y-1 border border-gray-100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 17h6l-6 6V3a2 2 0 012-2h8a2 2 0 012 2v14a2 2 0 01-2 2H9a2 2 0 01-2-2V7a2 2 0 012-2h2"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="text-lg font-bold text-gray-900">10,000+ Units Delivered</h3>
                        <span class="text-xs font-bold bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full">2K+ CLIENTS</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">Trusted by 2,000+ contractors, event planners, and homeowners across {{ $stateName }}. Proven track record.</p>
                </div>

                {{-- Card 6: 100% Satisfaction Guarantee --}}
                <div class="group relative bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all hover:-translate-y-1 border border-gray-100">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="text-lg font-bold text-gray-900">100% Satisfaction Guarantee</h3>
                        <span class="text-xs font-bold bg-teal-100 text-teal-700 px-2 py-0.5 rounded-full whitespace-nowrap">24H REPLACE</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">If you're not happy with your unit, we'll replace it free of charge within 24 hours. Your satisfaction guaranteed.</p>
                </div>
            </div>

            {{-- Trust Banner --}}
            <div class="relative bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 rounded-2xl p-8 shadow-xl overflow-hidden">
                <div class="absolute inset-0 opacity-40" style="background-image: url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;><g fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.05&quot;><path d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/></g></g></svg>')"></div>
                <div class="relative flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-white mb-2">Background-Checked & Trusted</h3>
                        <p class="text-blue-100">All drivers are background-checked, drug-tested, and uniformed for your peace of mind. {{ $yearsInBusiness }}+ years of excellence in {{ $stateName }}.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 text-center min-w-[100px]">
                            <div class="text-2xl font-bold text-white">98%</div>
                            <div class="text-xs text-blue-200">On-Time Rate</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 text-center min-w-[100px]">
                            <div class="text-2xl font-bold text-white">15s</div>
                            <div class="text-xs text-blue-200">Answer Time</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 text-center min-w-[100px]">
                            <div class="text-2xl font-bold text-white">{{ $yearsInBusiness }}+</div>
                            <div class="text-xs text-blue-200">Years in Biz</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </section>

     {{-- ================================================================
          TRUSTED & CERTIFIED
          ================================================================ --}}
     <section class="py-16 sm:py-20 px-4 sm:px-6 bg-white relative overflow-hidden">
         {{-- Background Pattern --}}
         <div class="absolute inset-0 opacity-5">
             <div class="absolute top-10 left-10 w-32 h-32 bg-blue-500 rounded-full blur-3xl"></div>
             <div class="absolute bottom-10 right-10 w-40 h-40 bg-amber-500 rounded-full blur-3xl"></div>
         </div>

         <div class="max-w-6xl mx-auto relative">
             <div class="text-center mb-12">
                 <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4">
                     Trused & <span class="text-blue-600">Certified</span> Across {{ $stateName }}
                 </h2>
                 <p class="text-gray-600 max-w-2xl mx-auto">We maintain the highest standards in the industry. Here's proof:</p>
             </div>

             <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
                 {{-- Certification 1 --}}
                 <div class="bg-gray-50 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow border border-gray-100">
                     <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                         <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                             <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                         </svg>
                     </div>
                     <div class="font-black text-2xl text-blue-700 mb-1">A+</div>
                     <div class="text-sm font-bold text-gray-900">BBB Rating</div>
                     <div class="text-xs text-gray-500 mt-1">Accredited Business</div>
                 </div>

                 {{-- Certification 2 --}}
                 <div class="bg-gray-50 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow border border-gray-100">
                     <div class="w-16 h-16 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                         <svg class="w-8 h-8 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                             <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                         </svg>
                     </div>
                     <div class="font-black text-2xl text-emerald-700 mb-1">OSHA</div>
                     <div class="text-sm font-bold text-gray-900">Compliant</div>
                     <div class="text-xs text-gray-500 mt-1">Job Site Standards</div>
                 </div>

                 {{-- Certification 3 --}}
                 <div class="bg-gray-50 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow border border-gray-100">
                     <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                         <svg class="w-8 h-8 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                             <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                         </svg>
                     </div>
                     <div class="font-black text-2xl text-purple-700 mb-1">EPA</div>
                     <div class="text-sm font-bold text-gray-900">Certified</div>
                     <div class="text-xs text-gray-500 mt-1">Environmental Safety</div>
                 </div>

                 {{-- Certification 4 --}}
                 <div class="bg-gray-50 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow border border-gray-100">
                     <div class="w-16 h-16 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                         <svg class="w-8 h-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                             <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                         </svg>
                     </div>
                     <div class="font-black text-2xl text-amber-700 mb-1">ISO</div>
                     <div class="text-sm font-bold text-gray-900">9001:2015</div>
                     <div class="text-xs text-gray-500 mt-1">Quality Management</div>
                 </div>
             </div>

             {{-- Insurance Bar --}}
             <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-2xl p-6 sm:p-8 shadow-xl">
                 <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                     <div class="flex-1">
                         <h3 class="text-xl font-bold text-white mb-2">Fully Licensed & Insured</h3>
                         <p class="text-slate-300 text-sm">$2M General Liability • Workers' Comp • Bonded • All Local Permits</p>
                     </div>
                     <div class="flex gap-4">
                         <div class="text-center">
                             <div class="text-2xl font-black text-emerald-400">$2M+</div>
                             <div class="text-xs text-slate-400">Liability Coverage</div>
                         </div>
                         <div class="text-center">
                             <div class="text-2xl font-black text-blue-400">50+</div>
                             <div class="text-xs text-slate-400">Trained Drivers</div>
                         </div>
                         <div class="text-center">
                             <div class="text-2xl font-black text-amber-400">{{ $yearsInBusiness }}+</div>
                             <div class="text-xs text-slate-400">Years in Business</div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section>

     {{-- ================================================================
          OUR PROMISE TO YOU
          ================================================================ --}}
     <section class="py-16 sm:py-24 px-4 sm:px-6 bg-slate-900 relative overflow-hidden">
         {{-- Background Pattern --}}
         <div class="absolute inset-0 opacity-10">
             <div class="absolute top-0 left-0 w-96 h-96 bg-amber-500 rounded-full blur-3xl"></div>
             <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
         </div>

         <div class="max-w-6xl mx-auto relative">
             <div class="text-center mb-16">
                 <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-4">
                     Our <span class="text-amber-400">Promise</span> to You
                 </h2>
                 <p class="text-slate-300 text-lg max-w-2xl mx-auto">We don't just rent porta potties. We deliver peace of mind, every single time.</p>
             </div>

             <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                 {{-- Promise 1 --}}
                 <div class="relative bg-slate-800/50 backdrop-blur-sm rounded-3xl p-8 border border-slate-700 hover:border-amber-500/50 transition group">
                     <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-amber-500/20 group-hover:scale-110 transition-transform">
                         <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                             <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                         </svg>
                     </div>
                     <div class="absolute -top-4 -right-4 w-10 h-10 bg-amber-500 text-white rounded-full flex items-center justify-center font-black text-lg shadow-lg">1</div>
                     <h3 class="text-2xl font-extrabold text-white mb-3 text-center">15-Second Answer</h3>
                     <p class="text-slate-300 text-center leading-relaxed">Every call answered in 15 seconds or less. No bots, no menus — just real humans ready to help you.</p>
                 </div>

                 {{-- Promise 2 --}}
                 <div class="relative bg-slate-800/50 backdrop-blur-sm rounded-3xl p-8 border border-slate-700 hover:border-emerald-500/50 transition group">
                     <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                         <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                             <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                         </svg>
                     </div>
                     <div class="absolute -top-4 -right-4 w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center font-black text-lg shadow-lg">2</div>
                     <h3 class="text-2xl font-extrabold text-white mb-3 text-center">Hospital-Grade Clean</h3>
                     <p class="text-slate-300 text-center leading-relaxed">Every unit is deep-sanitized with hospital-grade disinfectants. We don't cut corners on cleanliness.</p>
                 </div>

                 {{-- Promise 3 --}}
                 <div class="relative bg-slate-800/50 backdrop-blur-sm rounded-3xl p-8 border border-slate-700 hover:border-blue-500/50 transition group">
                     <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-blue-500/20 group-hover:scale-110 transition-transform">
                         <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                             <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                         </svg>
                     </div>
                     <div class="absolute -top-4 -right-4 w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-black text-lg shadow-lg">3</div>
                     <h3 class="text-2xl font-extrabold text-white mb-3 text-center">Same-Day or It's Free</h3>
                     <p class="text-slate-300 text-center leading-relaxed">Order by 2PM for same-day delivery. If we're late, your rental is 100% free. No excuses.</p>
                 </div>
             </div>

             {{-- Guarantee Banner --}}
             <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 sm:p-8 shadow-2xl">
                 <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                     <div class="flex-1">
                         <h3 class="text-2xl font-black text-white mb-2">100% Satisfaction Guarantee</h3>
                         <p class="text-amber-100">If you're not happy with your unit, we'll replace it free within 24 hours. That's our promise.</p>
                     </div>
                     <a href="tel:{{ $phoneRaw }}"
                        data-tracking-label="home-promise-cta"
                        class="inline-flex items-center gap-3 bg-slate-900 hover:bg-slate-800 text-white font-black py-4 px-10 rounded-full shadow-xl hover:shadow-2xl transition hover:scale-[1.02] min-h-[44px] text-lg whitespace-nowrap">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                             <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                         </svg>
                         <span>Call {{ $phoneDisplay }}</span>
                     </a>
                 </div>
             </div>
         </div>
     </section>

     {{-- ================================================================
          TESTIMONIALS — 6 Attractive & Trustable
         ================================================================ --}}
    <section class="py-16 px-4 bg-gray-50">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-center mb-4 text-gray-900">
                What Our Customers Say
            </h2>
            <p class="text-center text-gray-600 mb-12 max-w-2xl mx-auto">Based on {{ $reviewCount }} verified Google Reviews • Average rating: {{ $rating }}/5</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                // Always use static city names from DB for consistent testimonials
                // Query DB directly since $topCities is empty for USA-wide homepage
                $staticCities = \App\Models\City::active()->with('state')->orderBy('priority', 'desc')->take(4)->get();
                $staticCity = $staticCities[0]->name ?? 'Aaronsburg';
                $staticState = $staticCities[0]->state->name ?? 'Pennsylvania';
                $staticCity2 = $staticCities[1]->name ?? 'Harrisburg';
                $staticCity3 = $staticCities[2]->name ?? 'Lancaster';
                $staticCity4 = $staticCities[3]->name ?? 'York';

                $location1 = $staticCity . ', ' . $staticState;
                $location2 = $staticCity2 . ', ' . $staticState;
                $location3 = $staticCity3 . ', ' . $staticState;
                $location4 = $staticCity4 . ', ' . $staticState;

                $testimonials6 = [
                    ['name' => 'John D.', 'location' => $location1, 'rating' => 5, 'content' => 'Called at 10 AM for a construction site, had two units delivered by 1 PM. Drivers were professional, units were spotless. Will use every time.'],
                    ['name' => 'Sarah M.', 'location' => $location2, 'rating' => 5, 'content' => 'Used the luxury restroom trailer for our daughters wedding. Guests kept asking who provided it - looked like a permanent restroom. 10/10.'],
                    ['name' => 'Mike R.', 'location' => $location1, 'rating' => 5, 'content' => 'Had a pipe burst, called at 11 PM, had emergency units delivered by 2 AM. Saved our home from water damage. Forever grateful.'],
                    ['name' => 'Lisa T.', 'location' => $location3, 'rating' => 5, 'content' => 'Best porta potty company in ' . $stateName . '. Called 5 companies, they were the only ones who answered at 7 AM on Saturday. Same-day delivery too!'],
                    ['name' => 'Robert K.', 'location' => $location1, 'rating' => 5, 'content' => 'We\'ve been using them for 3 years on all our construction sites. Never once had a complaint. Units are always clean, drivers are always on time.'],
                    ['name' => 'Amanda S.', 'location' => $location4, 'rating' => 5, 'content' => 'Rented 8 units for our corporate event. Setup was clean, breakdown was fast, and the units looked brand new. Highly recommend!'],
                ];
                @endphp

                @foreach($testimonials6 as $t)
                    <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex text-yellow-400 mb-3">
                            @for($i = 0; $i < $t['rating']; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-gray-600 mb-4">"{{ $t['content'] }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($t['name'], 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $t['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $t['location'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="tel:{{ $phoneRaw }}"
                   data-tracking-label="home-testimonials"
                   class="inline-block bg-amber-500 hover:bg-amber-400 text-white font-bold py-3 px-6 rounded-full shadow-lg shadow-amber-500/30 transition hover:scale-[1.02]">
                    Call To Join Our Happy Customers
                </a>
            </div>
        </div>
    </section>

    {{-- ================================================================
         VIDEO + CTA
         ================================================================ --}}
    <section class="py-16 sm:py-20 px-4 sm:px-6 bg-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4">See Why {{ $cityName }} Calls Us First</h2>
            <p class="text-gray-600 mb-8 max-w-2xl mx-auto">Watch how we deliver clean, sanitized porta potties across {{ $stateName }} in hours, not days.</p>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden max-w-3xl mx-auto mb-8">
                <div class="relative aspect-video bg-slate-900">
                    <iframe
                        height="350"
                        src="https://www.youtube.com/embed/qnmJ31rg118?si=3bBne_xcz4OdFhJe"
                        title="Porta Potty Rental - {{ $domain?->business_name ?? 'Potty Direct' }}"
                        class="inset-0 w-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin"
                    ></iframe>
                </div>
                <div class="p-4 bg-gray-50 border-t border-gray-200">
                    <p class="text-center text-sm text-gray-500">
                        Learn about our <strong>same-day delivery</strong>, <strong>clean units</strong>, and
                        <strong>transparent pricing</strong> in under a minute.
                    </p>
                </div>
            </div>
        </div>
    </section>

      {{-- ================================================================
           BLOG PREVIEW (SEO Boost)
           ================================================================ --}}
     @php
         $recentPosts = \App\Models\BlogPost::where('is_published', true)
             ->where('domain_id', $domain?->id)
             ->orderBy('published_at', 'desc')
             ->limit(3)
             ->get();
     @endphp

     @if($recentPosts && count($recentPosts) > 0)
         <section class="py-16 px-4 bg-gray-50">
             <div class="max-w-6xl mx-auto">
                 <div class="text-center mb-10">
                     <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3">Latest Tips & Guides</h2>
                     <p class="text-gray-600 max-w-2xl mx-auto">Expert advice on porta potty rentals, event planning, and job site sanitation.</p>
                 </div>

                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                     @foreach($recentPosts as $post)
                         <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
                             @if($post->featured_image)
                                 <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                             @else
                                 <div class="w-full h-48 bg-blue-100 flex items-center justify-center">
                                     <svg class="w-12 h-12 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 8V5a2 2 0 00-2-2h-2M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1"/></svg>
                                 </div>
                             @endif
                             <div class="p-6">
                                 <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">{{ $post->title }}</h3>
                                 <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ Str::limit(strip_tags($post->excerpt ?? $post->content), 120) }}</p>
                                 <a href="{{ route('blog.show', $post->slug) }}" class="text-emerald-600 font-bold hover:underline text-sm">Read More →</a>
                             </div>
                         </article>
                     @endforeach
                 </div>

                 <div class="text-center">
                     <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold min-h-[44px]">
                         View All Articles
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                     </a>
                 </div>
             </div>
         </section>
     @endif

    {{-- ================================================================
         FAQ — 20 Questions with Expand/Collapse
         ================================================================ --}}
    <section id="faq" class="py-16 sm:py-20 px-4 sm:px-6 bg-white">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 text-balance">Frequently Asked Questions</h2>
                <p class="text-gray-600">Everything we get asked before people call.</p>
            </div>

            @php
                $homeFaqs = [
                    ['q' => 'How much does porta potty rental cost in '.$cityName.'?', 'a' => 'Rates start at $100-175/day for standard units in '.$cityName.', with discounts for long-term and bulk orders. Call '.$phoneDisplay.' for a no-obligation custom quote.'],
                    ['q' => 'Do you offer same-day porta potty delivery in '.$stateName.'?', 'a' => 'Yes! Order by 2PM for same-day delivery to '.$cityName.' and surrounding areas. Call '.$phoneDisplay.' to check real-time availability.'],
                    ['q' => 'What types of porta potty units do you offer?', 'a' => 'Standard portable toilets, deluxe flushable units with handwashing stations, ADA-compliant accessible units, and luxury restroom trailers. Call '.$phoneDisplay.' to discuss your needs.'],
                    ['q' => 'Do you offer restroom trailers for events?', 'a' => 'Yes. Our luxury restroom trailers feature climate control, porcelain fixtures, and elegant interiors for weddings and corporate events in '.$cityName.'. Call '.$phoneDisplay.' to book.'],
                    ['q' => 'How many porta potties do I need for my event?', 'a' => '1 standard unit per 50 guests for a 4-hour event. If alcohol is served, add 20% more. For construction sites, OSHA requires 1 unit per 20 workers. Call '.$phoneDisplay.' and we\'ll help you determine the right number.'],
                    ['q' => 'What is included in the rental?', 'a' => 'Delivery, setup, pickup, and — for weekly/monthly rentals — regular servicing. No hidden fees — the price we quote is the price you pay. Call '.$phoneDisplay.' for transparent pricing.'],
                    ['q' => 'Do you service construction sites in '.$county.' County?', 'a' => 'Yes, we provide long-term construction rentals with weekly pumping, restocking, and 24/7 emergency service throughout '.$county.' County. Call '.$phoneDisplay.' for competitive jobsite rates.'],
                    ['q' => 'Are your portable toilets ADA-compliant?', 'a' => 'All ADA units meet federal accessibility standards, and we provide permit certification for '.$stateName.' projects. Call '.$phoneDisplay.' to order compliant units.'],
                    ['q' => 'Can I rent porta potties for a one-day event in '.$cityName.'?', 'a' => 'Absolutely! We offer short-term event rentals with delivery, setup, and post-event removal in '.$cityName.'. Call '.$phoneDisplay.' to plan your event sanitation needs.'],
                    ['q' => 'How often are units serviced?', 'a' => 'Standard rentals include weekly servicing (cleaning, restocking supplies, waste removal). Additional servicing is available for a small fee.'],
                    ['q' => 'Do you provide handwashing stations?', 'a' => 'Yes, we offer portable handwashing stations with soap, paper towels, and fresh water. These can be paired with any porta potty rental.'],
                    ['q' => 'What is your service area in '.$stateName.'?', 'a' => 'We serve '.$cityName.' and all surrounding communities including '.$nearbyCity1.', '.$nearbyCity2.', and '.$nearbyCity3.'. Call '.$phoneDisplay.' to confirm service to your exact location.'],
                    ['q' => 'Do you offer emergency porta potty rentals?', 'a' => 'Yes, we provide 24/7 emergency response for disasters, pipe bursts, and urgent job site needs. Call '.$phoneDisplay.' for immediate dispatch.'],
                    ['q' => 'Are your units sanitized between rentals?', 'a' => 'Every unit is deep-cleaned, disinfected, and restocked before delivery. We follow strict OSHA and health department sanitation protocols.'],
                    ['q' => 'Do you offer dumpster rental too?', 'a' => 'Yes, we provide roll-off dumpsters (10-40 yard) for construction debris and cleanouts. Ask about bundle discounts when you call '.$phoneDisplay.'.'],
                    ['q' => 'What payment methods do you accept?', 'a' => 'We accept all major credit cards, checks, and offer net-30 terms for qualified contractors. Call '.$phoneDisplay.' to set up your account.'],
                    ['q' => 'Do you provide septic services?', 'a' => 'Yes, we offer professional septic pumping and maintenance for residential and commercial properties. Call '.$phoneDisplay.' to schedule service.'],
                    ['q' => 'Can you deliver to remote job sites?', 'a' => 'Yes, we deliver to remote locations and undeveloped job sites throughout '.$stateName.'. Call '.$phoneDisplay.' to discuss your site access needs.'],
                    ['q' => 'What happens if a unit is damaged or tipped over?', 'a' => 'We provide 24/7 emergency response to replace damaged units. Our service includes damage assessment and immediate replacement. Call '.$phoneDisplay.' anytime.'],
                    ['q' => 'Do you offer portable shower units?', 'a' => 'Yes, we rent portable shower units with hot and cold water for construction sites and emergency response situations. Call '.$phoneDisplay.' for availability.'],
                ];
                $visibleFaqs = array_slice($homeFaqs, 0, 6);
                $hiddenFaqs = array_slice($homeFaqs, 6);
            @endphp

            <div x-data="{ expanded: false }">
                <div class="space-y-3" id="faq-container">
                    @foreach($visibleFaqs as $faq)
                        <details id="faq-{{ \Illuminate\Support\Str::slug(\Illuminate\Support\Str::limit($faq['q'], 50, '')) }}" class="border border-gray-200 rounded-xl hover:shadow-md transition group scroll-mt-24">
                            <summary class="flex justify-between items-start gap-4 p-5 cursor-pointer list-none">
                                <h3 class="text-sm sm:text-base font-semibold text-gray-900 group-hover:text-emerald-600 transition flex-1">{{ $faq['q'] }}</h3>
                                <span aria-hidden="true" class="flex-shrink-0 w-7 h-7 rounded-full bg-gray-100 group-hover:bg-emerald-500 group-hover:text-white text-gray-500 flex items-center justify-center text-lg font-bold transition group-open:rotate-45">+</span>
                            </summary>
                            <div class="px-5 pb-5 text-gray-600 leading-relaxed text-sm sm:text-base">
                                <p>{{ $faq['a'] }}</p>
                            </div>
                        </details>
                    @endforeach

                    <div x-show="expanded" x-collapse x-cloak class="space-y-3">
                        @foreach($hiddenFaqs as $faq)
                            <details id="faq-{{ \Illuminate\Support\Str::slug(\Illuminate\Support\Str::limit($faq['q'], 50, '')) }}" class="border border-gray-200 rounded-xl hover:shadow-md transition group scroll-mt-24">
                                <summary class="flex justify-between items-start gap-4 p-5 cursor-pointer list-none">
                                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 group-hover:text-emerald-600 transition flex-1">{{ $faq['q'] }}</h3>
                                    <span aria-hidden="true" class="flex-shrink-0 w-7 h-7 rounded-full bg-gray-100 group-hover:bg-emerald-500 group-hover:text-white text-gray-500 flex items-center justify-center text-lg font-bold transition group-open:rotate-45">+</span>
                                </summary>
                                <div class="px-5 pb-5 text-gray-600 leading-relaxed text-sm sm:text-base">
                                    <p>{{ $faq['a'] }}</p>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>

                @if(count($hiddenFaqs) > 0)
                    <div class="text-center mt-6">
                        <button type="button" @click="expanded = !expanded" :aria-expanded="expanded ? 'true' : 'false'" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold min-h-[44px]">
                            <span x-text="expanded ? 'Show Less' : 'Show More FAQs'"></span>
                            <svg x-bind:class="expanded ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>
     </section>

      {{-- ================================================================
           SERVING AREAS — Rich Modern Design
          ================================================================ --}}
     <section class="py-16 sm:py-20 px-4 sm:px-6 bg-white relative overflow-hidden">
         {{-- Background Decoration --}}
         <div class="absolute inset-0 opacity-5">
             <div class="absolute top-20 left-10 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
             <div class="absolute bottom-20 right-10 w-80 h-80 bg-amber-500 rounded-full blur-3xl"></div>
         </div>

         <div class="max-w-6xl mx-auto relative">
             <div class="text-center mb-12">
                 <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-sm font-bold px-4 py-2 rounded-full mb-4">
                     <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 8a1 1 0 100-2 0 1 1 0 002 0zm8-1a1 1 0 100-2 0 1 1 0 002 0zm2 1a1 1 0 100-2 0 1 1 0 002 0zM2 8a1 1 0 100-2 0 1 1 0 002 0zm14 0a1 1 0 100-2 0 1 1 0 002 0zM5.657 4.343a1 1 0 111.414 1.414L5.657 7.172a1 1 0 01-1.414-1.414l1.414-1.415zm8.828 8.828a1 1 0 111.414 1.414l-1.414 1.415a1 1 0 01-1.414-1.414l1.414-1.415zm-7.414-4a1 1 0 011.414 0l1.415 1.414a1 1 0 01-1.414 1.414L5.657 8.343a1 1 0 010-1.414z"/></svg>
                     NATIONWIDE SERVICE
                 </div>
                 <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4">
                     We Serve <span class="text-blue-600">{{ $stateName }}</span> & Beyond
                 </h2>
                 <p class="text-lg text-gray-600 max-w-2xl mx-auto">From major cities to rural communities, we deliver clean, reliable porta potties anywhere in {{ $stateName }}. Same-day delivery available.</p>
             </div>

             {{-- Interactive Map/Grid --}}
             <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-8 sm:p-10 shadow-2xl mb-12 relative overflow-hidden">
                 <div class="absolute inset-0 opacity-10">
                     <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,<svg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;><g fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;><g fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.05&quot;><path d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/></g></g></svg>')"></div>
                 </div>

                 <div class="relative">
                     <h3 class="text-xl sm:text-2xl font-black text-white mb-6 text-center">Communities We Serve in {{ $stateName }}</h3>

                     <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mb-8">
                         @if(!empty($topCities))
                             @foreach(array_slice($topCities, 0, 10) as $c)
                                 <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center hover:bg-white/20 transition group cursor-default">
                                     <div class="w-3 h-3 bg-emerald-400 rounded-full mx-auto mb-2 group-hover:scale-150 transition-transform"></div>
                                     <div class="text-sm font-bold text-white">{{ $c['name'] }}</div>
                                     <div class="text-xs text-slate-400">{{ $c['state']['code'] ?? '' }}</div>
                                 </div>
                             @endforeach
                         @else
                             <div class="col-span-full text-center text-slate-400 py-8">
                                 <div class="text-4xl mb-2">📍</div>
                                 <div class="text-lg font-bold text-white">All Major Cities</div>
                                 <div class="text-sm">Call {{ $phoneDisplay }} for availability</div>
                             </div>
                         @endif
                     </div>

                     <div class="text-center">
                         <div class="inline-flex items-center gap-4 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3">
                             <div class="flex items-center gap-2 text-emerald-400">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                             </div>
                             <span class="text-white font-bold">50+ ZIP Codes Covered</span>
                         </div>
                     </div>
                 </div>
             </div>

             {{-- CTA --}}
             <div class="text-center">
                 <a href="tel:{{ $phoneRaw }}"
                    data-tracking-label="home-areas-cta"
                    class="inline-flex items-center gap-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white font-bold py-4 px-10 rounded-full shadow-2xl shadow-amber-500/30 transition hover:scale-[1.03] min-h-[44px] text-lg">
                     <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                         <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                     </svg>
                     <span>Call {{ $phoneDisplay }} to Confirm Service Area</span>
                 </a>
                 <p class="mt-4 text-gray-500">Average answer time: 15 seconds • Real humans answer</p>
                 <div class="mt-6">
                     <a href="/locations"
                        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold min-h-[44px]">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                             <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                         </svg>
                         <span>View All Locations</span>
                     </a>
                 </div>
             </div>
         </div>
     </section>

 @endsection
