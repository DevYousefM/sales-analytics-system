<?php

namespace App\Console\Commands;

use App\Repositories\ConfigRepository;
use Illuminate\Console\Command;

class ChangeIncrementPercentConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:change-increment-percent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to change the increment percent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $percent = $this->ask('Enter the increment percent: ');
            app(ConfigRepository::class)->updateIncrementPercent($percent);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
