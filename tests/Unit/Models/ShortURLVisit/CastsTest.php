<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURLVisit;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
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
        $shortUrlVisit = ShortURLVisit::factory()
            ->for(ShortURL::factory())
            ->create([
                'visited_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $shortUrlVisit->refresh();

        $this->assertInstanceOf(Carbon::class, $shortUrlVisit->visited_at);
        $this->assertInstanceOf(Carbon::class, $shortUrlVisit->created_at);
        $this->assertInstanceOf(Carbon::class, $shortUrlVisit->updated_at);
    }

    #[Test]
    public function carbon_immutable_date_objects_are_returned(): void
    {
        Date::use(CarbonImmutable::class);

        $shortUrlVisit = ShortURLVisit::factory()
            ->for(ShortURL::factory())
            ->create([
                'visited_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $shortUrlVisit->refresh();

        $this->assertInstanceOf(CarbonImmutable::class, $shortUrlVisit->visited_at);
        $this->assertInstanceOf(CarbonImmutable::class, $shortUrlVisit->created_at);
        $this->assertInstanceOf(CarbonImmutable::class, $shortUrlVisit->updated_at);

        Date::use(Carbon::class);
    }
}
