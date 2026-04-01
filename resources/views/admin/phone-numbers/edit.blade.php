@extends('admin.layout')
@section('title', 'Edit Phone Number')
@section('page-title', 'Edit Phone Number')

@section('content')
<form method="POST" action="{{ route('admin.phone-numbers.update', $phoneNumber) }}" class="max-w-2xl space-y-6">
    @csrf @method('PUT')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="font-bold text-gray-800 border-b pb-2">Phone Number Details</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Number</label><div class="font-mono text-sm bg-gray-100 px-3 py-2 rounded-lg">{{ $phoneNumber->number }}</div></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">City</label><select name="city_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"><option value="">None</option>@foreach($cities as $city)<option value="{{ $city->id }}" {{ $phoneNumber->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>@endforeach</select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Friendly Name</label><input type="text" name="friendly_name" value="{{ old('friendly_name', $phoneNumber->friendly_name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Status</label><select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"><option value="active" {{ $phoneNumber->status == 'active' ? 'selected' : '' }}>Active</option><option value="inactive" {{ $phoneNumber->status == 'inactive' ? 'selected' : '' }}>Inactive</option><option value="released" {{ $phoneNumber->status == 'released' ? 'selected' : '' }}>Released</option></select></div>
            <div class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ $phoneNumber->is_active ? 'checked' : '' }} class="w-4 h-4"><label class="text-sm">Active</label></div>
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700">Save Changes</button>
        <a href="{{ route('admin.phone-numbers.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-300">Cancel</a>
    </div>
</form>
@endsection
