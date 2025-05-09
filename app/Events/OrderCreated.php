<?php

namespace App\Events;

use App\Services\WebSocket\WebSocketMessenger;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        (new WebSocketMessenger())->sendToWebSocket($data, 'orders', 'order-created');
    }
}
