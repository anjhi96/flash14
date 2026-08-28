<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'color',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Categories that currently have at least one published post.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereHas('posts', fn (Builder $q) => $q->published());
    }
}
