{{-- resources/views/domains/pottydirect/blog/partials/sidebar.blade.php --}}
<aside class="space-y-6">
    {{-- Categories --}}
    <div class="bg-white rounded-xl border border-slate-100 p-6 sticky top-24">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Categories
        </h3>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('blog.index', request()->except('category')) }}"
                   class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-all duration-200
                   {{ !$selectedCategory ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span class="font-medium">All Posts</span>
                    <span class="text-xs {{ !$selectedCategory ? 'text-white/70' : 'text-slate-400' }}">{{ $posts->total() }}</span>
                </a>
            </li>
            @foreach($categories as $category)
                <li>
                    <a href="{{ route('blog.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                       class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-all duration-200
                       {{ $selectedCategory?->id === $category->id ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="flex items-center gap-2">
                            @if($category->icon)
                                <span class="text-base">{{ $category->icon }}</span>
                            @endif
                            <span class="font-medium">{{ $category->name }}</span>
                        </span>
                        <span class="text-xs {{ $selectedCategory?->id === $category->id ? 'text-white/70' : 'text-slate-400' }}">{{ $category->posts_count ?? 0 }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-xl border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Search
        </h3>
        <form action="{{ route('blog.index') }}" method="GET" class="relative">
            <input type="text" name="q" placeholder="Search articles..."
                   class="w-full px-4 py-2.5 pl-10 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all"
                   value="{{ request('q') }}">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </form>
    </div>

    {{-- Featured Posts (if not showing in main content) --}}
    @if($featuredPosts && $featuredPosts->isNotEmpty())
        <div class="bg-white rounded-xl border border-slate-100 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Featured
            </h3>
            <div class="space-y-4">
                @foreach($featuredPosts->take(3) as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="flex gap-3 group">
                        <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gradient-to-br from-emerald-100 to-emerald-200">
                            @if($post->featured_image_url)
                                <img src="{{ $post->featured_image_url }}"
                                     alt="{{ $post->title }}"
                                     loading="lazy"
                                     decoding="async"
                                     width="64"
                                     height="64"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xl opacity-50">&#x1FABD;</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-slate-800 mb-1 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                {{ $post->title }}
                            </h4>
                            <time class="text-xs text-slate-400">{{ $post->published_at?->format('M j, Y') }}</time>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</aside>
