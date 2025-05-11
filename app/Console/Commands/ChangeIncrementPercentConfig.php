<?php

namespace App\Console\Commands;

use App\Services\ConfigService;
use Illuminate\Console\Command;

class ChangeIncrementPercentConfig extends Command
{
    protected $signature = 'config:change-increment-percent';

    protected $description = 'This command is used to change the increment percent';

    public function handle()
    {
        try {
            $percent = $this->ask('Enter the increment percent: ');
            app(ConfigService::class)->updateIncrementPercent($percent);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
