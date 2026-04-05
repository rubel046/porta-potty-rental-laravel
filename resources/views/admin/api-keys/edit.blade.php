@extends('admin.layout')
@section('title', 'Edit AI API Key')
@section('page-title', 'Edit AI API Key')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.api-keys.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to API Keys
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.api-keys.update', $apiKey) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Provider</label>
                <select name="provider" id="provider" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($providers as $key => $label)
                        <option value="{{ $key }}" {{ $apiKey->provider == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('provider')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                <input type="password" name="api_key" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="sk-..." value="{{ $apiKey->api_key }}">
                <p class="text-xs text-gray-500 mt-1">Leave as-is to keep current key, or enter new value to update.</p>
                @error('api_key')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                <select name="model" id="model" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($models as $key => $label)
                        <option value="{{ $key }}" {{ $apiKey->model == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <p id="model-hint" class="text-xs text-gray-500 mt-1"></p>
                @error('model')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name (optional)</label>
                <input type="text" name="name" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="My Groq Key 1" value="{{ $apiKey->name }}">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <input type="number" name="priority" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="100" value="{{ $apiKey->priority }}" min="1" max="999">
                <p class="text-xs text-gray-500 mt-1">Lower number = higher priority. Keys will be tried in priority order.</p>
                @error('priority')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $apiKey->is_active ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                    Update API Key
                </button>
                <a href="{{ route('admin.api-keys.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const providerModels = {
    groq: {
        'llama-3.3-70b-versatile': { name: 'Llama 3.3 70B (Production)', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'llama-3.1-8b-instant': { name: 'Llama 3.1 8B Instant (Fast)', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'qwen/qwen3-32b': { name: 'Qwen3 32B (Preview)', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'meta-llama/llama-4-scout-17b-16e-instruct': { name: 'Llama 4 Scout 17B', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'moonshotai/kimi-k2-instruct': { name: 'Kimi K2 Instruct', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'openai/gpt-oss-120b': { name: 'GPT OSS 120B', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'openai/gpt-oss-20b': { name: 'GPT OSS 20B', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'groq/compound': { name: 'Compound', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'groq/compound-mini': { name: 'Compound Mini', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
    },
    claude: {
        'claude-3-5-sonnet-20241022': { name: 'Claude 3.5 Sonnet', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'claude-3-opus-20240229': { name: 'Claude 3 Opus', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'claude-3-haiku-20240307': { name: 'Claude 3 Haiku', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
    },
    gemini: {
        'gemini-2.0-flash': { name: 'Gemini 2.0 Flash', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'gemini-1.5-pro': { name: 'Gemini 1.5 Pro', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'gemini-1.5-flash': { name: 'Gemini 1.5 Flash', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
    },
    openai: {
        'gpt-4o': { name: 'GPT-4o', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'gpt-4o-mini': { name: 'GPT-4o Mini', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
        'gpt-4-turbo': { name: 'GPT-4 Turbo', tokenLimit: '2M tokens/day', requestLimit: '20K requests/day' },
    },
};

document.getElementById('provider').addEventListener('change', function() {
    const provider = this.value;
    const modelSelect = document.getElementById('model');
    const hint = document.getElementById('model-hint');
    modelSelect.innerHTML = '<option value="">Select Model</option>';
    hint.classList.add('hidden');
    
    if (provider && providerModels[provider]) {
        Object.entries(providerModels[provider]).forEach(([key, data]) => {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = data.name;
            modelSelect.appendChild(option);
        });
    }
});

document.getElementById('model').addEventListener('change', function() {
    const hint = document.getElementById('model-hint');
    const provider = document.getElementById('provider').value;
    
    if (this.value && providerModels[provider] && providerModels[provider][this.value]) {
        const data = providerModels[provider][this.value];
        hint.innerHTML = `<span class="text-green-600">✓</span> ${data.tokenLimit} | ${data.requestLimit}`;
        hint.classList.remove('hidden');
    } else {
        hint.classList.add('hidden');
    }
});

// Show hint on page load if model is selected
document.addEventListener('DOMContentLoaded', function() {
    const modelSelect = document.getElementById('model');
    const hint = document.getElementById('model-hint');
    const provider = document.getElementById('provider').value;
    
    if (modelSelect.value && providerModels[provider] && providerModels[provider][modelSelect.value]) {
        const data = providerModels[provider][modelSelect.value];
        hint.innerHTML = `<span class="text-green-600">✓</span> ${data.tokenLimit} | ${data.requestLimit}`;
        hint.classList.remove('hidden');
    }
});
</script>
@endsection
