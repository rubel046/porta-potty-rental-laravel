@extends('domains.pottydirect.layout')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->seo_description ?? $post->excerpt)
@section('canonical', $post->url)

@push('schema')
@php
$articleSchema = [
    "@context" => "https://schema.org",
    "@type" => "BlogPosting",
    "headline" => $post->title,
    "description" => $post->seo_description ?? strip_tags($post->excerpt),
    "image" => $post->featured_image_url ?? url('/og-image.jpg'),
    "datePublished" => $post->published_at?->toIso8601String(),
    "dateModified" => $post->updated_at->toIso8601String(),
    "wordCount" => str_word_count(strip_tags($post->content ?? '')),
    "articleSection" => $post->category?->name,
    "keywords" => $post->focus_keyword,
    "author" => [
        "@type" => "Organization",
        "name" => ($domain?->business_name ?? 'Potty Direct'),
        "url" => url('/'),
    ],
    "publisher" => [
        "@type" => "Organization",
        "name" => ($domain?->business_name ?? 'Potty Direct'),
        "logo" => ["@type" => "ImageObject", "url" => url('/logo.png')]
    ],
    "mainEntityOfPage" => ["@type" => "WebPage", "@id" => $post->url],
    "inLanguage" => "en-US",
];

$videoSchema = [
    "@context" => "https://schema.org",
    "@type" => "VideoObject",
    "@id" => url('/') . '#video',
    "name" => $post->title,
    "description" => $post->seo_description ?? strip_tags($post->excerpt),
    "thumbnailUrl" => $post->featured_image_url ?? url('/og-image.jpg'),
    "uploadDate" => $post->published_at?->toIso8601String() ?? '2025-01-01',
    "publisher" => [
        "@type" => "Organization",
        "name" => ($domain?->business_name ?? 'Potty Direct'),
    ],
];
// Drop any null values cleanly
$articleSchema = array_filter($articleSchema, fn($v) => $v !== null && $v !== '');
$videoSchema = array_filter($videoSchema, fn($v) => $v !== null && $v !== '');
@endphp
<script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@if(!empty($videoSchema))
<script type="application/ld+json">{!! json_encode($videoSchema, JSON_UNESCAPED_SLASHES) !!}</script>
@endif
@endpush

