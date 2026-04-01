@extends('layouts.admin')
@section('page_title', isset($buyer) ? "Edit: {$buyer->company_name}" : 'Add Buyer')

@section('content')

    <div class="max-w-3xl">
        <form method="POST"
              action="{{ isset($buyer) ? route('admin.buyers.update', $buyer) : route('admin.buyers.store') }}"
              class="card p-6">
            @csrf
            @if(isset($buyer)) @method('PUT') @endif

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Company Name *</label>
                    <input type="text" name="company_name" class="form-input"
                           value="{{ old('company_name', $buyer->company_name ?? '') }}" required>
                    @error('company_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Contact Name *</label>
                    <input type="text" name="contact_name" class="form-input"
                           value="{{ old('contact_name', $buyer->contact_name ?? '') }}" required>
                </div>

                <div>
                    <label class="form-label">Phone Number *</label>
                    <input type="text" name="phone" class="form-input"
                           value="{{ old('phone', $buyer->phone ?? '') }}"
                           placeholder="+17135551234" required>
                </div>

                <div>
                    <label class="form-label">Backup Phone</label>
                    <input type="text" name="backup_phone" class="form-input"
                           value="{{ old('backup_phone', $buyer->backup_phone ?? '') }}">
                </div>

                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input"
                           value="{{ old('email', $buyer->email ?? '') }}">
                </div>

                <div>
                    <label class="form-label">Payout Per Call ($) *</label>
                    <input type="number" name="payout_per_call" step="0.01" min="1" class="form-input"
                           value="{{ old('payout_per_call', $buyer->payout_per_call ?? '10.00') }}" required>
                </div>

                <div>
                    <label class="form-label">Daily Call Cap *</label>
                    <input type="number" name="daily_call_cap" min="1" max="100" class="form-input"
                           value="{{ old('daily_call_cap', $buyer->daily_call_cap ?? 20) }}" required>
                </div>

                <div>
                    <label class="form-label">Monthly Call Cap *</label>
                    <input type="number" name="monthly_call_cap" min="1" max="5000" class="form-input"
                           value="{{ old('monthly_call_cap', $buyer->monthly_call_cap ?? 500) }}" required>
                </div>

                <div>
                    <label class="form-label">Timezone *</label>
                    <select name="timezone" class="form-input" required>
                        @foreach(['America/New_York'=>'Eastern','America/Chicago'=>'Central','America/Denver'=>'Mountain','America/Los_Angeles'=>'Pacific'] as $tz => $label)
                            <option value="{{ $tz }}" {{ old('timezone', $buyer->timezone ?? 'America/Chicago') === $tz ? 'selected' : '' }}>
                                {{ $label }} ({{ $tz }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Ring Timeout (seconds) *</label>
                    <input type="number" name="ring_timeout" min="10" max="60" class="form-input"
                           value="{{ old('ring_timeout', $buyer->ring_timeout ?? 25) }}" required>
                </div>

                <div>
                    <label class="form-label">Priority (1=highest) *</label>
                    <input type="number" name="priority" min="1" max="10" class="form-input"
                           value="{{ old('priority', $buyer->priority ?? 1) }}" required>
                </div>

                @if(isset($buyer))
                    <div class="flex items-center gap-2 pt-6">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active"
                               {{ old('is_active', $buyer->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300">
                        <label for="is_active" class="text-sm">Active</label>
                    </div>
                @endif
            </div>

            <div class="grid md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="form-label">Business Hours Start</label>
                    <input type="time" name="business_hours_start" class="form-input"
                           value="{{ old('business_hours_start', $buyer->business_hours['start'] ?? '07:00') }}">
                </div>
                <div>
                    <label class="form-label">Business Hours End</label>
                    <input type="time" name="business_hours_end" class="form-input"
                           value="{{ old('business_hours_end', $buyer->business_hours['end'] ?? '20:00') }}">
                </div>
            </div>

            <div class="mt-6">
                <label class="form-label">Notes</label>
                <textarea name="notes" rows="3" class="form-input">{{ old('notes', $buyer->notes ?? '') }}</textarea>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn-primary">
                    {{ isset($buyer) ? '💾 Update Buyer' : '➕ Add Buyer' }}
                </button>
                <a href="{{ route('admin.buyers.index') }}" class="text-gray-500 text-sm">Cancel</a>
            </div>
        </form>
    </div>

@endsection
