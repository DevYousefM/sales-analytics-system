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

        Log::info("🟢 WebSocket server initialized on 0.0.0.0:8080");
        Log::info("🟢 Internal push server initialized on 0.0.0.0:8090");

        if (!$wsServer || !$pushServer) {
            Log::error("❌ Failed to initialize servers. WebSocket error: $errstr | Push error: $pErrStr");
            return;
        }

        while (true) {
            $readSockets = array_filter($this->clients, fn($client) => is_resource($client));
            $readSockets[] = $wsServer;
            $readSockets[] = $pushServer;

            if (stream_select($readSockets, $write, $except, null) === false) {
                Log::warning("⚠️ stream_select encountered an error, skipping this tick");
                continue;
            }

            foreach ($readSockets as $socket) {
                if ($socket === $wsServer) {
                    $newClient = @stream_socket_accept($wsServer, 0);
                    if ($newClient) {
                        Log::info("🔌 New WebSocket client attempting connection");
                        $this->handshake($newClient);
                        $this->clients[] = $newClient;
                        Log::info("✅ Client added. Total clients: " . count($this->clients));
                    }
                } elseif ($socket === $pushServer) {
                    $pushClient = @stream_socket_accept($pushServer, 0);
                    if ($pushClient) {
                        $message = fread($pushClient, 4096);
                        fclose($pushClient);

                        Log::info("📨 Received push message:\n$message");

                        $data = json_decode($message, true);

                        if (!is_array($data) || !isset($data['channel'], $data['event'], $data['data'])) {
                            Log::warning("❌ Invalid push payload: $message");
                            continue;
                        }

                        $channel = $data['channel'];
                        $event = $data['event'];
                        $payload = json_encode([
                            'channel' => $channel,
                            'event' => $event,
                            'data' => $data['data'],
                        ]);

                        Log::debug("📦 Encoded payload: $payload");

                        $encoded = Frame::encode($payload);
                        $matchCount = 0;

                        foreach ($this->subscriptions as $sub) {
                            if (
                                isset($sub['socket']) &&
                                is_resource($sub['socket']) &&
                                $sub['channel'] === $channel &&
                                $sub['event'] === $event
                            ) {
                                Log::info("📤 Sending message to client on [$channel::$event]");
                                fwrite($sub['socket'], $encoded);
                                $matchCount++;
                            }
                        }

                        Log::info("🔁 Push completed. Dispatched to $matchCount clients for [$channel::$event]");
                    }
                } else {
                    $data = @fread($socket, 4096);
                    if ($data === false || strlen($data) === 0) {
                        Log::info("🔌 Client disconnected.");
                        $this->closeClient($socket);
                        continue;
                    }

                    Log::debug("📥 Raw data received (hex): " . bin2hex($data));

                    $decoded = Frame::decode($data);
                    if ($decoded === null) {
                        Log::warning("❌ Invalid WebSocket frame. Closing connection.");
                        $this->closeClient($socket);
                        continue;
                    }

                    switch ($decoded['opcode']) {
                        case 0x1: // Text frame
                            Log::debug("🔓 Decoded text frame: " . $decoded['payload']);
                            Handler::process($decoded['payload'], $this->clients, $socket, $this->subscriptions);
                            break;
                        case 0x8: // Close frame
                            Log::info("🔌 Client sent close frame.");
                            $this->closeClient($socket);
                            break;
                        case 0x9: // Ping frame
                            Log::info("🏓 Received ping from client.");
                            fwrite($socket, Frame::encode($decoded['payload'], 0xA)); // Send pong
                            break;
                        case 0xA: // Pong frame
                            Log::info("🏓 Received pong from client.");
                            break;
                        default:
                            Log::warning("❌ Unsupported opcode: {$decoded['opcode']}. Closing connection.");
                            $this->closeClient($socket);
                            break;
                    }
                }
            }
        }
    }

    protected function handshake($client)
    {
        $headers = fread($client, 4096);
        Log::debug("📡 Handshake headers:\n$headers");

        // Validate required headers
        if (
            !preg_match("/GET .* HTTP\/1\.1\r\n/", $headers) ||
            !preg_match("/Upgrade: websocket\r\n/i", $headers) ||
            !preg_match("/Connection: Upgrade\r\n/i", $headers) ||
            !preg_match("/Sec-WebSocket-Version: 13\r\n/i", $headers) ||
            !preg_match("/Sec-WebSocket-Key: ([^\r\n]*)\r\n/", $headers, $match)
        ) {
            Log::warning("❌ Invalid WebSocket handshake request.");
            fwrite($client, "HTTP/1.1 400 Bad Request\r\n\r\n");
            fclose($client);
            return;
        }

        $key = trim($match[1]);
        $acceptKey = base64_encode(sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));

        $response = "HTTP/1.1 101 Switching Protocols\r\n"
            . "Upgrade: websocket\r\n"
            . "Connection: Upgrade\r\n"
            . "Sec-WebSocket-Accept: {$acceptKey}\r\n\r\n";

        fwrite($client, $response);
        Log::info("🤝 Handshake successful. Client is now connected.");
    }

    protected function closeClient($socket)
    {
        // Send close frame before closing
        if (is_resource($socket)) {
            fwrite($socket, Frame::encode('', 0x8));
            fclose($socket);
        }
        $this->removeClient($socket);
    }

    protected function removeClient($socket)
    {
        $clientIndex = array_search($socket, $this->clients);
        if ($clientIndex !== false) {
            unset($this->clients[$clientIndex]);
            Log::info("🧹 Client removed from active list.");
        }

        $before = count($this->subscriptions);
        $this->subscriptions = array_filter($this->subscriptions, fn($sub) => $sub['socket'] !== $socket);
        $after = count($this->subscriptions);
        Log::info("🧹 Cleaned up " . ($before - $after) . " subscriptions for disconnected client.");
    }
}
