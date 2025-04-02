<?php

namespace Database\Seeders;


use App\Models\Booking;
use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Ensure there are users and bookings in the database
         $bookings = Booking::all();

         foreach ($bookings as $booking) {
             Feedback::create([
                 'user_id' => 2,
                 'booking_id' => $booking->id,
                 'feedback' => 'This is a sample feedback for booking #' . $booking->id,
                 'rating' => rand(1, 5),
             ]);
         }
    }
}
