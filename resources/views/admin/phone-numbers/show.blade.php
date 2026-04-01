@extends('admin.layout')
@section('title', $phoneNumber->number)
@section('page-title', $phoneNumber->number)

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <dl class="grid grid-cols-2 gap-3 text-sm mb-4">
        <div><dt class="text-gray-500">Number</dt><dd class="font-mono font-medium">{{ $phoneNumber->number }}</dd></div>
        <div><dt class="text-gray-500">Friendly Name</dt><dd>{{ $phoneNumber->friendly_name ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Area Code</dt><dd>{{ $phoneNumber->area_code }}</dd></div>
        <div><dt class="text-gray-500">City</dt><dd>{{ $phoneNumber->city?->name ?? '—' }}</dd></div>
        <div><dt class="text-gray-500">Provider</dt><dd class="capitalize">{{ $phoneNumber->provider }}</dd></div>
        <div><dt class="text-gray-500">Total Calls</dt><dd>{{ number_format($phoneNumber->total_calls) }}</dd></div>
        <div><dt class="text-gray-500">Status</dt><dd>@if($phoneNumber->is_active)<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>@else<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>@endif</dd></div>
    </dl>
    <div class="flex gap-2">
        <a href="{{ route('admin.phone-numbers.edit', $phoneNumber) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
    </div>
</div>
@endsection
