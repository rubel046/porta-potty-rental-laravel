@extends('admin.layout')
@section('title', 'Edit State')
@section('page-title', "Edit: {$state->name}")

@section('content')
<form method="POST" action="{{ route('admin.global.states.update', $state) }}" class="max-w-2xl">
    @csrf
    @method('PUT')
    
    <div class="mb-6">
        <a href="{{ route('admin.global.states.index') }}" class="text-green-600 hover:text-green-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to States
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">State Details</h3>
        
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">State Name *</label>
                <input type="text" name="name" value="{{ old('name', $state->name) }}" required
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-2.5 border">
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">State Code *</label>
                    <input type="text" name="code" value="{{ old('code', $state->code) }}" required maxlength="2" placeholder="FL"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-2.5 border uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug *</label>
                    <input type="text" name="slug" value="{{ old('slug', $state->slug) }}" required placeholder="florida"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-2.5 border">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Timezone</label>
                <input type="text" name="timezone" value="{{ old('timezone', $state->timezone) }}" placeholder="America/New_York"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-2.5 border">
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-100 flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">
                Update State
            </button>
            <a href="{{ route('admin.global.states.index') }}" class="px-6 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg font-medium">
                Cancel
            </a>
        </div>
    </div>
</form>
@endsection