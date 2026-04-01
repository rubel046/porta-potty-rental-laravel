@extends('admin.layout')
@section('title', 'Buyers')
@section('page-title', 'Buyers')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search company..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Filter</button>
    </form>
    <a href="{{ route('admin.buyers.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">+ Add Buyer</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wider">
            <tr>
                <th class="px-6 py-3">Company</th>
                <th class="px-6 py-3">Contact</th>
                <th class="px-6 py-3">Payout/Call</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Calls Today</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($buyers as $buyer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium">{{ $buyer->company_name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $buyer->contact_name }}<br><span class="text-xs">{{ $buyer->phone }}</span></td>
                    <td class="px-6 py-3">${{ number_format($buyer->payout_per_call, 2) }}</td>
                    <td class="px-6 py-3">
                        @if($buyer->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-3">{{ $buyer->today_calls }}</td>
                    <td class="px-6 py-3 flex gap-2">
                        <a href="{{ route('admin.buyers.show', $buyer) }}" class="text-blue-600 hover:text-blue-800 text-xs">View</a>
                        <a href="{{ route('admin.buyers.edit', $buyer) }}" class="text-green-600 hover:text-green-800 text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.buyers.destroy', $buyer) }}" onsubmit="return confirm('Delete this buyer?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No buyers found</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $buyers->links() }}</div>
</div>
@endsection
