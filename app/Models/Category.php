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

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function serviceProviderProfiles()
    {
        return $this->belongsToMany(ServiceProviderProfile::class, 'service_provider_profile_categories', 'category_id', 'service_provider_profile_id');
    }
}
