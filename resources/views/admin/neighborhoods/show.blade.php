@extends('admin.layout')
@section('title', $neighborhood->name)
@section('page-title', $neighborhood->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.neighborhoods.index') }}" class="text-sm text-blue-600 hover:text-blue-700">
        ← Back to Neighborhoods
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Neighborhood Info --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Neighborhood Details</h3>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-400">Name</dt>
                    <dd class="font-medium">{{ $neighborhood->name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">City</dt>
                    <dd class="font-medium">{{ $neighborhood->city->name }}, {{ $neighborhood->city->state->code }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Slug</dt>
                    <dd class="font-mono text-xs">{{ $neighborhood->slug }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">Type</dt>
                    <dd class="font-medium">{{ $neighborhood->neighborhood_type ?? '—' }}</dd>
                </div>
                @if($neighborhood->latitude)
                    <div>
                        <dt class="text-gray-400">Coordinates</dt>
                        <dd class="font-mono text-xs">{{ $neighborhood->latitude }}, {{ $neighborhood->longitude }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-gray-400">Status</dt>
                    <dd>
                        <form method="POST" action="{{ route('admin.neighborhoods.toggle', $neighborhood) }}">
                            @csrf
                            <button type="submit"
                                    class="px-2 py-0.5 rounded-full text-xs font-medium border transition
                                    {{ $neighborhood->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                                {{ $neighborhood->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </dd>
                </div>
            </dl>
            @if($neighborhood->description)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <dt class="text-xs text-gray-400 mb-1">Description</dt>
                    <dd class="text-sm text-gray-600">{{ $neighborhood->description }}</dd>
                </div>
            @endif
        </div>

        {{-- Service Pages --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Service Pages ({{ $neighborhood->servicePages->count() }})</h3>
                @if($neighborhood->servicePages->isEmpty())
                    <form method="POST" action="{{ route('admin.neighborhoods.generate', $neighborhood) }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Generate All Types
                        </button>
                    </form>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/50 text-left text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 font-medium">Type</th>
                            <th class="px-6 py-3 font-medium">Slug</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium">Words</th>
                            <th class="px-6 py-3 font-medium">Preview</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($neighborhood->servicePages as $sp)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-3 font-medium text-gray-800">{{ $domain?->getServiceTypeLabel($sp->service_type) ?? $sp->service_type }}</td>
                                <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $sp->slug }}</td>
                                <td class="px-6 py-3">
                                    @if($sp->is_published)
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">Published</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">{{ $sp->generation_status ?? 'Draft' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ number_format($sp->word_count) }}</td>
                                <td class="px-6 py-3">
                                    <a href="{{ url("neighborhoods/{$sp->slug}") }}" target="_blank"
                                       class="text-blue-600 hover:text-blue-700 text-xs font-medium">
                                        View →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                    <p>No service pages yet. Click "Generate All Types" above.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h4 class="font-semibold text-gray-800 mb-3">Actions</h4>
            <div class="space-y-3">
                <form method="POST" action="{{ route('admin.neighborhoods.generate', $neighborhood) }}">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        Generate Content
                    </button>
                </form>
                <a href="{{ $neighborhood->url }}" target="_blank"
                   class="w-full flex items-center justify-center px-4 py-2 border border-gray-200 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                    View Public Page
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
