<?php

namespace App\Interface;

interface ServiceProviderInterface
{
    public function providers(array $queryParams);
    public function getProviderDetails($id);
}
