<?php

namespace App\Libraries;

use App\Interfaces\WayOutInterface;
use Illuminate\Support\Facades\Http;

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

                if (
                    (is_array($weather) && (array_keys($weather)[0] != 'error'))    //TODO
                    && ($weather != 'De Wallen')                                    //TODO
                ) {
                    $response = "{$city['name']} | {$weather['today']} - {$weather['tomorrow']}";

                    $this->out->iteration($response);
                } else { //TODO Just for test functionality while weather API is not working
                    $response = "{$city['name']} | Test today - Test tomorrow";

                    $this->out->iteration($response);
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
