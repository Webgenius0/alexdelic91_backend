<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = [
            ['day_name' => 'S', 'day_slug' => Str::slug('Sunday')],
            ['day_name' => 'M', 'day_slug' => Str::slug('Monday')],
            ['day_name' => 'T', 'day_slug' => Str::slug('Tuesday')],
            ['day_name' => 'W', 'day_slug' => Str::slug('Wednesday')],
            ['day_name' => 'Th', 'day_slug' => Str::slug('Thursday')],
            ['day_name' => 'F', 'day_slug' => Str::slug('Friday')],
            ['day_name' => 'Sa', 'day_slug' => Str::slug('Saturday')],
            
        ];

        DB::table('days')->insert($days);
    }
}
