<?php
namespace Database\Seeders;

use App\Models\JobPostDate;
use Illuminate\Database\Seeder;

class JobPostDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobPostDate::insert([
            [
                'job_post_id' => 1,
                'date'        => '2025-04-26',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_post_id' => 1,
                'date'        => '2025-04-28',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_post_id' => 2,
                'date'        => '2025-04-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_post_id' => 2,
                'date'        => '2025-04-22',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_post_id' => 3,
                'date'        => '2025-04-29',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_post_id' => 3,
                'date'        => '2025-04-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_post_id' => 4,
                'date'        => '2025-04-23',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_post_id' => 4,
                'date'        => '2025-04-24',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_post_id' => 5,
                'date'        => '2025-05-23',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_post_id' => 5,
                'date'        => '2025-05-24',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
    }
}
