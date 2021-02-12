<?php

namespace App\Http\Controllers;

use App\Libraries\Forecast;
use App\Libraries\ScopeOut;

class WeatherController extends Controller
{
    public function index()
    {
        $forecast = new Forecast(new ScopeOut());

        return $forecast->forecast();
    }
}
