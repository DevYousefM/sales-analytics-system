<?php

namespace App\Services\WebSocket;

use Illuminate\Support\Facades\Log;

class Handler
{
    public static function process(string $message, array &$clients, $from, array &$subscriptions)
    {
        // Log::info("📩 Incoming message from client: $message");

        $data = json_decode($message, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Log::warning("❌ JSON decode error: " . json_last_error_msg());
            fwrite($from, Frame::encode("❌ Invalid JSON"));
            return;
        }

        if (isset($data['type']) && $data['type'] === 'subscribe') {
            $channel = $data['channel'] ?? '';
            $event = $data['event'] ?? '';

            $subscriptions[] = [
                'socket' => $from,
                'channel' => $channel,
                'event' => $event,
            ];

            // Log::info("✅ Client subscribed to channel: '$channel', event: '$event'");
        } else {
            // Log::info("🔁 Echoing message back to client");
            fwrite($from, Frame::encode("Echo: $message"));
        }
    }
}
