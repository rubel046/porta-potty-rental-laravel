{{-- resources/views/domains/waterdamage/blog/partials/category-pills.blade.php --}}
<div class="mb-8 md:mb-10">
    <div class="-mx-4 px-4 sm:mx-0 sm:px-0">
        <div class="flex gap-2 overflow-x-auto pb-3 sm:pb-0 sm:flex-wrap sm:overflow-visible scrollbar-hide snap-x">
            <a href="{{ route('blog.index', request()->except('category')) }}"
               class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium snap-start transition-all duration-200
               {{ !$selectedCategory ? 'bg-emerald-500 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                All Posts
                <span class="text-xs opacity-70">({{ $posts->total() }})</span>
            </a>

            @foreach($categories as $category)
                <a href="{{ route('blog.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                   class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium snap-start transition-all duration-200
                   {{ $selectedCategory?->id === $category->id ? 'bg-emerald-500 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    @if($category->icon)
                        <span class="text-base">{{ $category->icon }}</span>
                    @endif
                    {{ $category->name }}
                    <span class="text-xs opacity-70">({{ $category->posts_count ?? 0 }})</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
