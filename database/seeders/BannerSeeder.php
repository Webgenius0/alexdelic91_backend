<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::insert([
            [
                'image_url' => 'frontend/banner-1.jpg',
                'status' => 'active',
            ],
            [
                'image_url' => 'frontend/banner-2.jpg',
                'status' => 'active',
            ],
            [
                'image_url' => 'frontend/banner-3.jpg',
                'status' => 'active',
            ],
            [
                'image_url' => 'frontend/banner-4.jpg',
                'status' => 'inactive',
            ]
        ]);
    }
}
