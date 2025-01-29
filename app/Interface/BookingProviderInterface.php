<?php

namespace App\Interface;

use Illuminate\Http\Request;

interface BookingProviderInterface
{
    public function book($data);
}
