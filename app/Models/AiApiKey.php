<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AiApiKey extends Model
{
    protected $fillable = [
        'provider',
        'api_key',
        'model',
        'name',
        'priority',
        'is_active',
        'failure_count',
        'last_used_at',
        'cooldown_until',
        'tokens_used_today',
        'tokens_reset_at',
        'requests_today',
        'requests_reset_at',
        'requests_this_minute',
        'minute_reset_at',
        'failure_reset_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'failure_count' => 'integer',
        'last_used_at' => 'datetime',
        'cooldown_until' => 'datetime',
        'tokens_used_today' => 'integer',
        'tokens_reset_at' => 'datetime',
        'requests_today' => 'integer',
        'requests_reset_at' => 'datetime',
        'requests_this_minute' => 'integer',
        'minute_reset_at' => 'datetime',
        'failure_reset_at' => 'datetime',
    ];

    protected $hidden = [
        'api_key',
    ];

    const PROVIDER_GROQ = 'groq';

    const PROVIDER_CLAUDE = 'claude';

    const PROVIDER_GEMINI = 'gemini';

    const PROVIDER_OPENAI = 'openai';

    const PROVIDERS = [
        self::PROVIDER_GROQ => 'Groq',
        self::PROVIDER_CLAUDE => 'Claude',
        self::PROVIDER_GEMINI => 'Gemini',
        self::PROVIDER_OPENAI => 'OpenAI',
    ];

    public const DAILY_TOKEN_LIMITS = [
        'allam-2-7b' => 500000,
        'llama-3.3-70b-versatile' => 100000,
        'llama-3.1-8b-instant' => 500000,
        'qwen/qwen3-32b' => 500000,
        'meta-llama/llama-4-scout-17b-16e-instruct' => 500000,
        'moonshotai/kimi-k2-instruct' => 300000,
        'openai/gpt-oss-120b' => 200000,
        'openai/gpt-oss-20b' => 200000,
        'groq/compound' => 0,
        'groq/compound-mini' => 0,
        'claude-3-5-sonnet-20241022' => 200000,
        'claude-3-opus-20240229' => 200000,
        'claude-3-haiku-20240307' => 200000,
        'gemini-2.0-flash' => 150000,
        'gemini-1.5-pro' => 150000,
        'gemini-1.5-flash' => 150000,
        'gpt-4o' => 500000,
        'gpt-4o-mini' => 500000,
        'gpt-4-turbo' => 500000,
    ];

    public const DAILY_REQUEST_LIMITS = [
        'allam-2-7b' => 7000,
        'llama-3.3-70b-versatile' => 1000,
        'llama-3.1-8b-instant' => 14400,
        'qwen/qwen3-32b' => 1000,
        'meta-llama/llama-4-scout-17b-16e-instruct' => 1000,
        'moonshotai/kimi-k2-instruct' => 1000,
        'openai/gpt-oss-120b' => 1000,
        'openai/gpt-oss-20b' => 1000,
        'groq/compound' => 250,
        'groq/compound-mini' => 250,
        'claude-3-5-sonnet-20241022' => 5000,
        'claude-3-opus-20240229' => 5000,
        'claude-3-haiku-20240307' => 5000,
        'gemini-2.0-flash' => 1500,
        'gemini-1.5-pro' => 1000,
        'gemini-1.5-flash' => 1500,
        'gpt-4o' => 500,
        'gpt-4o-mini' => 500,
        'gpt-4-turbo' => 500,
    ];

    public const MINUTE_REQUEST_LIMITS = [
        'allam-2-7b' => 30,
        'llama-3.3-70b-versatile' => 30,
        'llama-3.1-8b-instant' => 30,
        'qwen/qwen3-32b' => 60,
        'meta-llama/llama-4-scout-17b-16e-instruct' => 30,
        'moonshotai/kimi-k2-instruct' => 60,
        'openai/gpt-oss-120b' => 30,
        'openai/gpt-oss-20b' => 30,
        'groq/compound' => 30,
        'groq/compound-mini' => 30,
        'claude-3-5-sonnet-20241022' => 50,
        'claude-3-opus-20240229' => 50,
        'claude-3-haiku-20240307' => 50,
        'gemini-2.0-flash' => 15,
        'gemini-1.5-pro' => 50,
        'gemini-1.5-flash' => 15,
        'gpt-4o' => 500,
        'gpt-4o-mini' => 500,
        'gpt-4-turbo' => 500,
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('cooldown_until')
                    ->orWhere('cooldown_until', '<=', now());
            });
    }

    public function scopeOrderedByPriority(Builder $query): Builder
    {
        return $query->orderBy('priority')->orderBy('failure_count');
    }

    public function isInCooldown(): bool
    {
        return $this->cooldown_until && $this->cooldown_until->isFuture();
    }

    public function isDailyLimitReached(): bool
    {
        $this->resetIfNewDay();

        $tokenLimit = self::DAILY_TOKEN_LIMITS[$this->model] ?? 100000;
        $requestLimit = self::DAILY_REQUEST_LIMITS[$this->model] ?? 1000;

        return $this->tokens_used_today >= $tokenLimit || $this->requests_today >= $requestLimit;
    }

    public function resetIfNewDay(): void
    {
        $needsReset = false;
        $updates = [];
        $nextReset = now()->addDay()->startOfDay();

        if (! $this->tokens_reset_at || $this->tokens_reset_at->isPast()) {
            $needsReset = true;
            $updates['tokens_used_today'] = 0;
            $updates['tokens_reset_at'] = $nextReset;
            $updates['requests_today'] = 0;
            $updates['requests_reset_at'] = $nextReset;
        }

        if (! $this->failure_reset_at || $this->failure_reset_at->isPast()) {
            $needsReset = true;
            $updates['failure_count'] = 0;
            $updates['failure_reset_at'] = $nextReset;
        }

        if ($needsReset) {
            $this->update($updates);
        }
    }

    public function resetIfNewMinute(): void
    {
        if (! $this->minute_reset_at || $this->minute_reset_at->isPast()) {
            $this->update([
                'requests_this_minute' => 0,
                'minute_reset_at' => now()->addMinute()->startOfMinute(),
            ]);
        }
    }

    public function isMinuteLimitReached(): bool
    {
        $this->resetIfNewMinute();

        $limit = self::MINUTE_REQUEST_LIMITS[$this->model] ?? 30;

        return $this->requests_this_minute >= $limit;
    }

    public function recordSuccess(int $tokensUsed = 0): void
    {
        $this->resetIfNewDay();
        $this->resetIfNewMinute();

        $this->update([
            'failure_count' => 0,
            'cooldown_until' => null,
            'last_used_at' => now(),
            'tokens_used_today' => $this->tokens_used_today + $tokensUsed,
            'requests_today' => $this->requests_today + 1,
            'requests_this_minute' => $this->requests_this_minute + 1,
        ]);
    }

    public function recordFailure(): void
    {
        $this->update([
            'failure_count' => $this->failure_count + 1,
            'last_used_at' => now(),
        ]);
    }

    public function putInCooldown(int $minutes = 5): void
    {
        $this->update([
            'cooldown_until' => now()->addMinutes($minutes),
        ]);
    }

    public function getMaskedKeyAttribute(): string
    {
        $key = $this->api_key;
        if (strlen($key) <= 8) {
            return '****'.substr($key, -4);
        }

        return substr($key, 0, 4).'****'.substr($key, -4);
    }

    public function getTokenLimitAttribute(): int
    {
        return self::DAILY_TOKEN_LIMITS[$this->model] ?? 100000;
    }

    public function getRequestLimitAttribute(): int
    {
        return self::DAILY_REQUEST_LIMITS[$this->model] ?? 1000;
    }

    public function getTokensPercentageAttribute(): float
    {
        return $this->token_limit > 0
            ? round(($this->tokens_used_today / $this->token_limit) * 100, 1)
            : 0;
    }

    public function getRequestsPercentageAttribute(): float
    {
        return $this->request_limit > 0
            ? round(($this->requests_today / $this->request_limit) * 100, 1)
            : 0;
    }

    public static function getAvailableModels(string $provider): array
    {
        return match ($provider) {
            self::PROVIDER_GROQ => [
                'llama-3.1-8b-instant' => 'Llama 3.1 8B Instant (14,400 req/day)',
                'allam-2-7b' => 'ALLaM-2-7B (7,000 req/day)',
                'qwen/qwen3-32b' => 'Qwen3-32B (1,000 req/day)',
                'meta-llama/llama-4-scout-17b-16e-instruct' => 'Llama 4 Scout 17B (Preview)',
                'llama-3.3-70b-versatile' => 'Llama 3.3 70B',
                'openai/gpt-oss-120b' => 'GPT OSS 120B',
                'openai/gpt-oss-20b' => 'GPT OSS 20B',
                'moonshotai/kimi-k2-instruct' => 'Kimi K2 (Deprecated)',
                'groq/compound' => 'Compound',
                'groq/compound-mini' => 'Compound Mini',
            ],
            self::PROVIDER_CLAUDE => [
                'claude-3-5-sonnet-20241022' => 'Claude 3.5 Sonnet',
                'claude-3-opus-20240229' => 'Claude 3 Opus',
                'claude-3-haiku-20240307' => 'Claude 3 Haiku',
            ],
            self::PROVIDER_GEMINI => [
                'gemini-2.0-flash' => 'Gemini 2.0 Flash',
                'gemini-1.5-pro' => 'Gemini 1.5 Pro',
                'gemini-1.5-flash' => 'Gemini 1.5 Flash',
            ],
            self::PROVIDER_OPENAI => [
                'gpt-4o' => 'GPT-4o',
                'gpt-4o-mini' => 'GPT-4o Mini',
                'gpt-4-turbo' => 'GPT-4 Turbo',
            ],
            default => [],
        };
    }
}
