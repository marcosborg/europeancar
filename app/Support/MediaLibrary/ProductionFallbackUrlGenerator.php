<?php

namespace App\Support\MediaLibrary;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;
use Throwable;

class ProductionFallbackUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        $url = parent::getUrl();

        if ($this->localFileExists()) {
            return $url;
        }

        return $this->fallbackUrl($url) ?? $url;
    }

    private function localFileExists(): bool
    {
        try {
            return Storage::disk($this->getDiskName())->exists($this->getPathRelativeToRoot());
        } catch (Throwable) {
            return false;
        }
    }

    private function fallbackUrl(string $url): ?string
    {
        $baseUrl = rtrim((string) config('media-library.fallback_url'), '/');

        if ($baseUrl === '' || $this->media === null || ! Str::startsWith($this->media->mime_type, 'image/')) {
            return null;
        }

        $path = 'media-library/'.$this->media->getKey();

        if ($this->conversion !== null) {
            $path .= '/'.$this->conversion->getName();
        }

        return $baseUrl.'/'.$path;
    }
}
