@extends('admin.layout')
@section('title', 'Neighborhoods')
@section('page-title', 'Neighborhood Service Pages')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <p class="text-sm text-gray-500">
            {{ $totalNeighborhoods }} total neighborhoods —
            <span class="text-emerald-600 font-medium">{{ $withContent }}</span> have published content
        </p>
    </div>
    <div class="flex gap-3">
        <form method="POST" action="{{ route('admin.neighborhoods.bulk-generate') }}" class="inline">
            @csrf
            <input type="hidden" name="limit" value="20">
            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition min-h-[44px]">
                Generate Next 20
            </button>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 text-left text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-medium">Neighborhood</th>
                    <th class="px-6 py-4 font-medium">City</th>
                    <th class="px-6 py-4 font-medium">State</th>
                    <th class="px-6 py-4 font-medium text-center">Pages</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($neighborhoods as $nb)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $nb->name }}</div>
                            @if($nb->description)
                                <div class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ Str::limit($nb->description, 80) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $nb->city->name }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $nb->city->state->code }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-medium">{{ $nb->service_pages_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($nb->service_pages_count > 0)
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">Content Ready</span>
                            @else
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.neighborhoods.show', $nb) }}"
                                   class="px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition min-h-[36px]">
                                    View
                                </a>
                                @if($nb->service_pages_count === 0)
                                    <form method="POST" action="{{ route('admin.neighborhoods.generate', $nb) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition min-h-[36px]">
                                            Generate
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <p class="font-medium">No neighborhoods yet</p>
                            <p class="text-sm mt-1">Run <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">php artisan neighborhoods:seed</code> to import neighborhoods from Wikipedia.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($neighborhoods->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $neighborhoods->links() }}
        </div>
    @endif
</div>
@endsection
