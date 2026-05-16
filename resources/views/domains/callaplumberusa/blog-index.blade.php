@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@section('title', 'Plumbing Blog | Professional Plumbing Tips & Guides')
@section('meta_description', 'Expert plumbing guides, DIY tips, and professional advice. Learn about plumbing maintenance, water heater care, drain cleaning, and more.')

@section('pagination_headers')
@if($posts->currentPage() > 1)
    <link rel="prev" href="{{ $posts->previousPageUrl() }}">
@endif
@if($posts->hasMorePages())
    <link rel="next" href="{{ $posts->nextPageUrl() }}">
@endif
@if($posts->currentPage() > 1)
    <meta name="robots" content="noindex, follow">
@endif
@endsection

@section('content')
<section class="bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">Plumbing Blog</h1>
        <p class="text-slate-500 text-lg max-w-2xl">
            Expert plumbing guides, DIY tips, and professional advice from Plumbing Pro.
        </p>
    </div>
</section>

<section class="py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8">
            {{-- LEFT: Blog Cards --}}
            <div class="flex-1 min-w-0">
                {{-- Mobile Category Pills --}}
                <div class="md:hidden mb-6">
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                        <a href="{{ route('blog.index') }}"
                           class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium {{ !$selectedCategory ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            All ({{ $totalPostsCount ?? $posts->total() }})
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('blog.category', ['slug' => $cat->slug]) }}"
                               class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium {{ $selectedCategory?->id === $cat->id ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                                @if($cat->icon)<span class="mr-1">{{ $cat->icon }}</span>@endif
                                {{ $cat->name }} ({{ $cat->posts_count ?? 0 }})
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6 flex items-center justify-between">
                    <p class="text-sm text-slate-500">
                        @if($selectedCategory)
                            <span class="font-semibold text-slate-700">{{ $selectedCategory->name }}</span>
                            <span class="ml-1">({{ $posts->total() }})</span>
                        @else
                            All Articles
                            <span class="ml-1">({{ $totalPostsCount ?? $posts->total() }})</span>
                        @endif
                    </p>
                    <div class="flex gap-1">
                        <a href="{{ route('blog.index', array_merge(request()->query(), ['sort' => 'latest'])) }}"
                           class="px-3 py-1 text-xs font-medium rounded-full {{ !request('sort') || request('sort') === 'latest' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Latest
                        </a>
                        <a href="{{ route('blog.index', array_merge(request()->query(), ['sort' => 'popular'])) }}"
                           class="px-3 py-1 text-xs font-medium rounded-full {{ request('sort') === 'popular' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Popular
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($posts as $post)
                        <article class="group bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            <a href="{{ route('blog.show', $post->slug) }}" class="block">
                                 @if($post->featured_image_url)
                                     <div class="relative aspect-[16/9] overflow-hidden">
                                         <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @if($post->category)
                                            <span class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full">{{ $post->category->name }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="relative aspect-[16/9] bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.42 10.922a6.376 6.376 0 01-7.624 7.624M9.233 14.177L4.05 19.36a2.25 2.25 0 01-3.182-3.182l5.183-5.183a6.375 6.375 0 017.624-7.624 6.375 6.375 0 01-4.442 11.206z"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            <div class="p-5">
                                <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                                    <time>{{ $post->published_at?->format('M j, Y') }}</time>
                                    <span>&#x2022;</span>
                                    <span>{{ $post->reading_time_text }}</span>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <h2 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">{{ $post->title }}</h2>
                                </a>
                                <p class="text-slate-500 text-sm line-clamp-2">{!! Str::limit(strip_tags($post->excerpt), 250) !!}</p>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-slate-500">No posts found.</p>
                        </div>
                    @endforelse
                </div>

                @if($posts->hasPages())
                    <div class="mt-8">
                        <x-blog-pagination :paginator="$posts" />
                    </div>
                @endif
            </div>

            {{-- RIGHT: Category List --}}
            <div class="w-full md:w-64 flex-shrink-0">
                <div class="bg-white rounded-xl border border-slate-100 p-5 sticky top-24">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Categories</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('blog.index') }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ !$selectedCategory ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>All Posts</span>
                                <span class="text-xs {{ !$selectedCategory ? 'text-white/70' : 'text-slate-400' }}">{{ $totalPostsCount ?? $posts->total() }}</span>
                            </a>
                        </li>
                        @foreach($categories->take(30) as $cat)
                            <li>
                                <a href="{{ route('blog.category', ['slug' => $cat->slug]) }}"
                                   class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ $selectedCategory?->id === $cat->id ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                                    <span class="flex items-center gap-2">
                                        @if($cat->icon)<span>{{ $cat->icon }}</span>@endif
                                        <span>{{ $cat->name }}</span>
                                    </span>
                                    <span class="text-xs {{ $selectedCategory?->id === $cat->id ? 'text-white/70' : 'text-slate-400' }}">{{ $cat->posts_count ?? 0 }}</span>
                                </a>
                            </li>
                        @endforeach
                        @if($categories->count() > 30)
                            <button id="loadMoreCategories" onclick="toggleCategories()"
                                    class="w-full text-xs text-slate-500 hover:text-slate-700 mt-2 text-left px-3 py-1">
                                Show All ({{ $categories->count() - 30 }} more)
                            </button>
                            <div id="moreCategories" class="hidden space-y-1 mt-1">
                                @foreach($categories->skip(30) as $cat)
                                    <li>
                                        <a href="{{ route('blog.category', ['slug' => $cat->slug]) }}"
                                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ $selectedCategory?->id === $cat->id ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                                            <span class="flex items-center gap-2">
                                                @if($cat->icon)<span>{{ $cat->icon }}</span>@endif
                                                <span>{{ $cat->name }}</span>
                                            </span>
                                            <span class="text-xs {{ $selectedCategory?->id === $cat->id ? 'text-white/70' : 'text-slate-400' }}">{{ $cat->posts_count ?? 0 }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-12 px-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold mb-3">Need a Plumber Now?</h2>
        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-2 bg-white text-orange-600 font-bold text-xl py-3 px-8 rounded-full hover:scale-105 transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            {{ domain_phone_display() }}
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script>
function toggleCategories() {
    const moreCats = document.getElementById('moreCategories');
    const btn = document.getElementById('loadMoreCategories');
    if (moreCats.classList.contains('hidden')) {
        moreCats.classList.remove('hidden');
        btn.textContent = 'Show Less';
    } else {
        moreCats.classList.add('hidden');
        btn.textContent = 'Show All ({{ $categories->count() - 30 }} more)';
    }
}
</script>
@endpush
