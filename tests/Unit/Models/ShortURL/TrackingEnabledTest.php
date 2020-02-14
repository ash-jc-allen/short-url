<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURL;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrackingEnabledTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function true_is_returned_if_tracking_is_enabled_for_the_short_url()
    {
        $shortURL = ShortURL::create([
            'destination_url'                => 'https://google.com',
            'default_short_url'              => config('app.url').'/short/12345',
            'url_key'                        => '12345',
            'single_use'                     => true,
            'track_visits'                   => true,
            'redirect_status_code'           => 301,
        ]);

        $this->assertTrue($shortURL->trackingEnabled());
    }

    /** @test */
    public function false_is_returned_if_tracking_is_disabled_for_the_short_url()
    {
        $shortURL = ShortURL::create([
            'destination_url'                => 'https://google.com',
            'default_short_url'              => config('app.url').'/short/12345',
            'url_key'                        => '12345',
            'single_use'                     => true,
            'track_visits'                   => false,
            'redirect_status_code'           => 301,
        ]);

        $this->assertFalse($shortURL->trackingEnabled());
    }
}
