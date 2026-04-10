@extends('admin.layout')
@section('title', 'Edit City')
@section('page-title', "Edit: {$city->name}")

@section('content')
<form method="POST" action="{{ route('admin.global.cities.update', $city) }}" class="max-w-2xl space-y-6">
    @csrf @method('PUT')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="font-bold text-gray-800 border-b pb-2">City Information</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                <select name="state_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    @foreach($states as $state)
                        <option value="{{ $state->id }}" {{ $city->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                <input type="text" name="name" value="{{ old('name', $city->name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $city->slug) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Population</label>
                <input type="number" name="population" value="{{ old('population', $city->population) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $city->latitude) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $city->longitude) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Zip Codes</label>
                <input type="text" name="zip_codes" value="{{ old('zip_codes', is_array($city->zip_codes) ? implode(', ', $city->zip_codes) : $city->zip_codes) }}" placeholder="e.g. 77001, 77002" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nearby Cities</label>
                <input type="text" name="nearby_cities" value="{{ old('nearby_cities', is_array($city->nearby_cities) ? implode(', ', $city->nearby_cities) : '') }}" placeholder="e.g. Houston, Pasadena" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <p class="text-xs text-gray-500 mt-1">Comma separated city names</p>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700">Save Changes</button>
        <a href="{{ route('admin.global.cities.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-300">Cancel</a>
    </div>
</form>
@endsection