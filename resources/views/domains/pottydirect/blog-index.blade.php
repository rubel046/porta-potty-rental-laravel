@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@section('title', $selectedCategory ? ($selectedCategory->name . ' — Porta Potty Rental Blog') : 'Porta Potty Rental Blog | Guides, Tips & Pricing Info')
@section('meta_description', $selectedCategory ? ($selectedCategory->description ?? 'Read our ' . strtolower($selectedCategory->name) . ' guides for porta potty rentals. Expert tips, pricing, and advice.') : 'Expert guides on porta potty rental pricing, event planning, and construction site requirements. Everything you need to know about renting portable toilets.')

@push('schema')
@php
$blogSchema = [
    "@context" => "https://schema.org",
    "@type" => "Blog",
    "@id" => url('/') . "#blog",
    "name" => "Porta Potty Rental Blog | Potty Direct",
    "description" => "Expert guides on porta potty rental pricing, event planning, construction site requirements, and more.",
    "url" => route('blog.index'),
    "publisher" => ["@id" => url('/') . "#organization"],
];
$breadcrumbItems = [
    ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => route('home')],
    ["@type" => "ListItem", "position" => 2, "name" => "Blog", "item" => route('blog.index')],
];
if ($selectedCategory) {
    $breadcrumbItems[] = ["@type" => "ListItem", "position" => 3, "name" => $selectedCategory->name, "item" => route('blog.category', ['slug' => $selectedCategory->slug])];
}
$breadcrumbSchema = [
    "@context" => "https://schema.org",
    "@type" => "BreadcrumbList",
    "itemListElement" => $breadcrumbItems,
];
@endphp
<script type="application/ld+json">{!! json_encode($blogSchema, JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

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
        <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">{{ $selectedCategory ? $selectedCategory->name . ' Guides & Tips' : 'Portable Toilet Rental Blog' }}</h1>
        <p class="text-slate-500 text-lg max-w-2xl">
            {{ $selectedCategory ? ($selectedCategory->description ?? 'Expert guides, tips, and advice for ' . strtolower($selectedCategory->name) . '.') : 'Expert guides on porta potty rental, event planning, construction site requirements, and more.' }}
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
                        @foreach($categories->take(15) as $cat)
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
                                         <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" width="640" height="360" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                         @if($post->category)
                                             <span class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold text-white bg-emerald-600 rounded-full">{{ $post->category->name }}</span>
                                         @endif
                                     </div>
                                @else
                                    <div class="relative aspect-[16/9] bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center">
                                        <span class="text-6xl opacity-50">&#x1FABD;</span>
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
                                    <h2 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition-colors line-clamp-2">{{ $post->title }}</h2>
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
                        @foreach($categories->take(15) as $cat)
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
                        @if($categories->count() > 15)
                            <button id="loadMoreCategories" onclick="toggleCategories()"
                                    class="w-full text-xs text-slate-500 hover:text-slate-700 mt-2 text-left px-3 py-1">
                                Show All ({{ $categories->count() - 15 }} more)
                            </button>
                            <div id="moreCategories" class="hidden space-y-1 mt-1">
                                @foreach($categories->skip(15) as $cat)
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

<section class="py-12 px-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold mb-3">Need a Porta Potty Now?</h2>
        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-2 bg-white text-amber-600 font-bold text-xl py-3 px-8 rounded-full hover:scale-105 transition-all">
            &#x1F4DE; {{ domain_phone_display() }}
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
        btn.textContent = 'Show All ({{ $categories->count() - 15 }} more)';
    }
}
</script>
@endpush
