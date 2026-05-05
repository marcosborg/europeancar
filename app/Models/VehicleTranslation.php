<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleTranslation extends Model
{
    protected $fillable = [
        'vehicle_id',
        'locale',
        'title',
        'slug',
        'description',
        'meta_title',
        'meta_description',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
