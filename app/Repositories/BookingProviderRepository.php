<?php

namespace App\Repositories;

use App\Exceptions\CustomException;
use App\Interface\BookingProviderInterface;
use App\Models\Booking;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;
use Pest\ArchPresets\Custom;

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
            throw new CustomException("User Not Found", 200);
        }

        $provider = User::with('serviceProviderProfile')->find($data['service_provider_id']);

        // Check if booking is within the provider's available hours
        if (!$provider->isAvailableForBooking($data['start_time'], $data['end_time'])) {
            throw new CustomException("This provider is only available from {$provider->serviceProviderProfile->start_time} to {$provider->serviceProviderProfile->end_time}", 409);
        }

        $booking = new Booking();

        if ($booking->overlaps($data['start_time'], $data['end_time'], $data['booking_date'])) {
            throw new CustomException("Booking overlaps with another booking", 409);
        }

        $booking = Booking::create([
            'user_id' => $user->id,
            'service_provider_id' => $data['service_provider_id'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'booking_date' => $data['booking_date'],
            'address' => $data['address'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'notes' => $data['notes'],
        ]);

        return $booking;
    }

    public function pastBookings()
    {
        if (!auth()->check()) {
            throw new CustomException("Unauthorized access", 401);
        }

        $user = auth()->user();

        $bookings = Booking::with([
            'serviceProvider:id,name,avatar',
            'serviceProvider.serviceProviderProfile:id,user_id,category_id',
            'serviceProvider.serviceProviderProfile.category:id,category_name'
        ])
            ->where('user_id', $user->id)
            ->whereDate('booking_date', '<', now())
            ->get();

        if ($bookings->isEmpty()) {
            throw new CustomException("No past bookings found", 200);
        }

        return $bookings;
    }

    public function upcomingBookings()
    {
        if (!auth()->check()) {
            throw new CustomException("Unauthorized access", 401);
        }
        $user = auth()->user();

        $bookings = Booking::with([
            'serviceProvider:id,name,avatar',
            'serviceProvider.serviceProviderProfile:id,user_id,category_id',
            'serviceProvider.serviceProviderProfile.category:id,category_name'
        ])
            ->where('user_id', $user->id)
            ->whereDate('booking_date', '>=', now())
            ->get();

        if ($bookings->isEmpty()) {
            throw new CustomException("No upcoming bookings found", 200);
        }

        return $bookings;
    }

    public function single($id)
    {
        if (!auth()->check()) {
            throw new CustomException("Unauthorized access", 401);
        }

        $user = auth()->user();

        $booking = Booking::with([
            'serviceProvider:id,name,avatar',
            'serviceProvider.serviceProviderProfile:id,user_id,category_id',
            'serviceProvider.serviceProviderProfile.category:id,category_name'
        ])
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$booking) {
            throw new CustomException("Booking not found", 200);
        }

        return $booking;
    }


    public function providerSingle($id)
    {
        if (!auth()->check()) {
            throw new CustomException("Unauthorized access", 401);
        }

        $user = auth()->user();

        $booking = Booking::with([
            'user:id,name,avatar',
            'serviceProvider:id',
            'serviceProvider.serviceProviderProfile:id,user_id,category_id',
            'serviceProvider.serviceProviderProfile.category:id,category_name'
        ])
            ->where('service_provider_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$booking) {
            throw new CustomException("Booking not found", 200);
        }

        return $booking;
    }

    public function edit($data, $id)
    {
        if (!auth()->check()) {
            throw new CustomException("Unauthorized access", 401);
        }

        $user = auth()->user();

        $booking = Booking::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$booking) {
            throw new CustomException("Booking Not Found", 200);
        }

        $booking->update([
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'booking_date' => $data['booking_date'],
            'address' => $data['address'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'notes' => $data['notes'],
        ]);

        return $booking;
    }

    public function cancel($id)
    {
        if (!auth()->check()) {
            throw new CustomException("Unauthorized access", 401);
        }

        $user = auth()->user();

        $booking = Booking::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$booking) {
            throw new CustomException("Booking Not Found", 200);
        }

        $booking->status = 'cancelled';
        $booking->save();

        return $booking;
    }

    public function booked($id)
    {
        if (!auth()->check()) {
            throw new CustomException("Unauthorized access", 401);
        }

        $user = auth()->user();

        $booking = Booking::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$booking) {
            throw new CustomException("Booking Not Found", 200);
        }

        $booking->status = 'booked';
        $booking->save();

        return $booking;
    }

    public function getProviderBookings($date)
    {
        if (!auth()->check()) {
            throw new CustomException("Unauthorized access", 401);
        }

        $user = auth()->user();

        $query = Booking::with([
            'user:id,name,avatar',
            'serviceProvider:id',
            'serviceProvider.serviceProviderProfile:id,user_id,category_id',
            'serviceProvider.serviceProviderProfile.category:id,category_name'
        ])
            ->where('service_provider_id', $user->id)
            ->whereNot('status', 'deleted');

        if ($date) {
            $query->whereDate('booking_date', $date);
        }

        $bookings = $query->get();

        if ($bookings->isEmpty()) {
            throw new CustomException("No bookings found", 200);
        }

        return $bookings;
    }

    public function getProviderBookingsHistory()
    {
        if (!auth()->check()) {
            throw new CustomException("Unauthorized access", 401);
        }

        $user = auth()->user();

        $bookings = Booking::with([
            'user:id,name,avatar',
            'serviceProvider:id',
            'serviceProvider.serviceProviderProfile:id,user_id,category_id',
            'serviceProvider.serviceProviderProfile.category:id,category_name'
        ])
            ->where('service_provider_id', $user->id)
            ->whereDate('booking_date', '<', now())
            ->whereNot('status', 'deleted')
            ->get();

        if ($bookings->isEmpty()) {
            throw new CustomException("No past bookings found", 200);
        }

        return $bookings;
    }

    public function providerWithRatingSingle($id)
    {
        if (!auth()->check()) {
            throw new CustomException("Unauthorized access", 401);
        }

        $user = auth()->user();

        $booking = Booking::with([
            'user:id,name,avatar',
            'serviceProvider:id',
            'serviceProvider.serviceProviderProfile:id,user_id,category_id',
            'serviceProvider.serviceProviderProfile.category:id,category_name',
            'feedback'
        ])
            ->where('service_provider_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$booking) {
            throw new CustomException("Booking Not Found", 200);
        }

        return $booking;
    }
}
