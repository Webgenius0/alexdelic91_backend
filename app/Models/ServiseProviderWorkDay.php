<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiseProviderWorkDay extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'service_provider_profile_id' => 'integer',
        'day_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function day()
    {
        return $this->belongsTo(Day::class, 'day_id');
    }
}
