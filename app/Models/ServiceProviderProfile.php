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

    public function feedbacksReceived()
    {
        return $this->hasMany(Feedback::class, 'service_provider_id');
    }

    public function bookingDataAndTime(){
        return $this->hasMany(Booking::class, 'service_provider_id');
    }
}
