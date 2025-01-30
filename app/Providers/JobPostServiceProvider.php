<?php

namespace App\Providers;

use App\Repositories\JobPostRepository;
use Illuminate\Support\ServiceProvider;
use App\Interface\JobPostRepositoryInterface;

class JobPostServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(JobPostRepositoryInterface::class, JobPostRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
