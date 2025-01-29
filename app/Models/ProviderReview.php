<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderReview extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'service_provider_id' => 'integer',
        'user_id' => 'integer',
        'booking_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
