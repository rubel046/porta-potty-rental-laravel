<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected array $imageFolders = [
        'service-images',
        'hero-banner-images',
    ];

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
                'url' => asset('storage/'.$image),
                'filename' => basename($image),
            ];
        }, $images);
    }

    public function getImagesByKeyword(string $keyword, int $count = 3): array
    {
        $allImages = $this->getAllImages();
        $matched = [];

        foreach ($allImages as $image) {
            $filename = strtolower(basename($image));
            if (str_contains($filename, strtolower($keyword))) {
                $matched[] = $image;
            }
        }

        if (empty($matched)) {
            return $this->getRandomImagesForContent($count);
        }

        shuffle($matched);

        return array_map(function ($image) {
            return [
                'path' => $image,
                'url' => asset('storage/'.$image),
                'filename' => basename($image),
            ];
        }, array_slice($matched, 0, $count));
    }

    protected function getAllImages(): Collection
    {
        $allImages = collect();

        foreach ($this->imageFolders as $folder) {
            $images = $this->getImagesFromFolder($folder);
            $allImages = $allImages->merge($images);
        }

        return $allImages;
    }

    protected function getImagesFromFolder(string $folder): Collection
    {
        try {
            $files = Storage::disk('public')->files($folder);

            return collect($files)->filter(function ($file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
            });
        } catch (\Exception $e) {
            return collect();
        }
    }
}
