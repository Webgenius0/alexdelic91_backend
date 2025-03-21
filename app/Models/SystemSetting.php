<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemSetting extends Model {

    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
