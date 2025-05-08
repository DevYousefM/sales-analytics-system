<?php

namespace App\Services\WebSocket;


class WebSocketServer
{
    protected $clients = [];
    public function start()
    {
        $wsServer = stream_socket_server("tcp://0.0.0.0:8080", $errno, $errstr);
        $pushServer = stream_socket_server("tcp://0.0.0.0:8090", $pErrno, $pErrStr);

        echo "🟢 Internal push server on 8090\n";
        echo "WebSocket server started on 0.0.0.0:8080\n";

        if (!$wsServer || !$pushServer) {
            echo "❌ Server error: $errstr | $pErrStr\n";
            return;
        }

        while (true) {
            $readSockets = array_filter($this->clients, fn($client) => is_resource($client));
            $readSockets[] = $wsServer;
            $readSockets[] = $pushServer;

            if (stream_select($readSockets, $write, $except, 0) === false) {
                continue;
            }

            foreach ($readSockets as $socket) {
                if ($socket === $wsServer) {
                    $newClient = @stream_socket_accept($wsServer);
                    if ($newClient) {
                        $this->handshake($newClient);
                        $this->clients[] = $newClient;
                    }
                } elseif ($socket === $pushServer) {
                    $pushClient = @stream_socket_accept($pushServer);
                    if ($pushClient) {
                        $message = fread($pushClient, 4096);
                        $encoded = Frame::encode($message);

                        foreach ($this->clients as $client) {
                            fwrite($client, $encoded);
                        }

                        fclose($pushClient);
                    }
                } else {
                    $data = @fread($socket, 1024);
                    if (!$data) {
                        fclose($socket);
                        unset($this->clients[array_search($socket, $this->clients)]);
                        continue;
                    }

                    $decoded = Frame::decode($data);
                    Handler::process($decoded, $this->clients, $socket);
                }
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
