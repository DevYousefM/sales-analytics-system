<?php

namespace App\Listeners;

use App\Events\ProductEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductListener
{

    public function __construct()
    {
        //
    }

    public function handle(ProductEvent $event) {}
}
