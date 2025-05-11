<?php

namespace App\Providers;

use App\Repositories\ConfigRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepository::class, function ($app) {
            return new ProductRepository($app->make(ConfigRepository::class));
        });
        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService($app->make(ProductRepository::class), $app->make(ConfigRepository::class));
        });

        $this->app->bind(OrderRepository::class, function ($app) {
            return new OrderRepository($app->make(ProductRepository::class), $app->make(ConfigRepository::class));
        });
        $this->app->bind(OrderService::class, function ($app) {
            return new OrderService($app->make(OrderRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
