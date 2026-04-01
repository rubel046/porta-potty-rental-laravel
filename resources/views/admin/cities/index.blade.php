@extends('admin.layout')
@section('title', 'Cities')
@section('page-title', 'Cities')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <form method="GET" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search city..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
        <select name="state_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All States</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    </form>
    <a href="{{ route('admin.cities.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">+ Add City</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
            <tr>
                <th class="px-6 py-3">City</th>
                <th class="px-6 py-3">State</th>
                <th class="px-6 py-3">Pages</th>
                <th class="px-6 py-3">Active</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($cities as $city)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium">{{ $city->name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $city->state?->name ?? '—' }}</td>
                    <td class="px-6 py-3">{{ $city->service_pages_count }}</td>
                    <td class="px-6 py-3">
                        @if($city->is_active)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 flex gap-2">
                        <a href="{{ route('admin.cities.show', $city) }}" class="text-blue-600 hover:text-blue-800 text-xs">View</a>
                        <a href="{{ route('admin.cities.edit', $city) }}" class="text-green-600 hover:text-green-800 text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-800 text-xs">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No cities found</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $cities->links() }}</div>
</div>
@endsection
