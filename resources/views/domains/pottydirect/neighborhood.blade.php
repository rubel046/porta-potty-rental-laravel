@extends(DomainViewHelper::resolveLayout())
@php
    $phoneRaw = domain_phone_raw();
    $phoneDisplay = domain_phone_display();
    $serviceLabel = $domainLabel;
    $domainName = $domain?->business_name ?? 'Potty Direct';
@endphp

@section('title', $page->meta_title ?: "{$serviceLabel} in {$neighborhood->name}, {$city->name} | {$domainName}")
@section('meta_description', $page->meta_description ?: "{$serviceLabel} in {$neighborhood->name}, {$city->name}. Same-day delivery available. Call {$phoneDisplay}.")
@section('canonical', url("neighborhoods/{$page->slug}"))
@section('og_title', $page->h1_title ?: "{$serviceLabel} in {$neighborhood->name}")
@section('og_description', $page->meta_description ?: "{$serviceLabel} serving {$neighborhood->name}, {$city->name}.")
@if($city->latitude && $city->longitude)
    @section('og_latitude', $city->latitude)
    @section('og_longitude', $city->longitude)
@endif

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "{{ $serviceLabel }} in {{ $neighborhood->name }}, {{ $city->name }}",
    "provider": {
        "@type": "LocalBusiness",
        "name": "{{ $domainName }}",
        "url": "{{ url('/') }}"
    },
    "areaServed": {
        "@type": "Neighborhood",
        "name": "{{ $neighborhood->name }}",
        "containedInPlace": {
            "@type": "City",
            "name": "{{ $city->name }}"
        }
    }
}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <x-icon name="chevron-right" class="w-4 h-4 text-gray-400" />
        <a href="{{ url('/neighborhoods/' . Str::slug($city->state->code)) }}" class="hover:text-blue-600 transition">{{ $city->state->name }}</a>
        <x-icon name="chevron-right" class="w-4 h-4 text-gray-400" />
        <a href="{{ url('/neighborhoods/' . $city->slug) }}" class="hover:text-blue-600 transition">{{ $city->name }}</a>
        <x-icon name="chevron-right" class="w-4 h-4 text-gray-400" />
        <span class="text-gray-800 font-medium">{{ $neighborhood->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">{{ $page->h1_title ?: "{$serviceLabel} in {$neighborhood->name}, {$city->name}" }}</h1>

                {{-- Hero CTA --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-5 mb-8 text-white">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-lg font-semibold">{{ $serviceLabel }} — {$neighborhood->name}</p>
                            <p class="text-blue-100 text-sm mt-1">Same-day delivery available. No hidden fees.</p>
                        </div>
                        <a href="tel:{{ $phoneRaw }}"
                           data-tracking-label="neighborhood_hero_phone"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-700 rounded-xl font-bold text-lg hover:bg-blue-50 transition shadow-lg min-h-[48px]">
                            <x-icon name="phone" class="w-5 h-5" />
                            {{ $phoneDisplay }}
                        </a>
                    </div>
                </div>

                {{-- Content --}}
                <div class="prose prose-gray max-w-none">
                    {!! $page->content !!}
                </div>

                {{-- Related Service Pages --}}
                @if($relatedPages->isNotEmpty())
                    <div class="mt-10 pt-8 border-t border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">More Services in {{ $neighborhood->name }}</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($relatedPages as $related)
                                <a href="{{ url("neighborhoods/{$related->slug}") }}"
                                   class="block p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                    <div class="font-semibold text-gray-800">{{ $related->h1_title ?: $related->service_type }}</div>
                                    <div class="text-sm text-gray-500 mt-1">Learn more →</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Nearby cities / neighborhoods section --}}
                @if($neighborhood->description)
                    <div class="mt-10 pt-8 border-t border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">About {{ $neighborhood->name }}</h2>
                        <p class="text-gray-600">{{ $neighborhood->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Contact Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Get Pricing & Availability</h3>
                <div class="space-y-4">
                    <a href="tel:{{ $phoneRaw }}"
                       data-tracking-label="neighborhood_sidebar_phone"
                       class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-xl font-bold text-lg hover:bg-blue-700 transition min-h-[48px]">
                        <x-icon name="phone" class="w-5 h-5" />
                        {{ $phoneDisplay }}
                    </a>
                    <p class="text-xs text-gray-400 text-center">Order by 2PM for same-day delivery</p>
                </div>
            </div>

            {{-- Service Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-3">Service Info</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2">
                        <x-icon name="map-pin" class="w-4 h-4 text-blue-500 flex-shrink-0" />
                        <span>Serving <strong>{{ $neighborhood->name }}</strong>, {{ $city->name }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <x-icon name="truck" class="w-4 h-4 text-blue-500 flex-shrink-0" />
                        <span>Same-day delivery available</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <x-icon name="clock" class="w-4 h-4 text-blue-500 flex-shrink-0" />
                        <span>7 days a week support</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <x-icon name="shield-check" class="w-4 h-4 text-blue-500 flex-shrink-0" />
                        <span>Licensed & insured</span>
                    </li>
                </ul>
            </div>

            {{-- Service Area --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-3">Service Area</h3>
                <p class="text-sm text-gray-600">{{ $neighborhood->name }}, {{ $city->name }}, {{ $city->state->code }}</p>
                <a href="{{ url('/locations') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium mt-2 inline-block">
                    View all locations →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
