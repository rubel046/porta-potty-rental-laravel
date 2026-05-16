@extends('admin.layout')
@section('title', 'Edit GMB Account')
@section('page-title', 'Edit GMB Account')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Edit Form --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            @if(session('gmb_oauth_code'))
                <div class="mb-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                        <div class="text-sm text-amber-800">
                            <p class="font-medium mb-1">OAuth code detected in session</p>
                            <p class="mb-2">Exchange it for API tokens for this account?</p>
                            <form method="POST" action="{{ route('admin.gmb-accounts.exchange-token') }}">
                                @csrf
                                <input type="hidden" name="account_id" value="{{ $gmbAccount->id }}">
                                <button type="submit" class="px-4 py-1.5 bg-amber-600 text-white rounded-lg text-sm font-medium hover:bg-amber-700 transition">
                                    Exchange Tokens
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.gmb-accounts.update', $gmbAccount) }}" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                    <select name="domain_id" required class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @foreach($domains as $domain)
                            <option value="{{ $domain->id }}" {{ old('domain_id', $gmbAccount->domain_id) == $domain->id ? 'selected' : '' }}>
                                {{ $domain->name }} ({{ $domain->domain }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                    <input type="text" name="account_name" value="{{ old('account_name', $gmbAccount->account_name) }}" required
                           class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Email</label>
                    <input type="email" name="account_email" value="{{ old('account_email', $gmbAccount->account_email) }}"
                           class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location ID</label>
                    <input type="text" name="location_id" value="{{ old('location_id', $gmbAccount->location_id) }}"
                           class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                           placeholder="Required for posting and review syncing">
                    @error('location_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <h4 class="font-medium text-gray-800 mb-3">API Tokens</h4>
                    <p class="text-xs text-gray-400 mb-3">
                        @if($gmbAccount->access_token)
                            <span class="text-emerald-600">✓ Tokens stored (encrypted).</span>
                        @else
                            <span class="text-amber-600">No tokens stored.</span>
                        @endif
                        Tokens are encrypted at rest. Update only if they expire.
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Access Token</label>
                            <textarea name="access_token" rows="2"
                                      class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm font-mono text-xs focus:border-emerald-500 focus:ring-emerald-500"
                                      placeholder="Leave blank to keep existing">{{ $decryptedAccess ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Refresh Token</label>
                            <textarea name="refresh_token" rows="2"
                                      class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm font-mono text-xs focus:border-emerald-500 focus:ring-emerald-500"
                                      placeholder="Leave blank to keep existing">{{ $decryptedRefresh ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Token Expires At</label>
                            <input type="datetime-local" name="token_expires_at"
                                   value="{{ old('token_expires_at', $gmbAccount->token_expires_at?->format('Y-m-d\TH:i')) }}"
                                   class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5 space-y-4">
                    <h4 class="font-medium text-gray-800 mb-3">Automation Settings</h4>

                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $gmbAccount->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                            <p class="text-xs text-gray-400">Enable this GMB account for automation</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="auto_post" value="1" {{ old('auto_post', $gmbAccount->auto_post) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Auto-Post Blog Content</span>
                            <p class="text-xs text-gray-400">Automatically post new blog articles to Google Business Profile</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="auto_reply_reviews" value="1" {{ old('auto_reply_reviews', $gmbAccount->auto_reply_reviews) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Auto-Reply to Reviews</span>
                            <p class="text-xs text-gray-400">Automatically reply to new Google reviews</p>
                        </div>
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition min-h-[44px]">
                        Update Account
                    </button>
                    <a href="{{ route('admin.gmb-accounts.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition min-h-[44px]">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Stats Sidebar --}}
    <div class="space-y-6">
        {{-- Account Stats --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h4 class="font-semibold text-gray-800 mb-4">Account Stats</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Status</span>
                    @if($gmbAccount->is_active)
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">Active</span>
                    @else
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">Inactive</span>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Posts</span>
                    <span class="text-sm font-medium">{{ $gmbAccount->total_posts_count }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Reviews</span>
                    <span class="text-sm font-medium">{{ $gmbAccount->total_reviews_count }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Unreplied Reviews</span>
                    <span class="text-sm font-medium {{ $gmbAccount->unread_reviews_count > 0 ? 'text-amber-600' : '' }}">{{ $gmbAccount->unread_reviews_count }}</span>
                </div>
            </div>
        </div>

        {{-- Token Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h4 class="font-semibold text-gray-800 mb-4">Token Status</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Access Token</span>
                    @if($gmbAccount->access_token)
                        <span class="text-emerald-600 text-xs font-medium">Stored ✓</span>
                    @else
                        <span class="text-red-500 text-xs font-medium">Missing</span>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Refresh Token</span>
                    @if($gmbAccount->refresh_token)
                        <span class="text-emerald-600 text-xs font-medium">Stored ✓</span>
                    @else
                        <span class="text-amber-500 text-xs font-medium">Missing</span>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Expires</span>
                    <span class="text-xs {{ $gmbAccount->token_expires_at && $gmbAccount->token_expires_at->isFuture() ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $gmbAccount->token_expires_at ? $gmbAccount->token_expires_at->diffForHumans() : 'N/A' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Location ID</span>
                    <span class="text-xs {{ $gmbAccount->location_id ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $gmbAccount->location_id ? 'Set ✓' : 'Missing' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h4 class="font-semibold text-gray-800 mb-4">Quick Actions</h4>
            <div class="space-y-3">
                <a href="{{ route('admin.gmb-accounts.oauth.connect') }}"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                    Re-connect with Google
                </a>
                <form method="POST" action="{{ route('admin.gmb-accounts.sync', $gmbAccount) }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-blue-200 text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Full Sync Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Recent Posts History --}}
@if(isset($recentPosts) && $recentPosts->count() > 0)
<div class="mt-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h4 class="font-semibold text-gray-800">Post History</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/50 text-left text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 font-medium">Type</th>
                        <th class="px-6 py-3 font-medium">Content</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentPosts as $post)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-3">
                                <span class="text-xs font-medium uppercase {{ $post->type === 'review_reply' ? 'text-purple-600' : 'text-blue-600' }}">
                                    {{ $post->type === 'review_reply' ? 'Reply' : 'Post' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-600 max-w-md truncate">{{ $post->content }}</td>
                            <td class="px-6 py-3">
                                @if($post->status === 'published')
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">Published</span>
                                @elseif($post->status === 'failed')
                                    <span class="px-2 py-0.5 bg-red-50 text-red-700 rounded-full text-xs font-medium">Failed</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-xs text-gray-400">{{ $post->created_at->format('M j, Y g:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
