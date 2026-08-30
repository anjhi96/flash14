<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsGenerationRun extends Model
{
    protected $fillable = [
        'triggered_by', 'started_at', 'finished_at',
        'items_fetched', 'articles_created', 'status', 'error_message',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'items_fetched' => 'integer',
            'articles_created' => 'integer',
        ];
    }
}
