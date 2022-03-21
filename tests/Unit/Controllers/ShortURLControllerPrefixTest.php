<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Controllers;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use ShortURL as ShortURLAlias;

class ShortURLControllerPrefixTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('short-url.prefix', '/s');

        parent::getEnvironmentSetUp($app);
    }

    /** @test */
    public function visitor_is_redirected_to_the_destination_url_with_custom_prefix()
    {
        ShortURL::create([
            'destination_url'      => 'https://google.com',
            'default_short_url'    => ShortURLAlias::domain().'/'.ShortURLAlias::prefixUrl('12345'),
            'url_key'              => '12345',
            'single_use'           => true,
            'track_visits'         => true,
            'redirect_status_code' => 301,
            'activated_at'         => now()->subMinute(),
        ]);

        $this->get('/s/12345')->assertStatus(301)->assertRedirect('https://google.com');
    }
}
