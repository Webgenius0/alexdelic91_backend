<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bookings')->insert([
            [
                'user_id' => 2,
                'service_provider_id' => 5,
                'job_post_id' => null,
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'booking_date' => Carbon::today()->toDateString(),
                'address' => '123 Example St, City, Country',
                'latitude' => '40.712776',
                'longitude' => '-74.005974',
                'notes' => 'First booking, test notes',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'service_provider_id' => 5,
                'job_post_id' => null,
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'booking_date' => Carbon::tomorrow()->toDateString(),
                'address' => '123 Example St, City, Country',
                'latitude' => '40.712776',
                'longitude' => '-74.005974',
                'notes' => 'Second booking, test notes',
                'status' => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'service_provider_id' => 5,
                'job_post_id' => 1,
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'booking_date' => Carbon::tomorrow()->toDateString(),
                'address' => '123 Example St, City, Country',
                'latitude' => '40.712776',
                'longitude' => '-74.005974',
                'notes' => 'Second booking, test notes',
                'status' => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'service_provider_id' => 5,
                'job_post_id' => 3,
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'booking_date' => Carbon::tomorrow()->toDateString(),
                'address' => '123 Example St, City, Country',
                'latitude' => '40.712776',
                'longitude' => '-74.005974',
                'notes' => 'Second booking, test notes',
                'status' => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
