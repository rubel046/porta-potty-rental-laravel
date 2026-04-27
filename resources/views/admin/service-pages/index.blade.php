@extends('admin.layout')
@section('title', 'Service Pages')
@section('page-title', 'Service Pages Management')

@section('content')
<div class="mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">All Service Pages</h2>
        <p class="text-sm text-gray-500">Service pages are auto-generated from cities. Create a city to add pages.</p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search Slug</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search slug..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
        </div>
        <div class="w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1">City</label>
            <select name="city_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white hover:bg-gray-50">
                <option value="">All Cities</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
            <select name="service_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white hover:bg-gray-50">
                <option value="">All Types</option>
                <option value="general" {{ request('service_type') == 'general' ? 'selected' : '' }}>General</option>
                <option value="construction" {{ request('service_type') == 'construction' ? 'selected' : '' }}>Construction</option>
                <option value="wedding" {{ request('service_type') == 'wedding' ? 'selected' : '' }}>Wedding</option>
                <option value="event" {{ request('service_type') == 'event' ? 'selected' : '' }}>Event</option>
            </select>
        </div>
        <div class="w-40" x-data="{ open: false, selected: '{{ request('published') }}' }">
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <div class="relative">
                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white hover:bg-gray-50">
                    <span x-text="selected === '1' ? 'Published' : (selected === '0' ? 'Draft' : 'All Status')"></span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition.opacity style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <button type="button" @click="selected = ''; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '' ? 'bg-green-50 text-green-700 font-medium' : ''">All Status</button>
                    <button type="button" @click="selected = '1'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '1' ? 'bg-green-50 text-green-700 font-medium' : ''">Published</button>
                    <button type="button" @click="selected = '0'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '0' ? 'bg-green-50 text-green-700 font-medium' : ''">Draft</button>
                </div>
                <input type="hidden" name="published" :value="selected">
            </div>
        </div>
        <div class="flex gap-2 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'city_id', 'service_type', 'published', 'from_date', 'to_date']))
                <a href="{{ route('admin.service-pages.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition">
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 text-left text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-medium">Slug</th>
                    <th class="px-6 py-4 font-medium">City</th>
                    <th class="px-6 py-4 font-medium">Type</th>
                    <th class="px-6 py-4 font-medium">Views</th>
                    <th class="px-6 py-4 font-medium">SEO Score</th>
                    <th class="px-6 py-4 font-medium">Published</th>
                    <th class="px-6 py-4 font-medium">Generation</th>
                    <th class="px-6 py-4 font-medium">Created</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($servicePages as $page)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-mono text-xs text-gray-900">{{ Str::limit($page->slug, 35) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">{{ $page->city?->name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 capitalize">{{ $page->service_type }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="@if($page->views > 100) text-green-600 font-medium @else text-gray-500 @endif">
                                {{ number_format($page->views) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($page->seo_score >= 80)
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-green-500 rounded-full" style="width: {{ $page->seo_score }}%"></div>
                                    </div>
                                    <span class="text-green-600 font-medium">{{ $page->seo_score }}</span>
                                </div>
                            @elseif($page->seo_score >= 50)
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-500 rounded-full" style="width: {{ $page->seo_score }}%"></div>
                                    </div>
                                    <span class="text-yellow-600 font-medium">{{ $page->seo_score }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-red-500 rounded-full" style="width: {{ $page->seo_score }}%"></div>
                                    </div>
                                    <span class="text-red-600 font-medium">{{ $page->seo_score }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($page->is_published)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($page->generation_status === 'success')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Generated
                                </span>
                            @elseif($page->generation_status === 'processing')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                                    Processing
                                </span>
                            @elseif($page->generation_status === 'failed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700" title="{{ $page->generation_error }}">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                    Failed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-500 text-xs">{{ $page->created_at?->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.service-pages.show', $page) }}" class="p-1.5 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition" title="SEO Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </a>
                                <a href="{{ route('admin.service-pages.edit', $page) }}" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <a href="{{ url($page->slug) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-gray-500 font-medium">No service pages found</p>
                                <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($servicePages->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                {{ $servicePages->firstItem() }} - {{ $servicePages->lastItem() }} of {{ $servicePages->total() }}
            </div>
            <nav class="flex items-center gap-1">
                @if($servicePages->currentPage() > 1)
                    <a href="{{ $servicePages->previousPageUrl() }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Previous</a>
                @endif
                
                @foreach($servicePages->getUrlRange(max(1, $servicePages->currentPage() - 2), min($servicePages->lastPage(), $servicePages->currentPage() + 2)) as $page => $url)
                    @if($page == $servicePages->currentPage())
                        <span class="px-3 py-1.5 text-sm rounded-lg bg-green-600 text-white font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">{{ $page }}</a>
                    @endif
                @endforeach

                @if($servicePages->currentPage() < $servicePages->lastPage())
                    <a href="{{ $servicePages->nextPageUrl() }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Next</a>
                @endif
            </nav>
        </div>
    @endif
</div>
@endsection
