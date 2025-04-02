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
            ['day_name' => 'Su', 'day_slug' => Str::slug('Sunday')],
            ['day_name' => 'Mo', 'day_slug' => Str::slug('Monday')],
            ['day_name' => 'Tu', 'day_slug' => Str::slug('Tuesday')],
            ['day_name' => 'We', 'day_slug' => Str::slug('Wednesday')],
            ['day_name' => 'Th', 'day_slug' => Str::slug('Thursday')],
            ['day_name' => 'Fr', 'day_slug' => Str::slug('Friday')],
            ['day_name' => 'Sa', 'day_slug' => Str::slug('Saturday')],
            
        ];

        DB::table('days')->insert($days);
    }
}
