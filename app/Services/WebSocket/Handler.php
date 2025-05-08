<?php

namespace  App\Services\WebSocket;

class Handler
{
    public static function  process(string $message, array $clients, $from)
    {

        $response = "Echo: $message";

        $encoded = Frame::encode($response);

        foreach ($clients as $client) {
            if ($client !== $from) {
                fwrite($client, $encoded);
            }
        }
    }
}
