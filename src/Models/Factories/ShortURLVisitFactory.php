<?php

namespace AshAllenDesign\ShortURL\Models\Factories;

use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;

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
            'operating_system' => $this->faker->randomElement(
                array_keys(Agent::getPlatforms()),
            ),
            'operating_system_version' => $this->faker->randomFloat(8, 20),
            'browser' => $this->faker->randomElement(Agent::getBrowsers()),
            'browser_version' => $this->faker->userAgent(),
            'device_type' => $this->faker->randomElement(
                array_merge(
                    array_keys(Agent::getPhoneDevices()),
                    array_keys(Agent::getTabletDevices()),
                    array_keys(Agent::getDesktopDevices()),
                )),
            'utm_source' => $this->faker->randomElement(['newsletter', 'facebook', 'twitter', 'instagram']),
            'utm_medium' => $this->faker->randomElement(['cpc', 'banner', 'email', 'social']),
            'utm_campaign' => $this->faker->randomElement(['summer_sale', 'winter_sale', 'autumn_sale', 'spring_sale']),
            'utm_term' => $this->faker->randomElement(['shoes', 'boots', 'trainers', 'sandals']),
            'utm_content' => $this->faker->randomElement(['red', 'blue', 'green', 'yellow']),
            'visited_at' => Carbon::now(),
            'referer_url' => $this->faker->url(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
