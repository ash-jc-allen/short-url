<?php

namespace AshAllenDesign\ShortURL\Providers;

use Illuminate\Support\ServiceProvider;

class ShortURLProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([dirname(__DIR__, 1).'/Config/short-url.php' => config_path('short-url.php')]);
        $this->mergeConfigFrom(dirname(__DIR__, 1).'/Config/short-url.php', 'short-url');

        $this->loadMigrationsFrom(__DIR__.'/../Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
}
