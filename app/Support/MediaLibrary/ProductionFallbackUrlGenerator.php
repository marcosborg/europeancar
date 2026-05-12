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

        if ($this->shouldUseMediaController()) {
            return $this->mediaControllerUrl();
        }

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

    private function shouldUseMediaController(): bool
    {
        return $this->media !== null
            && $this->getDiskName() === 'local'
            && Str::startsWith($this->media->mime_type, 'image/')
            && $this->localFileExists();
    }

    private function mediaControllerUrl(?string $baseUrl = null): string
    {
        $path = route('media.show', array_filter([
            'media' => $this->media?->getKey(),
            'conversion' => $this->conversion?->getName(),
        ]), false);

        if ($baseUrl === null) {
            return $path;
        }

        return rtrim($baseUrl, '/').$path;
    }

    private function fallbackUrl(string $url): ?string
    {
        $baseUrl = rtrim((string) config('media-library.fallback_url'), '/');

        if ($baseUrl === '' || $this->media === null || ! Str::startsWith($this->media->mime_type, 'image/')) {
            return null;
        }

        return $this->mediaControllerUrl($baseUrl);
    }
}
