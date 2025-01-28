<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceProviderWorkDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for service providers' work days
        $serviceProviders = \App\Models\ServiceProviderProfile::all(); 
        $days = \App\Models\Day::all(); 

        foreach ($serviceProviders as $serviceProvider) {
            foreach ($days as $day) {
                DB::table('servise_provider_work_days')->insert([
                    'service_provider_id' => $serviceProvider->id,
                    'day_id' => $day->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
