<?php

namespace App\Services;

use Anthropic\Anthropic;
use Illuminate\Support\Facades\Log;

class AnthropicService
{
    protected Anthropic $client;

    protected string $apiKey;

    protected float $totalCost = 0;

    protected int $totalInputTokens = 0;

    protected int $totalOutputTokens = 0;

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.key', env('ANTHROPIC_API_KEY'));
        $this->client = new Anthropic([
            'apiKey' => $this->apiKey,
        ]);
    }

    public function generateContent(string $prompt, int $maxTokens = 8192): ?string
    {
        try {
            $response = $this->client->messages->create([
                'model' => 'claude-sonnet-4-20250620',
                'max_tokens' => $maxTokens,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            if (isset($response->usage)) {
                $inputTokens = $response->usage->input_tokens ?? 0;
                $outputTokens = $response->usage->output_tokens ?? 0;

                $this->totalInputTokens += $inputTokens;
                $this->totalOutputTokens += $outputTokens;

                $inputCost = ($inputTokens / 1000000) * 3.00;
                $outputCost = ($outputTokens / 1000000) * 15.00;
                $this->totalCost += ($inputCost + $outputCost);

                Log::info('Anthropic API Usage', [
                    'input_tokens' => $inputTokens,
                    'output_tokens' => $outputTokens,
                    'input_cost' => round($inputCost, 4),
                    'output_cost' => round($outputCost, 4),
                ]);
            }

            return $response->content[0]->text ?? null;
        } catch (\Exception $e) {
            Log::error("Anthropic API Error: {$e->getMessage()}");

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
        return ! empty($this->apiKey) && $this->apiKey !== 'your-anthropic-api-key-here';
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
        $inputCost = ($inputTokens / 1000000) * 3.00;
        $outputCost = ($outputTokens / 1000000) * 15.00;

        return round($inputCost + $outputCost, 4);
    }
}
