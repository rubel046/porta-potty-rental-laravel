@extends('admin.layout')

@section('title', 'Index URLs')
@section('page-title', 'Index URLs')

@section('content')
<div class="space-y-4">
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-500">Total URLs</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-sm text-gray-500">Pending</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['submitted'] }}</div>
            <div class="text-sm text-gray-500">Submitted</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <div class="text-2xl font-bold text-green-600">{{ $stats['indexed'] }}</div>
            <div class="text-sm text-gray-500">Indexed</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <div class="text-2xl font-bold text-red-600">{{ $stats['failed'] }}</div>
            <div class="text-sm text-gray-500">Failed</div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <form method="GET" class="p-4 border-b border-gray-100 flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search URLs..."
                   class="px-3 py-2 border border-gray-200 rounded-lg text-sm w-full sm:w-auto sm:flex-1 min-w-0">
            <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="indexed" {{ request('status') === 'indexed' ? 'selected' : '' }}>Indexed</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <select name="type" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                <option value="">All Types</option>
                <option value="service" {{ request('type') === 'service' ? 'selected' : '' }}>Service</option>
                <option value="state" {{ request('type') === 'state' ? 'selected' : '' }}>State</option>
                <option value="blog" {{ request('type') === 'blog' ? 'selected' : '' }}>Blog</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">
                Filter
            </button>
            <a href="{{ route('admin.indexing-urls.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">
                Clear
            </a>
        </form>

        <form method="POST" action="{{ route('admin.indexing-urls.batch') }}">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300">
                            </th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">URL</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Type</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Date</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($urls as $url)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="ids[]" value="{{ $url->id }}" class="url-checkbox rounded border-gray-300">
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ $url->url }}" target="_blank" class="text-blue-600 hover:underline truncate max-w-xs block">
                                        {{ $url->url }}
                                    </a>
                                    @if($url->error_message)
                                        <div class="text-xs text-red-500 mt-1">{{ $url->error_message }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($url->type === 'service') bg-blue-100 text-blue-700
                                        @elseif($url->type === 'state') bg-purple-100 text-purple-700
                                        @else bg-green-100 text-green-700 @endif">
                                        {{ ucfirst($url->type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($url->status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($url->status === 'submitted') bg-blue-100 text-blue-700
                                        @elseif($url->status === 'indexed') bg-green-100 text-green-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($url->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ $url->created_at?->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" class="text-red-600 hover:text-red-800 text-xs remove-url"
                                        data-id="{{ $url->id }}" data-url="{{ route('admin.indexing-urls.destroy', $url->id) }}">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    No URLs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 flex flex-wrap gap-3 items-center justify-between">
                <div class="flex gap-2 items-center">
                    <select name="action" required class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="">Bulk Action</option>
                        <option value="submit">Submit for Indexing</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">
                        Apply
                    </button>
                </div>
                <div class="flex items-center gap-1">
                    @if ($urls->previousPageUrl())
                        <a href="{{ $urls->previousPageUrl() }}" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm hover:bg-gray-50 text-gray-700">« Previous</a>
                    @else
                        <span class="px-3 py-1.5 text-gray-400 text-sm">« Previous</span>
                    @endif

                    <span class="px-3 py-1.5 text-sm text-gray-600">{{ $urls->currentPage() }} / {{ $urls->lastPage() }}</span>

                    @if ($urls->nextPageUrl())
                        <a href="{{ $urls->nextPageUrl() }}" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm hover:bg-gray-50 text-gray-700">Next »</a>
                    @else
                        <span class="px-3 py-1.5 text-gray-400 text-sm">Next »</span>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('select-all').addEventListener('change', function() {
    document.querySelectorAll('.url-checkbox').forEach(cb => cb.checked = this.checked);
});

document.querySelectorAll('.remove-url').forEach(btn => {
    btn.addEventListener('click', async function() {
        if (!confirm('Remove this URL from indexing queue?')) return;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = this.dataset.url;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    });
});
</script>
@endpush
@endsection