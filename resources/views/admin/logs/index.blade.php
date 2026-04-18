@extends('admin.layout')
@section('title', 'System Logs')
@section('page-title', 'System Logs')

@section('content')
<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Total Logs</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Info</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['info'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Warning</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['warning'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Error</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['error'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Critical</p>
                    <p class="text-2xl font-bold text-red-800">{{ $stats['critical'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-200 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- File Selector --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-gray-500 mb-1">Log File</label>
                <select name="file" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="laravel" {{ request('file', 'laravel') === 'laravel' ? 'selected' : '' }}>Laravel (laravel.log)</option>
                    <option value="blog-generation" {{ request('file') === 'blog-generation' ? 'selected' : '' }}>Blog Generation (blog-generation.log)</option>
                    <option value="city-page-generation" {{ request('file') === 'city-page-generation' ? 'selected' : '' }}>City Page Generation (city-page-generation.log)</option>
                    <option value="worker" {{ request('file') === 'worker' ? 'selected' : '' }}>Worker (worker.log)</option>
                    <option value="calls" {{ request('file') === 'calls' ? 'selected' : '' }}>Calls (calls-*.log)</option>
                    <option value="google-indexing" {{ request('file') === 'google-indexing' ? 'selected' : '' }}>Google Indexing (google-indexing*.log)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Level</label>
                <select name="level" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Levels</option>
                    @foreach($levels as $key => $label)
                        <option value="{{ $key }}" {{ request('level') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Per Page</label>
                <select name="per_page" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    @foreach([50, 100, 200, 500] as $count)
                        <option value="{{ $count }}" {{ $perPage == $count ? 'selected' : '' }}>{{ $count }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">Filter</button>
                <a href="{{ route('admin.logs.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">Reset</a>
            </div>
        </form>
    </div>

    {{-- Actions --}}
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">
            Log Entries 
            <span class="text-gray-400 text-sm font-normal">
                ({{ $paginatedLogs ? count($paginatedLogs) : 0 }} of {{ $totalLogs }})
            </span>
        </h2>
        <div class="flex gap-2">
            <button type="button" onclick="location.reload()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh
            </button>
            <form method="POST" action="{{ route('admin.logs.clear') }}" onsubmit="return confirm('Are you sure you want to clear all logs?');">
                @csrf
                <input type="hidden" name="file" value="{{ request('file', 'laravel') }}">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Clear Logs
                </button>
            </form>
            <form method="POST" action="{{ route('admin.logs.download') }}">
                @csrf
                <input type="hidden" name="file" value="{{ request('file', 'laravel') }}">
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download
                </button>
            </form>
        </div>
    </div>

    {{-- Logs List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Timestamp</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Log Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($paginatedLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">{{ $log['timestamp'] }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $levelColors = [
                                        'info' => 'bg-blue-100 text-blue-700',
                                        'warning' => 'bg-yellow-100 text-yellow-700',
                                        'error' => 'bg-red-100 text-red-700',
                                        'critical' => 'bg-red-200 text-red-800',
                                    ];
                                    $levelColor = $levelColors[$log['level']] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $levelColor }}">
                                    {{ strtoupper($log['level']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 max-w-xl truncate" title="{{ $log['message'] }}">
                                {{ Str::limit($log['message'], 100) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button type="button" onclick="showLogDetail({{ json_encode($log) }})" class="text-indigo-600 hover:text-indigo-800 text-xs">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                No log entries found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($totalPages > 1)
            <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 bg-gray-50">
                <div class="text-sm text-gray-500">
                    Page {{ $currentPage }} of {{ $totalPages }}
                </div>
                <div class="flex gap-1">
                    @php
                        $queryParams = [];
                        if (request('search')) $queryParams['search'] = request('search');
                        if (request('level') && request('level') !== 'all') $queryParams['level'] = request('level');
                        $queryParams['per_page'] = $perPage;
                    @endphp
                    
                    @if($currentPage > 1)
                        <a href="{{ route('admin.logs.index', array_merge($queryParams, ['page' => 1])) }}" 
                           class="px-3 py-1 text-sm rounded hover:bg-gray-200 {{ $currentPage == 1 ? 'text-gray-400 cursor-not-allowed' : 'text-gray-600' }}">
                            ««
                        </a>
                        <a href="{{ route('admin.logs.index', array_merge($queryParams, ['page' => $currentPage - 1])) }}" 
                           class="px-3 py-1 text-sm rounded hover:bg-gray-200 text-gray-600">
                            «
                        </a>
                    @endif
                    
                    @php
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $currentPage + 2);
                        if ($end - $start < 4) {
                            if ($start == 1) $end = min($totalPages, 5);
                            if ($end == $totalPages) $start = max(1, $totalPages - 4);
                        }
                    @endphp
                    
                    @for($i = $start; $i <= $end; $i++)
                        <a href="{{ route('admin.logs.index', array_merge($queryParams, ['page' => $i])) }}" 
                           class="px-3 py-1 text-sm rounded {{ $i == $currentPage ? 'bg-indigo-600 text-white' : 'hover:bg-gray-200 text-gray-600' }}">
                            {{ $i }}
                        </a>
                    @endfor
                    
                    @if($currentPage < $totalPages)
                        <a href="{{ route('admin.logs.index', array_merge($queryParams, ['page' => $currentPage + 1])) }}" 
                           class="px-3 py-1 text-sm rounded hover:bg-gray-200 text-gray-600">
                            »
                        </a>
                        <a href="{{ route('admin.logs.index', array_merge($queryParams, ['page' => $totalPages])) }}" 
                           class="px-3 py-1 text-sm rounded hover:bg-gray-200 text-gray-600">
                            »»
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Log Detail Modal -->
<div id="log-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="font-bold text-gray-800">Log Details</h3>
            <button onclick="closeLogModal()" class="p-1 hover:bg-gray-100 rounded">
                <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-60px)]">
            <div id="log-detail-content"></div>
        </div>
    </div>
</div>

<script>
function showLogDetail(log) {
    const content = document.getElementById('log-detail-content');
    const levelConfig = {
        'info': { bg: 'bg-blue-500', text: 'text-blue-600', label: 'INFO' },
        'warning': { bg: 'bg-yellow-500', text: 'text-yellow-600', label: 'WARNING' },
        'error': { bg: 'bg-red-500', text: 'text-red-600', label: 'ERROR' },
        'critical': { bg: 'bg-red-600', text: 'text-red-700', label: 'CRITICAL' },
    };
    const level = levelConfig[log.level] || { bg: 'bg-gray-500', text: 'text-gray-600', label: log.level.toUpperCase() };
    
    let contextHtml = '';
    if (log.context && Object.keys(log.context).length > 0) {
        contextHtml = `
            <div class="mt-6">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-4 h-4 rounded bg-gray-300"></div>
                    <h4 class="text-sm font-semibold text-gray-700">Context</h4>
                </div>
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-4 overflow-x-auto">
                    <pre class="text-xs text-green-400 font-mono leading-relaxed">${JSON.stringify(log.context, null, 2)}</pre>
                </div>
            </div>
        `;
    }
    
    content.innerHTML = `
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl ${level.bg} flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold ${level.text} bg-opacity-10" style="background-color: ${level.bg}20;">
                            ${level.label}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">${log.env} environment</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Timestamp</p>
                    <p class="text-sm font-mono text-gray-600">${log.timestamp}</p>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-6">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-4 h-4 rounded bg-gray-300"></div>
                    <h4 class="text-sm font-semibold text-gray-700">Message</h4>
                </div>
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-5 overflow-x-auto">
                    <pre class="text-sm text-gray-100 font-mono leading-relaxed whitespace-pre-wrap">${escapeHtml(log.message)}</pre>
                </div>
            </div>
            
            ${contextHtml}
            
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button onclick="closeLogModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    Close
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('log-modal').classList.remove('hidden');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function closeLogModal() {
    document.getElementById('log-modal').classList.add('hidden');
}

document.getElementById('log-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogModal();
    }
});
</script>
@endsection
