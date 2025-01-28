<?php

namespace App\Repositories;

use App\Interface\BookingProviderInterface;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingProviderRepository implements BookingProviderInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function book(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'booking_date' => 'required|date',
        ]);

        $booking = new Booking();

        if ($booking->overlaps($request->start_time, $request->end_time, $request->booking_date)) {
            return response()->json(['message' => 'This time slot is already booked.'], 409);
        }

        Booking::create($request->only(['user_id', 'start_time', 'end_time', 'booking_date']));

        return response()->json(['message' => 'Booking successful!'], 201);
    }
}
