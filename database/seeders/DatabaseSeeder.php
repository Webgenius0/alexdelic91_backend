<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SocialMediaSeeder::class);
        $this->call(SystemSettingSeeder::class);
        $this->call(DynamicPageSeeder::class);
        $this->call(ServiceTypesTableSeeder::class);
        $this->call(ServiceLocationSeeder::class);
        $this->call(SubcategorySeeder::class);
        $this->call(DaysTableSeeder::class);
        $this->call(ServiceProviderProfileSeeder::class);
        $this->call(ServiceProviderSubcategorySeeder::class);
        $this->call(ServiceProviderImagesSeeder::class);
        $this->call(ServiceProviderWorkDaysSeeder::class);
        $this->call(BookingSeeder::class);
        $this->call(FeedbackSeeder::class);
        $this->call(FaqsTableSeeder::class);
        $this->call(BannerSeeder::class);
    }
}
