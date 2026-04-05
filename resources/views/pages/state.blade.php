@extends('layouts.app')

@section('title', "Porta Potty Rental in {$state->name} | Cities We Serve")
@section('meta_description', "Find affordable porta potty rental in {$state->name}. Same-day delivery available in {$cities->total()}+ cities. Construction, events, weddings & more. Call for a free quote!")
@section('canonical', url()->current())

@push('schema')
@php
$url = url('/');
$phone = phone_raw();

$localBusinessSchema = [
    "@context" => "https://schema.org",
    "@type" => "LocalBusiness",
    "@id" => $url . "#business",
    "name" => "Potty Direct",
    "description" => "Porta potty rental in " . $state->name . ". Same-day delivery available.",
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
<script type="application/ld+json">{!! json_encode($localBusinessSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

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
            <nav class="flex items-center justify-center gap-2 text-sm text-slate-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('locations') }}" class="hover:text-white transition">Locations</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white">{{ $state->name }}</span>
            </nav>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
                Porta Potty Rental<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-300">{{ $state->name }}</span>
            </h1>
            <p class="text-xl text-slate-300 mb-6">
                Serving {{ $cities->total() }} cities across {{ $state->name }}
            </p>
            <div class="flex flex-wrap justify-center gap-4 text-sm text-slate-400">
                <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">🚚 Same-Day Delivery</span>
                <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">💰 No Hidden Fees</span>
                <span class="bg-white/10 backdrop-blur px-4 py-2 rounded-full">✅ Licensed & Insured</span>
            </div>
        </div>
    </section>

    {{-- Cities Grid --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($cities as $city)
                    @php $cityPage = $city->getServicePage('general'); @endphp
                    <a href="{{ $cityPage ? url($cityPage->slug) : '#' }}"
                       class="bg-white border border-slate-200 rounded-xl p-5
                              hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-100/50 hover:-translate-y-1
                              transition-all duration-300 text-center group">
                        <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition text-base">
                            {{ $city->name }}
                        </h3>
                        <p class="text-sm text-slate-400 mt-1">{{ $state->code }}</p>
                        @if($city->population)
                            <p class="text-xs text-slate-400 mt-1">
                                Pop: {{ number_format($city->population) }}
                            </p>
                        @endif
                        @if($cityPage)
                            <span class="inline-block mt-2 text-xs text-emerald-600 font-medium opacity-0 group-hover:opacity-100 transition">
                                View →
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            @if($cities->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $cities->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- Quick Links --}}
    <section class="py-12 md:py-16 px-4 bg-slate-50">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8">
                Explore Our Services
            </h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('services') }}#standard"
                   class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg hover:border-emerald-300 transition-all text-center group border border-slate-200">
                    <div class="text-4xl mb-3">🚽</div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition">Standard Units</h3>
                    <p class="text-sm text-slate-500 mt-1">Construction & Events</p>
                </a>
                <a href="{{ route('services') }}#deluxe"
                   class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg hover:border-emerald-300 transition-all text-center group border border-slate-200">
                    <div class="text-4xl mb-3">🚿</div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition">Deluxe Flushable</h3>
                    <p class="text-sm text-slate-500 mt-1">Weddings & Parties</p>
                </a>
                <a href="{{ route('services') }}#ada"
                   class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg hover:border-emerald-300 transition-all text-center group border border-slate-200">
                    <div class="text-4xl mb-3">♿</div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition">ADA Accessible</h3>
                    <p class="text-sm text-slate-500 mt-1">Compliance Ready</p>
                </a>
                <a href="{{ route('pricing') }}"
                   class="bg-white p-6 rounded-xl shadow-sm hover:shadow-lg hover:border-emerald-300 transition-all text-center group border border-slate-200">
                    <div class="text-4xl mb-3">💰</div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition">View Pricing</h3>
                    <p class="text-sm text-slate-500 mt-1">Transparent Rates</p>
                </a>
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('locations') }}"
                   class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold transition">
                    Browse all cities in {{ $state->name }} →
                </a>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-16 md:py-20 px-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">
                Need a Porta Potty in {{ $state->name }}?
            </h2>
            <p class="text-emerald-100 mb-8">Call now for instant pricing and same-day delivery.</p>
            <a href="tel:{{ phone_raw() }}"
               class="inline-flex items-center gap-3 bg-white text-emerald-600 font-bold text-2xl
                      py-4 px-12 rounded-full hover:scale-105 transition-all shadow-xl shadow-emerald-500/30">
                📞 {{ phone_display() }}
            </a>
            <p class="mt-5 text-emerald-100 text-sm">Mon-Sat 7AM-8PM • Free Quote</p>
        </div>
    </section>
@endsection
