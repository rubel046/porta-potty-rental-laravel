@extends(\App\Models\Domain::getLayoutPathStatic())

@section('title', $state->seo_title)
@section('meta_description', $state->seo_description)
@section('canonical', url()->current())

@push('schema')
@php
$url = url('/');
$phone = domain_phone_raw();

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

    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiMyMDI5NDIiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-30"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-white/90 text-sm font-medium">Serving {{ $state->name }}</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    {{ $state->h1_title ?? 'Porta Potty Rental' }}
                </h1>
                
                <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">
                    Professional portable sanitation solutions for construction sites, events, weddings, and more across {{ $state->name }}.
                </p>
                
                <div class="flex flex-wrap justify-center gap-4 mb-10">
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-5 py-2.5 rounded-full">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-white text-sm font-medium">Same-Day Delivery</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-5 py-2.5 rounded-full">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-white text-sm font-medium">Clean & Sanitized</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-5 py-2.5 rounded-full">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-white text-sm font-medium">No Hidden Fees</span>
                    </div>
                </div>
                
                <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-lg px-8 py-4 rounded-full transition-all shadow-2xl shadow-emerald-500/30 hover:shadow-emerald-600/40 hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
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
                    <div class="text-3xl font-bold text-emerald-600">{{ $cities->total() }}+</div>
                    <div class="text-sm text-slate-500 mt-1">Cities Served</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-600">24/7</div>
                    <div class="text-sm text-slate-500 mt-1">Emergency Service</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-600">Same Day</div>
                    <div class="text-sm text-slate-500 mt-1">Delivery Available</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-600">15+</div>
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
                        <span class="inline-block text-emerald-600 font-semibold text-sm tracking-wider uppercase mb-4">About Our Service</span>
                        <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-6">
                            Professional Porta Potty Solutions in {{ $state->name }}
                        </h2>
                        <p class="text-lg text-slate-600 mb-8">
                            We provide reliable, clean, and affordable portable restroom solutions for projects and events throughout {{ $state->name }}.
                        </p>
                        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Get Free Quote
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-3 prose prose-slate prose-lg max-w-none
                            prose-headings:text-slate-800 prose-headings:font-bold
                            prose-h2:text-xl prose-h2:mt-10 prose-h2:mb-4 prose-h2:border-b prose-h2:border-slate-200 prose-h2:pb-3
                            prose-h3:text-lg prose-h3:mt-6 prose-h3:mb-3
                            prose-p:text-slate-600 prose-p:leading-relaxed prose-p:mb-4
                            prose-li:text-slate-600 prose-li:leading-relaxed prose-li:mb-2
                            prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
                            prose-strong:text-slate-800 prose-strong:font-semibold
                            prose-blockquote:border-l-emerald-500 prose-blockquote:bg-emerald-50 prose-blockquote:rounded-r-xl
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
                <span class="inline-block text-emerald-600 font-semibold text-sm tracking-wider uppercase mb-4">Find Your Location</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">
                    Cities We Serve in {{ $state->name }}
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Select your city below to view local pricing and availability
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                @foreach($cities as $city)
                    @php $cityPage = $city->getServicePage('general'); @endphp
                    <a href="{{ $cityPage ? url($cityPage->slug) : '#' }}"
                       class="group relative bg-white rounded-xl p-4 border border-slate-200 hover:border-emerald-300 hover:shadow-lg transition-all duration-300 text-center">
                        <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-b-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <h3 class="font-semibold text-slate-800 group-hover:text-emerald-600 transition-colors">
                            {{ $city->name }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">{{ $state->code }}</p>
                        @if($city->population && $city->population > 50000)
                            <span class="inline-block mt-2 text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">
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
                    
                    <span class="px-4 py-2 bg-emerald-500 text-white rounded-lg">
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
                <span class="inline-block text-emerald-600 font-semibold text-sm tracking-wider uppercase mb-4">What We Offer</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">
                    Portable Restroom Solutions
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    From standard units to luxury trailers, we have the perfect solution for your needs
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('services') }}#standard" class="group bg-gradient-to-br from-slate-50 to-white p-8 rounded-2xl border border-slate-200 hover:border-emerald-300 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-emerald-500 transition-colors">
                        <svg class="w-7 h-7 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-emerald-600 transition-colors">Standard Units</h3>
                    <p class="text-slate-600 text-sm">Perfect for construction sites and basic needs</p>
                </a>

                <a href="{{ route('services') }}#deluxe" class="group bg-gradient-to-br from-slate-50 to-white p-8 rounded-2xl border border-slate-200 hover:border-emerald-300 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-blue-500 transition-colors">
                        <svg class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-emerald-600 transition-colors">Deluxe Flushable</h3>
                    <p class="text-slate-600 text-sm">Enhanced comfort for events and weddings</p>
                </a>

                <a href="{{ route('services') }}#ada" class="group bg-gradient-to-br from-slate-50 to-white p-8 rounded-2xl border border-slate-200 hover:border-emerald-300 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-purple-500 transition-colors">
                        <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-emerald-600 transition-colors">ADA Accessible</h3>
                    <p class="text-slate-600 text-sm"> wheelchair-accessible units</p>
                </a>

                <a href="{{ route('services') }}#luxury" class="group bg-gradient-to-br from-slate-50 to-white p-8 rounded-2xl border border-slate-200 hover:border-emerald-300 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-amber-500 transition-colors">
                        <svg class="w-7 h-7 text-amber-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-emerald-600 transition-colors">Luxury Trailers</h3>
                    <p class="text-slate-600 text-sm">Premium restroom trailers for VIP events</p>
                </a>
            </div>
        </div>
    </section>

    {{-- Trust Badges --}}
    <section class="py-12 bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center">
                <div class="text-center">
                    <div class="text-4xl mb-2">🏆</div>
                    <div class="text-white font-semibold">Licensed & Insured</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-2">⭐</div>
                    <div class="text-white font-semibold">4.9/5 Customer Rating</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-2">🔒</div>
                    <div class="text-white font-semibold">Secure Booking</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-2">💯</div>
                    <div class="text-white font-semibold">Satisfaction Guaranteed</div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="relative py-20 lg:py-28 bg-gradient-to-br from-emerald-500 to-emerald-700 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,_rgba(255,255,255,0.3)_0%,_transparent_50%)]"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">
                Ready to Get Started?
            </h2>
            <p class="text-xl text-emerald-100 mb-10 max-w-2xl mx-auto">
                Get a free, no-obligation quote for your porta potty rental in {{ $state->name }}. Same-day delivery available!
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="tel:{{ domain_phone_raw() }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-white text-emerald-600 font-bold text-xl px-10 py-4 rounded-full hover:bg-emerald-50 transition-all shadow-2xl shadow-black/20 hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ domain_phone_display() }}
                </a>
                <a href="{{ route('services') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 text-white font-semibold px-8 py-4 rounded-full hover:bg-emerald-700 transition-colors border-2 border-white/20">
                    View All Services
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            <p class="mt-6 text-emerald-200 text-sm">
                24/7 Emergency Service Available
            </p>
        </div>
    </section>
@endsection
