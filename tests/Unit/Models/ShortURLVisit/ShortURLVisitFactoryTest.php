<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURLVisit;

use PHPUnit\Framework\Attributes\Test;
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;

final class ShortURLVisitFactoryTest extends TestCase
{
    #[Test]
    public function test_that_short_url_visit_model_factory_works_fine(): void
    {
        $shortURL = ShortURL::factory()->create();

        $shortURLVisit = ShortURLVisit::factory()->for($shortURL)->create();

        $this->assertDatabaseCount('short_url_visits', 1)
            ->assertDatabaseCount('short_urls', 1)
            ->assertModelExists($shortURLVisit)
            ->assertModelExists($shortURL);

        $this->assertTrue($shortURLVisit->shortURL->is($shortURL));
    }
}
