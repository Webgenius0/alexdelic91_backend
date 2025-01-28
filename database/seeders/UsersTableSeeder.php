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
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'User',
                'email' => 'user@user.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'Service Provider',
                'email' => 'serviceprovider@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'service_provider',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            // make more 4 people rafi,saidur,and rkb and mijanur all admin
            [
                'name' => 'Example Business',
                'email' => 'rafi@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'service_provider',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'Example Business 3',
                'email' => 'saidur@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'service_provider',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'Example Business 4',
                'email' => 'rkb@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'service_provider',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],

            [
                'name' => 'Example Business 2',
                'email' => 'mizanur@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 'service_provider',
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
        ]);
    }
}
