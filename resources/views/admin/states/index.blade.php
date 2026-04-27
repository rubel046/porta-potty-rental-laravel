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
                    <th class="px-6 py-4 font-medium">Views</th>
                    <th class="px-6 py-4 font-medium">Content</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium">Generated</th>
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
                            <span class="@if($state->views > 100) text-green-600 font-medium @else text-gray-500 @endif">
                                {{ number_format($state->views ?? 0) }}
                            </span>
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
                            @if($domain && isset($state->domain_status))
                                @if($state->domain_status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            @else
                                @if($state->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if(isset($state->domain_state) && $state->domain_state)
                                @if($state->domain_state->generation_status === 'success')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Generated
                                    </span>
                                @elseif($state->domain_state->generation_status === 'processing')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Processing
                                    </span>
                                @elseif($state->domain_state->generation_status === 'failed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800" title="{{ $state->domain_state->generation_error }}">
                                        Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Pending
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form method="POST" action="{{ route('admin.states.toggle-status', $state) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg transition {{ ($domain && isset($state->domain_status) && $state->domain_status) || (!isset($state->domain_status) && $state->is_active) ? 'text-gray-400 hover:text-yellow-600 hover:bg-yellow-50' : 'text-gray-400 hover:text-green-600 hover:bg-green-50' }}" title="{{ ($domain && isset($state->domain_status) && $state->domain_status) || (!isset($state->domain_status) && $state->is_active) ? 'Deactivate' : 'Activate' }}">
                                        @if(($domain && isset($state->domain_status) && $state->domain_status) || (!isset($state->domain_status) && $state->is_active))
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </button>
                                </form>
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
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No states found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
