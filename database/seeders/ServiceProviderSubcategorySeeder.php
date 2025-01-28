<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceProviderSubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_provider_subcategories')->insert([
            [
                'service_provider_id' => 1, 
                'subcategory_id' => 2, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_provider_id' => 1, 
                'subcategory_id' => 3, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_provider_id' => 2, 
                'subcategory_id' => 2, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_provider_id' => 2, 
                'subcategory_id' => 3, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_provider_id' => 3, 
                'subcategory_id' => 2, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_provider_id' => 3, 
                'subcategory_id' => 3, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
