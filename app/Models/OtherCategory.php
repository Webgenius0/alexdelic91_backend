<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherCategory extends Model
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

    // Define the relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Define the relationship with ServiceProviderProfile
    public function serviceProviderProfile()
    {
        return $this->belongsTo(ServiceProviderProfile::class, 'service_provider_profile_id');
    }
}
