@extends('admin.layout')
@section('title', 'Blog Posts')
@section('page-title', 'Blog Posts')

@section('content')
<div class="mb-4 flex flex-wrap justify-between items-center gap-4">
    <form method="GET" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
        <select name="is_published" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Published</option>
            <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Draft</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    </form>
    <a href="{{ route('admin.blog-posts.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">+ Add Post</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Title</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">City</th>
                    <th class="px-6 py-3">Views</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($posts as $post)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium">{{ Str::limit($post->title, 40) }}</td>
                        <td class="px-6 py-3">
                            @if($post->category)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">{{ $post->category->name }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-gray-500">{{ $post->city?->name ?? '—' }}</td>
                        <td class="px-6 py-3">
                            <span class="@if($post->views > 100) text-green-600 font-medium @else text-gray-500 @endif">
                                {{ number_format($post->views) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            @if($post->is_published)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Published</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 flex gap-2">
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-gray-400 hover:text-gray-600 text-xs">View</a>
                            <a href="{{ route('admin.blog-posts.edit', $post) }}" class="text-green-600 hover:text-green-800 text-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-800 text-xs">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No posts found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center">
        {{ $posts->links() }}
        <div class="text-sm text-gray-500">{{ $posts->total() }} posts</div>
    </div>
</div>
@endsection
