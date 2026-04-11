@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@section('title', 'Porta Potty Rental Blog | Guides, Tips & Pricing Info')
@section('meta_description', 'Expert guides on porta potty rental pricing, event planning, construction site requirements, and more. Everything you need to know about portable toilet rentals.')
@section('canonical', route('blog.index'))
@section('pagination_headers')
@if(isset($paginationHeaders) && $paginationHeaders)
{!! $paginationHeaders !!}
@endif
@endsection

@push('schema')
@php
$url = url('/');
$phone = phone_raw();
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Blog",
    "headline": "Porta Potty Rental Blog | Guides, Tips & Pricing Info",
    "description": "Expert guides on porta potty rental pricing, event planning, construction site requirements, and more.",
    "url": "{{ $url }}/blog"
}
</script>
@endpush

@section('content')

    {{-- Trust Banner --}}
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white py-3">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-center gap-4 text-center md:text-left">
            <div class="flex items-center gap-2">
                <span>⭐</span>
                <span class="font-semibold">4.9/5 Rating from 500+ Reviews</span>
            </div>
            <span class="text-emerald-100">|</span>
            <span>🏢 BBB A+ Rated</span>
            <span class="text-emerald-100">|</span>
            <span>🏗️ 25+ Years Experience</span>
        </div>
    </div>

    {{-- Hero --}}
    <section class="relative py-16 md:py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-20 text-[180px]">📝</div>
            <div class="absolute bottom-10 left-10 text-[120px]">🚽</div>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-5">
                Porta Potty Rental Blog
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">
                Expert guides, pricing tips, and event planning advice for your portable sanitation needs
            </p>
        </div>
    </section>

    {{-- Blog Posts --}}
    <section class="py-12 md:py-16 px-4">
        <div class="max-w-5xl mx-auto">
            {{-- Categories --}}
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('blog.index') }}"
                   class="px-4 py-2 rounded-full text-sm font-medium transition-all
                          {{ !request('category') ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All Posts
                </a>
                @foreach(\App\Models\BlogCategory::whereHas('posts')->get() as $category)
                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                       class="px-4 py-2 rounded-full text-sm font-medium transition-all
                              {{ request('category') === $category->slug ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            @forelse($posts as $post)
                <article class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 mb-6
                            hover:shadow-xl hover:border-emerald-200 transition-all duration-300 group">
                    <div class="flex flex-col md:flex-row gap-6 md:gap-8">
                        {{-- Image --}}
                        <div class="md:w-56 h-40 bg-gradient-to-br from-blue-100 via-emerald-50 to-blue-50
                                rounded-xl flex items-center justify-center text-5xl flex-shrink-0
                                group-hover:scale-105 transition-transform duration-300">
                            🚽
                        </div>

                        {{-- Content --}}
                        <div class="flex-1">
                            @if($post->category)
                                <span class="inline-flex items-center text-xs font-semibold text-emerald-600 uppercase tracking-wider bg-emerald-50 px-3 py-1 rounded-full">
                            {{ $post->category->name }}
                        </span>
                            @endif

                            <h2 class="text-xl md:text-2xl font-bold text-slate-800 mt-3 mb-3
                                   group-hover:text-emerald-600 transition-colors">
                                <a href="{{ $post->url }}">{{ $post->title }}</a>
                            </h2>

                            <p class="text-slate-500 text-sm md:text-base mb-4 line-clamp-2 leading-relaxed">
                                {{ $post->excerpt }}
                            </p>

                            <div class="flex items-center gap-5 text-sm text-slate-400">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $post->published_at?->format('M d, Y') }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{ $post->reading_time_text }}
                                </span>
                            </div>
                        </div>

                        {{-- Arrow --}}
                        <div class="hidden md:flex items-center self-center">
                            <svg class="w-6 h-6 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">📝</div>
                    <p class="text-slate-400 text-lg mb-2">No blog posts yet.</p>
                    <p class="text-slate-400">Check back soon for helpful guides and tips!</p>
                </div>
            @endforelse

            @if(method_exists($posts, 'links') && $posts->hasPages())
                <nav class="mt-12 flex justify-center items-center gap-2">
                    {{-- Previous --}}
                    @if($posts->onFirstPage())
                        <span class="px-4 py-2 rounded-lg border border-slate-200 text-slate-400 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </span>
                    @else
                        <a href="{{ $posts->previousPageUrl() }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($posts->getUrlRange(max(1, $posts->currentPage() - 2), min($posts->lastPage(), $posts->currentPage() + 2)) as $page => $url)
                        @if($page == $posts->currentPage())
                            <span class="px-4 py-2 rounded-lg bg-emerald-500 text-white font-semibold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Ellipsis if needed --}}
                    @if($posts->currentPage() < $posts->lastPage() - 2)
                        <span class="px-2 py-2 text-slate-400">...</span>
                        <a href="{{ $posts->url($posts->lastPage()) }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all">
                            {{ $posts->lastPage() }}
                        </a>
                    @endif

                    {{-- Next --}}
                    @if($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <span class="px-4 py-2 rounded-lg border border-slate-200 text-slate-400 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-12 md:py-16 px-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold mb-3">Need a Porta Potty Now?</h2>
            <p class="text-emerald-100 mb-6">Call us for instant pricing and same-day delivery.</p>
            <a href="tel:{{ phone_raw() }}"
               class="inline-flex items-center gap-2 bg-white text-emerald-600 font-bold text-xl
                      py-3 px-8 rounded-full hover:scale-105 transition-all shadow-lg">
                📞 {{ phone_display() }}
            </a>
        </div>
    </section>
@endsection
