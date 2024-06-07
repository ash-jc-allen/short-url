<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURL;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;

final class CastsTest extends TestCase
{
    #[Test]
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
    #[Test]
    public function carbon_immutable_date_objects_are_returned(): void
    {
        Date::use(CarbonImmutable::class);

        $shortUrl = ShortURL::factory()
            ->create([
                'activated_at' => now(),
                'deactivated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $shortUrl->refresh();

        $this->assertInstanceOf(CarbonImmutable::class, $shortUrl->activated_at);
        $this->assertInstanceOf(CarbonImmutable::class, $shortUrl->deactivated_at);
        $this->assertInstanceOf(CarbonImmutable::class, $shortUrl->created_at);
        $this->assertInstanceOf(CarbonImmutable::class, $shortUrl->updated_at);

        Date::use(Carbon::class);
    }

    #[Test]
    public function forward_query_params_is_casted_correctly(): void
    {
        $shortUrl = ShortURL::factory()->create(['forward_query_params' => 1]);

        $this->assertTrue($shortUrl->forward_query_params);
    }
}
