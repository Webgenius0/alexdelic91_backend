<?php

namespace App\Http\Controllers\Api\BookingProvider;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Interface\BookingProviderInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

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
        try {
            $booking = $this->bookingProviderInterface->book($request->validated());

            return $this->success($booking, 'Booking created successfully', 201);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error([], "An unexpected error occurred", 500);
        }
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

    public function providerSingle($id)
    {
        $booking = $this->bookingProviderInterface->providerSingle($id);

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

    public function cancel($id)
    {
        $booking = $this->bookingProviderInterface->cancel($id);

        if (!$booking) {
            return $this->error([], "An error occurred while cancelling the booking", 500);
        }

        return $this->success($booking, 'Booking cancelled successfully', 200);
    }

    public function booked($id)
    {
        $booking = $this->bookingProviderInterface->booked($id);

        if (!$booking) {
            return $this->error([], "An error occurred while fetching the booking", 500);
        }

        return $this->success($booking, 'Booking fetched successfully', 200);
    }

    public function getProviderBookings(Request $request)
    {
        $bookings = $this->bookingProviderInterface->getProviderBookings($request->date);

        if (!$bookings) {
            return $this->error([], "An error occurred while fetching provider bookings", 500);
        }

        return $this->success($bookings, 'Provider bookings fetched successfully', 200);
    }

    public function getProviderBookingsHistory()
    {
        $bookings = $this->bookingProviderInterface->getProviderBookingsHistory();

        if (!$bookings) {
            return $this->error([], "An error occurred while fetching provider bookings history", 500);
        }

        return $this->success($bookings, 'Provider bookings history fetched successfully', 200);
    }

    public function providerWithRatingSingle($id)
    {
        $booking = $this->bookingProviderInterface->providerWithRatingSingle($id);

        if (!$booking) {
            return $this->error([], "An error occurred while fetching the booking", 500);
        }

        return $this->success($booking, 'Booking fetched successfully', 200);
    }
}
