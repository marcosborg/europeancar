<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'necessary' => 'boolean',
            'analytics' => 'boolean',
            'marketing' => 'boolean',
            'consented_at' => 'datetime',
        ];
    }
}
