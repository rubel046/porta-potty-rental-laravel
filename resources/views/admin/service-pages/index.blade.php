@extends('admin.layout')
@section('title', 'Service Pages')
@section('page-title', 'Service Pages')

@section('content')
<div class="mb-4 flex flex-wrap justify-between items-center gap-4">
    <form method="GET" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search slug..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
        <select name="city_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All Cities</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
            @endforeach
        </select>
        <select name="service_type" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All Types</option>
            <option value="general" {{ request('service_type') == 'general' ? 'selected' : '' }}>General</option>
            <option value="construction" {{ request('service_type') == 'construction' ? 'selected' : '' }}>Construction</option>
            <option value="wedding" {{ request('service_type') == 'wedding' ? 'selected' : '' }}>Wedding</option>
            <option value="event" {{ request('service_type') == 'event' ? 'selected' : '' }}>Event</option>
        </select>
        <select name="published" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="1" {{ request('published') == '1' ? 'selected' : '' }}>Published</option>
            <option value="0" {{ request('published') == '0' ? 'selected' : '' }}>Draft</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    </form>
</div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Slug</th>
                    <th class="px-6 py-3">City</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">SEO Score</th>
                    <th class="px-6 py-3">Published</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($servicePages as $page)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-mono text-xs">{{ Str::limit($page->slug, 30) }}</td>
                        <td class="px-6 py-3">{{ $page->city?->name ?? '—' }}</td>
                        <td class="px-6 py-3"><span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $page->service_type }}</span></td>
                        <td class="px-6 py-3">
                            @if($page->seo_score >= 80)
                                <span class="text-green-600 font-medium">{{ $page->seo_score }}/100</span>
                            @elseif($page->seo_score >= 50)
                                <span class="text-yellow-600 font-medium">{{ $page->seo_score }}/100</span>
                            @else
                                <span class="text-red-600 font-medium">{{ $page->seo_score }}/100</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">@if($page->is_published)<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Published</span>@else<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Draft</span>@endif</td>
                        <td class="px-6 py-3 flex gap-2">
                            <a href="{{ url($page->slug) }}" target="_blank" class="text-gray-400 hover:text-gray-600 text-xs">View</a>
                            <a href="{{ route('admin.service-pages.edit', $page) }}" class="text-green-600 hover:text-green-800 text-xs">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No service pages found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center">
        {{ $servicePages->links() }}
        <div class="text-sm text-gray-500">{{ $servicePages->total() }} pages</div>
    </div>
</div>
@endsection
