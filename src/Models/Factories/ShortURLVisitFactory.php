<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Models\Factories;

use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<ShortURLVisit>
 */
class ShortURLVisitFactory extends Factory
{
    protected $model = ShortURLVisit::class;

    public function definition(): array
    {
        return [
            'ip_address' => $this->faker->ipv4(),
            'operating_system' => $this->faker->randomElement([
                'OS X',
                'iOS',
                'Android',
                'null',
            ]),
            'operating_system_version' => $this->faker->randomFloat(8, 20),
            'browser' => $this->faker->randomElement([
                'Firefox',
                'Safari',
                'Chrome',
                'Googlebot',
            ]),
            'browser_version' => $this->faker->randomFloat(8, 20),
            'device_type' => $this->faker->randomElement([
                'desktop',
                'mobile',
                'tablet',
                'robot',
            ]),
            'visited_at' => Carbon::now(),
            'referer_url' => $this->faker->url(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
