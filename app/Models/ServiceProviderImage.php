<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProviderImage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'service_provider_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProviderProfile::class, 'service_provider_id');
    }
}
