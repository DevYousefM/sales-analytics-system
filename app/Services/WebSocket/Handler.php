<?php

namespace App\Services\WebSocket;

use Illuminate\Support\Facades\Log;

class Handler
{
    public static function process(string $message, array &$clients, $from, array &$subscriptions)
    {
        Log::info("📩 Incoming message from client: $message");

        // Skip empty messages
        if (trim($message) === '') {
            Log::warning("❌ Empty message received.");
            fwrite($from, Frame::encode(json_encode(['error' => 'Empty message'])));
            return;
        }

        $data = json_decode($message, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning("❌ JSON decode error: " . json_last_error_msg() . " for message: $message");
            fwrite($from, Frame::encode(json_encode(['error' => 'Invalid JSON'])));
            return;
        }

        if (isset($data['type']) && $data['type'] === 'subscribe') {
            $channel = $data['channel'] ?? '';
            $event = $data['event'] ?? '';

            if (empty($channel) || empty($event)) {
                Log::warning("❌ Invalid subscription request: channel or event missing.");
                fwrite($from, Frame::encode(json_encode(['error' => 'Channel and event required'])));
                return;
            }

            $subscriptions[] = [
                'socket' => $from,
                'channel' => $channel,
                'event' => $event,
            ];

            Log::info("✅ Client subscribed to channel: '$channel', event: '$event'");
            fwrite($from, Frame::encode(json_encode(['status' => 'subscribed', 'channel' => $channel, 'event' => $event])));
        } else {
            Log::info("🔁 Echoing message back to client");
            fwrite($from, Frame::encode(json_encode(['echo' => $message])));
        }
    }
}
