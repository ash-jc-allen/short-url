<?php

namespace AshAllenDesign\ShortURL\Models\Factories;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;

class ShortURLVisitFactory extends Factory
{
    protected $model = ShortURLVisit::class;

    public function definition(): array
    {
        return [
            'ip_address' => $this->faker->ipv4(),
            'operating_system' => $this->faker->randomElement(
                $this->agentArrayKeys(Agent::getPlatforms())
            ),
            'operating_system_version' => $this->faker->randomFloat(8, 20),
            'browser' => $this->faker->randomElement(Agent::getBrowsers()),
            'browser_version' => $this->faker->userAgent(),
            'device_type' => $this->faker->randomElement(
                array_merge(
                    $this->agentArrayKeys(Agent::getPhoneDevices()),
                    $this->agentArrayKeys(Agent::getTabletDevices()),
                    $this->agentArrayKeys(Agent::getDesktopDevices()),
                )),
            'visited_at' => Carbon::now(),
            'referer_url' => $this->faker->url(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * This method grabs the keys of the properties used by Agent class as
     * a list of detection rules. We grab the keys because they are the data
     * being stored in this table.
     *
     * @param  array  $agentProperties
     * @return array
     */
    protected function agentArrayKeys(array $agentProperties): array
    {
        return collect($agentProperties)->keys()->toArray();
    }
}
