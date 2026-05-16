@extends('admin.layout')
@section('title', 'City Page Quality Scores')
@section('page-title', 'City Page Quality Scores')

@php
$gradeColors = [
    'A' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'badge' => 'bg-green-500', 'row' => 'bg-green-50/50'],
    'B' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'badge' => 'bg-blue-500', 'row' => 'bg-blue-50/30'],
    'C' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'badge' => 'bg-amber-500', 'row' => 'bg-amber-50/30'],
    'D' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'badge' => 'bg-orange-500', 'row' => 'bg-orange-50/30'],
    'F' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'badge' => 'bg-red-500', 'row' => 'bg-red-50/30'],
];
@endphp

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">Quality Scores</h2>
        <p class="text-sm text-gray-500">SEO quality scoring for city service pages (worst scores first — <strong>{{ $paginator->total() }}</strong> scored, showing {{ $paginator->perPage() }} per page)</p>
    </div>
</div>

{{-- Summary Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="text-2xl font-bold text-gray-900">{{ $paginator->total() }}</div>
        <div class="text-xs text-gray-500 mt-1">Total Pages</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="text-2xl font-bold text-gray-900">{{ $averageScore }}</div>
        <div class="text-xs text-gray-500 mt-1">Average Score</div>
    </div>
    @foreach(['A', 'B', 'C', 'D', 'F'] as $grade)
        @php $colors = $gradeColors[$grade]; @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold {{ $colors['bg'] }} {{ $colors['text'] }}">{{ $grade }}</span>
                <span class="text-2xl font-bold text-gray-900">{{ $gradeDistribution[$grade] }}</span>
            </div>
            <div class="text-xs text-gray-500 mt-1">Grade {{ $grade }}</div>
        </div>
    @endforeach
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 text-left text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-medium">City</th>
                    <th class="px-6 py-4 font-medium">State</th>
                    <th class="px-6 py-4 font-medium">Score</th>
                    <th class="px-6 py-4 font-medium">Grade</th>
                    <th class="px-6 py-4 font-medium">Words</th>
                    <th class="px-6 py-4 font-medium">FAQs</th>
                    <th class="px-6 py-4 font-medium">Testimonials</th>
                    <th class="px-6 py-4 font-medium">Last Updated</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($paginator->items() as $index => $result)
                    @php
                        $page = $result['page'];
                        $s = $result['score'];
                        $colors = $gradeColors[$s['grade']] ?? $gradeColors['F'];
                        $cityName = $page->city?->name ?? 'Unknown';
                        $stateCode = $page->city?->state?->code ?? '--';
                    @endphp
                    <tr x-data="{ open: false }" class="hover:bg-gray-50/50 transition {{ $colors['row'] }}">
                        <td class="px-6 py-4">
                            <button @click="open = !open" class="flex items-center gap-2 text-left">
                                <svg x-show="!open" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                <svg x-show="open" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $cityName }}</div>
                                    <div class="text-xs text-gray-400">{{ $page->service_type_label ?? $page->service_type }}</div>
                                </div>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-gray-900">{{ $stateCode }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all duration-500" style="width: {{ $s['score'] }}%; background-color: {{ $s['score'] >= 80 ? '#22C55E' : ($s['score'] >= 60 ? '#3B82F6' : ($s['score'] >= 40 ? '#F59E0B' : ($s['score'] >= 20 ? '#F97316' : '#EF4444'))) }};"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $s['score'] }}/{{ $s['max_score'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $colors['bg'] }} {{ $colors['text'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $colors['badge'] }}"></span>
                                {{ $s['grade'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-900">{{ $s['word_count'] }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $s['faq_count'] }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $s['testimonial_count'] }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $page->updated_at ? $page->updated_at->format('M j, Y') : '—' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.service-pages.edit', $page) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Page">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.cities.generate-pages', $page->city) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Regenerate">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr x-show="open" x-cloak>
                        <td colspan="9" class="px-6 py-4 bg-gray-50/80 border-b border-gray-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                                @foreach($s['details'] as $detail)
                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                        <span class="text-xs text-gray-600">{{ $detail[0] }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-400">{{ $detail[2] }}</span>
                                            @if($detail[1] > 0)
                                                <span class="text-xs font-medium text-green-600">+{{ $detail[1] }}</span>
                                            @else
                                                <span class="text-xs font-medium text-red-400">0</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-gray-500 font-medium">No service pages found</p>
                                <p class="text-sm text-gray-400 mt-1">Generate content for your cities first</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $paginator->onEachSide(1)->links('vendor.pagination.tailwind') }}
</div>
@endsection
