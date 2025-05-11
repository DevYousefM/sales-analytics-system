<?php

namespace App\Console\Commands;

use App\Services\ConfigService;
use Illuminate\Console\Command;

class UpdateTemperature extends Command
{
    protected $signature = 'config:update-temperature';

    protected $description = 'This Command is used to update temperature from Open Weather API';

    public function handle()
    {
        try {
            $temp = app(ConfigService::class)->updateTemp();
            $this->info('Temperature updated successfully to ' . $temp);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
