<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
    ];

    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
    ];
}
