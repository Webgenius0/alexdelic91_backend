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

    public function providers(array $queryParams) {

        $data = ServiceProviderProfile::query()->with(['user','subCategories','serviceType','serviceLocation','workingDays','serviceProviderImage','bookingDataAndTime','category.subCategories','booking.feedbacks']);
//        return $data->get();
        if ($queryParams == []) {
            return $data->get();
        }
        if (!empty($queryParams['business_name'])) {
            $data = $data->where('business_name', 'like', '%' . $queryParams['business_name'] . '%');
        }

        if (!empty($queryParams['latitude']) && !empty($queryParams['longitude'] && !empty($queryParams['radius']))) {
            $data = $data->selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance", [$queryParams['latitude'], $queryParams['longitude'], $queryParams['latitude']])->having('distance', '<', $queryParams['radius']);

        }

        if(!empty($queryParams['category_id'])) {
            $data = $data->where('category_id', $queryParams['category_id']);
        }

        if (!empty($queryParams['subcategory_id'])) {
            $data = $data->whereHas('subCategories', function ($query) use ($queryParams) {
                $query->where('subcategories.id', $queryParams['subcategory_id']);
            });
        }

        if (!empty($queryParams['avg_rating'])) {
            $data = $data->withAvg('feedbacks', 'rating')->having('feedbacks_avg_rating', '>=', floatval($queryParams['avg_rating']));
        }


        return $data->get();
    }


    public function getProviderDetails($id)  {
        return ServiceProviderProfile::with(['user','serviceType','serviceLocation','workingDays','serviceProviderImage','bookingDataAndTime'])->findOrFail($id);
    }

}
