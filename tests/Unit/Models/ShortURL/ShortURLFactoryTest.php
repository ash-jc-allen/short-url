<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURL;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;

final class ShortURLFactoryTest extends TestCase
{
    public function test_that_the_short_url_model_factory_works_fine(): void
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

    public function test_that_the_short_url_model_factory_works_fine_with_date_facade_configured_to_use_carbon_immutable(): void
    {
        Date::use(CarbonImmutable::class);

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

        Date::use(Carbon::class);
    }
}
