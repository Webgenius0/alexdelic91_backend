<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceProviderProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_provider_profiles')->insert([
            [
                'user_id' => 5,
                'business_name' => 'Example Business',
                'category_id' => 2,
                'phone' => '1234567890',
                'address' => 'Mohakhali Bus Terminal Masjid',
                'latitude' => '23.77399186108644',
                'longitude' => '90.40206962512619',
                'service_location_id' => 1,
                'description' => 'This is an example service provider profile description.',
                'city' => 'Example City',
                'division' => 'Example Division',
                'zip_code' => '12345',
                'start_time' => '09:00',
                'end_time' => '17:00',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'business_name' => 'Example Business 2',
                'category_id' => 2,
                'phone' => '1234567890',
                'address' => 'Mohakhali DOHS Park',
                'latitude' => '23.782019880030816',
                'longitude' => '90.39727981656856',
                'service_location_id' => 2,
                'description' => 'This is an example service provider profile description.',
                'city' => 'Example City',
                'division' => 'Example Division',
                'zip_code' => '12345',    
                'start_time' => '09:00',
                'end_time' => '15:00',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'business_name' => 'Example Business 3',
                'category_id' => 2,
                'phone' => '1234567890',
                'address' => 'Park Rd',
                'latitude' => '23.80347976658275',
                'longitude' => '90.42149933084384',
                'service_location_id' => 3,
                'description' => 'This is an example service provider profile description.',
                'city' => 'Example City',
                'division' => 'Example Division',
                'zip_code' => '12345',
                'start_time' => '09:00',
                'end_time' => '12:00',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
