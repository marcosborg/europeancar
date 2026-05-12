<?php

namespace App\Models;

use Database\Factories\SiteSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SiteSetting extends Model implements HasMedia
{
    /** @use HasFactory<SiteSettingFactory> */
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'footer_text' => 'array',
            'business_hours' => 'array',
            'seo_defaults' => 'array',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('site_logo')->useDisk('public')->singleFile();
        $this->addMediaCollection('site_favicon')->useDisk('public')->singleFile();
        $this->addMediaCollection('site_default_seo')->useDisk('public')->singleFile();
    }

    public static function current(): self
    {
        return self::query()->firstOrCreate([], [
            'site_name' => 'European Car Sales and Rentals',
            'slogan' => 'Drive Europe. Choose Excellence.',
        ]);
    }
}
