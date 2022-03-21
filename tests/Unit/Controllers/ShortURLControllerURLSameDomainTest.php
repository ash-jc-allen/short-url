<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Controllers;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use ShortURL as ShortURLAlias;

class ShortURLControllerURLSameDomainTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('short-url.url', 'http://test.localhost');
        $app['config']->set('short-url.domain', 'test.localhost');

        parent::getEnvironmentSetUp($app);
    }

    /** @test */
    public function visitor_is_not_redirected_to_the_destination_url_with_domain()
    {
        ShortURL::create([
            'destination_url'      => 'https://google.com',
            'default_short_url'    => ShortURLAlias::url().'/'.ShortURLAlias::prefixUrl('12345'),
            'url_key'              => '12345',
            'single_use'           => true,
            'track_visits'         => true,
            'redirect_status_code' => 301,
            'activated_at'         => now()->subMinute(),
        ]);

        $this->get(ShortURLAlias::url().'/'.ShortURLAlias::prefixUrl('12345'))
            ->assertStatus(301);
    }
}
