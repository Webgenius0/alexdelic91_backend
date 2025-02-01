<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_types')->insert([
            ['service_type' => 'Cleaning Services'],
            ['service_type' => 'Beauty and Wellness (e.g., hairdressing, makeup, spa)'],
            ['service_type' => 'Home Maintenance (e.g., plumbing, electrical work)'],
            ['service_type' => 'Transportation Services (e.g., taxi, delivery)'],
            ['service_type' => 'Event Planning and Catering'],
            ['service_type' => 'Other'],
        ]);
    }
}
