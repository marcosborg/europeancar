<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function show(Media $media, ?string $conversion = null): StreamedResponse
    {
        abort_unless(Str::startsWith($media->mime_type, 'image/'), 404);

        if ($conversion !== null) {
            abort_unless($media->hasGeneratedConversion($conversion), 404);
        }

        $disk = $conversion === null ? $media->disk : $media->conversions_disk;
        $path = $media->getPathRelativeToRoot($conversion ?? '');

        abort_unless(Storage::disk($disk)->exists($path), 404);

        return Storage::disk($disk)->response($path, $media->file_name, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
