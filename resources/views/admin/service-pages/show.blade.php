@extends('admin.layout')
@section('title', 'Service Page')
@section('page-title', 'Service Page')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4">{{ $servicePage->h1_title }}</h2>
            <dl class="grid grid-cols-2 gap-3 text-sm mb-4">
                <div><dt class="text-gray-500">City</dt><dd>{{ $servicePage->city?->name }}</dd></div>
                <div><dt class="text-gray-500">Type</dt><dd class="capitalize">{{ $servicePage->service_type }}</dd></div>
                <div><dt class="text-gray-500">Views</dt><dd>{{ number_format($servicePage->views) }}</dd></div>
                <div><dt class="text-gray-500">Calls Generated</dt><dd>{{ number_format($servicePage->calls_generated) }}</dd></div>
                <div><dt class="text-gray-500">Word Count</dt><dd>{{ number_format($servicePage->word_count) }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd>{{ $servicePage->is_published ? 'Published' : 'Draft' }}</dd></div>
            </dl>
            <div class="flex gap-2">
                <a href="{{ url($servicePage->slug) }}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">View Page</a>
                <a href="{{ route('admin.service-pages.edit', $servicePage) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Edit</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Meta Tags</h3>
            <div class="space-y-4">
                <div>
                    <dt class="text-xs text-gray-500 mb-1">Title Tag ({{ strlen($servicePage->meta_title ?? '') }} chars)</dt>
                    <dd class="text-sm font-medium text-gray-800 break-all">{{ $servicePage->meta_title ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 mb-1">Meta Description ({{ strlen($servicePage->meta_description ?? '') }} chars)</dt>
                    <dd class="text-sm text-gray-600 break-all">{{ $servicePage->meta_description ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500 mb-1">Canonical URL</dt>
                    <dd class="text-sm text-blue-600 break-all">{{ $servicePage->canonical_url ?? '—' }}</dd>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Schema Markup</h3>
            @if($servicePage->schema_markup)
                <pre class="text-xs bg-gray-50 p-4 rounded-lg overflow-x-auto">{{ json_encode($servicePage->schema_markup, JSON_PRETTY_PRINT) }}</pre>
            @else
                <p class="text-sm text-gray-500">No schema markup defined.</p>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">SEO Score</h3>
                <span class="text-2xl font-bold {{ $servicePage->seo_score >= 70 ? 'text-green-600' : ($servicePage->seo_score >= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $servicePage->seo_score ?? 0 }}/100
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div class="h-2 rounded-full {{ $servicePage->seo_score >= 70 ? 'bg-green-500' : ($servicePage->seo_score >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $servicePage->seo_score ?? 0 }}%"></div>
            </div>
            <button onclick="document.getElementById('seo-checklist').classList.toggle('hidden')" class="text-sm text-blue-600 hover:underline">Toggle Checklist</button>
        </div>

        <div id="seo-checklist" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">SEO Checklist</h3>
            <ul class="space-y-3 text-sm">
                <li class="flex items-start gap-2">
                    <span class="{{ strlen($servicePage->meta_title ?? '') >= 30 && strlen($servicePage->meta_title ?? '') <= 60 ? 'text-green-500' : 'text-red-500' }}">✓</span>
                    <span>Title tag 30-60 chars <span class="text-gray-500">({{ strlen($servicePage->meta_title ?? '') }})</span></span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="{{ strlen($servicePage->meta_description ?? '') >= 120 && strlen($servicePage->meta_description ?? '') <= 160 ? 'text-green-500' : 'text-red-500' }}">✓</span>
                    <span>Meta description 120-160 chars <span class="text-gray-500">({{ strlen($servicePage->meta_description ?? '') }})</span></span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="{{ !empty($servicePage->h1_title) && str_contains(strtolower($servicePage->h1_title ?? ''), strtolower($servicePage->city->name ?? '')) ? 'text-green-500' : 'text-red-500' }}">✓</span>
                    <span>H1 contains city name</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="{{ ($servicePage->word_count ?? 0) >= 1500 ? 'text-green-500' : (($servicePage->word_count ?? 0) >= 1000 ? 'text-yellow-500' : 'text-red-500') }}">✓</span>
                    <span>Content 1500+ words <span class="text-gray-500">({{ $servicePage->word_count ?? 0 }})</span></span>
                </li>
                <li class="flex items-start gap-2">
                    @php
                        $hasCanonical = filled($servicePage->canonical_url) || (filled($servicePage->schema_markup) && isset($servicePage->schema_markup['url']));
                        $canonicalUrl = $servicePage->canonical_url ?? ($servicePage->schema_markup['url'] ?? '');
                    @endphp
                    <span class="{{ $hasCanonical ? 'text-green-500' : 'text-red-500' }}">✓</span>
                    <span>Canonical URL set <span class="text-gray-500">{{ $canonicalUrl ? substr($canonicalUrl, 0, 30) . '...' : '' }}</span></span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="{{ filled($servicePage->phone_number) ? 'text-green-500' : 'text-red-500' }}">✓</span>
                    <span>Phone number present</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="{{ filled($servicePage->schema_markup) ? 'text-green-500' : 'text-red-500' }}">✓</span>
                    <span>Schema markup defined</span>
                </li>
                <li class="flex items-start gap-2">
                    @php
                        $internalLinks = substr_count($servicePage->content ?? '', 'href="/');
                    @endphp
                    <span class="{{ $internalLinks >= 3 ? 'text-green-500' : ($internalLinks >= 1 ? 'text-yellow-500' : 'text-red-500') }}">✓</span>
                    <span>Internal links (3+) <span class="text-gray-500">({{ $internalLinks }})</span></span>
                </li>
                <li class="flex items-start gap-2">
                    @php
                        $h2Count = substr_count(strtolower($servicePage->content ?? ''), '<h2');
                        $h3Count = substr_count(strtolower($servicePage->content ?? ''), '<h3');
                    @endphp
                    <span class="{{ $h2Count >= 3 && $h3Count >= 2 ? 'text-green-500' : 'text-yellow-500' }}">✓</span>
                    <span>Heading structure (H2+H3) <span class="text-gray-500">({{ $h2Count }}/{{ $h3Count }})</span></span>
                </li>
                <li class="flex items-start gap-2">
                    @php
                        preg_match_all('/<img[^>]+>/i', $servicePage->content ?? '', $imgMatches);
                        $imgCount = count($imgMatches[0] ?? []);
                    @endphp
                    <span class="{{ $imgCount >= 1 ? 'text-green-500' : 'text-red-500' }}">✓</span>
                    <span>Images present <span class="text-gray-500">({{ $imgCount }})</span></span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
