<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://api.groq.com/openai/v1';

    protected string $model = 'llama-3.3-70b-versatile';

    protected float $totalCost = 0;

    protected int $totalInputTokens = 0;

    protected int $totalOutputTokens = 0;

    public function __construct()
    {
        $this->apiKey = config('services.groq.key', env('GROQ_API_KEY'));
    }

    public function generateContent(string $prompt, int $maxTokens = 8192, int $retries = 3): ?string
    {
        $lastError = null;

        for ($attempt = 1; $attempt <= $retries; $attempt++) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(60)->post($this->baseUrl.'/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'max_tokens' => $maxTokens,
                    'temperature' => 0.7,
                ]);

                if ($response->failed()) {
                    $errorBody = $response->body();
                    Log::warning("Groq API attempt {$attempt} failed: {$errorBody}");

                    if ($response->status() === 429) {
                        sleep(2 * $attempt);

                        continue;
                    }

                    $lastError = $errorBody;

                    continue;
                }

                $data = $response->json();

                if (isset($data['usage'])) {
                    $inputTokens = $data['usage']['prompt_tokens'] ?? 0;
                    $outputTokens = $data['usage']['completion_tokens'] ?? 0;

                    $this->totalInputTokens += $inputTokens;
                    $this->totalOutputTokens += $outputTokens;

                    $inputCost = ($inputTokens / 1000000) * 0.0001;
                    $outputCost = ($outputTokens / 1000000) * 0.0004;
                    $this->totalCost += ($inputCost + $outputCost);

                    Log::info('Groq API Usage', [
                        'model' => $this->model,
                        'input_tokens' => $inputTokens,
                        'output_tokens' => $outputTokens,
                        'input_cost' => round($inputCost, 6),
                        'output_cost' => round($outputCost, 6),
                    ]);
                }

                return $data['choices'][0]['message']['content'] ?? null;
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                Log::warning("Groq API attempt {$attempt} exception: {$e->getMessage()}");

                if ($attempt < $retries) {
                    sleep($attempt);
                }
            }
        }

        Log::error("Groq API failed after {$retries} attempts: {$lastError}");

        return null;
    }

    public function generateJson(string $prompt, int $maxTokens = 8192): ?array
    {
        $promptWithJson = $prompt."\n\nRespond ONLY with valid JSON. Output ONLY the JSON object, nothing else.";

        $response = $this->generateContent($promptWithJson, $maxTokens);

        if (! $response) {
            return null;
        }

        $jsonString = trim($response);

        Log::info('Groq raw response', ['response' => substr($jsonString, 0, 500)]);

        $jsonString = preg_replace('/^```json\s*/i', '', $jsonString);
        $jsonString = preg_replace('/^```\s*/i', '', $jsonString);
        $jsonString = preg_replace('/\s*```$/i', '', $jsonString);

        if (preg_match('/\{[\s\S]*\}/s', $jsonString, $matches)) {
            $jsonString = $matches[0];
        }

        $jsonString = $this->sanitizeJson($jsonString);

        Log::info('Groq extracted JSON', ['json' => substr($jsonString, 0, 500)]);

        $decoded = json_decode($jsonString, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        Log::warning('JSON parsing failed', [
            'error' => json_last_error_msg(),
            'json_last_error' => json_last_error(),
            'response_length' => strlen($jsonString),
            'json_preview' => substr($jsonString, -200),
        ]);

        return null;
    }

    protected function sanitizeJson(string $json): string
    {
        $json = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $json);

        $json = preg_replace('/,\s*([}\]])/', '$1', $json);

        $json = preg_replace('/([{,]\s*)([a-zA-Z_][a-zA-Z0-9_]*)\s*:/', '$1"$2":', $json);

        if (preg_match('/\{[\s\S]*\}/', $json, $matches)) {
            $json = $matches[0];
        }

        if (preg_match('/"content"\s*:\s*"(.*)"/s', $json, $matches)) {
            $oldContent = $matches[1];
            $newContent = str_replace("\n", '\\n', $oldContent);
            $newContent = str_replace("\r", '\\r', $newContent);
            $json = preg_replace('/"content"\s*:\s*"(.*)"/s', '"content": "'.$newContent.'"', $json);
        }

        return $json;
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey) && $this->apiKey !== 'your-groq-api-key-here';
    }

    public function getTotalCost(): float
    {
        return $this->totalCost;
    }

    public function getTotalTokens(): array
    {
        return [
            'input' => $this->totalInputTokens,
            'output' => $this->totalOutputTokens,
            'total' => $this->totalInputTokens + $this->totalOutputTokens,
        ];
    }

    public function resetCost(): void
    {
        $this->totalCost = 0;
        $this->totalInputTokens = 0;
        $this->totalOutputTokens = 0;
    }

    public static function calculateCost(int $inputTokens, int $outputTokens): float
    {
        $inputCost = ($inputTokens / 1000000) * 0.0001;
        $outputCost = ($outputTokens / 1000000) * 0.0004;

        return round($inputCost + $outputCost, 6);
    }
}
