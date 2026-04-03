@extends('admin.layout')
@section('title', $city->name)
@section('page-title', $city->name)

@section('content')
<div class="grid lg:grid-cols-4 gap-6">
    <div class="lg:col-span-3 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-800">Service Pages</h2>
                <div class="flex gap-2">
                    @if($city->servicePages->count() > 0)
                        <button type="submit" form="bulk-delete-form" onclick="return confirm('Delete selected pages?')" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed" id="delete-selected-btn" disabled>
                            Delete Selected
                        </button>
                    @endif
                    <form method="POST" action="{{ route('admin.cities.generate-pages', $city) }}">
                        @csrf
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">Generate Content</button>
                    </form>
                </div>
            </div>
            <form id="bulk-delete-form" method="POST" action="{{ route('admin.service-pages.bulk-destroy') }}">
                @csrf
                @method('DELETE')
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-xs text-gray-500 uppercase"><th class="pb-2 w-8"><input type="checkbox" id="select-all"></th><th class="pb-2">Type</th><th class="pb-2">Slug</th><th class="pb-2">Views</th><th class="pb-2">Calls</th></tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($city->servicePages as $page)
                            <tr>
                                <td class="py-2"><input type="checkbox" name="page_ids[]" value="{{ $page->id }}" class="page-checkbox rounded"></td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $page->service_type }}</span>
                                </td>
                                <td class="py-2 text-xs font-mono">{{ Str::limit($page->slug, 30) }}</td>
                                <td class="py-2">{{ number_format($page->views) }}</td>
                                <td class="py-2">{{ number_format($page->calls_generated) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-center text-gray-400">No pages generated yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
                @if($city->servicePages->count() > 0)
                    <div class="mt-4 text-sm text-gray-500">
                        {{ $city->servicePages->count() }} pages
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4">City Details</h2>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-500">City</dt><dd class="font-medium">{{ $city->name }}</dd></div>
                <div><dt class="text-gray-500">State</dt><dd class="font-medium">{{ $city->state?->name }}</dd></div>
                <div><dt class="text-gray-500">Population</dt><dd>{{ $city->population ? number_format($city->population) : '—' }}</dd></div>
                <div><dt class="text-gray-500">Area Codes</dt><dd>{{ $city->area_codes ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Status</dt>
                    <dd>@if($city->is_active)<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>@else<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>@endif</dd>
                </div>
            </dl>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('admin.cities.edit', $city) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-gray-800">Phone Numbers</h2>
            </div>
            @forelse($city->phoneNumbers as $number)
                <div class="p-3 bg-gray-50 rounded-lg mb-2">
                    <div class="font-mono text-sm">{{ $number->number }}</div>
                    <div class="text-xs text-gray-500">{{ $number->total_calls }} calls</div>
                </div>
            @empty
                <p class="text-gray-400 text-sm">No phone numbers assigned</p>
            @endforelse
            <a href="{{ route('admin.phone-numbers.index', ['city_id' => $city->id]) }}" class="text-blue-600 text-sm hover:text-blue-700">Manage Numbers →</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.page-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateDeleteButton();
    });

    document.querySelectorAll('.page-checkbox').forEach(cb => {
        cb.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const checked = document.querySelectorAll('.page-checkbox:checked');
        const btn = document.getElementById('delete-selected-btn');
        btn.disabled = checked.length === 0;
        btn.textContent = checked.length > 0 ? 'Delete Selected (' + checked.length + ')' : 'Delete Selected';
    }
</script>
@endsection
