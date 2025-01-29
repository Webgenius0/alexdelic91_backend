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
        return ServiceProviderProfile::with(['user','serviceType','serviceLocation','workingDays','serviceProviderImage'])->get();
    }

    public function providerDetails($id)  {
        return ServiceProviderProfile::with(['user','serviceType','serviceLocation','workingDays','serviceProviderImage'])->findOrFail($id);
    }

}
