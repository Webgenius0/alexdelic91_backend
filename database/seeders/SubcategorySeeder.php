<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            [
                'category_id' => 1,
                'subcategory_name' => 'Beauty salon',
                'subcategory_slug' => Str::slug('Beauty salon'),
                'icon' => 'backend/images/Cleaning.png',
                'status' => 'active',
            ],
            [
                'category_id' => 1,
                'subcategory_name' => 'Home cleaning',
                'subcategory_slug' => Str::slug('Home cleaning'),
                'icon' => 'backend/images/Vect.png',
                'status' => 'active',
            ],
            [
                'category_id' => 2,
                'subcategory_name' => 'Garden cleaning',
                'subcategory_slug' => Str::slug('Garden cleaning'),
                'icon' => 'backend/images/Garden.png',
                'status' => 'active',
            ],
            [
                'category_id' => 3,
                'subcategory_name' => 'Moving',
                'subcategory_slug' => Str::slug('Moving'),
                'icon' => 'backend/images/Vector.png',
                'status' => 'inactive',
            ],
        ];

        DB::table('subcategories')->insert($subcategories);
    }
}
