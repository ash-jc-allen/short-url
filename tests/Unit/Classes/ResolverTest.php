<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Classes;

use AshAllenDesign\ShortURL\Classes\Resolver;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResolverTest extends TestCase
{
    public static function trackingFieldsProvider(): array
    {
        return [
            // Firefox 125.0 on MacOS 10.15
            [
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:125.0) Gecko/20100101 Firefox/125.0',
                [
                    'operating_system' => 'OS X',
                    'operating_system_version' => null,
                    'browser' => 'Firefox',
                    'browser_version' => '125.0',
                    'referer_url' => null,
                    'device_type' => 'desktop',
                ],
            ],

            // Safari 17.4 on iOS 17.4 (iPad)
            [
                'Mozilla/5.0 (iPad; CPU OS 17_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4.1 Mobile/15E148 Safari/604.1',
                [
                    'operating_system' => 'iOS',
                    'operating_system_version' => '17.4.1',
                    'browser' => 'Safari',
                    'browser_version' => '17.4.1',
                    'referer_url' => null,
                    'device_type' => 'tablet',
                ],
            ],

            // Chrome 11.6 on Android 11 (Nexus 9 Tablet)
            [
                'Mozilla/5.0 (Linux; Android 11; Nexus 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Safari/537.36',
                [
                    'operating_system' => 'Android',
                    'operating_system_version' => '11',
                    'browser' => 'Chrome',
                    'browser_version' => '116',
                    'referer_url' => null,
                    'device_type' => 'tablet',
                ],
            ],

            // Googlebot Image bot
            [
                'Googlebot-Image/1.0',
                [
                    'operating_system' => null,
                    'operating_system_version' => null,
                    'browser' => 'Googlebot Image',
                    'browser_version' => '1.0',
                    'referer_url' => null,
                    'device_type' => 'robot',
                ],
            ],

            // Googlebot Desktop bot
            [
                'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Chrome/W.X.Y.Z Safari/537.36',
                [
                    'operating_system' => null,
                    'operating_system_version' => null,
                    'browser' => 'Googlebot',
                    'browser_version' => '2.1',
                    'referer_url' => null,
                    'device_type' => 'robot',
                ],
            ],
        ];
    }

    /** @test */
    public function exception_is_thrown_in_the_constructor_if_the_config_variables_are_invalid()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The config URL length is not a valid integer.');

        Config::set('short-url.key_length', 'INVALID');

        new Resolver();
    }

    /** @test */
    public function request_is_aborted_if_url_is_single_use_and_has_already_been_visited()
    {
        $this->expectException(NotFoundHttpException::class);

        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
        ]);

        ShortURLVisit::create(['short_url_id' => $shortURL->id, 'visited_at' => now()]);

        $request = Request::create(config('short-url.default_url').'/short/12345');

        $resolver = new Resolver();
        $resolver->handleVisit($request, $shortURL);
    }

    /** @test */
    public function request_is_not_aborted_if_url_is_single_use_and_has_not_been_visited()
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
            'activated_at' => now()->subSecond(),
        ]);

        $request = Request::create(config('short-url.default_url').'/short/12345');

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);

        $this->assertTrue($result);
    }

    /** @test */
    public function request_is_not_aborted_if_url_is_not_single_use_and_has_been_visited()
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => false,
            'track_visits' => true,
            'activated_at' => now()->subSecond(),
        ]);

        ShortURLVisit::create(['short_url_id' => $shortURL->id, 'visited_at' => now()]);

        $request = Request::create(config('short-url.default_url').'/short/12345');

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);
    }

    /** @test */
    public function visit_details_are_not_recorded_if_url_does_not_have_tracking_enabled()
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => false,
            'track_visits' => false,
            'activated_at' => now()->subSecond(),
        ]);

        $request = Request::create(config('short-url.default_url').'/short/12345');

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => null,
            'operating_system' => null,
            'operating_system_version' => null,
            'browser' => null,
            'browser_version' => null,
            'referer_url' => null,
        ]);
    }

    /**
     * @test
     *
     * @dataProvider trackingFieldsProvider
     */
    public function visit_is_recorded_if_url_has_tracking_enabled(string $userAgent, array $expectedTrackingData): void
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => false,
            'track_visits' => true,
            'track_ip_address' => true,
            'track_operating_system' => true,
            'track_operating_system_version' => true,
            'track_browser' => true,
            'track_browser_version' => true,
            'track_referer_url' => true,
            'track_device_type' => true,
            'activated_at' => now()->subSecond(),
        ]);

        $request = Request::create(
            uri: config('short-url.default_url').'/short/12345',
            server: [
                'HTTP_USER_AGENT' => $userAgent,
            ]
        );

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => $request->ip(),
            'referer_url' => null,
            ...$expectedTrackingData,
        ]);
    }

    /** @test */
    public function visit_is_recorded_if_url_has_tracking_enabled_and_the_user_agent_is_invalid(): void
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => false,
            'track_visits' => true,
            'track_ip_address' => true,
            'track_operating_system' => true,
            'track_operating_system_version' => true,
            'track_browser' => true,
            'track_browser_version' => true,
            'track_referer_url' => true,
            'track_device_type' => true,
            'activated_at' => now()->subSecond(),
        ]);

        $request = Request::create(
            uri: config('short-url.default_url').'/short/12345',
            server: [
                'HTTP_USER_AGENT' => 'INVALID',
            ]
        );

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => $request->ip(),
            'operating_system' => null,
            'operating_system_version' => null,
            'browser' => null,
            'browser_version' => null,
            'referer_url' => null,
            'device_type' => null,
        ]);
    }

    /** @test */
    public function visit_is_recorded_if_url_has_tracking_enabled_and_the_user_agent_is_empty(): void
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => false,
            'track_visits' => true,
            'track_ip_address' => true,
            'track_operating_system' => true,
            'track_operating_system_version' => true,
            'track_browser' => true,
            'track_browser_version' => true,
            'track_referer_url' => true,
            'track_device_type' => true,
            'activated_at' => now()->subSecond(),
        ]);

        $request = Request::create(
            uri: config('short-url.default_url').'/short/12345',
            server: [
                'HTTP_USER_AGENT' => null,
            ]
        );

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => $request->ip(),
            'operating_system' => null,
            'operating_system_version' => null,
            'browser' => null,
            'browser_version' => null,
            'referer_url' => null,
            'device_type' => null,
        ]);
    }

    /** @test */
    public function only_specific_fields_are_recorded_if_enabled()
    {
        // Disable default tracking for the IP address, browser
        // version and referer URL.

        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => false,
            'track_visits' => true,
            'track_ip_address' => false,
            'track_operating_system' => true,
            'track_operating_system_version' => true,
            'track_browser' => true,
            'track_browser_version' => false,
            'track_referer_url' => false,
            'track_device_type' => true,
            'activated_at' => now()->subSecond(),
        ]);

        $request = Request::create(
            uri: config('short-url.default_url').'/short/12345',
            server: [
                'HTTP_referer' => 'https://google.com',
                'HTTP_USER_AGENT' => self::trackingFieldsProvider()[1][0],
            ]
        );

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        // Safari 17.4 on iOS 17.4 (iPad)
        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => null,
            'operating_system' => 'iOS',
            'operating_system_version' => '17.4.1',
            'browser' => 'Safari',
            'browser_version' => null,
            'referer_url' => null,
            'device_type' => 'tablet',
        ]);
    }

    /** @test */
    public function request_is_aborted_if_url_is_single_use_and_the_tracking_is_not_enabled()
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => false,
            'activated_at' => now()->subSecond(),
        ]);

        $request = Request::create(config('short-url.default_url').'/short/12345');

        $resolver = new Resolver();

        // Visit the URL for the first time. This should be allowed.
        $resolver->handleVisit($request, $shortURL);

        $this->expectException(NotFoundHttpException::class);

        // Visit the URL for the second time. This should be aborted.
        $resolver->handleVisit($request, $shortURL);
    }

    /** @test */
    public function referer_url_is_stored_if_it_is_enabled()
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => false,
            'track_visits' => true,
            'track_ip_address' => true,
            'track_operating_system' => true,
            'track_operating_system_version' => true,
            'track_browser' => true,
            'track_browser_version' => true,
            'track_referer_url' => true,
            'track_device_type' => true,
            'activated_at' => now()->subSecond(),
        ]);

        $request = Request::create(config('short-url.default_url').'/short/12345', 'GET', [], [], [], [
            'HTTP_referer' => 'https://google.com',
            'HTTP_USER_AGENT' => static::trackingFieldsProvider()[1][0],
        ]);

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        // Safari 17.4 on iOS 17.4 (iPad)
        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => $request->ip(),
            'referer_url' => 'https://google.com',
        ]);
    }

    /** @test */
    public function fields_are_not_recorded_if_all_are_true_but_track_visits_is_disabled(): void
    {
        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => false,
            'track_visits' => false,
            'track_ip_address' => true,
            'track_operating_system' => true,
            'track_operating_system_version' => true,
            'track_browser' => true,
            'track_browser_version' => true,
            'track_referer_url' => true,
            'track_device_type' => true,
            'activated_at' => now()->subSecond(),
        ]);

        $request = Request::create(config('short-url.default_url').'/short/12345', 'GET', [], [], [], [
            'HTTP_referer' => 'https://google.com',
            'HTTP_USER_AGENT' => static::trackingFieldsProvider()[1][0],
        ]);

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => null,
            'operating_system' => null,
            'operating_system_version' => null,
            'browser' => null,
            'browser_version' => null,
            'referer_url' => null,
            'device_type' => null,
        ]);
    }
}
