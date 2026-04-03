<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;

    protected float $totalCost = 0;

    protected int $totalInputTokens = 0;

    protected int $totalOutputTokens = 0;

    protected string $model = 'gemini-2.0-flash';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
    }

    public function generateContent(string $prompt, int $maxTokens = 8192): ?string
    {
        try {
            $response = Http::timeout(120)->post(
                'https://generativelanguage.googleapis.com/v1beta/models/'.$this->model.':generateContent?key='.$this->apiKey,
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => $maxTokens,
                        'temperature' => 0.7,
                    ],
                ]
            );

            if (! $response->successful()) {
                Log::error('Gemini API Error', ['status' => $response->status(), 'body' => $response->body()]);

                return null;
            }

            $data = $response->json();

            if (isset($data['usageMetadata'])) {
                $inputTokens = $data['usageMetadata']['promptTokenCount'] ?? 0;
                $outputTokens = $data['usageMetadata']['candidatesTokenCount'] ?? 0;

                $this->totalInputTokens += $inputTokens;
                $this->totalOutputTokens += $outputTokens;

                $inputCost = ($inputTokens / 1000000) * 0.35;
                $outputCost = ($outputTokens / 1000000) * 0.70;
                $this->totalCost += ($inputCost + $outputCost);

                Log::info('Gemini API Usage', [
                    'input_tokens' => $inputTokens,
                    'output_tokens' => $outputTokens,
                    'input_cost' => round($inputCost, 4),
                    'output_cost' => round($outputCost, 4),
                ]);
            }

            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        } catch (\Exception $e) {
            Log::error("Gemini API Error: {$e->getMessage()}");

            return null;
        }
    }

    public function generateJson(string $prompt, int $maxTokens = 8192): ?array
    {
        $promptWithJson = $prompt."\n\nRespond ONLY with valid JSON. No explanations, no markdown formatting.";

        $response = $this->generateContent($promptWithJson, $maxTokens);

        if (! $response) {
            return null;
        }

        $jsonString = trim($response);
        $jsonString = preg_replace('/^```json\s*/', '', $jsonString);
        $jsonString = preg_replace('/\s*```$/', '', $jsonString);

        return json_decode($jsonString, true);
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey) && $this->apiKey !== 'your-gemini-api-key-here';
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
        $inputCost = ($inputTokens / 1000000) * 0.35;
        $outputCost = ($outputTokens / 1000000) * 0.70;

        return round($inputCost + $outputCost, 4);
    }
}
