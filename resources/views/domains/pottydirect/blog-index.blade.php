@extends(\App\Providers\DomainViewHelper::resolve('layout'))

@section('title', 'Porta Potty Rental Blog | Guides, Tips & Pricing Info')
@section('meta_description', 'Expert guides on porta potty rental pricing, event planning, construction site requirements, and more.')

@section('content')

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
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center">
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
                    <p class="text-slate-500 text-sm line-clamp-2">{{ $post->excerpt }}</p>
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
        <div class="mt-12 flex justify-center">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</section>

<section class="py-12 md:py-16 px-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-center">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold mb-3">Need a Porta Potty Now?</h2>
        <p class="text-emerald-100 mb-6">Call us for instant pricing and same-day delivery.</p>
        <a href="tel:{{ domain_phone_raw() }}" class="inline-flex items-center gap-2 bg-white text-emerald-600 font-bold text-xl py-3 px-8 rounded-full hover:scale-105 transition-all shadow-lg">
            📞 {{ domain_phone_display() }}
        </a>
    </div>
</section>
@endsection