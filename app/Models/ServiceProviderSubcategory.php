<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProviderSubcategory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'service_provider_profile_id' => 'integer',
        'subcategory_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function serviceProviders()
    {
        return $this->belongsToMany(ServiceProviderProfile::class, 'service_provider_subcategories', 'subcategory_id', 'service_provider_id');
    }
}
