@extends('admin.layout')
@section('title', 'Phone Numbers')
@section('page-title', 'Phone Numbers Management')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">All Phone Numbers</h2>
        <p class="text-sm text-gray-500">Manage your call tracking phone numbers</p>
    </div>
    <a href="{{ route('admin.phone-numbers.create') }}" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Number
    </a>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search number..." 
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
        <div class="w-40" x-data="{ open: false, selected: '{{ request('status') }}' }">
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
                <input type="hidden" name="status" :value="selected">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'city_id', 'status']))
                <a href="{{ route('admin.phone-numbers.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm hover:bg-gray-50 transition">
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
                    <th class="px-6 py-4 font-medium">Number</th>
                    <th class="px-6 py-4 font-medium">City</th>
                    <th class="px-6 py-4 font-medium">Area Code</th>
                    <th class="px-6 py-4 font-medium">Total Calls</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($phoneNumbers as $number)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-mono font-medium text-gray-900">{{ $number->number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-900">{{ $number->city?->name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $number->area_code ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="@if($number->total_calls > 100) text-green-600 font-medium @elseif($number->total_calls > 0) text-gray-600 @else text-gray-400 @endif">
                                {{ number_format($number->total_calls) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($number->is_active)
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
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.phone-numbers.show', $number) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.phone-numbers.edit', $number) }}" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.phone-numbers.destroy', $number) }}" onsubmit="return confirm('Are you sure you want to delete this number?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <p class="text-gray-500 font-medium">No phone numbers found</p>
                                <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($phoneNumbers->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                {{ $phoneNumbers->firstItem() }} - {{ $phoneNumbers->lastItem() }} of {{ $phoneNumbers->total() }}
            </div>
            <nav class="flex items-center gap-1">
                @if($phoneNumbers->currentPage() > 1)
                    <a href="{{ $phoneNumbers->previousPageUrl() }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Previous</a>
                @endif
                
                @foreach($phoneNumbers->getUrlRange(max(1, $phoneNumbers->currentPage() - 2), min($phoneNumbers->lastPage(), $phoneNumbers->currentPage() + 2)) as $page => $url)
                    @if($page == $phoneNumbers->currentPage())
                        <span class="px-3 py-1.5 text-sm rounded-lg bg-green-600 text-white font-medium">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">{{ $page }}</a>
                    @endif
                @endforeach

                @if($phoneNumbers->currentPage() < $phoneNumbers->lastPage())
                    <a href="{{ $phoneNumbers->nextPageUrl() }}" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Next</a>
                @endif
            </nav>
        </div>
    @endif
</div>
@endsection