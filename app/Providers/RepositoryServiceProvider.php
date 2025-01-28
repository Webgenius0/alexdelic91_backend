<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interface\CategoryInterface;
use App\Interface\ServiceProdiversInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\ServiceProviersRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(ServiceProdiversInterface::class, ServiceProviersRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
