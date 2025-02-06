<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProviderProfile extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'service_type_id' => 'integer',
        'service_location_id' => 'integer',
        'profile_completed' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function serviceLocation()
    {
        return $this->belongsTo(ServiceLocation::class);
    }

    public function workingDays()
    {
        return $this->hasMany(ServiseProviderWorkDay::class, 'service_provider_id');
    }

    public function serviceProviderImage()
    {
        return $this->hasMany(ServiceProviderImage::class, 'service_provider_id');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'service_provider_id');
    }

    public function bookingDataAndTime(){
        return $this->hasMany(Booking::class, 'service_provider_id');
    }

    public function category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'service_provider_subcategories', 'service_provider_id', 'subcategory_id');
    }

    public function feedbacks()
    {
        return $this->hasManyThrough(Feedback::class, Booking::class, 'service_provider_id', 'booking_id');
    }


}
