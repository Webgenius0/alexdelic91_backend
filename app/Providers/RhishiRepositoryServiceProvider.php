<?php

namespace App\Providers;

use App\Interface\BookingProviderInterface;
use App\Repositories\BookingProviderRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
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
