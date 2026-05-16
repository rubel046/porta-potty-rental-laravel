<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\GmbAccount;
use App\Services\GoogleBusinessProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class GmbAccountController extends Controller
{
    public function index(): View
    {
        $accounts = GmbAccount::with(['domain', 'gmbPosts' => function ($q) {
            $q->latest()->limit(5);
        }])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.gmb.index', compact('accounts'));
    }

    public function create(): View
    {
        $domains = Domain::all();

        return view('admin.gmb.create', compact('domains'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'account_name' => 'required|string|max:255',
            'account_email' => 'nullable|email|max:255',
            'location_id' => 'nullable|string|max:255',
            'access_token' => 'nullable|string',
            'refresh_token' => 'nullable|string',
            'token_expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'auto_post' => 'nullable|boolean',
            'auto_reply_reviews' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['auto_post'] = $request->boolean('auto_post');
        $validated['auto_reply_reviews'] = $request->boolean('auto_reply_reviews');

        if (!empty($validated['access_token'])) {
            $validated['access_token'] = Crypt::encryptString($validated['access_token']);
        }
        if (!empty($validated['refresh_token'])) {
            $validated['refresh_token'] = Crypt::encryptString($validated['refresh_token']);
        }

        GmbAccount::create($validated);

        return redirect()->route('admin.gmb-accounts.index')
            ->with('success', 'GMB account created successfully.');
    }

    public function edit(GmbAccount $gmbAccount): View
    {
        $domains = Domain::all();

        try {
            $decryptedAccess = $gmbAccount->getDecryptedAccessToken();
        } catch (\Exception $e) {
            $decryptedAccess = null;
        }

        try {
            $decryptedRefresh = $gmbAccount->getDecryptedRefreshToken();
        } catch (\Exception $e) {
            $decryptedRefresh = null;
        }

        $recentPosts = $gmbAccount->gmbPosts()->latest()->limit(10)->get();

        return view('admin.gmb.edit', compact('gmbAccount', 'domains', 'decryptedAccess', 'decryptedRefresh', 'recentPosts'));
    }

    public function update(Request $request, GmbAccount $gmbAccount): RedirectResponse
    {
        $validated = $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'account_name' => 'required|string|max:255',
            'account_email' => 'nullable|email|max:255',
            'location_id' => 'nullable|string|max:255',
            'access_token' => 'nullable|string',
            'refresh_token' => 'nullable|string',
            'token_expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'auto_post' => 'nullable|boolean',
            'auto_reply_reviews' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['auto_post'] = $request->boolean('auto_post');
        $validated['auto_reply_reviews'] = $request->boolean('auto_reply_reviews');

        if (!empty($validated['access_token'])) {
            $validated['access_token'] = Crypt::encryptString($validated['access_token']);
        } else {
            unset($validated['access_token']);
        }
        if (!empty($validated['refresh_token'])) {
            $validated['refresh_token'] = Crypt::encryptString($validated['refresh_token']);
        } else {
            unset($validated['refresh_token']);
        }

        $gmbAccount->update($validated);

        return redirect()->route('admin.gmb-accounts.index')
            ->with('success', 'GMB account updated successfully.');
    }

    public function destroy(GmbAccount $gmbAccount): RedirectResponse
    {
        $gmbAccount->delete();

        return redirect()->route('admin.gmb-accounts.index')
            ->with('success', 'GMB account deleted successfully.');
    }

    public function toggle(GmbAccount $gmbAccount): RedirectResponse
    {
        $gmbAccount->update(['is_active' => !$gmbAccount->is_active]);

        $status = $gmbAccount->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.gmb-accounts.index')
            ->with('success', "GMB account {$status} successfully.");
    }

    public function sync(GmbAccount $gmbAccount, GoogleBusinessProfileService $gmb): RedirectResponse
    {
        if (!$gmb->isConfigured()) {
            return redirect()->route('admin.gmb-accounts.index')
                ->with('error', 'GMB API not configured. Set GMB_CLIENT_ID and GMB_CLIENT_SECRET.');
        }

        if (empty($gmbAccount->location_id)) {
            return redirect()->route('admin.gmb-accounts.index')
                ->with('error', 'Location ID is required. Edit the account and add the location ID.');
        }

        $results = $gmb->syncAccount($gmbAccount);

        $message = "GMB sync complete. ";
        $message .= "Posts created: {$results['posts_created']}, ";
        $message .= "Reviews fetched: {$results['reviews_fetched']}, ";
        $message .= "Replies posted: {$results['reviews_replied']}.";

        if (!empty($results['errors'])) {
            $message .= " Errors: " . implode(' | ', array_slice($results['errors'], 0, 3));
        }

        return redirect()->route('admin.gmb-accounts.index')
            ->with('success', $message);
    }

    public function connect(GoogleBusinessProfileService $gmb): RedirectResponse
    {
        if (!$gmb->isConfigured()) {
            return redirect()->route('admin.gmb-accounts.index')
                ->with('error', 'GMB API not configured. Set GMB_CLIENT_ID and GMB_CLIENT_SECRET.');
        }

        return redirect($gmb->getAuthUrl());
    }

    public function callback(Request $request, GoogleBusinessProfileService $gmb): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()->route('admin.gmb-accounts.index')
                ->with('error', 'Google OAuth authorization was denied: ' . $request->error);
        }

        $code = $request->input('code');
        if (!$code) {
            return redirect()->route('admin.gmb-accounts.index')
                ->with('error', 'No authorization code received from Google.');
        }

        // Store the auth code in session so we can use it when creating/editing an account
        // The admin must have a GMB account selected to pair with this token
        session(['gmb_oauth_code' => $code]);

        return redirect()->route('admin.gmb-accounts.index')
            ->with('info', 'Google authorization received! ' .
                'Now create or edit a GMB account to pair with this authorization. ' .
                'The access/refresh tokens will be automatically exchanged when you save.');
    }

    public function exchangeToken(Request $request): RedirectResponse
    {
        $accountId = $request->input('account_id');
        $code = session('gmb_oauth_code');

        if (!$code) {
            return redirect()->route('admin.gmb-accounts.index')
                ->with('error', 'No OAuth code in session. Please connect with Google first.');
        }

        $account = GmbAccount::find($accountId);
        if (!$account) {
            return redirect()->route('admin.gmb-accounts.index')
                ->with('error', 'GMB account not found.');
        }

        $gmb = app(GoogleBusinessProfileService::class);
        $result = $gmb->exchangeAuthCode($code);

        if (!$result['success']) {
            return redirect()->route('admin.gmb-accounts.edit', $account)
                ->with('error', 'Failed to exchange authorization code: ' . ($result['error'] ?? 'Unknown error'));
        }

        $updateData = [
            'access_token' => Crypt::encryptString($result['access_token']),
            'token_expires_at' => now()->addSeconds($result['expires_in'] ?? 3600),
            'is_active' => true,
        ];

        if (!empty($result['refresh_token'])) {
            $updateData['refresh_token'] = Crypt::encryptString($result['refresh_token']);
        }

        $account->update($updateData);
        session()->forget('gmb_oauth_code');

        return redirect()->route('admin.gmb-accounts.edit', $account)
            ->with('success', 'Successfully connected to Google Business Profile! Tokens saved.');
    }
}
