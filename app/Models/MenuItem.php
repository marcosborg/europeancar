<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    /** @use HasFactory<\Database\Factories\MenuItemFactory> */
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'page_id',
        'label',
        'url',
        'route_name',
        'route_parameters',
        'open_in_new_tab',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'route_parameters' => 'array',
            'open_in_new_tab' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function resolvedUrl(): string
    {
        if ($this->url) {
            return $this->url;
        }

        if ($this->route_name) {
            return route($this->route_name, $this->route_parameters ?? []);
        }

        return '#';
    }
}
