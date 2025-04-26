<?php

namespace Database\Seeders;

use App\Models\JobPost;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobPost::insert([
            [
                'user_id' => 2,
                'title' => 'Sample Job Title',
                'category_id' => 1,
                'subcategory_id' => 2,
                'location' => 'Dhaka, Bangladesh',
                'latitude' => '23.8103',
                'longitude' => '90.4125',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'notes' => 'This is a sample job post.',
                'status' => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'title' => 'Sample Job Title 2',
                'category_id' => 2,
                'subcategory_id' => 2,
                'location' => 'Mohakhali Model High School',
                'latitude' => '23.780331920612035',
                'longitude' => '90.40536151822204',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'notes' => 'This is a sample job post.',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'title' => 'Sample Job Title 3',
                'category_id' => 1,
                'subcategory_id' => 1,
                'location' => 'Mohakhali Bus Terminal',
                'latitude' => '23.77220593911259',
                'longitude' => '90.4012075278467',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'notes' => 'This is a sample job post.',
                'status' => 'booked',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'title' => 'Sample Job Title 4',
                'category_id' => 3,
                'subcategory_id' => 3,
                'location' => 'Mohakhali Bus Terminal Masjid',
                'latitude' => '23.77399186108644',
                'longitude' => '90.40206962512619',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'notes' => 'This is a sample job post.',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'title' => 'Sample Job Title 5',
                'category_id' => 3,
                'subcategory_id' => 3,
                'location' => 'Mohakhali DOHS Park',
                'latitude' => '23.782019880030816',
                'longitude' => '90.39727981656856',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'notes' => 'This is a sample job post.',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
