<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Interface\ServiceProdiversInterface;
use App\Traits\ApiResponse;

class ProviderController extends Controller
{
    use ApiResponse;
    public $providers,$proivderDetails;
    private $serviceProvidersRepository;

    public function __construct(ServiceProdiversInterface $serviceProvidersRepository)
    {
        $this->serviceProvidersRepository = $serviceProvidersRepository;
    }

    public function providers() {
        $providers = $this->serviceProvidersRepository->providers();

        return $this->success($providers,'All providers are here',200);

    }

    public function providerDetails($id) {
        $proivderDetails = $this->serviceProvidersRepository->providerDetails($id);
        return $this->success($proivderDetails,'Provider details are here',200);

    }
}
