<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function show(Media $media, ?string $conversion = null): Response|StreamedResponse
    {
        abort_unless(Str::startsWith($media->mime_type, 'image/'), 404);

        if ($conversion !== null) {
            abort_unless($media->hasGeneratedConversion($conversion), 404);
        }

        $disk = $conversion === null ? $media->disk : $media->conversions_disk;
        $path = $media->getPathRelativeToRoot($conversion ?? '');

        if (! Storage::disk($disk)->exists($path)) {
            return $this->productionResponse($media, $conversion);
        }

        return Storage::disk($disk)->response($path, $media->file_name, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    private function productionResponse(Media $media, ?string $conversion): Response
    {
        abort_if(app()->isProduction(), 404);

        $baseUrl = rtrim((string) config('media-library.fallback_url'), '/');

        abort_if($baseUrl === '', 404);

        $path = route('media.show', array_filter([
            'media' => $media->getKey(),
            'conversion' => $conversion,
        ]), false);

        $response = Http::connectTimeout(3)
            ->timeout(10)
            ->get($baseUrl.$path);

        abort_unless($response->successful(), $response->notFound() ? 404 : 502);

        return response($response->body(), 200, [
            'Cache-Control' => 'public, max-age=31536000',
            'Content-Type' => $response->header('Content-Type', $media->mime_type),
        ]);
    }
}
