<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'category_name' => 'Cleaning',
                'category_slug' => Str::slug('Cleaning'),
                'icon' => 'backend/images/Cleaning.png',
                'status' => 'active',
            ],
            [
                'category_name' => 'Home',
                'category_slug' => Str::slug('Home'),
                'icon' => 'backend/images/Vect.png',
                'status' => 'active',
            ],
            [
                'category_name' => 'Garden',
                'category_slug' => Str::slug('Garden'),
                'icon' => 'backend/images/Garden.png',
                'status' => 'active',
            ],
            [
                'category_name' => 'Moving',
                'category_slug' => Str::slug('Moving'),
                'icon' => 'backend/images/Vector.png',
                'status' => 'active',
            ],
            [
                'category_name' => 'other',
                'category_slug' => Str::slug('other'),
                'icon' => 'backend/images/Vector.png',
                'status' => 'active',
            ],
        ]);
    }
}
