<?php

namespace App\Console\Commands;

use App\Events\UpdateAnalyticsEvent;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\OrderService;
use Illuminate\Console\Command;

class DispatchUpdateAnalysisEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:update-analysis-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to dispatch the update analysis event.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = app(OrderService::class)->getUpdateAnalysisEventData();
        event(new UpdateAnalyticsEvent($data));
    }
}
