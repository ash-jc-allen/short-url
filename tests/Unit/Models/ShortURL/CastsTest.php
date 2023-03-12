<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURL;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

final class CastsTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function carbon_date_objects_are_returned(): void
    {
        $shortUrl = ShortURL::factory()
            ->create([
                'activated_at' => now(),
                'deactivated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $shortUrl->refresh();

        $this->assertInstanceOf(Carbon::class, $shortUrl->activated_at);
        $this->assertInstanceOf(Carbon::class, $shortUrl->deactivated_at);
        $this->assertInstanceOf(Carbon::class, $shortUrl->created_at);
        $this->assertInstanceOf(Carbon::class, $shortUrl->updated_at);
    }
}
