<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiApiKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiApiKeyController extends Controller
{
    public function index(): View
    {
        $apiKeys = AiApiKey::orderBy('priority')->orderBy('provider')->get();
        $providers = AiApiKey::PROVIDERS;

        return view('admin.api-keys.index', compact('apiKeys', 'providers'));
    }

    public function create(): View
    {
        $providers = AiApiKey::PROVIDERS;

        return view('admin.api-keys.create', compact('providers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'provider' => 'required|in:'.implode(',', array_keys(AiApiKey::PROVIDERS)),
            'api_key' => 'required|string|max:255',
            'model' => 'required|string|max:100',
            'name' => 'nullable|string|max:100',
            'priority' => 'nullable|integer|min:1|max:999',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['priority'] = $validated['priority'] ?? 100;

        AiApiKey::create($validated);

        return redirect()->route('admin.api-keys.index')
            ->with('success', 'API key added successfully.');
    }

    public function edit(AiApiKey $apiKey): View
    {
        $providers = AiApiKey::PROVIDERS;
        $models = AiApiKey::getAvailableModels($apiKey->provider);

        return view('admin.api-keys.edit', compact('apiKey', 'providers', 'models'));
    }

    public function update(Request $request, AiApiKey $apiKey): RedirectResponse
    {
        $validated = $request->validate([
            'provider' => 'required|in:'.implode(',', array_keys(AiApiKey::PROVIDERS)),
            'api_key' => 'required|string|max:255',
            'model' => 'required|string|max:100',
            'name' => 'nullable|string|max:100',
            'priority' => 'nullable|integer|min:1|max:999',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['priority'] = $validated['priority'] ?? 100;

        $apiKey->update($validated);

        return redirect()->route('admin.api-keys.index')
            ->with('success', 'API key updated successfully.');
    }

    public function destroy(AiApiKey $apiKey): RedirectResponse
    {
        $apiKey->delete();

        return redirect()->route('admin.api-keys.index')
            ->with('success', 'API key deleted successfully.');
    }

    public function toggle(AiApiKey $apiKey): RedirectResponse
    {
        $apiKey->update(['is_active' => ! $apiKey->is_active]);

        return redirect()->back()
            ->with('success', 'API key status updated.');
    }

    public function reset(AiApiKey $apiKey): RedirectResponse
    {
        $apiKey->update([
            'cooldown_until' => null,
            'failure_count' => 0,
        ]);

        return redirect()->back()
            ->with('success', 'API key reset successfully.');
    }

    public function resetAll(): RedirectResponse
    {
        AiApiKey::query()->update([
            'cooldown_until' => null,
            'failure_count' => 0,
            'tokens_used_today' => 0,
            'requests_today' => 0,
            'tokens_reset_at' => now()->addDay()->startOfDay(),
            'requests_reset_at' => now()->addDay()->startOfDay(),
            'failure_reset_at' => now()->addDay()->startOfDay(),
        ]);

        return redirect()->back()
            ->with('success', 'All API keys reset successfully.');
    }

    public function getModels(Request $request): JsonResponse
    {
        $provider = $request->get('provider');
        $models = AiApiKey::getAvailableModels($provider);

        return response()->json($models);
    }
}
