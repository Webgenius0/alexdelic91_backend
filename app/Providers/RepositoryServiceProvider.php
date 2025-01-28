<?php

namespace App\Providers;

use App\Interface\BookingProviderInterface;
use Illuminate\Support\ServiceProvider;
use App\Interface\CategoryRepositoryInterface;
use App\Repositories\BookingProviderRepository;
use App\Repositories\CategoryRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(BookingProviderInterface::class, BookingProviderRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
