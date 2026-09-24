<?php

use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('the public layout uses the logo uploaded in the backoffice', function (): void {
    Storage::fake('public');

    $settings = SiteSetting::current();
    $settings
        ->addMedia(UploadedFile::fake()->image('backoffice-logo.png'))
        ->toMediaCollection('site_logo', 'public');

    $this->get('/pt')
        ->assertSuccessful()
        ->assertSee($settings->fresh()->siteLogoUrl(), false)
        ->assertDontSee(asset('assets/img/logo.png'), false);
});

test('the public layout falls back to the static logo', function (): void {
    $this->get('/pt')
        ->assertSuccessful()
        ->assertSee(asset('assets/img/logo.png'), false);
});
