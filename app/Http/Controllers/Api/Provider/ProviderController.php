<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Interface\ServiceProviderInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    use ApiResponse;

    private $serviceProviderRepository;

    public function __construct(ServiceProviderInterface $serviceProvidersRepository)
    {
        $this->serviceProviderRepository = $serviceProvidersRepository;
    }

    public function providers(Request $request)
    {
        $queryParams = $request->only(['business_name', 'latitude', 'longitude', 'radius', 'category_id', 'subcategory_id', 'avg_rating']) ?? [];

        $providers = $this->serviceProviderRepository->providers($queryParams);

        return $this->success($providers, 'All providers are here', 200);
    }

    public function providerDetails($id)
    {
        $providerDetails = $this->serviceProviderRepository->getProviderDetails($id);

        if (!$providerDetails) {
            return $this->error([], 'Provider not found', 200);
        }

        return $this->success($providerDetails, 'Provider details are here', 200);
    }
}
