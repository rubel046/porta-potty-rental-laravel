@extends('admin.layout')
@section('title', 'Edit Buyer')
@section('page-title', 'Edit Buyer')

@section('content')
<form method="POST" action="{{ route('admin.buyers.update', $buyer) }}" class="max-w-2xl space-y-6">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="font-bold text-gray-800 border-b pb-2">Basic Info</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                <input type="text" name="company_name" value="{{ old('company_name', $buyer->company_name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Name *</label>
                <input type="text" name="contact_name" value="{{ old('contact_name', $buyer->contact_name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                <input type="text" name="phone" value="{{ old('phone', $buyer->phone) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Backup Phone</label>
                <input type="text" name="backup_phone" value="{{ old('backup_phone', $buyer->backup_phone) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $buyer->email) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="font-bold text-gray-800 border-b pb-2">Payout & Limits</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Payout Per Call ($)</label><input type="number" step="0.01" name="payout_per_call" value="{{ old('payout_per_call', $buyer->payout_per_call) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Daily Call Cap</label><input type="number" name="daily_call_cap" value="{{ old('daily_call_cap', $buyer->daily_call_cap) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Monthly Call Cap</label><input type="number" name="monthly_call_cap" value="{{ old('monthly_call_cap', $buyer->monthly_call_cap) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Concurrent Limit</label><input type="number" name="concurrent_call_limit" value="{{ old('concurrent_call_limit', $buyer->concurrent_call_limit) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Ring Timeout (s)</label><input type="number" name="ring_timeout" value="{{ old('ring_timeout', $buyer->ring_timeout) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Priority</label><input type="number" name="priority" value="{{ old('priority', $buyer->priority) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label><input type="text" name="timezone" value="{{ old('timezone', $buyer->timezone) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div class="flex items-center gap-2 pt-4"><input type="checkbox" name="is_active" value="1" {{ $buyer->is_active ? 'checked' : '' }} class="w-4 h-4"><label class="text-sm">Active</label></div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="font-bold text-gray-800 border-b pb-2">Notes</h2>
        <textarea name="notes" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('notes', $buyer->notes) }}</textarea>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700">Save Changes</button>
        <a href="{{ route('admin.buyers.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-300">Cancel</a>
    </div>
</form>
@endsection
