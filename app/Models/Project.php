<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title', 'slug', 'client', 'category', 'description',
        'thumbnail', 'gallery', 'project_url', 'is_featured', 'order',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
    ];
}
