@extends('admin.layout')
@section('title', 'Phone Numbers')
@section('page-title', 'Phone Numbers')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <form method="GET" class="flex gap-2 flex-wrap">
        <select name="city_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All Cities</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
            @endforeach
        </select>
        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    </form>
    <a href="{{ route('admin.phone-numbers.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">+ Add Number</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
            <tr><th class="px-6 py-3">Number</th><th class="px-6 py-3">City</th><th class="px-6 py-3">Area Code</th><th class="px-6 py-3">Total Calls</th><th class="px-6 py-3">Active</th><th class="px-6 py-3">Actions</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($phoneNumbers as $number)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-mono">{{ $number->number }}</td>
                    <td class="px-6 py-3">{{ $number->city?->name ?? '—' }}</td>
                    <td class="px-6 py-3">{{ $number->area_code }}</td>
                    <td class="px-6 py-3">{{ number_format($number->total_calls) }}</td>
                    <td class="px-6 py-3">@if($number->is_active)<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>@else<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>@endif</td>
                    <td class="px-6 py-3 flex gap-2">
                        <a href="{{ route('admin.phone-numbers.show', $number) }}" class="text-blue-600 hover:text-blue-800 text-xs">View</a>
                        <a href="{{ route('admin.phone-numbers.edit', $number) }}" class="text-green-600 hover:text-green-800 text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.phone-numbers.destroy', $number) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-800 text-xs">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No phone numbers found</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $phoneNumbers->links() }}</div>
</div>
@endsection
