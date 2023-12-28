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
            'destination_url' => 'https://example.com',
            'default_short_url' => 'https://domain.com/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
        ]);

        /** @var ShortURLVisit $visit */
        $visit = ShortURLVisit::create(['short_url_id' => $shortURL->id, 'visited_at' => now()]);

        $this->assertTrue($visit->shortURL->is($shortURL));
    }
}
