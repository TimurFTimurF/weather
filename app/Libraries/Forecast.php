<?php

namespace App\Libraries;

use App\Interfaces\WayOutInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Forecast
{
    /**
     * @var WayOutInterface
     */
    private $out;

    /**
     * Forecast constructor.
     *
     * @param WayOutInterface $out
     */
    public function __construct(WayOutInterface $out)
    {
        $this->out = $out;
    }

    /**
     * Get forecast for needed cities
     *
     * @return mixed
     */
    public function forecast()
    {
        $forecast = config('app.weatherapi.forecast') . '?key=' . config('app.weatherapi.key');

        $cities = $this->getMusementData();

        foreach ($cities as $city) {
            $latitude = $city['latitude'] ?? null;
            $longitude = $city['longitude'] ?? null;
            if ($latitude && $longitude) {
                $url = "{$forecast}&q={$latitude},{$longitude}&days=2";

                $weather = Http::get($url)->json();

                if (is_array($weather) && (array_keys($weather)[0] != 'error')) {
                    $date = Carbon::now()->format('Y-m-d');
                    try {
                        if ($date == $weather['forecast']['forecastday'][0]['date']) {
                            $today = $weather['forecast']['forecastday'][0]['day']['condition']['text'];
                        }
                    } catch (\Exception $e) {
                        $today = '';
                        Log::warning('Undefined forecast for city ' . $city['name'] . ' at ' . $date);
                    }

                    $date = Carbon::now()->addDay()->format('Y-m-d');
                    try {
                        if ($date == $weather['forecast']['forecastday'][1]['date']) {
                            $tomorrow = $weather['forecast']['forecastday'][1]['day']['condition']['text'];
                        }
                    } catch (\Exception $e) {
                        $tomorrow = '';
                        Log::warning('Undefined forecast for city ' . $city['name'] . ' at ' . $date);
                    }
                    $response = "{$city['name']} | $today - $tomorrow";

                    $this->out->iteration($response);
                } else {
                    Log::error('Undefined forecast for city ' . $city['name']);
                }
            }
        }

        return $this->out->send();
    }

    /**
     * Return array of data from Musement.com
     *
     * @return array
     */
    private function getMusementData(): array
    {
        return Http::get(config('app.musement.cities'))->json();
    }
}
