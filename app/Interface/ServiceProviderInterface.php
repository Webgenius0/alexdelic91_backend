<?php

namespace App\Interface;

interface ServiceProviderInterface
{
    public function providers();
    public function getProviderDetails($id);
}
