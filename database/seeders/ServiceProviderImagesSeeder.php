<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceProviderImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_provider_images')->insert([
            [
                'service_provider_id' => 1, 
                'images' => 'backend/images/doctor.png', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_provider_id' => 1, 
                'images' => 'backend/images/consultation-image.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_provider_id' => 2, 
                'images' => 'backend/images/doctor.png', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_provider_id' => 2, 
                'images' => 'backend/images/consultation-image.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_provider_id' => 3, 
                'images' => 'backend/images/doctor.png', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_provider_id' => 3, 
                'images' => 'backend/images/consultation-image.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
