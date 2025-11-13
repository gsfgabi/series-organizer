<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedLink extends Model
{
    protected $fillable = [
        'title',
        'url',
        'icon',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];
}
