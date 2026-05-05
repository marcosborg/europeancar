<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VehicleFeature extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFeatureFactory> */
    use HasFactory;

    protected $fillable = ['category', 'slug', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(VehicleFeatureTranslation::class);
    }

    public function currentTranslation(): HasOne
    {
        return $this->hasOne(VehicleFeatureTranslation::class)->where('locale', app()->getLocale());
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'feature_vehicle');
    }

    public function name(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $this->translations->firstWhere('locale', $locale)?->name
            ?? $this->translations->firstWhere('locale', 'pt')?->name
            ?? $this->slug;
    }
}
