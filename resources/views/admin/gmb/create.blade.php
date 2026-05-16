@extends('admin.layout')
@section('title', 'Add GMB Account')
@section('page-title', 'Add GMB Account')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        {{-- OAuth Info Banner --}}
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="text-sm text-blue-800">
                    <p class="font-medium mb-1">Tip: Use OAuth for automatic token exchange</p>
                    <p>Instead of manually entering tokens, <a href="{{ route('admin.gmb-accounts.oauth.connect') }}" class="text-blue-600 underline font-medium">connect with Google</a> first, then save this account to automatically receive API tokens.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.gmb-accounts.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                <select name="domain_id" required class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Select domain</option>
                    @foreach($domains as $domain)
                        <option value="{{ $domain->id }}" {{ old('domain_id') == $domain->id ? 'selected' : '' }}>
                            {{ $domain->name }} ({{ $domain->domain }})
                        </option>
                    @endforeach
                </select>
                @error('domain_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                <input type="text" name="account_name" value="{{ old('account_name') }}" required
                       class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                       placeholder="e.g. PottyDirect Houston">
                @error('account_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Email</label>
                <input type="email" name="account_email" value="{{ old('account_email') }}"
                       class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                       placeholder="business@gmail.com">
                @error('account_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location ID</label>
                <input type="text" name="location_id" value="{{ old('location_id') }}"
                       class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                       placeholder="Found in GBP dashboard URL: /place/{locationId}/...">
                @error('location_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-gray-100 pt-5">
                <h4 class="font-medium text-gray-800 mb-3">API Tokens (or use OAuth above)</h4>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Access Token</label>
                        <textarea name="access_token" rows="2"
                                  class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm font-mono text-xs focus:border-emerald-500 focus:ring-emerald-500"
                                  placeholder="Paste access token here...">{{ old('access_token') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Refresh Token</label>
                        <textarea name="refresh_token" rows="2"
                                  class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm font-mono text-xs focus:border-emerald-500 focus:ring-emerald-500"
                                  placeholder="Paste refresh token here...">{{ old('refresh_token') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Token Expires At</label>
                        <input type="datetime-local" name="token_expires_at" value="{{ old('token_expires_at') }}"
                               class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-5 space-y-4">
                <h4 class="font-medium text-gray-800 mb-3">Automation Settings</h4>

                <label class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Active</span>
                        <p class="text-xs text-gray-400">Enable this GMB account for automation</p>
                    </div>
                </label>

                <label class="flex items-center gap-3">
                    <input type="checkbox" name="auto_post" value="1" {{ old('auto_post') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Auto-Post Blog Content</span>
                        <p class="text-xs text-gray-400">Automatically post new blog articles to Google Business Profile</p>
                    </div>
                </label>

                <label class="flex items-center gap-3">
                    <input type="checkbox" name="auto_reply_reviews" value="1" {{ old('auto_reply_reviews') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Auto-Reply to Reviews</span>
                        <p class="text-xs text-gray-400">Automatically reply to new Google reviews</p>
                    </div>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition min-h-[44px]">
                    Save GMB Account
                </button>
                <a href="{{ route('admin.gmb-accounts.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition min-h-[44px]">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
