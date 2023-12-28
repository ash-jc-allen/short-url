<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Controllers;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

class ShortURLDisableRouteTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('short-url.disable_default_route', true);

        parent::getEnvironmentSetUp($app);
    }

    /** @test */
    public function request_is_aborted_if_custom_routing_is_enabled_but_the_default_route_has_been_used(): void
    {
        ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
            'redirect_status_code' => 301,
            'activated_at' => now()->subMinute(),
        ]);

        $this->get('/short/12345')->assertNotFound();
    }
}
