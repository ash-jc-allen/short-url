<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Controllers;

use AshAllenDesign\ShortURL\Events\ShortURLVisited;
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class ShortURLControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function request_is_aborted_with_http_404_if_the_short_url_cannot_be_found()
    {
        $this->get('/short/INVALID')->assertNotFound();
    }

    /** @test */
    public function visitor_is_redirected_to_the_destination_url()
    {
        ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
            'redirect_status_code' => 301,
            'activated_at' => now()->subMinute(),
        ]);

        $this->get('/short/12345')->assertStatus(301)->assertRedirect('https://google.com');
    }

    /** @test */
    public function event_is_dispatched_when_the_short_url_is_visited()
    {
        Event::fake();

        $shortURL = ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'forward_query_params' => false,
            'track_visits' => true,
            'redirect_status_code' => 301,
            'track_ip_address' => true,
            'track_operating_system' => true,
            'track_operating_system_version' => false,
            'track_browser' => true,
            'track_browser_version' => true,
            'track_referer_url' => false,
            'track_device_type' => true,
            'activated_at' => now()->subMinute(),
            'deactivated_at' => null,
        ]);

        $this->get('/short/12345')->assertStatus(301)->assertRedirect('https://google.com');

        // Get the visit that was just logged.
        $visit = ShortURLVisit::first();

        Event::assertDispatched(ShortURLVisited::class, function (ShortURLVisited $event) use ($shortURL, $visit) {
            if ($shortURL->toArray() != $event->shortURL->fresh()->toArray()) {
                return false;
            }

            if ($visit->toArray() != $event->shortURLVisit->fresh()->toArray()) {
                return false;
            }

            return true;
        });
    }

    /** @test */
    public function visitor_is_redirected_with_correct_status_code()
    {
        ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
            'redirect_status_code' => 302,
            'activated_at' => now()->subMinute(),
        ]);

        $this->get('/short/12345')->assertStatus(302)->assertRedirect('https://google.com');
    }

    /** @test */
    public function request_is_aborted_if_the_activation_date_is_in_the_future()
    {
        ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
            'redirect_status_code' => 302,
            'activated_at' => now()->addMinute(),
            'deactivated_at' => null,
        ]);

        $this->get('/short/12345')->assertNotFound();
    }

    /** @test */
    public function request_is_aborted_if_the_deactivation_date_is_in_the_past()
    {
        ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
            'redirect_status_code' => 302,
            'activated_at' => now()->subMinutes(2),
            'deactivated_at' => now()->subMinute(),
        ]);

        $this->get('/short/12345')->assertNotFound();
    }

    /** @test */
    public function visitor_is_redirected_to_the_destination_url_if_the_deactivation_date_is_in_the_future()
    {
        ShortURL::create([
            'destination_url' => 'https://google.com',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'single_use' => true,
            'track_visits' => true,
            'redirect_status_code' => 302,
            'activated_at' => now()->subMinute(),
            'deactivated_at' => now()->addMinute(),
        ]);

        $this->get('/short/12345')->assertStatus(302)->assertRedirect('https://google.com');
    }

    /** @test */
    public function visitor_is_redirected_to_the_destination_without_source_query_parameters_if_option_set_to_false()
    {
        ShortURL::create([
            'destination_url' => 'https://google.com?param1=abc',
            'default_short_url' => config('short-url.default_url').'/short/12345',
            'url_key' => '12345',
            'forward_query_params' => false,
            'redirect_status_code' => 301,
            'single_use' => true,
            'track_visits' => true,
        ]);

        $this->get('/short/12345?param1=test&param2=test2')->assertStatus(301)->assertRedirect('https://google.com?param1=abc');
    }

    /**
     * @test
     *
     * @dataProvider forwardQueryParamsProvider
     */
    public function visitor_is_redirected_to_the_destination_with_source_query_parameters_if_option_set_to_true(
        string $shortUrl,
        string $requestUrl,
        string $destinationUrl,
        string $expectedDestinationUrl
    ): void {
        ShortURL::query()->create([
            'destination_url' => $destinationUrl,
            'default_short_url' => $shortUrl,
            'url_key' => '12345',
            'forward_query_params' => true,
            'redirect_status_code' => 301,
            'single_use' => true,
            'track_visits' => true,
        ]);

        $this->get($requestUrl)->assertStatus(301)->assertRedirect($expectedDestinationUrl);
    }

    public function forwardQueryParamsProvider(): array
    {
        return [
            [
                '/short/12345',
                '/short/12345?param1=test&param2=test2',
                'https://google.com?param1=abc',
                'https://google.com?param1=abc&param1=test&param2=test2',
            ],
            [
                '/short/12345',
                '/short/12345?param1=abc',
                'https://google.com',
                'https://google.com?param1=abc',
            ],
            [
                '/short/12345',
                '/short/12345?param1=abc',
                'https://google.com?param1=hello',
                'https://google.com?param1=hello&param1=abc',
            ],
            [
                '/short/12345',
                '/short/12345?param3=abc',
                'https://google.com?param1=hello&param2=123',
                'https://google.com?param1=hello&param2=123&param3=abc',
            ],
        ];
    }
}
