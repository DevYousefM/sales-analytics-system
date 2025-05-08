<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $msg = '';
    public function __construct()
    {
        $this->msg = 'Product Event';
        $this->sendToWebSocket($this->msg);
    }

    protected function sendToWebSocket($msg)
    {
        $payload = json_encode([
            'channel' => 'product',
            'event' => 'product-event',
            'data' => $msg,
        ]);

        $sock = fsockopen('127.0.0.1', 8090, $errno, $errstr, 1);
        if ($sock) {
            fwrite($sock, $payload);
            fclose($sock);
        } else {
            \Log::error("Push connection failed: $errstr ($errno)");
        }
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('product'),
        ];
    }
}
