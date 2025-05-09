<?php

namespace App\Services\WebSocket;

use Illuminate\Support\Facades\Log;

class WebSocketMessenger
{
    protected $host;
    protected $port;

    public function __construct($host = '127.0.0.1', $port = 8090)
    {
        $this->host = $host;
        $this->port = $port;
    }


    public function sendToWebSocket($data, $channel, $event)
    {
        try {
            $payload = json_encode([
                'event' => $event,
                'channel' => $channel,
                'data' => $data,
            ]);

            $sock = fsockopen($this->host, $this->port, $errno, $errstr, 1);
            if ($sock) {
                fwrite($sock, $payload);
                fclose($sock);
            }
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
        }
    }
}
