@extends('admin.layout')
@section('title', 'Create Invoice')
@section('page-title', 'Create Invoice')

@section('content')

    <div class="max-w-lg">
        <form method="POST" action="{{ route('admin.invoices.store') }}" class="card p-6">
            @csrf

            <div class="mb-6">
                <label class="form-label">Buyer *</label>
                <select name="buyer_id" class="form-input" required>
                    <option value="">Select Buyer</option>
                    @foreach($buyers as $buyer)
                        <option value="{{ $buyer->id }}" {{ old('buyer_id') == $buyer->id ? 'selected' : '' }}>
                            {{ $buyer->company_name }} — ${{ number_format($buyer->payout_per_call, 2) }}/call
                        </option>
                    @endforeach
                </select>
                @error('buyer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="form-label">Period Start *</label>
                    <input type="date" name="period_start" class="form-input"
                           value="{{ old('period_start', now()->startOfMonth()->format('Y-m-d')) }}" required>
                    @error('period_start') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Period End *</label>
                    <input type="date" name="period_end" class="form-input"
                           value="{{ old('period_end', now()->format('Y-m-d')) }}" required>
                    @error('period_end') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm">
                <p class="text-blue-800">
                    💡 The invoice will automatically include all <strong>billable calls</strong>
                    for the selected buyer within the specified date range.
                </p>
            </div>

            <button type="submit" class="btn-primary w-full">
                💵 Generate Invoice
            </button>

            <div class="mt-3 text-center">
                <a href="{{ route('admin.invoices.index') }}" class="text-sm text-gray-500">Cancel</a>
            </div>
        </form>
    </div>

@endsection
