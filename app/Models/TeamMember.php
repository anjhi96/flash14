<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'name', 'position', 'photo', 'bio', 'linkedin_url', 'order',
    ];
}
