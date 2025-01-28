<?php

namespace App\Repositories;

use App\Interface\ServiceProdiversInterface;
use App\Models\ServiceProviderProfile;

class ServiceProviersRepository implements ServiceProdiversInterface
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
