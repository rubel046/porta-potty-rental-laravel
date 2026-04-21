<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\DomainState;
use App\Models\IndexingUrl;
use App\Models\ServicePage;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SyncIndexingUrlsCommand extends Command
{
    protected $signature = 'indexing:sync {--type= : Filter by type (service, state, blog, static)}';

    protected $description = 'Sync URLs to indexing tracking table from sitemap';

    public function __construct(
        protected Client $http
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $type = $this->option('type');

        $this->info('Fetching URLs from sitemap...');

        try {
            $sitemapUrls = $this->fetchSitemapUrls();
        } catch (\Throwable $e) {
            $this->error('Failed to fetch sitemap: '.$e->getMessage());
            $sitemapUrls = [];
        }

        $this->info('Syncing to indexing table...');

        $count = 0;
        $baseUrl = config('app.url');
        $staticUrls = [
            '/',
            '/about',
            '/services',
            '/pricing',
            '/locations',
            '/blog',
            '/contact',
            '/privacy',
            '/terms',
        ];

        foreach ($sitemapUrls as $url) {
            if (str_contains($url, 'sitemap')) {
                continue;
            }

            $urlType = $this->detectType($url);

            if ($type && $urlType !== $type) {
                continue;
            }

            IndexingUrl::firstOrCreate(
                ['url' => $url],
                ['type' => $urlType, 'status' => 'pending']
            );
            $count++;
        }

        foreach ($staticUrls as $staticUrl) {
            $fullUrl = $baseUrl.$staticUrl;

            if ($type && 'static' !== $type) {
                continue;
            }

            IndexingUrl::firstOrCreate(
                ['url' => $fullUrl],
                ['type' => 'static', 'status' => 'pending']
            );
            $count++;
        }

        $total = IndexingUrl::count();
        $indexed = IndexingUrl::where('indexed', true)->count();
        $this->info("Synced {$count} URLs. Total: {$total} URLs, {$indexed} indexed");

        return Command::SUCCESS;
    }

    protected function fetchSitemapUrls(): array
    {
        $urls = [];
        $baseUrl = config('app.url');

        // Fetch sitemap-full.xml which contains all URLs
        $sitemapFile = 'sitemap-full.xml';
        try {
            $response = $this->http->get($baseUrl.'/'.$sitemapFile, ['timeout' => 60]);
            $content = $response->getBody()->getContents();
            preg_match_all('/<loc>([^<]+)<\/loc>/', $content, $matches);

            if (! empty($matches[1])) {
                $urls = $matches[1];
                $this->info('Found ' . count($urls) . ' URLs in ' . $sitemapFile);
            }
        } catch (\Throwable $e) {
            $this->warn("Failed to fetch {$sitemapFile}: ".$e->getMessage());
        }

        return $urls;
    }

    protected function detectType(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);

        // Blog posts
        if (str_contains($url, '/blog/')) {
            return 'blog';
        }

        // Static pages
        if ($path === '/' || in_array($path, ['/services', '/pricing', '/locations', '/about', '/blog', '/contact', '/privacy', '/privacy-policy', '/terms', '/terms-of-service'])) {
            return 'static';
        }

        // State pages: /porta-potty-rental-{state} (only state name, no city code)
        if (preg_match('#^/porta-potty-rental-[a-z]+$#', $path)) {
            return 'state';
        }

        // Service pages: URLs with city codes like -pa, -tx, etc. OR service type paths
        if (preg_match('#-[a-z]{2}$#', $path) || str_contains($url, '/construction') || str_contains($url, '/wedding') || str_contains($url, '/event') || str_contains($url, '/luxury') || str_contains($url, '/party') || str_contains($url, '/emergency') || str_contains($url, '/residential') || str_contains($url, '/general') || str_contains($url, '/portable')) {
            return 'service';
        }

        return 'static';
    }
}
