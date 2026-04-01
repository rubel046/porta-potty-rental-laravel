@extends('admin.layout')
@section('title', 'Create Invoice')
@section('page-title', 'Create Invoice')

@section('content')
<form method="POST" action="{{ route('admin.invoices.store') }}" class="max-w-2xl space-y-6">
    @csrf
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
        <h2 class="font-bold text-gray-800 border-b pb-2">Invoice Details</h2>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Buyer *</label>
            <select name="buyer_id" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">Select Buyer</option>
                @foreach($buyers as $buyer)
                    <option value="{{ $buyer->id }}" {{ old('buyer_id') == $buyer->id ? 'selected' : '' }}>{{ $buyer->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Period Start *</label><input type="date" name="period_start" value="{{ old('period_start', now()->startOfMonth()->format('Y-m-d')) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Period End *</label><input type="date" name="period_end" value="{{ old('period_end', now()->endOfMonth()->format('Y-m-d')) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label><input type="date" name="due_date" value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Adjustments ($)</label><input type="number" step="0.01" name="adjustments" value="{{ old('adjustments', 0) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('notes') }}</textarea>
        </div>
    </div>
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
        <strong>Note:</strong> Invoice will be auto-generated with all billable calls for the selected buyer within the period.
    </div>
    <div class="flex gap-3">
        <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700">Create Invoice</button>
        <a href="{{ route('admin.invoices.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-300">Cancel</a>
    </div>
</form>
@endsection
