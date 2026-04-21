@if($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $urlRange = [];
        $showMax = 5;
        $start = max(1, $currentPage - floor($showMax / 2));
        $end = min($lastPage, $start + $showMax - 1);
        if ($end - $start + 1 < $showMax) {
            $start = max(1, $end - $showMax + 1);
        }
    @endphp
    <nav class="flex items-center justify-center gap-1" role="navigation">
        {{-- Previous Page --}}
        @if($paginator->onFirstPage())
            <span class="px-4 py-2 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif

        {{-- First Page --}}
        @if($start > 1)
            <a href="{{ $paginator->url(1) }}" class="px-4 py-2 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition">1</a>
            @if($start > 2)
                <span class="px-4 py-2 text-slate-400">...</span>
            @endif
        @endif

        {{-- Page Numbers --}}
        @for($i = $start; $i <= $end; $i++)
            @if($i == $currentPage)
                <span class="px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg">{{ $i }}</span>
            @else
                <a href="{{ $paginator->url($i) }}" class="px-4 py-2 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition">{{ $i }}</a>
            @endif
        @endfor

        {{-- Last Page --}}
        @if($end < $lastPage)
            @if($end < $lastPage - 1)
                <span class="px-4 py-2 text-slate-400">...</span>
            @endif
            <a href="{{ $paginator->url($lastPage) }}" class="px-4 py-2 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition">{{ $lastPage }}</a>
        @endif

        {{-- Next Page --}}
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="px-4 py-2 text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </nav>

    {{-- Page Info --}}
    <div class="text-center mt-4 text-sm text-slate-500">
        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} articles
    </div>
@endif