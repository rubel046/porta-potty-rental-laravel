@extends('admin.layout')
@section('title', 'Create Phone Number')
@section('page-title', 'Create Phone Number')

@section('content')
<form method="POST" action="{{ route('admin.phone-numbers.store') }}" class="max-w-2xl space-y-6">
    @csrf
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="font-bold text-gray-800 border-b pb-2">Phone Number Details</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                <input type="text" name="number" value="{{ old('number') }}" required placeholder="+17135551234" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Area Code *</label>
                <input type="text" name="area_code" value="{{ old('area_code') }}" required placeholder="713" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                <select name="city_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="">None</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Friendly Name</label>
                <input type="text" name="friendly_name" value="{{ old('friendly_name') }}" placeholder="(713) 555-1234" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700">Add Number</button>
        <a href="{{ route('admin.phone-numbers.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-300">Cancel</a>
    </div>
</form>
@endsection
