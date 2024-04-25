<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Models\ShortURL;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class TrackingFieldsTest extends TestCase
{
    #[Test]
    public function array_is_returned_with_tracked_fields(): void
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
            'redirect_status_code' => 301,
            'track_ip_address' => true,
            'track_operating_system' => true,
            'track_operating_system_version' => false,
            'track_browser' => true,
            'track_browser_version' => true,
            'track_referer_url' => false,
            'track_device_type' => true,
        ]);

        $this->assertSame([
            'ip_address',
            'operating_system',
            'browser',
            'browser_version',
            'device_type',
        ], $shortURL->trackingFields());
    }

    #[Test]
    public function empty_array_is_returned_if_no_fields_are_set_to_be_tracked(): void
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
            'redirect_status_code' => 301,
            'track_ip_address' => false,
            'track_operating_system' => false,
            'track_operating_system_version' => false,
            'track_browser' => false,
            'track_browser_version' => false,
            'track_referer_url' => false,
            'track_device_type' => false,
        ]);

        $this->assertSame([], $shortURL->trackingFields());
    }
}
