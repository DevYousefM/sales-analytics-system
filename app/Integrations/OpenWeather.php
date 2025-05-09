<?php

namespace App\Integrations;

use Illuminate\Support\Facades\Http;

class OpenWeather
{
    protected static $weatherData = null;
    public static function FetchWeatherData()
    {
        if (is_null(self::$weatherData)) {

            $appID = env("OPEN_WEATHER_API_KEY");
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => 'Cairo',
                'appid' => $appID,
                'units' => 'metric'
            ]);
            self::$weatherData = $response->json();
        }
        return self::$weatherData;
    }
    public static  function getTemperature()
    {
        $data = self::FetchWeatherData();
        return $data['main']['temp'];
    }
}
