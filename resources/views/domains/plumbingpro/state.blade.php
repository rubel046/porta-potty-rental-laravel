@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', $state->seo_title)
@section('meta_description', $state->seo_description)
@section('canonical', url()->current())

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();

$plumberSchema = [
    "@context" => "https://schema.org",
    "@type" => "Plumber",
    "@id" => $url . "#business",
    "name" => $domain?->business_name ?? "Plumbing Pro",
    "description" => ($domain?->primary_service ?? "Plumbing services") . " in " . $state->name . ". " . ($domain?->tagline ?? "Same-day service available."),
    "url" => $url,
    "telephone" => $phone,
    "priceRange" => "$$",
    "areaServed" => [
        "@type" => "State",
        "name" => $state->name
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

$breadcrumbSchema = [
    "@context" => "https://schema.org",
    "@type" => "BreadcrumbList",
    "itemListElement" => [
        ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => route('home')],
        ["@type" => "ListItem", "position" => 2, "name" => "Locations", "item" => route('locations')],
        ["@type" => "ListItem", "position" => 3, "name" => $state->name, "item" => url()->current()]
    ]
];
@endphp
<script type="application/ld+json">{!! json_encode($plumberSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMyMDI5NDIiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-30"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                    <x-icon name="map-pin" class="w-5 h-5 text-blue-400" />
                    <span class="text-white/90 text-sm font-medium">Serving {{ $state->name }}</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    {{ $state->h1_title ?? ($domain?->primary_service ?? 'Plumbing Services') }}
                </h1>
                
                <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">
                    Professional {{ $domain?->primary_service ?? 'plumbing services' }} for {{ $state->name }}. {{ $domain?->tagline ?? 'Same-day service available.' }}
                </p>
                
                <div class="flex flex-wrap justify-center gap-4 mb-10">
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-5 py-2.5 rounded-full">
                        <x-icon name="check" class="w-5 h-5 text-blue-400" />
                        <span class="text-white text-sm font-medium">Same-Day Service</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-5 py-2.5 rounded-full">
                        <x-icon name="check" class="w-5 h-5 text-blue-400" />
                        <span class="text-white text-sm font-medium">Licensed &amp; Insured</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-5 py-2.5 rounded-full">
                        <x-icon name="check" class="w-5 h-5 text-blue-400" />
                        <span class="text-white text-sm font-medium">Upfront Pricing</span>
                    </div>
                </div>
                
                <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg px-8 py-4 rounded-full transition-all shadow-2xl shadow-blue-600/30 hover:shadow-blue-700/40 hover:scale-105">
                    <x-icon name="phone" class="w-6 h-6" />
                    {{ domain_phone_display() }}
                </a>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white to-transparent"></div>
    </section>

    {{-- Stats Bar --}}
    <section class="bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $cities->total() }}+</div>
                    <div class="text-sm text-slate-500 mt-1">Cities Served</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">24/7</div>
                    <div class="text-sm text-slate-500 mt-1">Emergency Service</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">Same Day</div>
                    <div class="text-sm text-slate-500 mt-1">Service Available</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">20+</div>
                    <div class="text-sm text-slate-500 mt-1">Years Experience</div>
                </div>
            </div>
        </div>
    </section>

    @if($state->hasContent())
    {{-- About Content Section --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-5 gap-12 items-start">
                <div class="lg:col-span-2">
                    <div class="sticky top-8">
                        <span class="inline-block text-blue-600 font-semibold text-sm tracking-wider uppercase mb-4">About Our Service</span>
                        <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-6">
                            Professional {{ $domain?->primary_service ? ucfirst($domain->primary_service) : 'Plumbing' }} Solutions in {{ $state->name }}
                        </h2>
                        <p class="text-lg text-slate-600 mb-8">
                            We provide reliable, professional, and affordable {{ $domain?->primary_service ?? 'plumbing' }} solutions for homes and businesses throughout {{ $state->name }}.
                        </p>
                        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                            <x-icon name="phone" class="w-5 h-5" />
                            Get Free Quote
                        </a>

                        @if(!empty($images))
                        <div class="mt-10">
                            <h3 class="text-xl font-bold text-slate-800 mb-4">Our Work in {{ $state->name }}</h3>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach(array_slice($images, 0, 6) as $image)
                                    @php
                                        $imageUrl = asset('storage/' . $image['path']);
                                    @endphp
                                    <div class="aspect-w-1 aspect-h-1">
                                        <img src="{{ $imageUrl }}" alt="{{ $image['alt'] ?? 'Plumbing service image for ' . $state->name }}" 
                                             class="object-cover rounded-lg shadow-md" loading="lazy">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="lg:col-span-3 prose prose-slate prose-lg max-w-none
                            prose-headings:text-slate-800 prose-headings:font-bold
                            prose-h2:text-xl prose-h2:mt-10 prose-h2:mb-4 prose-h2:border-b prose-h2:border-slate-200 prose-h2:pb-3
                            prose-h3:text-lg prose-h3:mt-6 prose-h3:mb-3
                            prose-p:text-slate-600 prose-p:leading-relaxed prose-p:mb-4
                            prose-li:text-slate-600 prose-li:leading-relaxed prose-li:mb-2
                            prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                            prose-strong:text-slate-800 prose-strong:font-semibold
                            prose-blockquote:border-l-blue-500 prose-blockquote:bg-blue-50 prose-blockquote:rounded-r-xl
                            prose-ul:list-disc prose-ul:pl-6">
                    {!! Str::markdown($state->content) !!}
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Cities Section --}}
    <section class="py-16 lg:py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block text-blue-600 font-semibold text-sm tracking-wider uppercase mb-4">Find Your Location</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">
                    Cities We Serve in {{ $state->name }}
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Select your city below to view local plumbing service options
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                @foreach($cities as $city)
                    @php $cityPage = $city->getServicePage('general'); @endphp
                    <a href="{{ $cityPage ? url($cityPage->slug) : '#' }}"
                       class="group relative bg-white rounded-xl p-4 border border-slate-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 text-center">
                        <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-blue-400 to-blue-600 rounded-b-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <h3 class="font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">
                            {{ $city->name }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">{{ $state->code }}</p>
                        @if($city->population && $city->population > 50000)
                            <span class="inline-block mt-2 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                                Major City
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            @if($cities->hasPages())
            <div class="mt-12 flex justify-center">
                <nav class="flex items-center gap-2">
                    @if($cities->onFirstPage())
                        <span class="px-4 py-2 text-slate-400 cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $cities->previousPageUrl() }}" class="px-4 py-2 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Previous</a>
                    @endif
                    
                    <span class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        Page {{ $cities->currentPage() }} of {{ $cities->lastPage() }}
                    </span>
                    
                    @if($cities->hasMorePages())
                        <a href="{{ $cities->nextPageUrl() }}" class="px-4 py-2 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Next</a>
                    @else
                        <span class="px-4 py-2 text-slate-400 cursor-not-allowed">Next</span>
                    @endif
                </nav>
            </div>
            @endif
        </div>
    </section>

    {{-- Services Grid --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block text-blue-600 font-semibold text-sm tracking-wider uppercase mb-4">What We Offer</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">
                    {{ $domain?->business_name ?? 'Plumbing Pro' }} Services
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    We have the right plumbing solution for your needs
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="group bg-gradient-to-br from-slate-50 to-white p-8 rounded-2xl border border-slate-200 hover:border-blue-300 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-blue-500 transition-colors">
                        <x-icon name="clock" class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">Emergency Plumbing</h3>
                    <p class="text-slate-600 text-sm">24/7 emergency service for burst pipes, sewer backups, and urgent plumbing issues</p>
                </div>

                <div class="group bg-gradient-to-br from-slate-50 to-white p-8 rounded-2xl border border-slate-200 hover:border-orange-300 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-orange-500 transition-colors">
                        <x-icon name="sink" class="w-7 h-7 text-orange-600 group-hover:text-white transition-colors" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-orange-600 transition-colors">Drain Cleaning</h3>
                    <p class="text-slate-600 text-sm">Professional drain cleaning, hydro jetting, and camera inspection services</p>
                </div>

                <div class="group bg-gradient-to-br from-slate-50 to-white p-8 rounded-2xl border border-slate-200 hover:border-blue-300 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-blue-500 transition-colors">
                        <x-icon name="wrench" class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">Pipe &amp; Leak Repair</h3>
                    <p class="text-slate-600 text-sm">Fast, reliable repair for burst pipes, slab leaks, and water main issues</p>
                </div>

                <div class="group bg-gradient-to-br from-slate-50 to-white p-8 rounded-2xl border border-slate-200 hover:border-orange-300 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-orange-500 transition-colors">
                        <svg class="w-7 h-7 text-orange-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-orange-600 transition-colors">Water Heater Service</h3>
                    <p class="text-slate-600 text-sm">Installation, repair, and replacement of tank and tankless water heaters</p>
                </div>

                <div class="group bg-gradient-to-br from-slate-50 to-white p-8 rounded-2xl border border-slate-200 hover:border-blue-300 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-blue-500 transition-colors">
                        <svg class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">Sewer Line Service</h3>
                    <p class="text-slate-600 text-sm">Complete sewer line repair, replacement, and video inspection services</p>
                </div>

                <div class="group bg-gradient-to-br from-slate-50 to-white p-8 rounded-2xl border border-slate-200 hover:border-orange-300 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-orange-500 transition-colors">
                        <svg class="w-7 h-7 text-orange-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-orange-600 transition-colors">Toilet &amp; Faucet Repair</h3>
                    <p class="text-slate-600 text-sm">Expert repair and installation for toilets, faucets, garbage disposals, and more</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Trust Badges --}}
    <section class="py-12 bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 items-start">
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-blue-500/15 text-blue-400 flex items-center justify-center">
                        <x-icon name="shield" class="w-6 h-6" />
                    </div>
                    <div class="text-white font-semibold">Licensed &amp; Insured</div>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-orange-500/15 text-orange-400 flex items-center justify-center">
                        <x-icon name="star" class="w-6 h-6" />
                    </div>
                    <div class="text-white font-semibold">
                        @if(($reviewCount ?? 0) > 0){{ number_format($reviewRating ?? 4.9, 1) }}/5 Rating@else Trusted Service @endif
                    </div>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-blue-500/15 text-blue-400 flex items-center justify-center">
                        <x-icon name="truck" class="w-6 h-6" />
                    </div>
                    <div class="text-white font-semibold">Same-Day Service</div>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-blue-500/15 text-blue-400 flex items-center justify-center">
                        <x-icon name="dollar" class="w-6 h-6" />
                    </div>
                    <div class="text-white font-semibold">Upfront Pricing</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Properties We Serve --}}
    <section class="py-16 lg:py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block text-orange-600 font-semibold text-sm tracking-wider uppercase mb-4">Properties We Serve</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">
                    Plumbing Services for Every Property Type
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    From single-family homes to large commercial facilities, we handle it all
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-8 rounded-2xl border border-slate-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 text-center">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-5">
                        <x-icon name="home" class="w-7 h-7 text-blue-600" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Residential Homes</h3>
                    <p class="text-slate-600 text-sm">Complete residential plumbing services from minor faucet repairs to full repiping. We keep your home running smoothly.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-200 hover:border-orange-300 hover:shadow-lg transition-all duration-300 text-center">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-5">
                        <x-icon name="building" class="w-7 h-7 text-orange-600" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Commercial Properties</h3>
                    <p class="text-slate-600 text-sm">Commercial plumbing for offices, retail stores, restaurants, and industrial facilities with minimal downtime.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 text-center">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-5">
                        <x-icon name="construction" class="w-7 h-7 text-blue-600" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">New Construction</h3>
                    <p class="text-slate-600 text-sm">Rough-in plumbing, fixture installation, and gas line services for new construction projects of all sizes.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-200 hover:border-orange-300 hover:shadow-lg transition-all duration-300 text-center">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-5">
                        <x-icon name="clock" class="w-7 h-7 text-orange-600" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Emergency Repairs</h3>
                    <p class="text-slate-600 text-sm">24/7 emergency plumbing for burst pipes, sewer backups, gas leaks, and any urgent plumbing issue.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-200 hover:border-blue-300 hover:shadow-lg transition-all duration-300 text-center">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Bathroom Remodeling</h3>
                    <p class="text-slate-600 text-sm">Complete bathroom plumbing for remodels including fixture installation, rerouting, and upgrades.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-200 hover:border-orange-300 hover:shadow-lg transition-all duration-300 text-center">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Water Heater Services</h3>
                    <p class="text-slate-600 text-sm">Installation, repair, and replacement of tank and tankless water heaters. Same-day service available.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials Section --}}
    @if(!empty($stateContent['testimonials']))
    <section class="py-16 lg:py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block text-blue-600 font-semibold text-sm tracking-wider uppercase mb-4">Testimonials</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">
                    What Our {{ $state->name }} Customers Say
                </h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($stateContent['testimonials'] as $testimonial)
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < ($testimonial['rating'] ?? 5); $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-slate-600 mb-6">{!! $testimonial['content'] !!}</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-bold">{{ substr($testimonial['customer_name'], 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800">{{ $testimonial['customer_name'] }}</div>
                            <div class="text-sm text-slate-500">{{ $state->name }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- FAQs Section --}}
    @if($faqs->isNotEmpty())
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block text-orange-600 font-semibold text-sm tracking-wider uppercase mb-4">FAQ</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">
                    Frequently Asked Questions
                </h2>
                <p class="text-lg text-slate-600">
                    Common questions about our {{ $state->name }} plumbing services
                </p>
            </div>

            <div class="space-y-4">
                @foreach($faqs as $faq)
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <button class="w-full flex items-center justify-between p-5 text-left bg-slate-50 hover:bg-slate-100 transition-colors" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180');">
                        <span class="font-semibold text-slate-800">{{ $faq['question'] ?? $faq->question }}</span>
                        <svg class="w-5 h-5 text-slate-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="hidden p-5 bg-white">
                        <p class="text-slate-600">{!! $faq['answer'] ?? $faq->answer !!}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    <section class="relative py-20 lg:py-28 bg-gradient-to-br from-blue-600 to-blue-800 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,_rgba(255,255,255,0.3)_0%,_transparent_50%)]"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">
                Need a Plumber in {{ $state->name }}?
            </h2>
            <p class="text-xl text-blue-200 mb-10 max-w-2xl mx-auto">
                Get a free, no-obligation quote for {{ $domain?->primary_service ?? 'plumbing services' }} in {{ $state->name }}. {{ $domain?->tagline ?? 'Same-day service available!' }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="tel:{{ domain_phone_raw() }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-white text-blue-700 font-bold text-xl px-10 py-4 rounded-full hover:bg-blue-50 transition-all shadow-2xl shadow-black/20 hover:scale-105">
                    <x-icon name="phone" class="w-6 h-6" />
                    {{ domain_phone_display() }}
                </a>
                <a href="{{ route('services') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-orange-500 text-white font-semibold px-8 py-4 rounded-full hover:bg-orange-600 transition-colors border-2 border-white/20">
                    View All Services
                    <x-icon name="arrow-right" class="w-5 h-5" />
                </a>
            </div>
            <p class="mt-6 text-blue-300 text-sm">
                24/7 Emergency Service Available
            </p>
        </div>
    </section>
@endsection
