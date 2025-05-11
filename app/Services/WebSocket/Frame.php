<?php

namespace App\Services\WebSocket;

class Frame
{
    public static function encode($payload, $opcode = 0x1)
    {
        $frameHead = [];
        $payloadLength = strlen($payload);

        // Set FIN bit and opcode (0x1 for text, 0x8 for close, 0x9 for ping, 0xA for pong)
        $frameHead[0] = 0x80 | $opcode;

        if ($payloadLength <= 125) {
            $frameHead[1] = $payloadLength;
        } elseif ($payloadLength <= 65535) {
            $frameHead[1] = 126;
            $frameHead[2] = ($payloadLength >> 8) & 255;
            $frameHead[3] = $payloadLength & 255;
        } else {
            $frameHead[1] = 127;
            for ($i = 9; $i >= 2; $i--) {
                $frameHead[$i] = ($payloadLength >> (8 * (9 - $i))) & 255;
            }
        }

        foreach ($frameHead as $i => $b) {
            $frameHead[$i] = chr($b);
        }

        return implode('', $frameHead) . $payload;
    }

    public static function decode($data)
    {
        if (strlen($data) < 2) {
            return null; // Not enough data for a valid frame
        }

        $firstByte = ord($data[0]);
        $fin = ($firstByte >> 7) & 1;
        $opcode = $firstByte & 0x0F;
        $secondByte = ord($data[1]);
        $isMasked = ($secondByte >> 7) & 1;
        $length = $secondByte & 127;

        // Validate frame
        if ($fin !== 1) {
            return null; // Fragmented frames not supported
        }
        if ($isMasked !== 1) {
            return null; // Client frames must be masked
        }

        // Determine mask and payload offsets based on length
        $maskOffset = ($length === 126) ? 4 : (($length === 127) ? 10 : 2);
        $payloadOffset = $maskOffset + 4;

        // Calculate actual payload length
        if ($length === 126) {
            if (strlen($data) < 4) return null;
            $length = unpack('n', substr($data, 2, 2))[1];
        } elseif ($length === 127) {
            if (strlen($data) < 10) return null;
            $length = unpack('J', substr($data, 2, 8))[1];
        }

        // Verify data length
        if (strlen($data) < $payloadOffset + $length) {
            return null; // Incomplete payload
        }

        $mask = substr($data, $maskOffset, 4);
        $payload = substr($data, $payloadOffset, $length);
        $text = '';

        // Unmask payload
        for ($i = 0; $i < strlen($payload); ++$i) {
            $text .= $payload[$i] ^ $mask[$i % 4];
        }

        return [
            'opcode' => $opcode,
            'payload' => $text,
        ];
    }
}
