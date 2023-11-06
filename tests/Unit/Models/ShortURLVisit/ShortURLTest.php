<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURLVisit;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

class ShortURLTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function short_url_can_be_fetched_from_visit(): void
    {
        $shortURL = ShortURL::create([
            'destination_url'   => 'https://example.com',
            'default_short_url' => 'https://domain.com/12345',
            'url_key'           => '12345',
            'single_use'        => true,
            'track_visits'      => true,
        ]);

        /** @var ShortURLVisit $visit */
        $visit = ShortURLVisit::create(['short_url_id' => $shortURL->id, 'visited_at' => now()]);

        $this->assertTrue($visit->shortURL->is($shortURL));
    }

    /** @test */
    public function short_url_can_be_fetched_from_visit_with_utm_params(): void
    {
        $shortURL = ShortURL::create([
            'destination_url'   => 'https://domain.com',
            'default_short_url' => 'https://domain.com/12345',
            'url_key'           => '12345',
            'single_use'        => true,
            'track_visits'      => true,
            'track_utm'         => true,4
        ]);

        $this->get('/short/12345?utm_source=newsletter&utm_medium=email&utm_campaign=spring_sale&utm_content=promo_banner&utm_term=short_url')
            ->assertStatus(301)
            ->assertRedirect('https://domain.com');

        // Get the visit that was just logged.
        $visit = ShortURLVisit::first();

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'utm_source'   => 'newsletter',
            'utm_medium'   => 'email',
            'utm_campaign' => 'spring_sale',
            'utm_content'  => 'promo_banner',
            'utm_term'     => 'short_url',
        ]);
    }

    public function short_url_can_be_fetched_from_visit_with_utm_params_within_destination_url(): void
    {
        $shortURL = ShortURL::create([
            'destination_url'   => 'https://domain.com??utm_source=newsletter&utm_medium=email&utm_campaign=spring_sale&utm_content=promo_banner&utm_term=short_url',
            'default_short_url' => 'https://domain.com/12345',
            'url_key'           => '12345',
            'single_use'        => true,
            'track_visits'      => true,
            'track_utm'         => true,4
        ]);

        $this->get('/short/12345')
            ->assertStatus(301)
            ->assertRedirect('https://domain.com?utm_source=newsletter&utm_medium=email&utm_campaign=spring_sale&utm_content=promo_banner&utm_term=short_url');

        // Get the visit that was just logged.
        $visit = ShortURLVisit::first();

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'utm_source'   => 'newsletter',
            'utm_medium'   => 'email',
            'utm_campaign' => 'spring_sale',
            'utm_content'  => 'promo_banner',
            'utm_term'     => 'short_url',
        ]);
    }
}
