<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\DomainState;
use App\Models\IndexingUrl;
use App\Models\ServicePage;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleIndexingService
{
    protected const MAX_URLS_PER_BATCH = 200;

    protected const API_URL = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

    protected ?string $clientEmail = null;

    protected ?string $privateKey = null;

    protected ?string $searchConsoleUrl = null;

    protected ?Client $httpClient = null;

    public function __construct()
    {
        $this->clientEmail = config('services.google.client_email');
        $this->privateKey = config('services.google.private_key');
        $this->searchConsoleUrl = config('services.google.search_console_url', config('app.url'));

        if (empty($this->clientEmail) || empty($this->privateKey)) {
            Log::warning('Google Indexing API credentials not configured');
        }
    }

    public function isConfigured(): bool
    {
        return ! empty($this->clientEmail) && ! empty($this->privateKey);
    }

    protected function getHttpClient(): Client
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client([
                'timeout' => 30,
            ]);
        }

        return $this->httpClient;
    }

    protected function getAccessToken(): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $cacheKey = 'google_indexing_token';
        $cachedToken = Cache::get($cacheKey);

        if ($cachedToken) {
            return $cachedToken;
        }

        $now = time();
        $exp = $now + 3600;

        $payload = [
            'iss' => $this->clientEmail,
            'scope' => 'https://www.googleapis.com/auth/indexing',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $exp,
        ];

        $privateKey = str_replace(['\\n', '\n'], ["\n", "\n"], $this->privateKey);

        try {
            $jwt = JWT::encode($payload, $privateKey, 'RS256');

            $response = $this->getHttpClient()->post('https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['access_token'])) {
                Cache::put($cacheKey, $data['access_token'], 3500);

                return $data['access_token'];
            }
        } catch (\Throwable $e) {
            Log::error('Failed to get Google access token', [
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    public function indexUrls(array $urls): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Google Indexing API not configured',
            ];
        }

        $accessToken = $this->getAccessToken();

        if (! $accessToken) {
            return [
                'success' => false,
                'message' => 'Failed to obtain access token',
            ];
        }

        $results = [
            'submitted' => 0,
            'errors' => [],
            'urls' => [],
        ];

        $batches = array_chunk($urls, 100);

        foreach ($batches as $batch) {
            $batchResults = $this->submitBatch($batch, $accessToken);
            $results['submitted'] += $batchResults['submitted'];
            $results['urls'] = array_merge($results['urls'], $batchResults['urls']);
            $results['errors'] = array_merge($results['errors'], $batchResults['errors']);

            usleep(50000);
        }

        return $results;
    }

    protected function submitBatch(array $urls, string $accessToken): array
    {
        $results = [
            'submitted' => 0,
            'errors' => [],
            'urls' => [],
        ];

        foreach ($urls as $url) {
            try {
                $response = $this->getHttpClient()->post(self::API_URL, [
                    'headers' => [
                        'Authorization' => 'Bearer '.$accessToken,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'url' => $url,
                        'type' => 'URL_UPDATED',
                    ],
                ]);

                if ($response->getStatusCode() === 200) {
                    $results['submitted']++;
                    $results['urls'][] = $url;
                } else {
                    $data = json_decode($response->getBody()->getContents(), true);
                    $results['errors'][] = $url.': '.$data['error']['message'] ?? 'Status '.$response->getStatusCode();
                }
            } catch (RequestException $e) {
                $message = $e->getMessage();
                if ($e->hasResponse()) {
                    $data = json_decode($e->getResponse()->getBody()->getContents(), true);
                    $message = $data['error']['message'] ?? $message;
                }
                $results['errors'][] = $url.': '.$message;

                if ($e->getCode() === 401) {
                    Cache::forget('google_indexing_token');
                }
            } catch (\Throwable $e) {
                $results['errors'][] = $url.': '.$e->getMessage();
            }
        }

        return $results;
    }

    public function processPendingUrls(): array
    {
        $stats = [
            'service_pages' => 0,
            'domain_states' => 0,
            'blog_posts' => 0,
            'total' => 0,
            'errors' => [],
        ];

        // Get pending URLs from tracking table that haven't been submitted yet
        $pendingUrls = IndexingUrl::where('status', 'pending')
            ->whereNull('requested_at')
            ->limit(self::MAX_URLS_PER_BATCH)
            ->get()
            ->pluck('url')
            ->toArray();

        if (empty($pendingUrls)) {
            return $stats;
        }

        $allUrls = $pendingUrls;

        // Track URLs in indexing_urls table
        foreach ($allUrls as $url) {
            $type = 'service';
            if (str_contains($url, '/blog/')) {
                $type = 'blog';
            } elseif (str_contains($url, '/state/')) {
                $type = 'state';
            }
            IndexingUrl::firstOrCreate(
                ['url' => $url],
                ['type' => $type, 'status' => 'pending']
            );
        }

        $results = $this->indexUrls($allUrls);

        $indexedUrls = $results['urls'];
        $indexedCount = count($indexedUrls);
        $stats['errors'] = $results['errors'] ?? [];

        // Update tracking records
        foreach ($indexedUrls as $url) {
            IndexingUrl::where('url', $url)->update([
                'indexed' => true,
                'indexed_at' => now(),
                'status' => 'indexed',
            ]);
        }

        // Update original records
        $baseUrl = config('app.url');
        $servicePageSlugs = [];
        $statePageSlugs = [];
        $blogPostSlugs = [];

        foreach ($indexedUrls as $url) {
            $path = str_replace($baseUrl.'/', '', $url);
            if (str_starts_with($path, 'blog/')) {
                $blogPostSlugs[] = str_replace('blog/', '', $path);
            } elseif (str_starts_with($path, 'state/')) {
                $statePageSlugs[] = str_replace('state/', '', $path);
            } else {
                $servicePageSlugs[] = $path;
            }
        }

        if (! empty($servicePageSlugs)) {
            $count = ServicePage::whereIn('slug', $servicePageSlugs)->update([
                'indexing_requested' => true,
            ]);
            $stats['service_pages'] = $count;
        }

        if (! empty($statePageSlugs)) {
            $domainStates = DomainState::whereHas('state', fn ($q) => $q->whereIn('slug', $statePageSlugs))->get();
            foreach ($domainStates as $domainState) {
                $domainState->update(['indexing_requested' => true]);
            }
            $stats['domain_states'] = $domainStates->count();
        }

        if (! empty($blogPostSlugs)) {
            $count = BlogPost::whereIn('slug', $blogPostSlugs)->update([
                'indexing_requested' => true,
            ]);
            $stats['blog_posts'] = $count;
        }

        $stats['total'] = $indexedCount;

        Log::info('Google Indexing processed', $stats);

        return $stats;
    }

    public function checkIndexedStatus(string $url): ?array
    {
        $accessToken = $this->getAccessToken();

        if (! $accessToken) {
            return null;
        }

        try {
            $response = $this->getHttpClient()->get(self::API_URL, [
                'headers' => [
                    'Authorization' => 'Bearer '.$accessToken,
                ],
                'query' => [
                    'url' => $url,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);

                return [
                    'url' => $url,
                    'latest_update' => $data['urlNotificationMetadata']['latestUpdate'] ?? null,
                    'indexed' => isset($data['urlNotificationMetadata']['latestUpdate']['type']) &&
                        $data['urlNotificationMetadata']['latestUpdate']['type'] === 'URL_UPDATED',
                ];
            }
        } catch (\Throwable $e) {
            Log::error('Google Indexing status check error', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    public function markAsIndexed(array $urls): int
    {
        $count = 0;
        $baseUrl = config('app.url');

        $servicePageSlugs = [];
        $statePageSlugs = [];
        $blogPostSlugs = [];

        foreach ($urls as $url) {
            $path = str_replace($baseUrl.'/', '', $url);
            if (str_starts_with($path, 'blog/')) {
                $blogPostSlugs[] = str_replace('blog/', '', $path);
            } elseif (str_starts_with($path, 'state/')) {
                $statePageSlugs[] = str_replace('state/', '', $path);
            } else {
                $servicePageSlugs[] = $path;
            }
        }

        if (! empty($servicePageSlugs)) {
            $count += ServicePage::whereIn('slug', $servicePageSlugs)->update([
                'indexed_at' => now(),
            ]);
        }

        if (! empty($statePageSlugs)) {
            $domainStates = DomainState::whereHas('state', fn ($q) => $q->whereIn('slug', $statePageSlugs))->get();
            foreach ($domainStates as $domainState) {
                $domainState->update(['indexed_at' => now()]);
                $count++;
            }
        }

        if (! empty($blogPostSlugs)) {
            $count += BlogPost::whereIn('slug', $blogPostSlugs)->update([
                'indexed_at' => now(),
            ]);
        }

        return $count;
    }

    public function getPendingCount(): int
    {
        // Use the indexing_urls table for tracking
        return IndexingUrl::where('indexed', false)
            ->where('status', 'pending')
            ->count();
    }
}
