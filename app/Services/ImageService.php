<?php

namespace App\Services;

use App\Models\Domain;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected array $imageFolders = [
        'service-images',
        'hero-banner-images',
    ];

    public function getDomainPrefix(): string
    {
        $domain = Domain::current();

        if (! $domain) {
            Log::error('ImageService: No domain found');
            return '';
        }

        $prefix = rtrim($domain->domain, '.com');
        if (! $this->folderExists("{$prefix}/service-images")) {
            Log::error("ImageService: Folder not found for domain {$prefix}");
            return '';
        }

        return $prefix;
    }

    protected function folderExists(string $path): bool
    {
        try {
            return count(Storage::disk('public')->files($path)) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getRandomImages(int $count = 5, ?string $folder = null): array
    {
        if ($folder) {
            $images = $this->getImagesFromFolder($folder);
        } else {
            $images = $this->getAllImages();
        }

        if (empty($images)) {
            return [];
        }

        $shuffled = $images->toArray();
        shuffle($shuffled);

        return array_slice($shuffled, 0, $count);
    }

    public function getRandomImagesForContent(int $count = 5): array
    {
        $images = $this->getRandomImages($count);

        return array_map(function ($image) {
            return [
                'path' => $image,
                'filename' => basename($image),
            ];
        }, $images);
    }

    protected function getAllImages(): Collection
    {
        $allImages = collect();
        $prefix = $this->getDomainPrefix();

        foreach ($this->imageFolders as $folder) {
            $images = $this->getImagesFromFolder($folder, $prefix);
            $allImages = $allImages->merge($images);
        }

        return $allImages;
    }

    protected function getImagesFromFolder(string $folder, ?string $prefix = null): Collection
    {
        try {
            $path = $prefix ? "{$prefix}/{$folder}" : $folder;
            $files = Storage::disk('public')->files($path);

            return collect($files)->filter(function ($file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
            });
        } catch (\Exception $e) {
            return collect();
        }
    }
}
