@extends('layouts.admin')
@section('page_title', "Edit: {$state->name} — State Landing Page")

@section('content')
<div class="flex-1 p-6">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('admin.states.index') }}" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('admin.states.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">States</a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-900 font-semibold">{{ $state->name }}</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Edit State Landing Page</h1>
                <p class="text-sm text-gray-500 mt-1">Manage SEO content and AI generation for {{ $state->name }}, {{ $state->code }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/porta-potty-rental-' . $state->slug) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    View Page
                </a>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.states.update', $state) }}" class="max-w-6xl">
        @csrf
        @method('PUT')

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Content Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Page Content
                        </h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">H1 Title</label>
                            <input type="text" name="h1_title" 
                                   value="{{ old('h1_title', $state->h1_title) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Porta Potty Rental in {{ $state->name }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Content (Markdown)</label>
                            <textarea name="content" id="content-editor" rows="20"
                                      class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                      placeholder="Enter your SEO content here...">{{ old('content', $state->content) }}</textarea>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-gray-400">Markdown format supported</p>
                                <p class="text-xs font-medium" id="word-count-display">
                                    <span id="word-count">{{ $state->word_count ?? 0 }}</span> words
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            SEO Settings
                        </h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <div class="relative">
                                <input type="text" name="meta_title" id="meta_title"
                                       value="{{ old('meta_title', $state->meta_title) }}"
                                       maxlength="60"
                                       class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm pr-16 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Porta Potty Rental {{ $state->name }} | Same-Day Delivery">
                                <span id="meta_title_count" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                    {{ strlen($state->meta_title ?? '') }}/60
                                </span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Recommended: 50-60 characters</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <div class="relative">
                                <textarea name="meta_description" id="meta_description" rows="3"
                                          maxlength="160"
                                          class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm resize-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                          placeholder="Find affordable porta potty rental in {{ $state->name }}...">{{ old('meta_description', $state->meta_description) }}</textarea>
                                <span id="meta_desc_count" class="absolute right-3 bottom-3 text-xs text-gray-400">
                                    {{ strlen($state->meta_description ?? '') }}/160
                                </span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Recommended: 120-160 characters</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Save Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Publish
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $state->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                        
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                {{-- AI Generation Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" 
                     x-data="{
                        status: 'idle',
                        progress: 0,
                        startedAt: null,
                        error: null,
                        checkProgress() {
                            fetch('{{ route('admin.states.generation-progress', $state) }}')
                                .then(r => r.json())
                                .then(data => {
                                    this.status = data.status;
                                    this.progress = data.progress;
                                    this.startedAt = data.started_at;
                                    this.error = data.error;
                                    if (data.status === 'processing') {
                                        setTimeout(() => this.checkProgress(), 2000);
                                    }
                                });
                        }
                     }" x-init="checkProgress()">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            AI Content Generator
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">
                            Generate SEO-optimized content using AI. This will create a comprehensive landing page for {{ $state->name }}.
                        </p>

                        {{-- Idle State --}}
                        <template x-if="status === 'idle'">
                            <form method="POST" action="{{ route('admin.states.generate-content', $state) }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-4 py-3 rounded-lg text-sm font-medium hover:from-purple-700 hover:to-indigo-700 transition flex items-center justify-center gap-2 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Generate with AI
                                </button>
                            </form>
                        </template>

                        {{-- Processing State --}}
                        <template x-if="status === 'processing'">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Generating...</span>
                                    <span class="text-sm font-medium text-purple-600" x-text="progress + '%'"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full transition-all duration-500"
                                         :style="'width: ' + progress + '%'"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Started: <span x-text="startedAt ? new Date(startedAt).toLocaleTimeString() : '...'"></span></p>
                            </div>
                        </template>

                        {{-- Completed State --}}
                        <template x-if="status === 'completed'">
                            <div class="text-center py-4">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <p class="font-medium text-green-700 mb-2">Content Generated!</p>
                                <button type="button" @click="status = 'idle'" 
                                        class="text-sm text-gray-500 hover:text-gray-700 underline">
                                    Generate again
                                </button>
                            </div>
                        </template>

                        {{-- Failed State --}}
                        <template x-if="status === 'failed'">
                            <div class="text-center py-4">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <p class="font-medium text-red-700 mb-1">Generation Failed</p>
                                <p class="text-xs text-red-600 mb-2" x-text="error"></p>
                                <button type="button" @click="status = 'idle'" 
                                        class="text-sm text-gray-500 hover:text-gray-700 underline">
                                    Try again
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Stats Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Page Stats
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">URL</span>
                                <span class="text-xs font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">/porta-potty-rental-{{ $state->slug }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Cities</span>
                                <span class="text-sm font-medium text-gray-900">{{ $state->cities_count }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Active Cities</span>
                                <span class="text-sm font-medium text-green-600">{{ $state->active_cities_count }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Words</span>
                                <span class="text-sm font-medium text-gray-900">{{ $state->word_count ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">SEO Score</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ ($state->seo_score ?? 0) >= 70 ? 'bg-green-100 text-green-800' : (($state->seo_score ?? 0) >= 40 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $state->seo_score ? round($state->seo_score) . '%' : 'N/A' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Status</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $state->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $state->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Word count for content
        const textarea = document.getElementById('content-editor');
        const wordCountEl = document.getElementById('word-count');
        const wordCountDisplay = document.getElementById('word-count-display');

        function countWords(str) {
            if (!str || str.trim() === '') return 0;
            return str.trim().split(/\s+/).length;
        }

        function updateWordCount() {
            const count = countWords(textarea.value);
            wordCountEl.textContent = count.toLocaleString();
            wordCountDisplay.className = count >= 1500 
                ? 'text-xs font-medium text-green-600' 
                : (count >= 1000 
                    ? 'text-xs font-medium text-yellow-600' 
                    : 'text-xs font-medium text-red-500');
        }

        textarea.addEventListener('input', updateWordCount);

        // Character count for meta title
        const metaTitle = document.getElementById('meta_title');
        const metaTitleCount = document.getElementById('meta_title_count');
        metaTitle.addEventListener('input', function() {
            metaTitleCount.textContent = this.value.length + '/60';
        });

        // Character count for meta description
        const metaDesc = document.getElementById('meta_description');
        const metaDescCount = document.getElementById('meta_desc_count');
        metaDesc.addEventListener('input', function() {
            metaDescCount.textContent = this.value.length + '/160';
        });
    });
</script>
@endpush
@endsection
