<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURL;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ShortURLTest extends TestCase
{
    #[Test]
    public function connection_can_be_overridden(): void
    {
        config(['short-url.connection' => 'custom']);

        $this->assertEquals(
            'custom',
            (new ShortURL())->getConnectionName(),
        );
    }

    #[Test]
    public function default_connection_is_used_if_the_override_is_not_set(): void
    {
        $this->assertNull((new ShortURL())->getConnectionName());
    }

    #[Test]
    public function short_url_keys_are_case_sensitive(): void
    {
        ShortURL::factory()->create(['url_key' => 'test']);
        $shortUrlTwo = ShortURL::factory()->create(['url_key' => 'Test']);

        $fetchedShortUrl = ShortURL::query()->where('url_key', 'Test')->sole();

        $this->assertSame($shortUrlTwo->id, $fetchedShortUrl->id);
    }
}
