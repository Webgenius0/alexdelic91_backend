<?php

namespace App\Http\Controllers\Api\BookingProvider;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Interface\BookingProviderInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function book(BookingRequest $request)
    {
        $booking = $this->bookingProviderInterface->book($request->validated());

        if (!$booking) {
            return $this->error([], "An error occurred while creating the booking", 500);
        }

        return $this->success($booking, 'Booking created successfully', 201);
    }

}
