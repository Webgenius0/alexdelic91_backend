<?php

namespace App\Http\Controllers\Api\BookingProvider;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Interface\BookingProviderInterface;
use App\Traits\ApiResponse;

class BookingProviderController extends Controller
{
    use ApiResponse;
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

    public function pastBookings()
    {
        $bookings = $this->bookingProviderInterface->pastBookings();

        if (!$bookings) {
            return $this->error([], "An error occurred while fetching past bookings", 500);
        }

        return $this->success($bookings, 'Past bookings fetched successfully', 200);
    }

    public function upcomingBookings()
    {
        $bookings = $this->bookingProviderInterface->upcomingBookings();

        if (!$bookings) {
            return $this->error([], "An error occurred while fetching upcoming bookings", 500);
        }

        return $this->success($bookings, 'Upcoming bookings fetched successfully', 200);
    }

    public function single($id)
    {
        $booking = $this->bookingProviderInterface->single($id);

        if (!$booking) {
            return $this->error([], "An error occurred while fetching the booking", 500);
        }

        return $this->success($booking, 'Booking fetched successfully', 200);
    }

    public function edit(BookingRequest $request, $id)
    {
        $booking = $this->bookingProviderInterface->edit($request->validated(), $id);

        if (!$booking) {
            return $this->error([], "An error occurred while updating the booking", 500);
        }

        return $this->success($booking, 'Booking updated successfully', 200);
    }

}