@section('content')

    {{-- Trust Banner --}}
    <div class="bg-slate-900 text-white py-3">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-center gap-3 md:gap-5 text-center md:text-left text-xs sm:text-sm">
            @if(($reviewCount ?? 0) > 0)
                <div class="flex items-center gap-2">
                    <x-icon name="star" class="w-4 h-4 text-amber-400" />
                    <span class="font-semibold">{{ number_format($reviewRating ?? 4.9, 1) }}/5 ({{ $reviewCount }}+ Reviews)</span>
                </div>
                <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
            @endif
            <span class="inline-flex items-center gap-1.5"><x-icon name="shield-check" class="w-4 h-4 text-emerald-400" />Licensed &amp; Insured</span>
            <span class="hidden md:inline text-slate-600" aria-hidden="true">·</span>
            <span class="inline-flex items-center gap-1.5"><x-icon name="truck" class="w-4 h-4 text-emerald-400" />Same-Day Delivery</span>
        </div>
    </div>

    <article>
        {{-- Header --}}
        <header class="relative py-16 md:py-20 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900"></div>
            <div class="absolute inset-0 opacity-10">
                
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
                 @if($post->featured_image_url)
                     <img src="{{ $post->featured_image_url }}"
                         alt="{{ $post->title }}"
                         loading="eager"
                         fetchpriority="high"
                         decoding="async"
                         width="1200"
                         height="480"
                         class="w-full h-64 md:h-80 object-cover rounded-2xl mb-10 shadow-lg">
                @else
                    <div class="h-64 md:h-80 bg-gradient-to-br from-emerald-50 to-emerald-100
                                rounded-2xl flex items-center justify-center mb-10 shadow-inner">
                        <x-icon name="home" class="w-16 h-16 text-emerald-400" />
                    </div>
                @endif

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
                <div class="mt-12 bg-slate-900 rounded-2xl p-8 text-center">
                    <h3 class="text-2xl font-bold text-white mb-3">Need a quote?</h3>
                    <p class="text-slate-400 mb-6">Get same-day delivery on porta potty rentals.</p>
                    <a href="tel:{{ domain_phone_raw() }}"
                       data-tracking-label="blog-cta"
                       class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-white font-bold text-lg
                              py-3 px-7 rounded-full hover:scale-[1.02] transition shadow-lg shadow-amber-500/30 ring-4 ring-amber-400/30 min-h-[44px]">
                        <x-icon name="phone" class="w-5 h-5" />
                        {{ domain_phone_display() }}
                    </a>
                </div>

                {{-- Related Posts --}}
                @if(isset($relatedPosts) && $relatedPosts->isNotEmpty())
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6">Related Articles</h3>
                    <div class="grid sm:grid-cols-3 gap-4">
                        @foreach($relatedPosts as $rp)
                        <a href="{{ $rp->url }}"
                           class="bg-white hover:bg-slate-50 p-5 rounded-xl transition border border-slate-200 hover:border-emerald-300 hover:shadow-md">
                             @if($rp->featured_image_url)
                                 <img src="{{ $rp->featured_image_url }}" alt="{{ $rp->title }}"
                                     loading="lazy" width="400" height="128" class="w-full h-32 object-cover rounded-lg mb-3">
                            @endif
                            <h4 class="font-bold text-slate-800 text-sm line-clamp-2">{{ $rp->title }}</h4>
                            <p class="text-xs text-slate-500 mt-2">{{ $rp->reading_time_text }}</p>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Related Landing Pages --}}
                @php
                    $landingGuides = [
                        ['route' => 'cost.page', 'icon' => 'currency-dollar', 'label' => 'Rental Cost Guide', 'desc' => 'Pricing factors explained'],
                        ['route' => 'party.page', 'icon' => 'sparkles', 'label' => 'Party Rentals', 'desc' => 'Events & celebrations'],
                        ['route' => 'how-many.page', 'icon' => 'calculator', 'label' => 'How Many Units?', 'desc' => 'Quantity estimator'],
                    ];
                @endphp
                <div class="mt-8">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Helpful Guides</h3>
                    <div class="grid sm:grid-cols-3 gap-3">
                        @foreach($landingGuides as $guide)
                        <a href="{{ route($guide['route']) }}"
                           class="flex items-center gap-3 bg-white hover:bg-slate-50 p-4 rounded-xl transition border border-slate-200 hover:border-emerald-300 hover:shadow-md min-h-[44px]">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <x-icon name="{{ $guide['icon'] }}" class="w-5 h-5" />
                            </div>
                            <div>
                                <div class="font-bold text-slate-800 text-sm">{{ $guide['label'] }}</div>
                                <div class="text-xs text-slate-500">{{ $guide['desc'] }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Related Services --}}
                <div class="mt-12 grid sm:grid-cols-3 gap-4">
                    <a href="{{ route('services') }}"
                       class="bg-white hover:bg-slate-50 p-6 rounded-xl text-center transition border border-slate-200 hover:border-emerald-300 hover:shadow-md">
                        <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-3">
                            <x-icon name="shield-check" class="w-6 h-6" />
                        </div>
                        <h4 class="font-bold text-slate-800">Our Services</h4>
                        <p class="text-sm text-slate-600 mt-1">Standard, Deluxe, ADA &amp; more</p>
                    </a>
                    <a href="{{ route('locations') }}"
                       class="bg-white hover:bg-slate-50 p-6 rounded-xl text-center transition border border-slate-200 hover:border-emerald-300 hover:shadow-md">
                        <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-3">
                            <x-icon name="map-pin" class="w-6 h-6" />
                        </div>
                        <h4 class="font-bold text-slate-800">Find Locations</h4>
                        <p class="text-sm text-slate-600 mt-1">Browse cities we serve</p>
                    </a>
                    <a href="{{ route('pricing') }}"
                       class="bg-white hover:bg-slate-50 p-6 rounded-xl text-center transition border border-slate-200 hover:border-emerald-300 hover:shadow-md">
                        <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-3">
                            <x-icon name="currency-dollar" class="w-6 h-6" />
                        </div>
                        <h4 class="font-bold text-slate-800">View Pricing</h4>
                        <p class="text-sm text-slate-600 mt-1">Transparent rates</p>
                    </a>
                </div>

                {{-- Back to Blog --}}
                <div class="mt-10 text-center">
                    <a href="{{ route('blog.index') }}"
                       class="inline-flex items-center gap-2 text-slate-500 hover:text-amber-600 font-medium transition">
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
