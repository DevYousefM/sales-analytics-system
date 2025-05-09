<?php

namespace App\Services\WebSocket;

use Illuminate\Support\Facades\Log;

class WebSocketServer
{
    protected $clients = [];
    protected $subscriptions = [];

    public function start()
    {
        $wsServer = stream_socket_server("tcp://0.0.0.0:8080", $errno, $errstr);
        $pushServer = stream_socket_server("tcp://0.0.0.0:8090", $pErrno, $pErrStr);

        // Log::info("🟢 WebSocket server initialized on 0.0.0.0:8080");
        // Log::info("🟢 Internal push server initialized on 0.0.0.0:8090");

        if (!$wsServer || !$pushServer) {
            // Log::error("❌ Failed to initialize servers. WebSocket error: $errstr | Push error: $pErrStr");
            return;
        }

        while (true) {
            $readSockets = array_filter($this->clients, fn($client) => is_resource($client));
            $readSockets[] = $wsServer;
            $readSockets[] = $pushServer;

            if (stream_select($readSockets, $write, $except, 0) === false) {
                // Log::warning("⚠️ stream_select encountered an error, skipping this tick");
                continue;
            }

            foreach ($readSockets as $socket) {

                if ($socket === $wsServer) {
                    $newClient = @stream_socket_accept($wsServer);
                    if ($newClient) {
                        // Log::info("🔌 New WebSocket client attempting connection");
                        $this->handshake($newClient);
                        $this->clients[] = $newClient;
                        // Log::info("✅ Client added. Total clients: " . count($this->clients));
                    }
                } elseif ($socket === $pushServer) {
                    $pushClient = @stream_socket_accept($pushServer);
                    if ($pushClient) {
                        $message = fread($pushClient, 4096);
                        fclose($pushClient);

                        // Log::info("📨 Received push message:\n$message");

                        $data = json_decode($message, true);

                        if (!is_array($data) || !isset($data['channel'], $data['event'], $data['data'])) {
                            // Log::warning("❌ Invalid push payload: $message");
                            continue;
                        }

                        $channel = $data['channel'];
                        $event = $data['event'];
                        $payload = json_encode([
                            'channel' => $channel,
                            'event' => $event,
                            'data' => $data['data'],
                        ]);

                        // Log::debug("📦 Encoded payload: $payload");

                        $encoded = Frame::encode($payload);
                        $matchCount = 0;

                        foreach ($this->subscriptions as $sub) {
                            if (
                                isset($sub['socket']) &&
                                is_resource($sub['socket']) &&
                                $sub['channel'] === $channel &&
                                $sub['event'] === $event
                            ) {
                                // Log::info("📤 Sending message to client on [$channel::$event]");
                                fwrite($sub['socket'], $encoded);
                                // Log::info("📤 Message sent to client on [$channel::$event]");
                                $matchCount++;
                            }
                        }

                        // Log::info("🔁 Push completed. Dispatched to $matchCount clients for [$channel::$event]");
                    }
                } else {
                    $data = @fread($socket, 1024);
                    if (!$data) {
                        // Log::info("🔌 Client disconnected.");
                        fclose($socket);
                        $this->removeClient($socket);
                        continue;
                    }

                    // Log::debug("📥 Raw data received (hex): " . bin2hex($data));

                    $decoded = Frame::decode($data);
                    // Log::debug("🔓 Decoded frame: " . json_encode($decoded));

                    Handler::process($decoded, $this->clients, $socket, $this->subscriptions);
                    // Log::info("🧠 Message processed by handler");
                }
            }
        }
    }

    protected function handshake($client)
    {
        $headers = fread($client, 4096);
        // Log::info("📡 Handshake started. Headers:\n$headers");

        if (preg_match("/Sec-WebSocket-Key\s*:\s*([^\r\n]*)/", $headers, $match)) {
            $key = trim($match[1]);

            if (empty($key)) {
                // Log::warning("❌ Empty WebSocket key during handshake.");
                fclose($client);
                return;
            }

            $acceptKey = base64_encode(
                sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true)
            );

            $response = "HTTP/1.1 101 Switching Protocols\r\n"
                . "Upgrade: websocket\r\n"
                . "Connection: Upgrade\r\n"
                . "Sec-WebSocket-Accept: {$acceptKey}\r\n\r\n";

            fwrite($client, $response);
            // Log::info("🤝 Handshake successful. Client is now connected.");
        } else {
            // Log::warning("❌ WebSocket key not found in headers. Connection refused.");
            fclose($client);
        }
    }

    protected function removeClient($socket)
    {
        $clientIndex = array_search($socket, $this->clients);
        if ($clientIndex !== false) {
            unset($this->clients[$clientIndex]);
            // Log::info("🧹 Client removed from active list.");
        }

        $before = count($this->subscriptions);
        $this->subscriptions = array_filter($this->subscriptions, function ($sub) use ($socket) {
            return $sub['socket'] !== $socket;
        });
        $after = count($this->subscriptions);
        $removed = $before - $after;

        // Log::info("🧹 Cleaned up $removed subscriptions for disconnected client.");
    }
}
