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
    public function register(): void {}

    public function boot(): void
    {
        //
    }
}
