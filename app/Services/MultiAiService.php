<?php

namespace App\Services;

use App\Models\AiApiKey;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MultiAiService
{
    protected const DEFAULT_COOLDOWN_MINUTES = 5;

    public function generateContent(string $prompt, ?string $systemPrompt = null): ?string
    {
        return $this->generateContentWithOptions($prompt, $systemPrompt, false);
    }

    public function generateJsonContent(string $prompt, ?string $systemPrompt = null): ?array
    {
        $result = $this->generateContentWithOptions($prompt, $systemPrompt, true);

        if ($result === null) {
            return null;
        }

        $decoded = json_decode($result, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('MultiAiService: Failed to parse JSON response', [
                'error' => json_last_error_msg(),
                'response' => substr($result, 0, 500),
            ]);

            return null;
        }

        return $decoded;
    }

    public function generateContentWithOptions(string $prompt, ?string $systemPrompt = null, bool $jsonMode = false): ?string
    {
        $keys = AiApiKey::active()->orderedByPriority()->get();

        if ($keys->isEmpty()) {
            Log::error('MultiAiService: No active API keys available');

            return null;
        }

        foreach ($keys as $apiKey) {
            $apiKey->refresh();

            if ($apiKey->isDailyLimitReached()) {
                continue;
            }

            if ($apiKey->isMinuteLimitReached()) {
                continue;
            }

            try {
                $result = $this->callProvider($apiKey, $prompt, $systemPrompt, $jsonMode);

                if ($result !== null) {
                    return $result;
                }
            } catch (\Exception $e) {
                Log::warning("MultiAiService: API key {$apiKey->id} failed", [
                    'provider' => $apiKey->provider,
                    'model' => $apiKey->model,
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                ]);

                if ($this->isRateLimitError($e)) {
                    $this->handleRateLimitError($apiKey, $e);
                } elseif ($this->isDailyLimitError($e)) {
                    $this->handleDailyLimitError($apiKey, $e);
                }
            }
        }

        Log::error('MultiAiService: All API keys exhausted');

        return null;
    }

    protected function handleRateLimitError(AiApiKey $apiKey, \Exception $e): void
    {
        $message = $e->getMessage();

        preg_match('/Used (\d+)/', $message, $usedMatch);
        preg_match('/Limit (\d+)/', $message, $limitMatch);

        $retrySeconds = $this->extractRetrySeconds($message);
        $cooldownMinutes = max(self::DEFAULT_COOLDOWN_MINUTES, (int) ceil($retrySeconds / 60));

        $updates = [
            'failure_count' => $apiKey->failure_count + 1,
            'last_used_at' => now(),
        ];

        if (! empty($usedMatch[1])) {
            $updates['tokens_used_today'] = (int) $usedMatch[1];
        }

        if (! empty($limitMatch[1])) {
            $updates['tokens_reset_at'] = now()->addDay()->startOfDay();
        }

        $apiKey->update($updates);
        $apiKey->putInCooldown($cooldownMinutes);

        Log::warning("MultiAiService: API key {$apiKey->id} rate limited - tokens synced, cooldown for {$cooldownMinutes} min (API said {$retrySeconds}s)");
    }

    protected function extractRetrySeconds(string $message): int
    {
        if (preg_match('/try again in (\d+m)?(\d+\.?\d*)s/', $message, $matches)) {
            $minutes = isset($matches[1]) ? (int) rtrim($matches[1], 'm') : 0;
            $seconds = (float) $matches[2];

            return ($minutes * 60) + $seconds;
        }

        return self::DEFAULT_COOLDOWN_MINUTES * 60;
    }

    protected function handleDailyLimitError(AiApiKey $apiKey, \Exception $e): void
    {
        $message = $e->getMessage();

        preg_match('/Used (\d+)/', $message, $usedMatch);

        $updates = [
            'failure_count' => $apiKey->failure_count + 1,
            'last_used_at' => now(),
        ];

        if (! empty($usedMatch[1])) {
            $updates['tokens_used_today'] = (int) $usedMatch[1];
        }

        $apiKey->update($updates);

        Log::warning("MultiAiService: API key {$apiKey->id} hit daily limit - tokens synced");
    }

    protected function callProvider(AiApiKey $apiKey, string $prompt, ?string $systemPrompt = null, bool $jsonMode = false): ?string
    {
        $tokensUsed = 0;

        $result = match ($apiKey->provider) {
            AiApiKey::PROVIDER_GROQ => $this->callGroq($apiKey, $prompt, $systemPrompt, $tokensUsed, $jsonMode),
            AiApiKey::PROVIDER_CLAUDE => $this->callClaude($apiKey, $prompt, $systemPrompt, $tokensUsed, $jsonMode),
            AiApiKey::PROVIDER_GEMINI => $this->callGemini($apiKey, $prompt, $systemPrompt, $tokensUsed, $jsonMode),
            AiApiKey::PROVIDER_OPENAI => $this->callOpenAi($apiKey, $prompt, $systemPrompt, $tokensUsed, $jsonMode),
            default => null,
        };

        if ($result !== null && $tokensUsed > 0) {
            $apiKey->recordSuccess($tokensUsed);
        } elseif ($result !== null) {
            $apiKey->recordSuccess(0);
        }

        return $result;
    }

    protected function callGroq(AiApiKey $apiKey, string $prompt, ?string $systemPrompt = null, int &$tokensUsed = 0, bool $jsonMode = false): ?string
    {
        $systemPrompt = $systemPrompt ?? 'You are a helpful assistant.';

        $body = [
            'model' => $apiKey->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 8192,
        ];

        if ($jsonMode) {
            $body['response_format'] = ['type' => 'json_object'];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey->api_key,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.groq.com/openai/v1/chat/completions', $body);

        if ($response->successful()) {
            $data = $response->json();

            $tokensUsed = ($data['usage']['prompt_tokens'] ?? 0) + ($data['usage']['completion_tokens'] ?? 0);

            return $data['choices'][0]['message']['content'] ?? null;
        }

        throw new \Exception($response->json()['error']['message'] ?? 'Groq API error', $response->status());
    }

    protected function callClaude(AiApiKey $apiKey, string $prompt, ?string $systemPrompt = null, int &$tokensUsed = 0, bool $jsonMode = false): ?string
    {
        $systemPrompt = $systemPrompt ?? 'You are a helpful assistant.';

        $body = [
            'model' => $apiKey->model,
            'system' => $systemPrompt,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 4096,
        ];

        if ($jsonMode) {
            $body['response_format'] = ['type' => 'json_object'];
        }

        $response = Http::withHeaders([
            'x-api-key' => $apiKey->api_key,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.anthropic.com/v1/messages', $body);

        if ($response->successful()) {
            $data = $response->json();

            $tokensUsed = ($data['usage']['input_tokens'] ?? 0) + ($data['usage']['output_tokens'] ?? 0);

            return $data['content'][0]['text'] ?? null;
        }

        throw new \Exception($response->json()['error']['message'] ?? 'Claude API error', $response->status());
    }

    protected function callGemini(AiApiKey $apiKey, string $prompt, ?string $systemPrompt = null, int &$tokensUsed = 0, bool $jsonMode = false): ?string
    {
        $contents = [
            'contents' => [
                ['parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 4096,
            ],
        ];

        if ($jsonMode) {
            $contents['generationConfig']['responseMimeType'] = 'application/json';
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$apiKey->model}:generateContent?key={$apiKey->api_key}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(120)->post($url, $contents);

        if ($response->successful()) {
            $data = $response->json();

            $tokensUsed = ($data['usageMetadata']['promptTokenCount'] ?? 0) + ($data['usageMetadata']['candidatesTokenCount'] ?? 0);

            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        }

        throw new \Exception($response->json()['error']['message'] ?? 'Gemini API error', $response->status());
    }

    protected function callOpenAi(AiApiKey $apiKey, string $prompt, ?string $systemPrompt = null, int &$tokensUsed = 0, bool $jsonMode = false): ?string
    {
        $systemPrompt = $systemPrompt ?? 'You are a helpful assistant.';

        $body = [
            'model' => $apiKey->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 4096,
        ];

        if ($jsonMode) {
            $body['response_format'] = ['type' => 'json_object'];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey->api_key,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', $body);

        if ($response->successful()) {
            $data = $response->json();

            $tokensUsed = ($data['usage']['prompt_tokens'] ?? 0) + ($data['usage']['completion_tokens'] ?? 0);

            return $data['choices'][0]['message']['content'] ?? null;
        }

        throw new \Exception($response->json()['error']['message'] ?? 'OpenAI API error', $response->status());
    }

    protected function isRateLimitError(\Exception $e): bool
    {
        $message = strtolower($e->getMessage());

        return str_contains($message, 'rate limit')
            || str_contains($message, '429')
            || str_contains($message, 'too many requests')
            || str_contains($message, 'quota');
    }

    protected function isDailyLimitError(\Exception $e): bool
    {
        $message = strtolower($e->getMessage());

        return str_contains($message, 'daily')
            || str_contains($message, 'exceeded')
            || str_contains($message, 'limit reached')
            || str_contains($message, 'tokens per day');
    }

    public function getStatus(): array
    {
        $keys = AiApiKey::all();

        return [
            'total' => $keys->count(),
            'active' => $keys->where('is_active', true)->count(),
            'in_cooldown' => $keys->filter(fn ($k) => $k->isInCooldown())->count(),
            'keys' => $keys->map(fn ($k) => [
                'id' => $k->id,
                'name' => $k->name ?? $k->provider.' - '.$k->model,
                'provider' => $k->provider,
                'model' => $k->model,
                'is_active' => $k->is_active,
                'failure_count' => $k->failure_count,
                'in_cooldown' => $k->isInCooldown(),
                'last_used_at' => $k->last_used_at?->toIso8601String(),
            ])->toArray(),
        ];
    }
}
