<?php

namespace App\Providers;

use App\Repositories\Contracts\OrderTravelRepositoryInterface;
use app\Repositories\Eloquent\OrderTravelRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(OrderTravelRepositoryInterface::class, OrderTravelRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
