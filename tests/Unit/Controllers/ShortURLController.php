<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Controllers;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
}
