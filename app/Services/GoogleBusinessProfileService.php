<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\GmbAccount;
use App\Models\GmbPost;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleBusinessProfileService
{
    protected const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    protected const MYBUSINESS_API = 'https://mybusiness.googleapis.com/v4';

    protected ?Client $httpClient = null;

    protected ?string $clientId = null;
    protected ?string $clientSecret = null;

    public function __construct()
    {
        $this->clientId = config('services.gmb.client_id');
        $this->clientSecret = config('services.gmb.client_secret');
    }

    public function isConfigured(): bool
    {
        return !empty($this->clientId) && !empty($this->clientSecret);
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

    public function getAuthUrl(): string
    {
        $redirectUri = url(config('services.gmb.redirect_uri'));
        $scopes = implode(' ', config('services.gmb.scopes', []));

        $params = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'scope' => $scopes,
            'response_type' => 'code',
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        return "https://accounts.google.com/o/oauth2/v2/auth?{$params}";
    }

    public function exchangeAuthCode(string $code): array
    {
        $redirectUri = url(config('services.gmb.redirect_uri'));

        try {
            $response = $this->getHttpClient()->post(self::TOKEN_URL, [
                'form_params' => [
                    'code' => $code,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'redirect_uri' => $redirectUri,
                    'grant_type' => 'authorization_code',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return [
                'success' => true,
                'access_token' => $data['access_token'] ?? null,
                'refresh_token' => $data['refresh_token'] ?? null,
                'expires_in' => $data['expires_in'] ?? 3600,
            ];
        } catch (\Throwable $e) {
            Log::error('GMB OAuth code exchange failed', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function refreshAccessToken(GmbAccount $account): ?string
    {
        $refreshToken = $account->getDecryptedRefreshToken();

        if (empty($refreshToken)) {
            Log::warning('GMB account has no refresh token', ['account_id' => $account->id]);

            return null;
        }

        $cacheKey = "gmb_access_token_{$account->id}";
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        try {
            $response = $this->getHttpClient()->post(self::TOKEN_URL, [
                'form_params' => [
                    'refresh_token' => $refreshToken,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'refresh_token',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['access_token'])) {
                $ttl = ($data['expires_in'] ?? 3600) - 60;
                Cache::put($cacheKey, $data['access_token'], $ttl);

                // Update expiry timestamp
                $account->update([
                    'token_expires_at' => now()->addSeconds($data['expires_in'] ?? 3600),
                ]);

                return $data['access_token'];
            }
        } catch (\Throwable $e) {
            Log::error('GMB token refresh failed', [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    protected function getAccessToken(GmbAccount $account): ?string
    {
        if ($account->token_expires_at && $account->token_expires_at->isFuture()) {
            $cacheKey = "gmb_access_token_{$account->id}";
            $cached = Cache::get($cacheKey);
            if ($cached) {
                return $cached;
            }
        }

        return $this->refreshAccessToken($account);
    }

    public function createPost(GmbAccount $account, string $content, ?string $blogPostId = null): array
    {
        $accessToken = $this->getAccessToken($account);
        if (!$accessToken) {
            return ['success' => false, 'error' => 'Failed to obtain access token'];
        }

        if (empty($account->location_id)) {
            return ['success' => false, 'error' => 'No location ID configured'];
        }

        $locationPath = "accounts/{$account->account_name}/locations/{$account->location_id}";
        $url = self::MYBUSINESS_API . "/{$locationPath}/localPosts";

        // GBP posts have a 1500 character limit for event posts, but regular posts
        // are limited. Let's keep content under 750 chars to be safe.
        $summary = mb_strlen($content) > 700
            ? mb_substr($content, 0, 697) . '...'
            : $content;

        $body = [
            'summary' => $summary,
            'callToAction' => [
                'actionType' => 'LEARN_MORE',
                'url' => $blogPostId
                    ? url("/blog/{$blogPostId}")
                    : url('/'),
            ],
        ];

        try {
            $response = $this->getHttpClient()->post($url, [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => $body,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() === 200) {
                // Find the blog post ID from slug if provided
                $blogPost = null;
                if ($blogPostId) {
                    $blogPost = BlogPost::where('slug', $blogPostId)->first();
                }

                GmbPost::create([
                    'gmb_account_id' => $account->id,
                    'type' => 'blog_post',
                    'content' => $summary,
                    'external_id' => $data['name'] ?? null,
                    'blog_post_id' => $blogPost?->id,
                    'status' => 'published',
                    'response_data' => json_encode($data),
                    'published_at' => now(),
                ]);

                $account->increment('total_posts_count');
                $account->update(['last_posted_at' => now()]);

                Log::info('GMB post created successfully', [
                    'account_id' => $account->id,
                    'external_id' => $data['name'] ?? null,
                ]);

                return [
                    'success' => true,
                    'external_id' => $data['name'] ?? null,
                    'data' => $data,
                ];
            }

            GmbPost::create([
                'gmb_account_id' => $account->id,
                'type' => 'blog_post',
                'content' => $summary,
                'blog_post_id' => $blogPost?->id,
                'status' => 'failed',
                'response_data' => json_encode($data),
            ]);

            Log::error('GMB post failed', [
                'account_id' => $account->id,
                'status' => $response->getStatusCode(),
                'response' => $data,
            ]);

            return ['success' => false, 'error' => $data['error']['message'] ?? 'Unknown error'];
        } catch (RequestException $e) {
            $message = $e->getMessage();
            $responseBody = null;
            if ($e->hasResponse()) {
                $responseBody = (string) $e->getResponse()->getBody();
                $responseData = json_decode($responseBody, true);
                $message = $responseData['error']['message'] ?? $message;
            }

            $blogPost = null;
            if ($blogPostId) {
                $blogPost = BlogPost::where('slug', $blogPostId)->first();
            }

            GmbPost::create([
                'gmb_account_id' => $account->id,
                'type' => 'blog_post',
                'content' => $summary,
                'blog_post_id' => $blogPost?->id,
                'status' => 'failed',
                'response_data' => $responseBody,
            ]);

            Log::error('GMB post request failed', [
                'account_id' => $account->id,
                'error' => $message,
            ]);

            if ($e->getCode() === 401) {
                Cache::forget("gmb_access_token_{$account->id}");
            }

            return ['success' => false, 'error' => $message];
        } catch (\Throwable $e) {
            Log::error('GMB post unexpected error', [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function fetchReviews(GmbAccount $account): array
    {
        $accessToken = $this->getAccessToken($account);
        if (!$accessToken) {
            return ['success' => false, 'error' => 'Failed to obtain access token'];
        }

        if (empty($account->location_id)) {
            return ['success' => false, 'error' => 'No location ID configured'];
        }

        $locationPath = "accounts/{$account->account_name}/locations/{$account->location_id}";
        $url = self::MYBUSINESS_API . "/{$locationPath}/reviews";

        try {
            $allReviews = [];
            $pageToken = null;

            do {
                $params = [];
                if ($pageToken) {
                    $params['pageToken'] = $pageToken;
                }

                $response = $this->getHttpClient()->get($url, [
                    'headers' => ['Authorization' => "Bearer {$accessToken}"],
                    'query' => $params,
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if (isset($data['reviews'])) {
                    $allReviews = array_merge($allReviews, $data['reviews']);
                }

                $pageToken = $data['nextPageToken'] ?? null;
            } while ($pageToken);

            $totalReviews = count($allReviews);
            $unreadReviews = count(array_filter($allReviews, fn($r) =>
                empty($r['comment']['originalReply'] ?? null)
            ));

            $account->update([
                'total_reviews_count' => $totalReviews,
                'unread_reviews_count' => $unreadReviews,
                'last_review_sync_at' => now(),
            ]);

            Log::info('GMB reviews fetched', [
                'account_id' => $account->id,
                'total' => $totalReviews,
                'unread' => $unreadReviews,
            ]);

            return [
                'success' => true,
                'reviews' => $allReviews,
                'total' => $totalReviews,
                'unread' => $unreadReviews,
            ];
        } catch (\Throwable $e) {
            Log::error('GMB fetch reviews failed', [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function replyToReview(GmbAccount $account, string $reviewId, string $replyText): array
    {
        $accessToken = $this->getAccessToken($account);
        if (!$accessToken) {
            return ['success' => false, 'error' => 'Failed to obtain access token'];
        }

        if (empty($account->location_id)) {
            return ['success' => false, 'error' => 'No location ID configured'];
        }

        $locationPath = "accounts/{$account->account_name}/locations/{$account->location_id}";
        $url = self::MYBUSINESS_API . "/{$locationPath}/reviews/{$reviewId}/reply";

        try {
            $response = $this->getHttpClient()->post($url, [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'comment' => $replyText,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode() === 200) {
                // Decrement unread count since we replied
                $account->decrement('unread_reviews_count');
                $account->update(['last_review_reply_at' => now()]);

                GmbPost::create([
                    'gmb_account_id' => $account->id,
                    'type' => 'review_reply',
                    'content' => $replyText,
                    'external_id' => $reviewId,
                    'status' => 'published',
                    'response_data' => json_encode($data),
                    'published_at' => now(),
                ]);

                Log::info('GMB review reply posted', [
                    'account_id' => $account->id,
                    'review_id' => $reviewId,
                ]);

                return ['success' => true, 'data' => $data];
            }

            Log::error('GMB review reply failed', [
                'account_id' => $account->id,
                'review_id' => $reviewId,
                'response' => $data,
            ]);

            return ['success' => false, 'error' => $data['error']['message'] ?? 'Unknown error'];
        } catch (RequestException $e) {
            $message = $e->getMessage();
            if ($e->hasResponse()) {
                $data = json_decode($e->getResponse()->getBody()->getContents(), true);
                $message = $data['error']['message'] ?? $message;
            }

            Log::error('GMB review reply request failed', [
                'account_id' => $account->id,
                'review_id' => $reviewId,
                'error' => $message,
            ]);

            if ($e->getCode() === 401) {
                Cache::forget("gmb_access_token_{$account->id}");
            }

            return ['success' => false, 'error' => $message];
        } catch (\Throwable $e) {
            Log::error('GMB review reply unexpected error', [
                'account_id' => $account->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function autoReplyToUnreviewed(GmbAccount $account, ?string $template = null): array
    {
        if (!$account->auto_reply_reviews) {
            return ['success' => false, 'error' => 'Auto-reply is disabled for this account'];
        }

        $result = $this->fetchReviews($account);
        if (!$result['success']) {
            return $result;
        }

        $reviews = $result['reviews'];
        $replied = 0;
        $errors = [];

        $defaultTemplate = "Thank you for your review! We appreciate your feedback and are glad you had a great experience with us. If you ever need anything else, don't hesitate to reach out.";

        foreach ($reviews as $review) {
            if (!empty($review['comment']['originalReply'] ?? null)) {
                // Already replied, skip
                continue;
            }

            $reviewId = $review['reviewId'] ?? null;
            if (!$reviewId) {
                continue;
            }

            $starRating = $review['starRating'] ?? 5;
            $replyText = $template ?? $defaultTemplate;

            // For lower ratings, use a more empathetic template
            if ($starRating < 4) {
                $replyText = "Thank you for your feedback. We take all reviews seriously and would love the opportunity to make things right. Please contact us directly so we can address your concerns personally.";
            }

            $replyResult = $this->replyToReview($account, $reviewId, $replyText);
            if ($replyResult['success']) {
                $replied++;
            } else {
                $errors[] = $replyResult['error'];
            }

            // Rate limit: 1 reply per second
            usleep(1000000);
        }

        Log::info('GMB auto-reply completed', [
            'account_id' => $account->id,
            'replied' => $replied,
            'errors' => count($errors),
        ]);

        return [
            'success' => true,
            'replied' => $replied,
            'errors' => $errors,
        ];
    }

    public function postBlogPost(GmbAccount $account, BlogPost $blogPost): array
    {
        // Check if already posted
        $existing = GmbPost::where('blog_post_id', $blogPost->id)
            ->where('status', 'published')
            ->first();

        if ($existing) {
            return ['success' => false, 'error' => 'Blog post already published to GBP'];
        }

        $content = strip_tags($blogPost->excerpt ?: mb_substr($blogPost->content, 0, 500));
        $content .= "\n\nRead more: " . url("/blog/{$blogPost->slug}");

        return $this->createPost($account, $content, $blogPost->slug);
    }

    public function getPendingBlogPosts(GmbAccount $account): array
    {
        $domainId = $account->domain_id;

        // Get blog posts that haven't been posted to this GMB account
        $postedBlogPostIds = GmbPost::where('gmb_account_id', $account->id)
            ->where('blog_post_id', '!=', null)
            ->where('status', 'published')
            ->pluck('blog_post_id')
            ->toArray();

        return BlogPost::where('domain_id', $domainId)
            ->where('is_published', true)
            ->whereNotIn('id', $postedBlogPostIds)
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function syncAccount(GmbAccount $account): array
    {
        $results = [
            'posts_created' => 0,
            'reviews_fetched' => 0,
            'reviews_replied' => 0,
            'errors' => [],
        ];

        // Step 1: Create pending blog posts if auto_post is enabled
        if ($account->auto_post) {
            $pendingPosts = $this->getPendingBlogPosts($account);
            foreach ($pendingPosts as $post) {
                $blogPost = BlogPost::find($post['id']);
                if (!$blogPost) {
                    continue;
                }

                $result = $this->postBlogPost($account, $blogPost);
                if ($result['success']) {
                    $results['posts_created']++;
                } else {
                    $results['errors'][] = "Post '{$blogPost->title}': {$result['error']}";
                }

                usleep(500000); // 0.5s between posts to avoid rate limits
            }
        }

        // Step 2: Fetch reviews
        $reviewResult = $this->fetchReviews($account);
        if ($reviewResult['success']) {
            $results['reviews_fetched'] = $reviewResult['total'] ?? 0;
        } else {
            $results['errors'][] = "Review fetch: {$reviewResult['error']}";
        }

        // Step 3: Auto-reply to unreviewed reviews
        if ($account->auto_reply_reviews) {
            $replyResult = $this->autoReplyToUnreviewed($account);
            if ($replyResult['success']) {
                $results['reviews_replied'] = $replyResult['replied'] ?? 0;
            } else {
                $results['errors'][] = "Auto-reply: {$replyResult['error']}";
            }
        }

        $account->update(['last_synced_at' => now()]);

        return $results;
    }
}
