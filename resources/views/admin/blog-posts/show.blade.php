@extends('admin.layout')
@section('title', $blogPost->title)
@section('page-title', $blogPost->title)

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <dl class="grid grid-cols-2 gap-3 text-sm mb-4">
        <div><dt class="text-gray-500">Category</dt><dd>{{ $blogPost->category?->name ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">City</dt><dd>{{ $blogPost->city?->name ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Status</dt><dd>@if($blogPost->is_published)<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Published</span>@else<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Draft</span>@endif</dd></div>
        <div><dt class="text-gray-500">Views</dt><dd>{{ number_format($blogPost->views) }}</dd></div>
        <div><dt class="text-gray-500">Word Count</dt><dd>{{ number_format($blogPost->word_count) }}</dd></div>
        <div><dt class="text-gray-500">Reading Time</dt><dd>{{ $blogPost->reading_time }} min</dd></div>
    </dl>
    <div class="flex gap-2">
        <a href="{{ route('blog.show', $blogPost->slug) }}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">View Post</a>
        <a href="{{ route('admin.blog-posts.edit', $blogPost) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Edit</a>
    </div>
</div>
@endsection
