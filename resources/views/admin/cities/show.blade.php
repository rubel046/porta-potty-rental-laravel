@extends('admin.layout')
@section('title', $city->name)
@section('page-title', $city->name)

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4">City Details</h2>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-500">State</dt><dd class="font-medium">{{ $city->state?->name }}</dd></div>
                <div><dt class="text-gray-500">County</dt><dd>{{ $city->county ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Population</dt><dd>{{ $city->population ? number_format($city->population) : '—' }}</dd></div>
                <div><dt class="text-gray-500">Area Codes</dt><dd>{{ $city->area_codes ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Status</dt>
                    <dd>@if($city->is_active)<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>@else<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>@endif</dd>
                </div>
            </dl>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('admin.cities.edit', $city) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-800">Service Pages</h2>
                <form method="POST" action="{{ route('admin.cities.generate-pages', $city) }}">
                    @csrf
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">Generate Content</button>
                </form>
            </div>
            <table class="w-full text-sm">
                <thead><tr class="text-left text-xs text-gray-500 uppercase"><th class="pb-2">Type</th><th class="pb-2">Slug</th><th class="pb-2">Views</th><th class="pb-2">Calls</th></tr></thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($city->servicePages as $page)
                        <tr><td class="py-2">{{ $page->service_type }}</td><td class="py-2 text-xs font-mono">{{ Str::limit($page->slug, 30) }}</td><td class="py-2">{{ number_format($page->views) }}</td><td class="py-2">{{ number_format($page->calls_generated) }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-400">No pages generated yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4">Phone Numbers</h2>
            @forelse($city->phoneNumbers as $number)
                <div class="p-3 bg-gray-50 rounded-lg mb-2">
                    <div class="font-mono text-sm">{{ $number->number }}</div>
                    <div class="text-xs text-gray-500">{{ $number->total_calls }} calls</div>
                </div>
            @empty
                <p class="text-gray-400 text-sm">No phone numbers assigned</p>
            @endforelse
            <a href="{{ route('admin.phone-numbers.index', ['city_id' => $city->id]) }}" class="text-blue-600 text-sm hover:text-blue-700">Manage Numbers →</a>
        </div>
    </div>
</div>
@endsection
