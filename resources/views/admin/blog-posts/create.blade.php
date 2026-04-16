@extends('admin.layout')
@section('title', 'Create Blog Post')
@section('page-title', 'Create Blog Post')

@section('content')
<form method="POST" action="{{ route('admin.blog-posts.store') }}" class="max-w-3xl space-y-6">
    @csrf
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="font-bold text-gray-800 border-b pb-2">Post Details</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="blog_category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="">None</option>
                    @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                <select name="city_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="">None</option>
                    @foreach($cities as $city)<option value="{{ $city->id }}">{{ $city->name }}</option>@endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
                <textarea name="excerpt" rows="4" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('excerpt') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
                <input type="text" name="featured_image" value="{{ old('featured_image') }}" placeholder="e.g., pottydirect/images/porta-potty-blog-1.jpg" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Content (Markdown) *</label>
                <textarea name="content" rows="12" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">{{ old('content') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                <textarea name="meta_description" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('meta_description') }}</textarea>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2"><input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="w-4 h-4"><label class="text-sm">Published</label></div>
                <div class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4"><label class="text-sm">Featured</label></div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700">Create Post</button>
        <a href="{{ route('admin.blog-posts.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-300">Cancel</a>
    </div>
</form>
@endsection
