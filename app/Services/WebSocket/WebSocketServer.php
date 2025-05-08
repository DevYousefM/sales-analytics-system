<?php

namespace App\Services\WebSocket;


class WebSocketServer
{
    protected $clients = [];
    public function start()
    {
        $server = stream_socket_server("tcp://0.0.0.0:8080", $errno, $errstr);
        echo "WebSocket server started on 0.0.0.0:8080\n";

        if (!$server) {
            echo "Error: $errstr ($errno)\n";
            return;
        }

        while (true) {
            // Remove closed/broken clients
            $readSockets = array_filter($this->clients, fn($client) => is_resource($client));
            $readSockets[] = $server;

            if (stream_select($readSockets, $write, $except, 0) === false) {
                continue;
            }

            // Handle new connection
            if (in_array($server, $readSockets)) {
                $newClient = @stream_socket_accept($server);
                if ($newClient) {
                    $this->handshake($newClient);
                    $this->clients[] = $newClient;
                }

                unset($readSockets[array_search($server, $readSockets)]);
            }

            // Handle client messages
            foreach ($readSockets as $client) {
                $data = @fread($client, 1024);
                if (!$data) {
                    fclose($client);
                    unset($this->clients[array_search($client, $this->clients)]);
                    continue;
                }

                $decoded = Frame::decode($data);
                Handler::process($decoded, $this->clients, $client);
            }
        }
    }
    protected function handshake($client)
    {
        $headers = fread($client, 4096);
        echo "🔹 Raw Headers:\n$headers\n";

        if (preg_match("/Sec-WebSocket-Key\s*:\s*([^\r\n]*)/", $headers, $match)) {
            $key = trim($match[1]);

            if (empty($key)) {
                echo "❌ WebSocket key is empty.\n";
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
            echo "✅ Handshake successful\n";
        } else {
            echo "❌ WebSocket key not found in headers.\n";
            echo "🔴 Full Headers: \n$headers\n";
            fclose($client);
        }
    }
}
