<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $fillable = [
        'page',
        'section_key',
        'section_name',
        'is_enabled',
        'order',
        'settings',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'settings'   => 'array',
        'order'      => 'integer',
    ];

    /**
     * Get sections for a given page, keyed by section_key.
     */
    public static function forPage(string $page = 'home'): \Illuminate\Support\Collection
    {
        return static::where('page', $page)
            ->orderBy('order')
            ->get()
            ->keyBy('section_key');
    }

    /**
     * Check if a section is enabled.
     */
    public static function isEnabled(string $sectionKey, bool $default = true): bool
    {
        $section = static::where('section_key', $sectionKey)->first();

        return $section ? $section->is_enabled : $default;
    }
}
