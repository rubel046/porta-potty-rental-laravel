<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\IndexingUrl;
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
            '/privacy',
            '/terms',
            '/complete-guide-to-porta-potty-rental',
            '/units-calculator',
            '/wedding-porta-potty-rental',
            '/festival-portable-toilets',
            '/construction-site-porta-potty-rental',
            '/faq',
            '/osha-porta-potty-requirements',
            '/standard-vs-deluxe-vs-luxury-porta-potty',
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

            if ($type && $type !== 'static') {
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
                $this->info('Found '.count($urls).' URLs in '.$sitemapFile);
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
        $staticPaths = ['/services', '/pricing', '/locations', '/about', '/blog', '/privacy', '/privacy-policy', '/terms', '/terms-of-service', '/complete-guide-to-porta-potty-rental', '/units-calculator', '/wedding-porta-potty-rental', '/festival-portable-toilets', '/construction-site-porta-potty-rental', '/faq', '/osha-porta-potty-requirements', '/standard-vs-deluxe-vs-luxury-porta-potty'];
        if ($path === '/' || in_array($path, $staticPaths)) {
            return 'static';
        }

        // State pages: /{slug_prefix}-rental-{state}
        $slugPrefixes = Domain::pluck('slug_prefix')->filter()->unique()->toArray();
        foreach ($slugPrefixes as $prefix) {
            if (preg_match('#^/'.$prefix.'-rental-[a-z]+$#', $path)) {
                return 'state';
            }
        }

        // Service pages: URLs with city codes like -pa, -tx, etc. OR service type paths
        $serviceTypes = [];
        foreach (Domain::pluck('service_types')->filter() as $types) {
            $serviceTypes = array_merge($serviceTypes, $types);
        }
        $serviceTypes = array_unique($serviceTypes);

        if (preg_match('#-[a-z]{2}$#', $path) || collect($serviceTypes)->contains(fn ($type) => str_contains($url, '/'.$type))) {
            return 'service';
        }

        return 'static';
    }
}
