<?php

namespace App\Console\Commands;

use App\Models\Domain;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class PingSearchEngines extends Command
{
    protected $signature = 'search:ping-sitemap {--domain= : Domain to ping for (defaults to primary)}';

    protected $description = 'Ping Google and Bing with the sitemap URL to trigger re-indexing';

    public function __construct(
        protected Client $http
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $domainSlug = $this->option('domain');
        $domains = $domainSlug
            ? Domain::where('domain', $domainSlug)->get()
            : Domain::where('is_primary', true)->get();

        if ($domains->isEmpty()) {
            $this->warn('No domains found to ping for.');
            return self::FAILURE;
        }

        $success = 0;
        foreach ($domains as $domain) {
            $baseUrl = rtrim($domain->url ?? "https://{$domain->domain}", '/');
            $sitemapUrl = $baseUrl . '/sitemap.xml';

            $this->info("Pinging search engines for {$domain->domain}...");

            $engines = [
                'Google' => "https://www.google.com/ping?sitemap=" . urlencode($sitemapUrl),
                'Bing'   => "https://www.bing.com/ping?sitemap=" . urlencode($sitemapUrl),
            ];

            foreach ($engines as $name => $pingUrl) {
                try {
                    $response = $this->http->get($pingUrl, ['timeout' => 15]);
                    $status = $response->getStatusCode();
                    if ($status === 200) {
                        $this->info("  ✓ {$name} pinged successfully");
                        $success++;
                    } else {
                        $this->warn("  ⚠ {$name} returned HTTP {$status}");
                    }
                } catch (\Throwable $e) {
                    $this->warn("  ⚠ {$name} ping failed: {$e->getMessage()}");
                }
            }
        }

        $total = count($domains) * 2;
        $this->info("Ping complete: {$success}/{$total} successful");

        return self::SUCCESS;
    }
}
