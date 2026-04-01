@extends('admin.layout')
@section('title', 'Service Page')
@section('page-title', 'Service Page')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h2 class="font-bold text-gray-800 mb-4">{{ $servicePage->h1_title }}</h2>
    <dl class="grid grid-cols-2 gap-3 text-sm mb-4">
        <div><dt class="text-gray-500">City</dt><dd>{{ $servicePage->city?->name }}</dd></div>
        <div><dt class="text-gray-500">Type</dt><dd class="capitalize">{{ $servicePage->service_type }}</dd></div>
        <div><dt class="text-gray-500">SEO Score</dt><dd>{{ $servicePage->seo_score }}/100</dd></div>
        <div><dt class="text-gray-500">Views</dt><dd>{{ number_format($servicePage->views) }}</dd></div>
        <div><dt class="text-gray-500">Calls Generated</dt><dd>{{ number_format($servicePage->calls_generated) }}</dd></div>
        <div><dt class="text-gray-500">Word Count</dt><dd>{{ number_format($servicePage->word_count) }}</dd></div>
    </dl>
    <div class="flex gap-2">
        <a href="{{ url($servicePage->slug) }}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">View Page</a>
        <a href="{{ route('admin.service-pages.edit', $servicePage) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Edit</a>
    </div>
</div>
@endsection
