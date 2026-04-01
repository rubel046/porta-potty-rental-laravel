@extends('layouts.admin')
@section('page_title', isset($post) ? "Edit: {$post->title}" : 'New Blog Post')

@section('content')

    <form method="POST"
          action="{{ isset($post) ? route('admin.blog-posts.update', $post) : route('admin.blog-posts.store') }}"
          class="max-w-5xl">
        @csrf
        @if(isset($post)) @method('PUT') @endif

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <div class="mb-6">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-input text-lg font-bold"
                               value="{{ old('title', $post->title ?? '') }}"
                               placeholder="How Much Does Porta Potty Rental Cost in 2026?" required>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-input font-mono text-sm"
                               value="{{ old('slug', $post->slug ?? '') }}"
                               placeholder="auto-generated-from-title">
                        <p class="text-xs text-gray-400 mt-1">Leave blank to auto-generate</p>
                    </div>

                    <div>
                        <label class="form-label">Content * (Markdown supported)</label>
                        <textarea name="content" rows="25"
                                  class="form-input font-mono text-sm leading-relaxed"
                                  placeholder="Write your blog post content here... Use Markdown formatting."
                                  required>{{ old('content', $post->content ?? '') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">
                            Supports Markdown: **bold**, *italic*, ## headings, - lists, [links](url)
                        </p>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="card p-6">
                    <h3 class="font-bold text-gray-700 mb-4">🔍 SEO Settings</h3>

                    <div class="mb-4">
                        <label class="form-label">Meta Title (max 60 chars)</label>
                        <input type="text" name="meta_title" class="form-input" maxlength="60"
                               value="{{ old('meta_title', $post->meta_title ?? '') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Meta Description (max 160 chars)</label>
                        <textarea name="meta_description" rows="2" class="form-input"
                                  maxlength="160">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Focus Keyword</label>
                        <input type="text" name="focus_keyword" class="form-input"
                               value="{{ old('focus_keyword', $post->focus_keyword ?? '') }}"
                               placeholder="e.g., porta potty rental cost">
                    </div>

                    <div>
                        <label class="form-label">Excerpt</label>
                        <textarea name="excerpt" rows="2" class="form-input"
                                  placeholder="Short summary for listings...">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Publish --}}
                <div class="card p-6">
                    <h3 class="font-bold text-gray-700 mb-4">📋 Publish</h3>

                    <div class="space-y-3">
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1"
                                   {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300">
                            <span class="text-sm">Published</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1"
                                   {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300">
                            <span class="text-sm">Featured</span>
                        </label>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn-primary w-full">
                            {{ isset($post) ? '💾 Update Post' : '📝 Create Post' }}
                        </button>
                    </div>

                    <div class="mt-3 text-center">
                        <a href="{{ route('admin.blog-posts.index') }}" class="text-sm text-gray-500">Cancel</a>
                    </div>
                </div>

                {{-- Category --}}
                <div class="card p-6">
                    <h3 class="font-bold text-gray-700 mb-4">📁 Category</h3>
                    <select name="blog_category_id" class="form-input">
                        <option value="">No Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('blog_category_id', $post->blog_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- City --}}
                <div class="card p-6">
                    <h3 class="font-bold text-gray-700 mb-4">🏙️ Related City</h3>
                    <select name="city_id" class="form-input">
                        <option value="">None (General)</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ old('city_id', $post->city_id ?? '') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}, {{ $city->state->code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Stats (edit only) --}}
                @if(isset($post))
                    <div class="card p-6">
                        <h3 class="font-bold text-gray-700 mb-4">📊 Stats</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Word Count</span>
                                <span class="font-medium">{{ number_format($post->word_count) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Reading Time</span>
                                <span class="font-medium">{{ $post->reading_time }} min</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Views</span>
                                <span class="font-medium">{{ number_format($post->views) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Created</span>
                                <span class="font-medium">{{ $post->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>

@endsection
