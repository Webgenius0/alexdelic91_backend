<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_locations')->insert([
            ['location_name' => 'At the customer’s location'],
            ['location_name' => 'At my business location'],
            ['location_name' => 'Both'],
        ]);
    }
}
