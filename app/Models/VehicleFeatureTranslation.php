<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleFeatureTranslation extends Model
{
    protected $fillable = ['vehicle_feature_id', 'locale', 'name'];

    public function feature(): BelongsTo
    {
        return $this->belongsTo(VehicleFeature::class, 'vehicle_feature_id');
    }
}
