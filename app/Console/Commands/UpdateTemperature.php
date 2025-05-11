<?php

namespace App\Console\Commands;

use App\Integrations\OpenWeather;
use App\Repositories\ConfigRepository;
use Illuminate\Console\Command;

class UpdateTemperature extends Command
{
    protected $signature = 'update-temperature';

    protected $description = 'This Command is used to update temperature from Open Weather API';

    public function handle()
    {
        try {
            $temp = app(ConfigRepository::class)->updateTemp();
            $this->info('Temperature updated successfully to ' . $temp);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
