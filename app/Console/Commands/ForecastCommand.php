<?php

namespace App\Console\Commands;

use App\Libraries\Forecast;
use App\Libraries\IterableOut;
use Illuminate\Console\Command;

class ForecastCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast';

    /**
     * The console command help text.
     *
     * @var string|null
     */
    protected $description = 'Get forecast from WeatherApi';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start');
        $forecast = new Forecast(new IterableOut($this));
        $forecast->forecast();
        $this->info('Done');
    }
}
