<?php

namespace App\Repositories;

use App\Interface\ServiceProviderInterface;
use App\Models\ServiceProviderProfile;
use Illuminate\Support\Facades\DB;

class ServiceProviderRepository implements ServiceProviderInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function providers(array $queryParams)
    {

        $userId = auth()->id();
        $user = auth()->user();
        $data = ServiceProviderProfile::query()
            ->with(['user', 'subCategories', 'serviceType', 'serviceLocation', 'workingDays', 'serviceProviderImage', 'bookingDataAndTime', 'category.subCategories', 'booking.feedbacks'])
            ->withAvg('feedbacks', 'rating');

        //        return $data->get();
        if ($queryParams == []) {
            $providers = $data->get();

            $bookmarkedProviderIds = DB::table('book_marks')
                ->where('user_id', $userId)
                ->pluck('service_provider_id')
                ->toArray();
            // return $bookmarkedProviderIds;
            // Add the is_bookmark flag
            $providers->map(function ($provider) use ($bookmarkedProviderIds) {
                // Check if the provider's user_id is in the bookmarked list
                $provider->is_bookmark = in_array($provider->user_id, $bookmarkedProviderIds);
                return $provider;
            });

            return $providers;
        }
        if (!empty($queryParams['business_name'])) {
            $data = $data->where('business_name', 'like', '%' . $queryParams['business_name'] . '%');
        }

        if (!empty($queryParams['latitude']) && !empty($queryParams['longitude'] && !empty($queryParams['radius']))) {

            $radiusInMeters = $queryParams['radius'];
            $radiusInDegrees = $radiusInMeters / 111000;

            $data = $data->where('latitude', '>=', $queryParams['latitude'] - $radiusInDegrees)
                ->where('latitude', '<=', $queryParams['latitude'] + $radiusInDegrees)
                ->where('longitude', '>=', $queryParams['longitude'] - ($radiusInDegrees / cos(deg2rad($queryParams['latitude']))))
                ->where('longitude', '<=', $queryParams['longitude'] + ($radiusInDegrees / cos(deg2rad($queryParams['latitude']))));
        }

        if (!empty($queryParams['category_id'])) {
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

        // Fetch the data as a collection
        $providers = $data->get();

        $bookmarkedProviderIds = DB::table('book_marks')
            ->where('user_id', $userId)
            ->pluck('service_provider_id')
            ->toArray();
        // return $bookmarkedProviderIds;
        // Add the is_bookmark flag
        $providers->map(function ($provider) use ($bookmarkedProviderIds) {
            // Check if the provider's user_id is in the bookmarked list
            $provider->is_bookmark = in_array($provider->user_id, $bookmarkedProviderIds);
            return $provider;
        });

        return $providers;
    }


    public function getProviderDetails($id)
    {
        return ServiceProviderProfile::with(['user', 'serviceType', 'serviceLocation', 'workingDays', 'serviceProviderImage', 'bookingDataAndTime'])->find($id);
    }
}
