<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Order newest first.
     *
     * Named `recent` rather than `latest` because Eloquent's query builder
     * already defines a native `latest()` method — a local scope with that
     * exact name would never be reached (PHP resolves the real method
     * before falling back to the scope-dispatch magic method).
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest();
    }
}
