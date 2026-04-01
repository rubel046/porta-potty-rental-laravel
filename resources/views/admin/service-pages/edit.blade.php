@extends('admin.layout')
@section('title', 'Edit Service Page')
@section('page-title', 'Edit Service Page')

@section('content')
<form method="POST" action="{{ route('admin.service-pages.update', $servicePage) }}" class="max-w-3xl space-y-6">
    @csrf @method('PUT')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="font-bold text-gray-800 border-b pb-2">Page Details</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">City *</label><select name="city_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">@foreach($cities as $city)<option value="{{ $city->id }}" {{ $servicePage->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>@endforeach</select></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Service Type *</label><select name="service_type" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"><option value="general" {{ $servicePage->service_type == 'general' ? 'selected' : '' }}>General</option><option value="construction" {{ $servicePage->service_type == 'construction' ? 'selected' : '' }}>Construction</option><option value="wedding" {{ $servicePage->service_type == 'wedding' ? 'selected' : '' }}>Wedding</option><option value="event" {{ $servicePage->service_type == 'event' ? 'selected' : '' }}>Event</option></select></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label><input type="text" name="slug" value="{{ old('slug', $servicePage->slug) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono"></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">H1 Title *</label><input type="text" name="h1_title" value="{{ old('h1_title', $servicePage->h1_title) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label><input type="text" name="meta_title" value="{{ old('meta_title', $servicePage->meta_title) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label><input type="text" name="phone_number" value="{{ old('phone_number', $servicePage->phone_number) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label><textarea name="meta_description" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('meta_description', $servicePage->meta_description) }}</textarea></div>
            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-1">Content (Markdown) *</label><textarea name="content" rows="10" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">{{ old('content', $servicePage->content) }}</textarea></div>
            <div class="flex items-center gap-2"><input type="checkbox" name="is_published" value="1" {{ $servicePage->is_published ? 'checked' : '' }} class="w-4 h-4"><label class="text-sm">Published</label></div>
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700">Save Changes</button>
        <a href="{{ route('admin.service-pages.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-300">Cancel</a>
    </div>
</form>
@endsection
