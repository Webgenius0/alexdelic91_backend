<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'booking_id' => 'integer',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }


}
