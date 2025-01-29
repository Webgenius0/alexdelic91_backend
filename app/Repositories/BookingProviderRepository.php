<?php

namespace App\Repositories;

use App\Interface\BookingProviderInterface;
use App\Models\Booking;
use App\Models\User;
use App\Traits\ApiResponse;

class BookingProviderRepository implements BookingProviderInterface
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function book($data)
    {
        // Find the user by ID
        $user = auth()->user();

        // If user is not found, return an error response
        if (!$user) {
            return $this->error([], "User Not Found", 404);
        }

        $provider = User::with('serviceProviderProfile')->findOrFail($data['service_provider_id']);

        // Check if booking is within the provider's available hours
        if (!$provider->isAvailableForBooking($data['start_time'], $data['end_time'])) {
            return $this->error([], "This provider is only available from {$provider->serviceProviderProfile->start_time} to {$provider->serviceProviderProfile->end_time}", 409);
        }

        $booking = new Booking();

        if ($booking->overlaps($data['start_time'], $data['end_time'], $data['booking_date'])) {
            return $this->error([], "Booking overlaps with another booking", 409);
        }

        try {

            $booking = Booking::create([
                'user_id' => $user->id,
                'service_provider_id' => $data['service_provider_id'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'booking_date' => $data['booking_date'],
                'notes' => $data['notes'],
            ]);

            return $booking;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function pastBookings()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Not Found", 404);
        }

        $bookings = Booking::where('user_id', $user->id)
            ->whereDate('booking_date', '<', now())
            ->get();

        return $bookings;
    }

    public function upcomingBookings()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Not Found", 404);
        }

        $bookings = Booking::where('user_id', $user->id)
            ->whereDate('booking_date', '>=', now())
            ->get();

        return $bookings;
    }

    public function single($id)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Not Found", 404);
        }

        $booking = Booking::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        return $booking;
    }

    public function edit($data, $id)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], "User Not Found", 404);
        }

        $booking = Booking::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$booking) {
            return $this->error([], "Booking Not Found", 404);
        }

        try {
            $booking->update([
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'booking_date' => $data['booking_date'],
                'notes' => $data['notes'],
            ]);

            return $booking;
        } catch (\Exception $e) {
            return null;
        }
    }
}
