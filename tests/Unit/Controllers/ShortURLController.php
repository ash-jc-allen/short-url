<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Controllers;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

class ShortURLController extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function request_is_aborted_with_http_404_if_the_short_url_cannot_be_found()
    {
        $this->get('/short/INVALID')->assertNotFound();
    }

    /** @test */
    public function visitor_is_redirected_to_the_destination_url()
    {
        ShortURL::create([
            'destination_url'   => 'https://google.com',
            'default_short_url' => config('app.url').'/short/12345',
            'url_key'           => '12345',
            'single_use'        => true,
            'track_visits'      => true,
        ]);

        $this->get('/short/12345')->assertStatus(301)->assertRedirect('https://google.com');
    }

    /** @test */
    public function request_is_aborted_if_custom_routing_is_enabled_but_the_default_route_has_been_used()
    {
        Config::set('short-url.custom_routing_enabled', true);

        ShortURL::create([
            'destination_url'   => 'https://google.com',
            'default_short_url' => config('app.url').'/short/12345',
            'url_key'           => '12345',
            'single_use'        => true,
            'track_visits'      => true,
        ]);

        $this->get('/short/12345')->assertNotFound();
    }
}
