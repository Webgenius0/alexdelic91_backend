<?php

namespace App\Http\Controllers\Api\BookingProvider;

use App\Http\Controllers\Controller;
use App\Interface\BookingProviderInterface;
use Illuminate\Http\Request;

class BookingProviderController extends Controller
{
    private $bookingProviderInterface;
    /**
     * Create a new class instance.
     */
    public function __construct(BookingProviderInterface $bookingProviderInterface)
    {
        $this->bookingProviderInterface = $bookingProviderInterface;
    }



}
