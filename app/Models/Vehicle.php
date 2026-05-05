<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Vehicle extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'first_registration_date' => 'date',
            'inspection_valid_until' => 'date',
            'published_at' => 'datetime',
            'featured' => 'boolean',
            'premium' => 'boolean',
            'price_on_request' => 'boolean',
            'financing_available' => 'boolean',
            'trade_in_available' => 'boolean',
            'vat_deductible' => 'boolean',
            'maintenance_history' => 'boolean',
            'service_book' => 'boolean',
            'non_smoker' => 'boolean',
            'accident_free' => 'boolean',
            'delivery_collection_available' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('vehicle_main')->singleFile();
        $this->addMediaCollection('vehicle_gallery');
        $this->addMediaCollection('vehicle_social');
        $this->addMediaCollection('vehicle_documents');
        $this->addMediaCollection('vehicle_technical_sheet')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('card')->width(900)->height(600)->nonQueued();
        $this->addMediaConversion('social')->width(1080)->height(1080)->nonQueued();
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(VehicleTranslation::class);
    }

    public function currentTranslation(): HasOne
    {
        return $this->hasOne(VehicleTranslation::class)->where('locale', app()->getLocale());
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(VehicleFeature::class, 'feature_vehicle');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function socialExports(): HasMany
    {
        return $this->hasMany(SocialExport::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeForSale(Builder $query): Builder
    {
        return $query->whereIn('type', ['sale', 'sale_rent']);
    }

    public function scopeForRent(Builder $query): Builder
    {
        return $query->whereIn('type', ['rent', 'sale_rent']);
    }

    public function translation(?string $locale = null): ?VehicleTranslation
    {
        $locale ??= app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'pt');
    }

    public function publicTitle(?string $locale = null): string
    {
        return $this->translation($locale)?->title
            ?? trim(($this->brand?->name ?? '').' '.($this->carModel?->name ?? '').' '.($this->version ?? ''));
    }

    public function publicUrl(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $translation = $this->translation($locale);

        return route($locale === 'en' ? 'vehicles.show.en' : 'vehicles.show.pt', [
            'locale' => $locale,
            'slug' => $translation?->slug ?? $this->id,
        ]);
    }

    public function mainImageUrl(string $conversion = ''): ?string
    {
        return $this->getFirstMediaUrl('vehicle_main', $conversion) ?: $this->getFirstMediaUrl('vehicle_gallery', $conversion);
    }
}
