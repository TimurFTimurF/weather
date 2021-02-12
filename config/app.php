<?php

return [

    'name' => env('APP_NAME', 'Weather'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost:8000/'),

    'timezone' => env('APP_ENV', 'production'),

    'aliases' => [
        'Http' => Illuminate\Support\Facades\Http::class,
    ],

    'musement' => [
        'cities' => env('MUSEMENT_URL', 'https://api.musement.com/') . 'api/v' . env('MUSEMENT_API_VERSION', 3) . '/' . env('MUSEMENT_CITY', 'cities'),
    ],

    'weatherapi' => [
        'url' => env('WEATHERAPI_URL', 'http://api.weatherapi.com/v1/'),
        'forecast' => env('WEATHERAPI_URL', 'http://api.weatherapi.com/v1/') . env('WEATHERAPI_FORECAST', 'forecast.json'),
        'key' => env('WEATHERAPI_KEY'),
    ]
];
