<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURL;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortURLFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_the_short_url_model_factory_works_fine()
    {
        $shortURL = ShortURL::factory()->create();

        $deactivatedShortURL = ShortURL::factory()->deactivated()->create();

        $inactiveShortURL = ShortURL::factory()->inactive()->create();

        $this->assertDatabaseCount('short_urls', 3)
            ->assertModelExists($shortURL)
            ->assertModelExists($deactivatedShortURL)
            ->assertModelExists($inactiveShortURL);

        $this->assertTrue($shortURL->activated_at !== null && $shortURL->deactivated_at == null);
        $this->assertTrue($deactivatedShortURL->activated_at !== null && $deactivatedShortURL->deactivated_at !== null);
        $this->assertTrue($inactiveShortURL->activated_at == null && $inactiveShortURL->deactivated_at == null);
    }
}
