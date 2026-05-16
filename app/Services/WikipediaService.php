<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WikipediaService
{
    protected const API_URL = 'https://en.wikipedia.org/w/api.php';
    protected const REST_API = 'https://en.wikipedia.org/api/rest_v1';

    protected ?Client $httpClient = null;

    protected function getHttpClient(): Client
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client([
                'timeout' => 15,
                'headers' => [
                    'User-Agent' => 'PottyDirect/1.0 (SEO content enrichment)',
                ],
            ]);
        }

        return $this->httpClient;
    }

    public function getCityData(string $cityName, string $stateName): array
    {
        $pageTitle = $this->findPageTitle($cityName, $stateName);
        if (!$pageTitle) {
            // Try without state
            $pageTitle = $this->searchPage($cityName);
        }

        if (!$pageTitle) {
            return ['success' => false, 'error' => 'Page not found'];
        }

        $summary = $this->getSummary($pageTitle);
        if (!$summary) {
            return ['success' => false, 'error' => 'Summary not available'];
        }

        $sections = $this->getRelevantSections($pageTitle);

        return [
            'success' => true,
            'description' => $summary['extract'] ?? '',
            'coordinates' => $summary['coordinates'] ?? null,
            'economy' => $sections['Economy'] ?? '',
            'geography' => $sections['Geography'] ?? '',
            'climate' => $sections['Climate'] ?? '',
            'culture' => $sections['Culture'] ?? '',
            'transportation' => $sections['Transportation'] ?? '',
            'sports' => $sections['Sports'] ?? '',
            'tourism' => $sections['Tourism and recreation'] ?? $sections['Tourism'] ?? '',
        ];
    }

    protected function findPageTitle(string $cityName, string $stateName): ?string
    {
        // Try "CityName, StateName" format
        $searchQueries = [
            "{$cityName}, {$stateName}",
            "{$cityName}",
        ];

        foreach ($searchQueries as $query) {
            $result = $this->searchPage($query);
            if ($result) {
                return $result;
            }
        }

        return null;
    }

    protected function searchPage(string $query): ?string
    {
        try {
            $response = $this->getHttpClient()->get(self::API_URL, [
                'query' => [
                    'action' => 'query',
                    'list' => 'search',
                    'srsearch' => $query,
                    'srlimit' => 3,
                    'format' => 'json',
                    'srprop' => '',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $pages = $data['query']['search'] ?? [];
            if (empty($pages)) {
                return null;
            }

            // Return the best match title
            return $pages[0]['title'];
        } catch (\Throwable $e) {
            Log::warning('Wikipedia search failed', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    protected function getSummary(string $pageTitle): ?array
    {
        $encodedTitle = urlencode(str_replace(' ', '_', $pageTitle));

        try {
            $response = $this->getHttpClient()->get(self::REST_API . "/page/summary/{$encodedTitle}");
            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data['extract'])) {
                return null;
            }

            return [
                'extract' => $data['extract'],
                'coordinates' => $data['coordinates'] ?? null,
                'description' => $data['description'] ?? '',
                'thumbnail' => $data['thumbnail']['source'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::warning('Wikipedia summary fetch failed', [
                'page' => $pageTitle,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    protected function getRelevantSections(string $pageTitle): array
    {
        $targetSections = [
            'Economy', 'Geography', 'Climate', 'Culture',
            'Transportation', 'Tourism and recreation', 'Tourism', 'Sports',
        ];

        // First get the table of contents
        $encodedTitle = urlencode(str_replace(' ', '_', $pageTitle));
        $result = [];

        try {
            $response = $this->getHttpClient()->get(self::API_URL, [
                'query' => [
                    'action' => 'parse',
                    'page' => $pageTitle,
                    'prop' => 'sections',
                    'format' => 'json',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $sections = $data['parse']['sections'] ?? [];

            foreach ($targetSections as $targetName) {
                foreach ($sections as $section) {
                    if ($section['line'] === $targetName && $section['toclevel'] == 1) {
                        $sectionIndex = $section['index'];
                        $content = $this->getSectionContent($pageTitle, $sectionIndex);
                        if ($content) {
                            $result[$targetName] = $content;
                        }
                        break;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Wikipedia section fetch failed', [
                'page' => $pageTitle,
                'error' => $e->getMessage(),
            ]);
        }

        return $result;
    }

    protected function getSectionContent(string $pageTitle, string $sectionIndex): ?string
    {
        try {
            $response = $this->getHttpClient()->get(self::API_URL, [
                'query' => [
                    'action' => 'parse',
                    'page' => $pageTitle,
                    'prop' => 'text',
                    'section' => $sectionIndex,
                    'format' => 'json',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $html = $data['parse']['text']['*'] ?? '';

            // Strip HTML tags but keep paragraph text
            $text = strip_tags($html);

            // Clean up whitespace
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);

            // Limit to 1000 chars to keep prompts manageable
            return Str::limit($text, 1000);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function buildCityContext(string $cityName, string $stateName, array $cityData): string
    {
        $parts = [];

        if (!empty($cityData['description'])) {
            $parts[] = "DESCRIPTION: {$cityData['description']}";
        }

        if (!empty($cityData['economy'])) {
            $parts[] = "ECONOMY/INDUSTRIES: {$cityData['economy']}";
        }

        if (!empty($cityData['climate'])) {
            $parts[] = "CLIMATE: {$cityData['climate']}";
        }

        if (!empty($cityData['geography'])) {
            $parts[] = "GEOGRAPHY: {$cityData['geography']}";
        }

        if (!empty($cityData['transportation'])) {
            $parts[] = "TRANSPORTATION: {$cityData['transportation']}";
        }

        if (!empty($cityData['tourism'])) {
            $parts[] = "TOURISM/VENUES: {$cityData['tourism']}";
        }

        if (!empty($cityData['sports'])) {
            $parts[] = "SPORTS: {$cityData['sports']}";
        }

        return implode("\n\n", $parts);
    }
}
