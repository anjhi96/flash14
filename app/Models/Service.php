<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title', 'slug', 'icon', 'short_description', 'description', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
