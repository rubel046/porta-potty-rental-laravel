@extends('admin.layout')
@section('title', 'GMB Accounts')
@section('page-title', 'Google Business Profile Accounts')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <p class="text-sm text-gray-500">Manage your Google Business Profile integrations — auto-post blog content, fetch & reply to reviews</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.gmb-accounts.oauth.connect') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition min-h-[44px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
            Connect with Google
        </a>
        <a href="{{ route('admin.gmb-accounts.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition min-h-[44px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add GMB Account
        </a>
    </div>
</div>

<div class="space-y-6">
    @forelse($accounts as $account)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-xl shadow-sm">
                            G
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $account->account_name }}</h3>
                            @if($account->account_email)
                                <p class="text-sm text-gray-400">{{ $account->account_email }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-0.5">Domain: {{ $account->domain->name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($account->is_active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-medium">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Stats Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-gray-800">{{ $account->total_posts_count }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">Total Posts</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-gray-800">{{ $account->total_reviews_count }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">Total Reviews</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold {{ $account->unread_reviews_count > 0 ? 'text-amber-600' : 'text-gray-800' }}">{{ $account->unread_reviews_count }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">Unreplied</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-sm font-medium text-gray-800">
                            @if($account->auto_post)
                                <span class="text-emerald-600">Enabled</span>
                            @else
                                <span class="text-gray-400">Disabled</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-400 mt-0.5">Auto Post</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-sm font-medium text-gray-800">
                            @if($account->auto_reply_reviews)
                                <span class="text-emerald-600">Enabled</span>
                            @else
                                <span class="text-gray-400">Disabled</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-400 mt-0.5">Auto Reply</div>
                    </div>
                </div>

                {{-- Last Activity --}}
                <div class="flex flex-wrap gap-4 text-xs text-gray-400 mb-4">
                    @if($account->last_posted_at)
                        <span>Last post: {{ $account->last_posted_at->diffForHumans() }}</span>
                    @endif
                    @if($account->last_review_sync_at)
                        <span>Reviews synced: {{ $account->last_review_sync_at->diffForHumans() }}</span>
                    @endif
                    @if($account->last_synced_at)
                        <span>Last full sync: {{ $account->last_synced_at->diffForHumans() }}</span>
                    @else
                        <span class="text-amber-500">Never synced</span>
                    @endif
                </div>

                {{-- Recent Posts --}}
                @if($account->gmbPosts && $account->gmbPosts->count() > 0)
                    <div class="border-t border-gray-100 pt-3 mb-4">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Recent Activity</div>
                        <div class="space-y-1.5">
                            @foreach($account->gmbPosts->take(5) as $post)
                                <div class="flex items-center gap-2 text-xs">
                                    @if($post->status === 'published')
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full flex-shrink-0"></span>
                                    @elseif($post->status === 'failed')
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full flex-shrink-0"></span>
                                    @else
                                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full flex-shrink-0"></span>
                                    @endif
                                    <span class="text-gray-500 uppercase text-2xs">{{ $post->type === 'review_reply' ? 'Reply' : 'Post' }}</span>
                                    <span class="text-gray-700 truncate max-w-[300px]">{{ Str::limit($post->content, 80) }}</span>
                                    <span class="text-gray-400 ml-auto flex-shrink-0">{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                    <div class="text-xs text-gray-400">
                        Location ID: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">{{ $account->location_id ?? '—' }}</code>
                    </div>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('admin.gmb-accounts.toggle', $account) }}">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg border transition min-h-[36px] {{ $account->is_active ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                                {{ $account->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.gmb-accounts.sync', $account) }}">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition min-h-[36px]">
                                Sync Now
                            </button>
                        </form>
                        <a href="{{ route('admin.gmb-accounts.edit', $account) }}"
                           class="px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition min-h-[36px]">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.gmb-accounts.destroy', $account) }}"
                              onsubmit="return confirm('Delete this GMB account?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition min-h-[36px]">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-12 text-center text-gray-400">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    <div>
                        <p class="font-medium text-gray-500">No GMB accounts yet</p>
                        <p class="text-sm mt-1">Connect your Google Business Profile to auto-post blog content and manage reviews.</p>
                    </div>
                    <div class="flex gap-3 mt-2">
                        <a href="{{ route('admin.gmb-accounts.oauth.connect') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                            Connect with Google
                        </a>
                        <a href="{{ route('admin.gmb-accounts.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                            Add Manually
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($accounts->hasPages())
    <div class="mt-6">
        {{ $accounts->links() }}
    </div>
@endif
@endsection
