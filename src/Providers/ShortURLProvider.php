<?php

namespace AshAllenDesign\ShortURL\Providers;

use Illuminate\Support\ServiceProvider;

class ExchangeRatesProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    public function boot()
    {
        $this->publishes([dirname(__DIR__, 1).'/Config/short-url.php' => config_path('short-url.php')]);
        $this->mergeConfigFrom(dirname(__DIR__, 1).'/Config/short-url.php', 'short-url');
    }
}
