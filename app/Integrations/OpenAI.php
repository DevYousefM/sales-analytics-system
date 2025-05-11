<?php

namespace App\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAI
{
    public static function GetRecommendationsWithAI($prompt)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env("OPEN_AI_API_KEY")
        ])->post(env("OPEN_AI_API_ENDPOINT"), [
            'model' => env("OPEN_AI_MODEL"),
            'messages' => [
                [
                    "role" => 'user',
                    'content' => $prompt
                ]
            ]
        ]);
        if (isset($response['error'])) {
            return [
                'error' => $response['error']['message']
            ];
        }
        
        return self::returnContent($response);
    }
    private static function returnContent($response)
    {
        return json_decode($response->json('choices.0.message.content'));
    }
}
