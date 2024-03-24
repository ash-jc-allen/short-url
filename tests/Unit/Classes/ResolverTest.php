<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Classes;

use AshAllenDesign\ShortURL\Classes\Resolver;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Mockery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WhichBrowser\Model\Browser;
use WhichBrowser\Model\Device;
use WhichBrowser\Model\OS;
use WhichBrowser\Parser;
use WhichBrowser\Resolver as WhichBrowserResolver;
use WhichBrowser\Model\Version;

class ResolverTest extends TestCase
{
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

    /** @test */
    public function visit_is_recorded_if_url_has_tracking_enabled()
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

        $request = Request::create(config('short-url.default_url').'/short/12345');

        // Mock the WhichBrowser classes so that we don't have
        // to mock the User-Agent header in the
        // request.
        $mockOSVersion = Mockery::mock(Version::class)->makePartial();
        $mockOSVersion->value = '19.10';
        $mockOS = Mockery::mock(OS::class)->makePartial();
        $mockOS->name = 'Ubuntu';
        $mockOS->version = $mockOSVersion;
        $mockBrowserVersion = Mockery::mock(Version::class)->makePartial();
        $mockBrowserVersion->value = '71.0';
        $mockBrowser = Mockery::mock(Browser::class)->makePartial();
        $mockBrowser->name = 'Firefox';
        $mockBrowser->version = $mockBrowserVersion;
        $mockDevice = Mockery::mock(Device::class)->makePartial();
        $mockDevice->type = 'mobile';
        $mockResolver = Mockery::mock(WhichBrowserResolver::class)->makePartial();
        $mockResolver->os = $mockOS;
        $mockResolver->browser = $mockBrowser;
        $mockResolver->device = $mockDevice;
        $mock = Mockery::mock(Parser::class)->makePartial();
        $mock->shouldReceive('analyse')->once()->andReturn($mockResolver);

        $resolver = new Resolver($mock);
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => $request->ip(),
            'operating_system' => 'Ubuntu',
            'operating_system_version' => '19.10',
            'browser' => 'Firefox',
            'browser_version' => '71.0',
            'referer_url' => null,
            'device_type' => 'mobile',
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

        $request = Request::create(config('short-url.default_url').'/short/12345', 'GET', [], [], [], [
            'HTTP_referer' => 'https://google.com',
        ]);

        // Mock the WhichBrowser classes so that we don't have
        // to mock the User-Agent header in the
        // request.
        $mockOSVersion = Mockery::mock(Version::class)->makePartial();
        $mockOSVersion->value = '19.10';
        $mockOS = Mockery::mock(OS::class)->makePartial();
        $mockOS->name = 'Ubuntu';
        $mockOS->version = $mockOSVersion;
        $mockBrowserVersion = Mockery::mock(Version::class)->makePartial();
        $mockBrowserVersion->value = '71.0';
        $mockBrowser = Mockery::mock(Browser::class)->makePartial();
        $mockBrowser->name = 'Firefox';
        $mockBrowser->version = $mockBrowserVersion;
        $mockDevice = Mockery::mock(Device::class)->makePartial();
        $mockDevice->type = 'tablet';
        $mockResolver = Mockery::mock(WhichBrowserResolver::class)->makePartial();
        $mockResolver->os = $mockOS;
        $mockResolver->browser = $mockBrowser;
        $mockResolver->device = $mockDevice;
        $mock = Mockery::mock(Parser::class)->makePartial();
        $mock->shouldReceive('analyse')->once()->andReturn($mockResolver);

        $resolver = new Resolver($mock);
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => null,
            'operating_system' => 'Ubuntu',
            'operating_system_version' => '19.10',
            'browser' => 'Firefox',
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
        ]);

        // Mock the WhichBrowser classes so that we don't have
        // to mock the User-Agent header in the
        // request.
        $mockOSVersion = Mockery::mock(Version::class)->makePartial();
        $mockOSVersion->value = '19.10';
        $mockOS = Mockery::mock(OS::class)->makePartial();
        $mockOS->name = 'Ubuntu';
        $mockOS->version = $mockOSVersion;
        $mockBrowserVersion = Mockery::mock(Version::class)->makePartial();
        $mockBrowserVersion->value = false;
        $mockBrowser = Mockery::mock(Browser::class)->makePartial();
        $mockBrowser->name = 'Firefox';
        $mockBrowser->version = $mockBrowserVersion;
        $mockDevice = Mockery::mock(Device::class)->makePartial();
        $mockDevice->type = 'mobile';
        $mockResolver = Mockery::mock(WhichBrowserResolver::class)->makePartial();
        $mockResolver->os = $mockOS;
        $mockResolver->browser = $mockBrowser;
        $mockResolver->device = $mockDevice;
        $mock = Mockery::mock(Parser::class)->makePartial();
        $mock->shouldReceive('analyse')->once()->andReturn($mockResolver);

        $resolver = new Resolver($mock);
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => $request->ip(),
            'operating_system' => 'Ubuntu',
            'operating_system_version' => '19.10',
            'browser' => 'Firefox',
            'browser_version' => 0,
            'referer_url' => 'https://google.com',
        ]);
    }

    /** @test */
    public function device_type_is_stored_if_it_is_enabled()
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
        ]);

        // Mock the WhichBrowser classes so that we don't have
        // to mock the User-Agent header in the
        // request.
        $mockOSVersion = Mockery::mock(Version::class)->makePartial();
        $mockOSVersion->value = '19.10';
        $mockOS = Mockery::mock(OS::class)->makePartial();
        $mockOS->name = 'Ubuntu';
        $mockOS->version = $mockOSVersion;
        $mockBrowserVersion = Mockery::mock(Version::class)->makePartial();
        $mockBrowserVersion->value = '71.0';
        $mockBrowser = Mockery::mock(Browser::class)->makePartial();
        $mockBrowser->name = 'Firefox';
        $mockBrowser->version = $mockBrowserVersion;
        $mockDevice = Mockery::mock(Device::class)->makePartial();
        $mockDevice->type = 'desktop';
        $mockResolver = Mockery::mock(WhichBrowserResolver::class)->makePartial();
        $mockResolver->os = $mockOS;
        $mockResolver->browser = $mockBrowser;
        $mockResolver->device = $mockDevice;
        $mock = Mockery::mock(Parser::class)->makePartial();
        $mock->shouldReceive('analyse')->once()->andReturn($mockResolver);

        $resolver = new Resolver($mock);
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id' => $shortURL->id,
            'ip_address' => $request->ip(),
            'operating_system' => 'Ubuntu',
            'operating_system_version' => '19.10',
            'browser' => 'Firefox',
            'browser_version' => '71.0',
            'referer_url' => 'https://google.com',
            'device_type' => 'desktop',
        ]);
    }

    /** @test */
    public function fields_are_not_recorded_if_all_are_true_but_track_visits_is_disabled()
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
        ]);

        // Mock the Parser class so that we don't have
        // to mock the User-Agent header in the
        // request.
        $mock = Mockery::mock(Parser::class)->makePartial();
        $mock->shouldReceive('analyse')->never();

        $resolver = new Resolver($mock);
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
