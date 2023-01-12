<?php

namespace AshAllenDesign\ShortURL\Models\Factories;

use AshAllenDesign\ShortURL\Classes\KeyGenerator;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShortURL>
 */
class ShortURLFactory extends Factory
{
    protected $model = ShortURL::class;

    public function definition(): array
    {
        $urlKey = (new KeyGenerator())->generateRandom();

        return [
            'destination_url' => $this->faker->url(),
            'default_short_url' => url($urlKey),
            'url_key' => $urlKey,
            'single_use' => $this->faker->boolean(),
            'forward_query_params' => $this->faker->boolean(),
            'track_visits' => $this->faker->boolean(),
            'redirect_status_code' => $this->faker->randomElement([301, 302]),
            'track_ip_address' => $this->faker->boolean(),
            'track_operating_system' => $this->faker->boolean(),
            'track_operating_system_version' => $this->faker->boolean(),
            'track_browser' => $this->faker->boolean(),
            'track_browser_version' => $this->faker->boolean(),
            'track_referer_url' => $this->faker->boolean(),
            'track_device_type' => $this->faker->boolean(),
            'activated_at' => now(),
            'deactivated_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * @return ShortURLFactory
     */
    public function deactivated(): ShortURLFactory
    {
        return $this->state(function () {
            return [
                'deactivated_at' => now()->subDay(),
            ];
        });
    }

    /**
     * @return ShortURLFactory
     */
    public function inactive(): ShortURLFactory
    {
        return $this->state(function () {
            return [
                'activated_at' => null,
                'deactivated_at' => null,
            ];
        });
    }
}
