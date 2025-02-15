<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FaqsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faqs')->insert([
            [
                'question' => 'What is Laravel?',
                'answer' => 'Laravel is an open-source PHP framework used for web application development.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'How does a seeder work?',
                'answer' => 'A seeder is used to insert dummy data into the database.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'What is Eloquent in Laravel?',
                'answer' => 'Eloquent is Laravel’s built-in ORM that provides an easy way to interact with the database.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'What is Middleware in Laravel?',
                'answer' => 'Middleware in Laravel is used to filter HTTP requests entering the application.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'What is the purpose of migrations in Laravel?',
                'answer' => 'Migrations allow version control for the database structure, making schema modifications easier.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
