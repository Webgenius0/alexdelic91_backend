<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'address' => 'Mohakhali',
                'latitude' => '23.777854678421768',
                'longitude' => '90.4054269888872',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'User',
                'email' => 'user@user.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'address' => 'Mohakhali',
                'latitude' => '23.777854678421768',
                'longitude' => '90.4054269888872',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'MD Rafiur Rahman',
                'email' => 'mdrafir@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'address' => 'Mohakhali Model High School',
                'latitude' => '23.780331920612035',
                'longitude' => '90.40536151822204',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'MD Mizanur Rahman',
                'email' => 'mizanur@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'address' => 'Mohakhali Bus Terminal',
                'latitude' => '23.77220593911259',
                'longitude' => '90.4012075278467',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'Service Provider',
                'email' => 'serviceprovider@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'service_provider',
                'address' => 'Mohakhali Bus Terminal Masjid',
                'latitude' => '23.77399186108644',
                'longitude' => '90.40206962512619',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'Example Business',
                'email' => 'rafi@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'service_provider',
                'address' => 'Mohakhali DOHS Park',
                'latitude' => '23.782019880030816',
                'longitude' => '90.39727981656856',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'Example Business 3',
                'email' => 'saidur@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'service_provider',
                'address' => 'Park Rd',
                'latitude' => '23.80347976658275',
                'longitude' => '90.42149933084384',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
        ]);
    }
}
