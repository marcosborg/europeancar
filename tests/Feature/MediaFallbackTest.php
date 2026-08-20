<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

test('missing sandbox media is read from production storage', function (): void {
    Storage::fake('local');
    config()->set('media-library.fallback_url', 'https://production.example');

    $media = Media::query()->create([
        'model_type' => 'vehicle',
        'model_id' => 1,
        'collection_name' => 'vehicle_main',
        'name' => 'vehicle',
        'file_name' => 'vehicle.jpg',
        'mime_type' => 'image/jpeg',
        'disk' => 'local',
        'conversions_disk' => 'local',
        'size' => 12,
        'manipulations' => [],
        'custom_properties' => [],
        'generated_conversions' => [],
        'responsive_images' => [],
    ]);

    Http::fake([
        "https://production.example/media-library/{$media->getKey()}" => Http::response(
            'production-image',
            200,
            ['Content-Type' => 'image/jpeg'],
        ),
    ]);

    expect($media->getUrl())->toBe("/media-library/{$media->getKey()}");

    $this->get($media->getUrl())
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'image/jpeg')
        ->assertContent('production-image');

    Http::assertSent(fn ($request): bool => $request->url() === "https://production.example/media-library/{$media->getKey()}");
});
