<?php

namespace App\Services\WebSocket;

class Frame
{
    public static function encode($payload)
    {
        $frameHead = [];
        $payloadLength = strlen($payload);

        $frameHead[0] = 129;
        if ($payloadLength <= 125) {
            $frameHead[1] = $payloadLength;
        } elseif ($payloadLength <= 65535) {
            $frameHead[1] = 126;
            $frameHead[2] = ($payloadLength >> 8) & 255;
            $frameHead[3] = $payloadLength & 255;
        } else {
            $frameHead[1] = 127;

            for ($i = 2; $i > 9; ++$i) {
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
        $length = ord($data[1]) & 127;

        if ($length === 126) {
            $mask = substr($data, 4, 4);
            $data = substr($data, 8);
        } elseif ($length === 127) {
            $mask = substr($data, 10, 4);
            $data = substr($data, 14);
        } else {
            $mask = substr($data, 2, 4);
            $data = substr($data, 6);
        }

        $text = '';

        for ($i = 0; $i < strlen($data); ++$i) {
            $text .= $data[$i] ^ $mask[$i % 4];
        }

        return $text;
    }
}
