@extends('layouts.app')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)

@push('schema')
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "Article",
            "headline": "{{ $post->title }}",
            "description": "{{ $post->meta_description ?? $post->excerpt }}",
            "datePublished": "{{ $post->published_at?->toIso8601String() }}",
            "dateModified": "{{ $post->updated_at->toIso8601String() }}",
            "publisher": {
                "@@type": "Organization",
                "name": "Porta Potty Rental USA"
            }
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

    <article>
        {{-- Header --}}
        <header class="relative py-16 md:py-20 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 right-10 text-[200px]">📝</div>
            </div>
            <div class="absolute top-1/2 right-1/4 -translate-y-1/2 w-[400px] h-[400px] bg-emerald-500/10 rounded-full blur-3xl"></div>

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6">
                <nav class="flex items-center gap-2 text-sm text-slate-400 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <a href="{{ route('blog.index') }}" class="hover:text-white transition">Blog</a>
                    @if($post->category)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-emerald-400">{{ $post->category->name }}</span>
                    @endif
                </nav>

                @if($post->category)
                    <span class="inline-flex items-center text-xs font-semibold text-emerald-300 uppercase tracking-wider bg-emerald-500/20 border border-emerald-400/30 px-3 py-1 rounded-full mb-4">
                        {{ $post->category->name }}
                    </span>
                @endif

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-6 leading-tight">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-5 text-sm text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $post->published_at?->format('F d, Y') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        {{ $post->reading_time_text }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ number_format($post->views) }} views
                    </span>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <div class="py-12 md:py-16 px-4">
            <div class="max-w-5xl mx-auto">
                {{-- Article Image --}}
                <div class="h-64 md:h-80 bg-gradient-to-br from-blue-100 via-emerald-50 to-blue-50
                            rounded-2xl flex items-center justify-center text-8xl mb-10 shadow-inner">
                    🚽
                </div>

                {{-- Article Body --}}
                <div class="prose prose-lg max-w-none
                            prose-headings:text-slate-800 prose-headings:font-bold
                            prose-h2:text-2xl prose-h2:mt-12 prose-h2:mb-5 prose-h2:border-b prose-h2:border-slate-200 prose-h2:pb-3
                            prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
                            prose-p:text-slate-600 prose-p:leading-relaxed prose-p:mb-5
                            prose-li:text-slate-600 prose-li:leading-relaxed
                            prose-li:mb-2
                            prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
                            prose-strong:text-slate-800 prose-strong:font-semibold
                            prose-blockquote:border-l-emerald-500 prose-blockquote:bg-emerald-50 prose-blockquote:rounded-r-xl prose-blockquote:py-1
                            prose-table:text-sm
                            prose-th:bg-slate-100 prose-th:p-3 prose-th:font-semibold
                            prose-td:p-4 prose-td:border prose-td:border-slate-100
                            prose-img:rounded-xl prose-img:shadow-lg">
                    {!! Str::markdown($post->content) !!}
                </div>

                {{-- CTA Box --}}
                <div class="mt-12 bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-8 text-center">
                    <h3 class="text-2xl font-bold text-white mb-3">Need a Quote?</h3>
                    <p class="text-slate-400 mb-6">Get same-day delivery on porta potty rentals.</p>
                    <a href="tel:{{ phone_raw() }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-bold text-xl
                              py-3 px-8 rounded-full hover:scale-105 transition-all shadow-lg">
                        📞 {{ phone_display() }}
                    </a>
                </div>

                {{-- Back to Blog --}}
                <div class="mt-10 text-center">
                    <a href="{{ route('blog.index') }}"
                       class="inline-flex items-center gap-2 text-slate-500 hover:text-emerald-600 font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Blog
                    </a>
                </div>
            </div>
        </div>
    </article>
@endsection
