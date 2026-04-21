@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@section('title', 'Porta Potty Rental Blog | Guides, Tips & Pricing Info')
@section('meta_description', 'Expert guides on porta potty rental pricing, event planning, construction site requirements, and more.')

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

<div class="bg-gradient-to-r from-amber-500 to-amber-600 text-white py-3">
    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-center gap-2 sm:gap-4 text-center md:text-left text-xs sm:text-sm">
        <div class="flex items-center gap-2">
            <span class="text-amber-200">⭐</span>
            <span class="font-semibold">4.9/5 Rating (500+ Reviews)</span>
        </div>
        <span class="hidden md:inline text-amber-200">|</span>
        <span>🏢 BBB A+ Rated</span>
        <span class="hidden md:inline text-amber-200">|</span>
        <span>🏗️ 25+ Years Experience</span>
    </div>
</div>

<section class="bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-slate-800 mb-3">Portable Restroom Rental Blog</h1>
        <p class="text-slate-500 text-lg max-w-2xl">
            Expert guides on porta potty rental, event planning, construction site requirements, and more.
        </p>
    </div>
</section>

<section class="py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
            <article class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg transition-shadow">
                <a href="{{ route('blog.show', $post->slug) }}" class="block">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover rounded-t-xl">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center rounded-t-xl">
                        <span class="text-6xl">🚽</span>
                    </div>
                    @endif
                </a>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                        <span class="text-emerald-600 font-medium">{{ $post->category?->name ?? 'Blog' }}</span>
                        <span>•</span>
                        <span>{{ $post->published_at?->format('M j, Y') }}</span>
                    </div>
                    <a href="{{ route('blog.show', $post->slug) }}" class="block">
                        <h2 class="text-lg font-bold text-slate-800 mb-2 hover:text-emerald-600 transition">{{ $post->title }}</h2>
                    </a>
                    <p class="text-slate-500 text-sm line-clamp-2">{!! $post->excerpt !!}</p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1 text-emerald-600 font-medium mt-4 hover:underline">
                        Read More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-xl font-semibold text-slate-700 mb-2">No blog posts yet</h3>
                <p class="text-slate-500">Check back soon!</p>
            </div>
            @endforelse
        </div>

        @if($posts->hasPages())
        <div class="mt-12">
            <x-blog-pagination :paginator="$posts" />
        </div>
        @endif
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold mb-3">Need a Porta Potty Now?</h2>
        <p class="text-amber-100 mb-6">Call us for instant pricing and same-day delivery.</p>
        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-2 bg-white text-amber-600 font-bold text-xl py-3 px-8 rounded-full hover:scale-105 transition-all shadow-lg">
            📞 {{ domain_phone_display() }}
        </a>
    </div>
</section>

{{-- Mobile Sticky CTA --}}
<div class="fixed bottom-4 left-4 right-4 md:hidden z-50">
    <a href="tel:{{ domain_phone_raw() }}"
       class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold text-lg py-4 px-6 rounded-2xl shadow-2xl shadow-amber-500/40 ring-4 ring-amber-400/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        <span>Call Now — Free Quote</span>
    </a>
</div>
<div class="h-20 md:hidden"></div>

@endsection