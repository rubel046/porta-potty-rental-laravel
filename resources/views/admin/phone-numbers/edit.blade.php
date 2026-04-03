@extends('admin.layout')
@section('title', 'Edit Phone Number')
@section('page-title', 'Edit Phone Number')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.phone-numbers.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to Phone Numbers
    </a>
</div>

<form method="POST" action="{{ route('admin.phone-numbers.update', $phoneNumber) }}" class="max-w-2xl space-y-6">
    @csrf
    @method('PUT')
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 border-b pb-3 mb-4">Phone Number Details</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <div class="font-mono text-sm bg-gray-100 px-4 py-2.5 rounded-lg text-gray-600">
                    {{ $phoneNumber->number }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Friendly Name</label>
                <input type="text" name="friendly_name" value="{{ old('friendly_name', $phoneNumber->friendly_name) }}" 
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            
            <div x-data="{ open: false, filter: '{{ $phoneNumber->city_id }}', selectedId: '{{ $phoneNumber->city_id }}', selectedName: '{{ $phoneNumber->city?->name ?? 'No city assigned' }}' }">
                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                <div class="relative">
                    <button type="button" @click="open = !open" @click.outside="open = false" 
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-left flex justify-between items-center bg-white hover:bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <span x-text="selectedName"></span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity style="display: none;" class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                        <div class="p-2 border-b border-gray-100">
                            <input type="text" x-model="filter" placeholder="Search cities..." 
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                @keydown.escape="open = false">
                        </div>
                        <div class="overflow-y-auto max-h-64">
                            <button type="button" @click="selectedId = ''; selectedName = 'No city assigned'; open = false" 
                                class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50"
                                :class="selectedId === '' ? 'bg-green-50 text-green-700 font-medium' : ''">
                                No city assigned
                            </button>
                            @foreach($cities as $city)
                                <button type="button" 
                                    @click="selectedId = '{{ $city->id }}'; selectedName = '{{ $city->name }}, {{ $city->state->code }}'; open = false" 
                                    class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50"
                                    x-show="'{{ strtolower($city->name) }}'.includes(filter.toLowerCase())"
                                    :class="selectedId == '{{ $city->id }}' ? 'bg-green-50 text-green-700 font-medium' : ''">
                                    {{ $city->name }}, {{ $city->state->code }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="city_id" :value="selectedId">
                </div>
            </div>
            
            <div>
                <label class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ $phoneNumber->is_active ? 'checked' : '' }}
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </label>
            </div>
        </div>
    </div>
    
    <div class="flex gap-3">
        <button type="submit" class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Save Changes
        </button>
        <a href="{{ route('admin.phone-numbers.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
            Cancel
        </a>
    </div>
</form>
@endsection