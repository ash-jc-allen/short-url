<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Providers;

use AshAllenDesign\ShortURL\Classes\Builder;
use AshAllenDesign\ShortURL\Classes\KeyGenerator;
use AshAllenDesign\ShortURL\Classes\Validation;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Interfaces\UrlKeyGenerator;
use AshAllenDesign\ShortURL\Interfaces\UserAgentDriver;
use Hashids\Hashids;
//use Sqids\Sqids; # Add Sqids
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ShortURLProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/short-url.php', 'short-url');

        $this->app->bind(UserAgentDriver::class, config('short-url.user_agent_driver'));
        $this->app->bind(UrlKeyGenerator::class, config('short-url.url_key_generator'));

        $this->app->bind('short-url.builder', function (Application $app): Builder {
            return new Builder(
                validation: $app->make(Validation::class),
                urlKeyGenerator: $app->make(UrlKeyGenerator::class),
            );
        });

        $this->app->when(KeyGenerator::class)
            ->needs(Hashids::class)
            ->give(fn (): Hashids => new Hashids(
                salt: config('short-url.key_salt'),
                minHashLength: (int) config('short-url.key_length'),
                alphabet: config('short-url.alphabet')
            ));

        // Tentative code (I may be wrong, just wondering how to implement this)

        // $this->app->when(KeyGenerator::class)
        //     ->needs(Sqids::class)
        //     ->give(fn (): Sqids => new Sqids(
        //         // Sqids doesn't have a salt parameter, was removed
        //         minHashLength: (int) config('short-url.key_length'),
        //         alphabet: config('short-url.alphabet')
        //     ));
    }

    /**
     * @throws ValidationException
     */
    public function boot(): void
    {
        // Config
        $this->publishes([
            __DIR__.'/../../config/short-url.php' => config_path('short-url.php'),
        ], 'short-url-config');

        // Migrations
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'short-url-migrations');

        // Routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        if (config('short-url') && config('short-url.validate_config')) {
            (new Validation())->validateConfig();
        }
    }
}
