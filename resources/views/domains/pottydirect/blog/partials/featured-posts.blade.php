{{-- resources/views/domains/pottydirect/blog/partials/featured-posts.blade.php --}}
<div class="mb-10 md:mb-12">
    <h2 class="text-xl md:text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
        </svg>
        Featured Articles
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Main Featured Post --}}
        <div class="md:col-span-2">
            @if($featuredPosts->first())
                @php($post = $featuredPosts->first())
                <article class="group relative bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg transition-shadow h-full">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block h-full">
                        @if($post->featured_image_url)
                            <div class="relative aspect-[21/9] overflow-hidden">
                                <img src="{{ $post->featured_image_url }}"
                                     alt="{{ $post->title }}"
                                     loading="lazy"
                                     decoding="async"
                                     width="840"
                                     height="360"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                <span class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold text-amber-800 bg-amber-400 rounded-full shadow-sm">
                                    Featured
                                </span>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-xl md:text-2xl font-bold text-white mb-2 line-clamp-2">{{ $post->title }}</h3>
                                    <div class="flex items-center gap-3 text-sm text-white/80">
                                        <time>{{ $post->published_at?->format('M j, Y') }}</time>
                                        <span>&#x2022;</span>
                                        <span>{{ $post->reading_time_text }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-6 md:p-8 flex flex-col justify-end h-full bg-gradient-to-br from-slate-50 to-slate-100">
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-amber-800 bg-amber-400 rounded-full w-fit mb-4">
                                    Featured
                                </span>
                                <h3 class="text-xl md:text-2xl font-bold text-slate-800 mb-3 line-clamp-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-emerald-600 transition">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="text-slate-500 text-sm line-clamp-2 mb-4">{!! Str::limit(strip_tags($post->excerpt), 120) !!}</p>
                                <div class="flex items-center gap-3 text-xs text-slate-400">
                                    <time>{{ $post->published_at?->format('M j, Y') }}</time>
                                    <span>&#x2022;</span>
                                    <span>{{ $post->reading_time_text }}</span>
                                </div>
                            </div>
                        @endif
                    </a>
                </article>
            @endif
        </div>

        {{-- Side Featured Posts --}}
        <div class="space-y-4">
            @foreach($featuredPosts->skip(1) as $post)
                <article class="group bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
                    <a href="{{ route('blog.show', $post->slug) }}" class="flex gap-4 p-4">
                        <div class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gradient-to-br from-emerald-100 to-emerald-200">
                            @if($post->featured_image_url)
                                <img src="{{ $post->featured_image_url }}"
                                     alt="{{ $post->title }}"
                                     loading="lazy"
                                     decoding="async"
                                     width="80"
                                     height="80"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-2xl opacity-50">
                                    &#x1FABD;
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-bold text-slate-800 mb-1 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                {{ $post->title }}
                            </h3>
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <time>{{ $post->published_at?->format('M j') }}</time>
                                <span>&#x2022;</span>
                                <span>{{ $post->reading_time_text }}</span>
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</div>
