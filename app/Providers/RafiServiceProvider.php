<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interface\CategoryInterface;
use App\Interface\ReviewsInterface;
use App\Interface\ServiceProviderInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\ReviewsRepository;
use App\Repositories\ServiceProviderRepository;

class RafiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(ServiceProviderInterface::class, ServiceProviderRepository::class);
        $this->app->bind(ReviewsInterface::class, ReviewsRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
