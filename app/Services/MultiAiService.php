<?php

namespace App\Services;

use App\Models\AiApiKey;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MultiAiService
{
    protected const COOLDOWN_MINUTES = 1;

    protected const MAX_RETRIES = 3;

    protected const COOLDOWN_ON_RATE_LIMIT_ONLY = true;

    public function generateContent(string $prompt, ?string $systemPrompt = null): ?string
    {
        $keys = AiApiKey::active()->orderedByPriority()->get();

        if ($keys->isEmpty()) {
            Log::error('MultiAiService: No active API keys available');

            return null;
        }

        foreach ($keys as $apiKey) {
            $apiKey->refresh();

            if ($apiKey->isDailyLimitReached()) {
                Log::info("MultiAiService: Skipping API key {$apiKey->id} - daily limit reached", [
                    'tokens_used' => $apiKey->tokens_used_today,
                    'requests_used' => $apiKey->requests_today,
                    'model' => $apiKey->model,
                ]);

                continue;
            }

            if ($apiKey->isMinuteLimitReached()) {
                Log::info("MultiAiService: Skipping API key {$apiKey->id} - minute limit reached", [
                    'requests_this_minute' => $apiKey->requests_this_minute,
                    'model' => $apiKey->model,
                ]);

                continue;
            }

            Log::info("MultiAiService: Trying API key {$apiKey->id}", [
                'model' => $apiKey->model,
                'provider' => $apiKey->provider,
            ]);

            try {
                $result = $this->callProvider($apiKey, $prompt, $systemPrompt);

                if ($result !== null) {
                    Log::info("MultiAiService: Success with API key {$apiKey->id}");

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
                    $apiKey->recordFailure();
                    $apiKey->putInCooldown(self::COOLDOWN_MINUTES);
                    Log::info("MultiAiService: API key {$apiKey->id} put in cooldown for ".self::COOLDOWN_MINUTES.' minute(s)');
                } elseif ($this->isDailyLimitError($e)) {
                    Log::info("MultiAiService: API key {$apiKey->id} hit daily limit error from API");
                }
            }
        }

        Log::error('MultiAiService: All API keys exhausted');

        return null;
    }

    protected function callProvider(AiApiKey $apiKey, string $prompt, ?string $systemPrompt = null): ?string
    {
        $tokensUsed = 0;

        $result = match ($apiKey->provider) {
            AiApiKey::PROVIDER_GROQ => $this->callGroq($apiKey, $prompt, $systemPrompt, $tokensUsed),
            AiApiKey::PROVIDER_CLAUDE => $this->callClaude($apiKey, $prompt, $systemPrompt, $tokensUsed),
            AiApiKey::PROVIDER_GEMINI => $this->callGemini($apiKey, $prompt, $systemPrompt, $tokensUsed),
            AiApiKey::PROVIDER_OPENAI => $this->callOpenAi($apiKey, $prompt, $systemPrompt, $tokensUsed),
            default => null,
        };

        if ($result !== null && $tokensUsed > 0) {
            $apiKey->recordSuccess($tokensUsed);
        } elseif ($result !== null) {
            $apiKey->recordSuccess(0);
        }

        return $result;
    }

    protected function callGroq(AiApiKey $apiKey, string $prompt, ?string $systemPrompt = null, int &$tokensUsed = 0): ?string
    {
        $systemPrompt = $systemPrompt ?? 'You are a helpful assistant.';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey->api_key,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => $apiKey->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 4096,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            $tokensUsed = ($data['usage']['prompt_tokens'] ?? 0) + ($data['usage']['completion_tokens'] ?? 0);

            return $data['choices'][0]['message']['content'] ?? null;
        }

        throw new \Exception($response->json()['error']['message'] ?? 'Groq API error', $response->status());
    }

    protected function callClaude(AiApiKey $apiKey, string $prompt, ?string $systemPrompt = null, int &$tokensUsed = 0): ?string
    {
        $systemPrompt = $systemPrompt ?? 'You are a helpful assistant.';

        $response = Http::withHeaders([
            'x-api-key' => $apiKey->api_key,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.anthropic.com/v1/messages', [
            'model' => $apiKey->model,
            'system' => $systemPrompt,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 4096,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            $tokensUsed = ($data['usage']['input_tokens'] ?? 0) + ($data['usage']['output_tokens'] ?? 0);

            return $data['content'][0]['text'] ?? null;
        }

        throw new \Exception($response->json()['error']['message'] ?? 'Claude API error', $response->status());
    }

    protected function callGemini(AiApiKey $apiKey, string $prompt, ?string $systemPrompt = null, int &$tokensUsed = 0): ?string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(120)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$apiKey->model}:generateContent?key={$apiKey->api_key}",
            [
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 4096,
                ],
            ]
        );

        if ($response->successful()) {
            $data = $response->json();

            $tokensUsed = ($data['usageMetadata']['promptTokenCount'] ?? 0) + ($data['usageMetadata']['candidatesTokenCount'] ?? 0);

            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        }

        throw new \Exception($response->json()['error']['message'] ?? 'Gemini API error', $response->status());
    }

    protected function callOpenAi(AiApiKey $apiKey, string $prompt, ?string $systemPrompt = null, int &$tokensUsed = 0): ?string
    {
        $systemPrompt = $systemPrompt ?? 'You are a helpful assistant.';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey->api_key,
            'Content-Type' => 'application/json',
        ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $apiKey->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 4096,
        ]);

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
            || str_contains($message, 'limit reached');
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
