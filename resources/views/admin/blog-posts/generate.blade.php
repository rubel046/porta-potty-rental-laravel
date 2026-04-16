@extends('admin.layout')
@section('title', 'Generate Blog Post with AI')
@section('page-title', 'AI Blog Post Generator')

@section('content')
<div class="max-w-2xl" x-data="{
    generating: false,
    generated: false,
    error: null,
    data: null,
category_id: '',
    city_id: '',

    async generate() {
        if (!this.category_id) {
            this.error = 'Please select a category';
            return;
        }

        this.generating = true;
        this.error = null;
        this.generated = false;
        this.data = null;

        const csrfToken = document.querySelector('meta[name=csrf-token]')?.content;
        if (!csrfToken) {
            this.error = 'CSRF token not found';
            this.generating = false;
            return;
        }

        try {
            const response = await fetch('{{ route('admin.blog-posts.generate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
body: JSON.stringify({
                    blog_category_id: this.category_id,
                    city_id: this.city_id || null
                })
            });

            const result = await response.json();
            console.log('API Response:', JSON.stringify(result, null, 2));

            if (result.success && result.data) {
                this.generated = true;
                this.data = result.data;
                this.populateForm(result.data);
            } else {
                this.error = result.error || 'Generation failed';
            }
        } catch (e) {
            this.error = e.message || 'An error occurred';
            console.error('Fetch error:', e);
        } finally {
            this.generating = false;
        }
    },

    populateForm(data) {
        if (!data) return;

        const setValue = (selector, value) => {
            const el = document.querySelector(selector);
            if (el) el.value = value || '';
        };

        setValue('[name=title]', data.title);
        setValue('[name=slug]', data.slug);
        setValue('[name=excerpt]', data.excerpt);
        setValue('[name=content]', data.content);
        setValue('[name=meta_title]', data.meta_title);
        setValue('[name=meta_description]', data.meta_description);
        setValue('[name=focus_keyword]', data.focus_keyword);
        setValue('[name=featured_image]', data.featured_image);
        setValue('[name=blog_category_id]', data.blog_category_id);
        setValue('[name=city_id]', data.city_id);

        const hiddenData = document.getElementById('generatedData');
        if (hiddenData) hiddenData.value = JSON.stringify(data);
    }
}">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Generate Blog Post with AI</h2>
                <p class="text-sm text-gray-500 mt-1">Select a category and optionally a city to generate SEO-optimized content</p>
            </div>
            <a href="{{ route('admin.blog-posts.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select x-model="category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Choose the category that best fits your blog topic</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City (Optional)</label>
                <select x-model="city_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="">No specific city (general content)</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}, {{ $city->state->code }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Select a city for location-specific SEO optimization</p>
            </div>

            <div x-show="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm" x-cloak>
                <span x-text="error"></span>
            </div>

            <div class="pt-4 border-t">
                <button type="button" @click="generate()"
                    :disabled="generating"
                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg text-sm font-medium hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg x-show="generating" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="generating ? 'Generating...' : 'Generate Content'"></span>
                </button>
            </div>
        </div>
    </div>

    <form x-show="generated" method="POST" action="{{ route('admin.blog-posts.store') }}" class="mt-6 space-y-6" id="generatedForm">
        @csrf
        <input type="hidden" name="generated_data" id="generatedData">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="flex items-center gap-2 pb-2 border-b">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <h3 class="font-semibold text-gray-800">AI Generated Content</h3>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-medium" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="blog_category_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <select name="city_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">None</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}, {{ $city->state->code }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
                    <textarea name="excerpt" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Content (Markdown) *</label>
                    <textarea name="content" rows="20" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono" required></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Focus Keyword</label>
                    <input type="text" name="focus_keyword" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
                    <input type="text" name="featured_image" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono" placeholder="pottydirect/service-images/...">
                </div>

                <div class="md:col-span-2 flex items-center gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" value="1" class="w-4 h-4">
                        <span class="text-sm">Publish immediately</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4">
                        <span class="text-sm">Featured</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700">Save Post</button>
            <button type="button" @click="generate()" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700">Regenerate</button>
            <a href="{{ route('admin.blog-posts.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
