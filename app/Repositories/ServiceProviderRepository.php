<?php

namespace App\Repositories;

use App\Interface\ServiceProviderInterface;
use App\Models\ServiceProviderProfile;

class ServiceProviderRepository implements ServiceProviderInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function providers() {
        return ServiceProviderProfile::with(['user','serviceType','serviceLocation','workingDays','serviceProviderImage','bookingDataAndTime'])->get();
    }

    public function getProviderDetails($id)  {
        return ServiceProviderProfile::with(['user','serviceType','serviceLocation','workingDays','serviceProviderImage','bookingDataAndTime'])->findOrFail($id);
    }

}
