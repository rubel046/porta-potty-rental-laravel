@extends('admin.layout')
@section('title', 'States')
@section('page-title', 'States Management')

@section('content')
<div class="mb-6">
    <div>
        <h2 class="text-lg font-semibold text-gray-800">All States</h2>
        <p class="text-sm text-gray-500">Manage your state landing pages and SEO content</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50 text-left text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-medium">State</th>
                    <th class="px-6 py-4 font-medium">Code</th>
                    <th class="px-6 py-4 font-medium">Cities</th>
                    <th class="px-6 py-4 font-medium">Content</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($states as $state)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900">{{ $state->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $state->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600">{{ $state->active_cities_count }} active / {{ $state->cities_count }} total</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($state->hasContent())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $state->word_count ?? 0 }} words
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    No content
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($state->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.states.edit', $state) }}" 
                                   class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition" 
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <a href="{{ url('/porta-potty-rental-' . $state->slug) }}" 
                                   target="_blank"
                                   class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                   title="View Page">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No states found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
