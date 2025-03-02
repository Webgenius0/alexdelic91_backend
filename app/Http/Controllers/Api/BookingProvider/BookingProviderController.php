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
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function pastBookings()
    {
        try {
            $bookings = $this->bookingProviderInterface->pastBookings();

            return $this->success($bookings, 'Past bookings fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function upcomingBookings()
    {
        try {
            $bookings = $this->bookingProviderInterface->upcomingBookings();

            return $this->success($bookings, 'Upcoming bookings fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function single($id)
    {
        try {
            $booking = $this->bookingProviderInterface->single($id);

            return $this->success($booking, 'Booking fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function providerSingle($id)
    {
        try {
            $booking = $this->bookingProviderInterface->providerSingle($id);

            return $this->success($booking, 'Booking fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function edit(BookingRequest $request, $id)
    {
        try {
            $booking = $this->bookingProviderInterface->edit($request->validated(), $id);

            return $this->success($booking, 'Booking updated successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function cancel($id)
    {
        try {
            $booking = $this->bookingProviderInterface->cancel($id);

            return $this->success($booking, 'Booking cancelled successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function booked($id)
    {
        try {
            $booking = $this->bookingProviderInterface->booked($id);

            return $this->success($booking, 'Booking fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function getProviderBookings(Request $request)
    {
        try {
            $bookings = $this->bookingProviderInterface->getProviderBookings($request->date);

            return $this->success($bookings, 'Provider bookings fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function getProviderBookingsHistory()
    {
        try {
            $bookings = $this->bookingProviderInterface->getProviderBookingsHistory();

            return $this->success($bookings, 'Provider bookings history fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }

    public function providerWithRatingSingle($id)
    {
        try {
            $booking = $this->bookingProviderInterface->providerWithRatingSingle($id);

            return $this->success($booking, 'Booking fetched successfully', 200);
        } catch (CustomException $e) {
            return $this->error([], $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), "An unexpected error occurred", 500);
        }
    }
}
