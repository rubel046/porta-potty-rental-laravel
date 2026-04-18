@extends('admin.layout')
@section('title', 'Cities')
@section('page-title', 'Cities Management')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">All Cities</h2>
        <p class="text-sm text-gray-500">Manage your city listings and service pages</p>
    </div>
    @if(!$domain)
        <a href="{{ route('admin.cities.create') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add City
        </a>
    @endif
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cities..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
        </div>
        <div class="w-40" x-data="{ open: false, selected: '{{ request('is_active') }}' }">
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <div class="relative">
                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white hover:bg-gray-50">
                    <span x-text="selected === '1' ? 'Active' : (selected === '0' ? 'Inactive' : 'All Status')"></span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition.opacity style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <button type="button" @click="selected = ''; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '' ? 'bg-green-50 text-green-700 font-medium' : ''">All Status</button>
                    <button type="button" @click="selected = '1'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '1' ? 'bg-green-50 text-green-700 font-medium' : ''">Active</button>
                    <button type="button" @click="selected = '0'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '0' ? 'bg-green-50 text-green-700 font-medium' : ''">Inactive</button>
                </div>
                <input type="hidden" name="is_active" :value="selected">
            </div>
        </div>
        <div class="w-44" x-data="{ open: false, selected: '{{ request('service_pages_count') }}' }">
            <label class="block text-xs font-medium text-gray-500 mb-1">Service Pages</label>
            <div class="relative">
                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white hover:bg-gray-50">
                    <span x-text="selected === 'has' ? 'Has Pages' : (selected === '0' ? 'No Pages' : 'All')"></span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition.opacity style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <button type="button" @click="selected = ''; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '' ? 'bg-green-50 text-green-700 font-medium' : ''">All</button>
                    <button type="button" @click="selected = 'has'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === 'has' ? 'bg-green-50 text-green-700 font-medium' : ''">Has Pages</button>
                    <button type="button" @click="selected = '0'; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '0' ? 'bg-green-50 text-green-700 font-medium' : ''">No Pages</button>
                </div>
                <input type="hidden" name="service_pages_count" :value="selected">
            </div>
        </div>
        <div class="w-48" x-data="{ open: false, search: '', selected: '{{ request('state_id') }}', states: [
            @foreach($states as $state){id: '{{ $state->id }}', name: '{{ $state->name }}'},@endforeach
        ]}" @click.outside="open = false">
            <label class="block text-xs font-medium text-gray-500 mb-1">State</label>
            <div class="relative">
                <button type="button" @click="open = !open" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white hover:bg-gray-50">
                    <span x-text="selected ? states.find(s => s.id == selected)?.name || 'All States' : 'All States'"></span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition.opacity style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-64 overflow-hidden">
                    <div class="p-2 border-b border-gray-100">
                        <input type="text" x-model="search" placeholder="Search..." class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div class="overflow-y-auto max-h-44">
                        <button type="button" @click="selected = ''; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === '' ? 'bg-green-50 text-green-700 font-medium' : ''">All States</button>
                        <template x-for="state in states.filter(s => s.name.toLowerCase().includes(search.toLowerCase()))" :key="state.id">
                            <button type="button" @click="selected = state.id; open = false" class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50" :class="selected === state.id ? 'bg-green-50 text-green-700 font-medium' : ''" x-text="state.name"></button>
                        </template>
                    </div>
                </div>
                <input type="hidden" name="state_id" :value="selected">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'state_id', 'is_active']))
                <a href="{{ route('admin.cities.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition">
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
                    <th class="px-6 py-4 font-medium">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    </th>
                    <th class="px-6 py-4 font-medium">City</th>
                    <th class="px-6 py-4 font-medium">State</th>
                    <th class="px-6 py-4 font-medium">Zip Codes</th>
                    <th class="px-6 py-4 font-medium">Service Pages</th>
                    <th class="px-6 py-4 font-medium">Content</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($cities as $city)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="city_ids[]" value="{{ $city->id }}" class="city-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $city->name }}</div>
                            <div class="text-xs text-gray-400">{{ $city->slug }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-900">{{ $city->state?->code ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php $zips = is_array($city->zip_codes) ? array_slice($city->zip_codes, 0, 3) : []; @endphp
                            @if(count($zips) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($zips as $zip)
                                        <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">{{ $zip }}</span>
                                    @endforeach
                                    @if(count($city->zip_codes ?? []) > 3)
                                        <span class="px-1.5 py-0.5 text-gray-400 text-xs">+{{ count($city->zip_codes) - 3 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-900">{{ $city->service_pages_count ?? 0 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($domain && isset($city->content_generated))
                                <form method="POST" action="{{ route('admin.cities.toggle-content-generated', $city) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-left" @if($city->content_generated) title="Mark as not generated" @else title="Mark as generated" @endif>
                                        @if($city->content_generated)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                                Generated
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                                Pending
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($domain && isset($city->domain_status))
                                @if($city->domain_status)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                        Inactive
                                    </span>
                                @endif
                            @else
                                @if($city->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                        Inactive
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form method="POST" action="{{ route('admin.cities.toggle-status', $city) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg transition {{ ($domain && isset($city->domain_status) && $city->domain_status) || (!isset($city->domain_status) && $city->is_active) ? 'text-gray-400 hover:text-yellow-600 hover:bg-yellow-50' : 'text-gray-400 hover:text-green-600 hover:bg-green-50' }}" title="{{ ($domain && isset($city->domain_status) && $city->domain_status) || (!isset($city->domain_status) && $city->is_active) ? 'Deactivate' : 'Activate' }}">
                                        @if(($domain && isset($city->domain_status) && $city->domain_status) || (!isset($city->domain_status) && $city->is_active))
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </button>
                                </form>
                                <a href="{{ route('admin.cities.show', $city) }}" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <p class="text-gray-500 font-medium">No cities found</p>
                                <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($cities->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                {{ $cities->firstItem() }} - {{ $cities->lastItem() }} of {{ $cities->total() }}
            </div>
            <nav class="flex items-center gap-1">
                @if($cities->currentPage() > 1)
                    <a href="{{ $cities->previousPageUrl() }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Previous</a>
                @endif
                
                @foreach($cities->getUrlRange(max(1, $cities->currentPage() - 2), min($cities->lastPage(), $cities->currentPage() + 2)) as $page => $url)
                    @if($page == $cities->currentPage())
                        <span class="px-3 py-1.5 text-sm rounded-lg bg-green-600 text-white font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">{{ $page }}</a>
                    @endif
                @endforeach

                @if($cities->currentPage() < $cities->lastPage())
                    <a href="{{ $cities->nextPageUrl() }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Next</a>
                @endif
            </nav>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.getElementById('select-all')?.addEventListener('change', function() {
        document.querySelectorAll('.city-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
@endpush
@endsection