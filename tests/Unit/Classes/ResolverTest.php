<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Classes;

use AshAllenDesign\ShortURL\Classes\Resolver;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Jenssegers\Agent\Agent;
use Mockery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResolverTest extends TestCase
{
    use RefreshDatabase;

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
            'destination_url'   => 'https://google.com',
            'default_short_url' => config('app.url').'/short/12345',
            'url_key'           => '12345',
            'single_use'        => true,
            'track_visits'      => true,
        ]);

        ShortURLVisit::create(['short_url_id' => $shortURL->id, 'visited_at' => now()]);

        $request = Request::create(config('app.url').'/short/12345');

        $resolver = new Resolver();
        $resolver->handleVisit($request, $shortURL);
    }

    /** @test */
    public function request_is_not_aborted_if_url_is_single_use_and_has_not_been_visited()
    {
        $shortURL = ShortURL::create([
            'destination_url'   => 'https://google.com',
            'default_short_url' => config('app.url').'/short/12345',
            'url_key'           => '12345',
            'single_use'        => true,
            'track_visits'      => true,
        ]);

        $request = Request::create(config('app.url').'/short/12345');

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);

        $this->assertTrue($result);
    }

    /** @test */
    public function request_is_not_aborted_if_url_is_not_single_use_and_has_been_visited()
    {
        $shortURL = ShortURL::create([
            'destination_url'   => 'https://google.com',
            'default_short_url' => config('app.url').'/short/12345',
            'url_key'           => '12345',
            'single_use'        => false,
            'track_visits'      => true,
        ]);

        ShortURLVisit::create(['short_url_id' => $shortURL->id, 'visited_at' => now()]);

        $request = Request::create(config('app.url').'/short/12345');

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);
    }

    /** @test */
    public function visit_is_not_recorded_if_url_does_not_have_tracking_enabled()
    {
        $shortURL = ShortURL::create([
            'destination_url'   => 'https://google.com',
            'default_short_url' => config('app.url').'/short/12345',
            'url_key'           => '12345',
            'single_use'        => false,
            'track_visits'      => false,
        ]);

        $request = Request::create(config('app.url').'/short/12345');

        $resolver = new Resolver();
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertEquals(0, ShortURLVisit::count());
    }

    /** @test */
    public function visit_is_recorded_if_url_has_tracking_enabled()
    {
        $shortURL = ShortURL::create([
            'destination_url'   => 'https://google.com',
            'default_short_url' => config('app.url').'/short/12345',
            'url_key'           => '12345',
            'single_use'        => false,
            'track_visits'      => true,
        ]);

        $request = Request::create(config('app.url').'/short/12345');

        // Mock the Agent class so that we don't have
        // to mock the User-Agent header in the
        // request.
        $mock = Mockery::mock(Agent::class)->makePartial();
        $mock->shouldReceive('platform')->twice()->withNoArgs()->andReturn('Ubuntu');
        $mock->shouldReceive('browser')->twice()->withNoArgs()->andReturn('Firefox');
        $mock->shouldReceive('version')->once()->withArgs(['Ubuntu'])->andReturn('19.10');
        $mock->shouldReceive('version')->once()->withArgs(['Firefox'])->andReturn('71.0');

        $resolver = new Resolver($mock);
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id'             => $shortURL->id,
            'ip_address'               => $request->ip(),
            'operating_system'         => 'Ubuntu',
            'operating_system_version' => '19.10',
            'browser'                  => 'Firefox',
            'browser_version'          => '71.0',
        ]);
    }

    /** @test */
    public function only_specific_fields_are_recorded_if_enabled_in_the_config()
    {
        // Disable default tracking for the IP address
        // and browser version.
        Config::set('short-url.tracking.fields.ip_address', false);
        Config::set('short-url.tracking.fields.browser_version', false);

        $shortURL = ShortURL::create([
            'destination_url'   => 'https://google.com',
            'default_short_url' => config('app.url').'/short/12345',
            'url_key'           => '12345',
            'single_use'        => false,
            'track_visits'      => true,
        ]);

        $request = Request::create(config('app.url').'/short/12345');

        // Mock the Agent class so that we don't have
        // to mock the User-Agent header in the
        // request.
        $mock = Mockery::mock(Agent::class)->makePartial();
        $mock->shouldReceive('platform')->twice()->withNoArgs()->andReturn('Ubuntu');
        $mock->shouldReceive('browser')->once()->withNoArgs()->andReturn('Firefox');
        $mock->shouldReceive('version')->once()->withArgs(['Ubuntu'])->andReturn('19.10');

        $resolver = new Resolver($mock);
        $result = $resolver->handleVisit($request, $shortURL);
        $this->assertTrue($result);

        $this->assertDatabaseHas('short_url_visits', [
            'short_url_id'             => $shortURL->id,
            'ip_address'               => null,
            'operating_system'         => 'Ubuntu',
            'operating_system_version' => '19.10',
            'browser'                  => 'Firefox',
            'browser_version'          => null,
        ]);
    }
}
