<?php

namespace App\Services;

use App\Models\ServicePage;

class PageQualityService
{
    public function score(ServicePage $page): array
    {
        $score = 0;
        $maxScore = 100;
        $details = [];

        $wordCount = str_word_count(strip_tags($page->content ?? ''));
        if ($wordCount >= 800) { $score += 25; $details[] = ['Content length', 25, "{$wordCount} words (target: 800+)"]; }
        elseif ($wordCount >= 500) { $score += 15; $details[] = ['Content length', 15, "{$wordCount} words (target: 800+)"]; }
        elseif ($wordCount >= 300) { $score += 10; $details[] = ['Content length', 10, "{$wordCount} words (target: 800+)"]; }
        else { $details[] = ['Content length', 0, "{$wordCount} words (target: 800+)"]; }

        $titleLen = strlen($page->meta_title ?? '');
        if ($titleLen >= 30 && $titleLen <= 60) { $score += 10; $details[] = ['Meta title length', 10, "{$titleLen} chars (target: 30-60)"]; }
        else { $details[] = ['Meta title length', 0, "{$titleLen} chars (target: 30-60)"]; }

        $descLen = strlen($page->seo_description ?? '');
        if ($descLen >= 120 && $descLen <= 160) { $score += 10; $details[] = ['Meta description', 10, "{$descLen} chars (target: 120-160)"]; }
        else { $details[] = ['Meta description', 0, "{$descLen} chars (target: 120-160)"]; }

        $h1Len = strlen($page->h1_title ?? '');
        if ($h1Len >= 20 && $h1Len <= 70) { $score += 5; $details[] = ['H1 title', 5, "{$h1Len} chars (target: 20-70)"]; }
        else { $details[] = ['H1 title', 0, "{$h1Len} chars (target: 20-70)"]; }

        $faqCount = $page->city?->faqs()?->where('is_active', true)->count() ?? 0;
        if ($faqCount >= 5) { $score += 10; $details[] = ['FAQs', 10, "{$faqCount} FAQs"]; }
        elseif ($faqCount >= 3) { $score += 7; $details[] = ['FAQs', 7, "{$faqCount} FAQs"]; }
        elseif ($faqCount >= 1) { $score += 4; $details[] = ['FAQs', 4, "{$faqCount} FAQs"]; }
        else { $details[] = ['FAQs', 0, 'No FAQs']; }

        $testCount = $page->city?->testimonials()?->where('is_active', true)->count() ?? 0;
        if ($testCount >= 4) { $score += 10; $details[] = ['Testimonials', 10, "{$testCount} testimonials"]; }
        elseif ($testCount >= 2) { $score += 7; $details[] = ['Testimonials', 7, "{$testCount} testimonials"]; }
        elseif ($testCount >= 1) { $score += 4; $details[] = ['Testimonials', 4, "{$testCount} testimonials"]; }
        else { $details[] = ['Testimonials', 0, 'No testimonials']; }

        $hasGeo = !empty($page->city?->latitude) && !empty($page->city?->longitude);
        $hasZip = !empty($page->city?->zip_codes);
        if ($hasGeo && $hasZip) { $score += 10; $details[] = ['Geo data', 10, 'Has lat/lng + zip']; }
        elseif ($hasGeo) { $score += 7; $details[] = ['Geo data', 7, 'Has lat/lng only']; }
        else { $details[] = ['Geo data', 0, 'Missing geo data']; }

        if ($page->is_published) { $score += 5; $details[] = ['Published', 5, 'Yes']; }
        else { $details[] = ['Published', 0, 'Not published']; }

        $nearbyCount = $page->city?->getNearbyAreaNames() ? count($page->city->getNearbyAreaNames()) : 0;
        if ($nearbyCount >= 5) { $score += 10; $details[] = ['Nearby cities', 10, "{$nearbyCount} nearby"]; }
        elseif ($nearbyCount >= 3) { $score += 7; $details[] = ['Nearby cities', 7, "{$nearbyCount} nearby"]; }
        elseif ($nearbyCount >= 1) { $score += 4; $details[] = ['Nearby cities', 4, "{$nearbyCount} nearby"]; }
        else { $details[] = ['Nearby cities', 0, "{$nearbyCount} nearby"]; }

        $hasImages = preg_match('/<img[^>]+>/i', $page->content ?? '');
        if ($hasImages) { $score += 5; $details[] = ['Content images', 5, 'Has images']; }
        else { $details[] = ['Content images', 0, 'No images']; }

        $grade = $score >= 80 ? 'A' : ($score >= 60 ? 'B' : ($score >= 40 ? 'C' : ($score >= 20 ? 'D' : 'F')));

        return [
            'score' => $score,
            'max_score' => $maxScore,
            'grade' => $grade,
            'word_count' => $wordCount,
            'faq_count' => $faqCount,
            'testimonial_count' => $testCount,
            'details' => $details,
        ];
    }

    public function scoreAllForDomain($domainId): array
    {
        $pages = ServicePage::where('domain_id', $domainId)
            ->with('city.state')
            ->limit(500)
            ->get();

        $results = [];
        foreach ($pages as $page) {
            $results[] = [
                'page' => $page,
                'score' => $this->score($page),
            ];
        }

        usort($results, fn($a, $b) => $a['score']['score'] - $b['score']['score']);

        return $results;
    }
}
