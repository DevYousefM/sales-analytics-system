<?php

namespace App\Console\Commands;

use App\Services\WebSocket\WebSocketServer;
use Illuminate\Console\Command;

class StartWebSocketServer extends Command
{
    protected $signature = 'websocket:serve';

    protected $description = 'Start the WebSocket server';

    public function handle()
    {
        $this->info('Starting WebSocket server on 127.0.0.1:8080');
        (new WebSocketServer())->start();
    }
}
