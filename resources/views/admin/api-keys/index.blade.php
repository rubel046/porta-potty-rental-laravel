@extends('admin.layout')
@section('title', 'AI API Keys')
@section('page-title', 'AI API Keys')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">API Keys</h2>
            <p class="text-sm text-gray-500 mt-1">Manage multiple AI API keys for content generation</p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="window.location.reload()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh
            </button>
            <form method="POST" action="{{ route('admin.api-keys.reset-all') }}" onsubmit="return confirm('Reset all API keys? This will clear all cooldowns and usage stats.');">
                @csrf
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm hover:bg-orange-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Reset All
                </button>
            </form>
            <a href="{{ route('admin.api-keys.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Key
            </a>
        </div>
    </div>



    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provider</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name / Model</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">API Key</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Daily Usage</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Failures</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
            <tbody class="divide-y divide-gray-50">
                    @forelse($apiKeys as $key)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @php
                                $providerColors = [
                                    'groq' => 'bg-purple-100 text-purple-700',
                                    'claude' => 'bg-orange-100 text-orange-700',
                                    'gemini' => 'bg-blue-100 text-blue-700',
                                    'openai' => 'bg-green-100 text-green-700',
                                ];
                                $providerColor = $providerColors[$key->provider] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $providerColor }}">
                                {{ strtoupper($key->provider) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800">{{ $key->name ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $key->model }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $key->masked_key }}</code>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-gray-700">{{ $key->priority }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $tokenPct = $key->tokens_percentage;
                                $reqPct = $key->requests_percentage;
                                $barColor = $tokenPct > 80 ? 'bg-red-500' : ($tokenPct > 50 ? 'bg-yellow-500' : 'bg-green-500');
                                
                                $formatNum = function($num) {
                                    if ($num >= 1000000) {
                                        return round($num / 1000000, 1) . 'M';
                                    } elseif ($num >= 1000) {
                                        return round($num / 1000, 1) . 'K';
                                    }
                                    return $num;
                                };
                            @endphp
                            <div class="space-y-2">
                                <div>
                                    <div class="flex justify-between items-center text-xs mb-1">
                                        <span class="text-gray-500">Tokens</span>
                                        <span class="font-medium text-gray-700">{{ $formatNum($key->tokens_used_today) }} / {{ $formatNum($key->token_limit) }}</span>
                                    </div>
                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full {{ $barColor }} rounded-full transition-all" style="width: {{ min($tokenPct, 100) }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center text-xs mb-1">
                                        <span class="text-gray-500">Requests</span>
                                        <span class="font-medium text-gray-700">{{ $formatNum($key->requests_today) }} / {{ $formatNum($key->request_limit) }}</span>
                                    </div>
                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full {{ $reqPct > 80 ? 'bg-red-500' : ($reqPct > 50 ? 'bg-yellow-500' : 'bg-green-500') }} rounded-full transition-all" style="width: {{ min($reqPct, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($key->isDailyLimitReached())
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    Daily Limit
                                </span>
                            @elseif($key->isInCooldown())
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                    Cooling Down
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $key->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $key->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($key->failure_count > 0)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    {{ $key->failure_count }}
                                </span>
                            @else
                                <span class="text-gray-400">0</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.api-keys.reset', $key) }}" onsubmit="return confirm('Reset usage stats for this API key?\n\nThis will clear:\n- Cooldown status\n- Tokens used today\n- Requests used today\n- Failure count\n\nThe key will be ready to use again.');">
                                    @csrf
                                    <button type="submit" class="p-1 hover:bg-gray-100 rounded text-orange-500" title="Reset usage stats">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.api-keys.toggle', $key) }}">
                                    @csrf
                                    <button type="submit" class="p-1 hover:bg-gray-100 rounded" title="{{ $key->is_active ? 'Disable' : 'Enable' }}">
                                        @if($key->is_active)
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        @else
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </button>
                                </form>
                                <a href="{{ route('admin.api-keys.edit', $key) }}" class="p-1 hover:bg-gray-100 rounded text-indigo-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.api-keys.destroy', $key) }}" onsubmit="return confirm('Are you sure you want to delete this API key?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 hover:bg-gray-100 rounded text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            No API keys configured. Add your first AI API key to start generating content.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
