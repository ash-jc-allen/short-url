<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Classes;

use AshAllenDesign\ShortURL\Classes\Builder;
use AshAllenDesign\ShortURL\Exceptions\ShortURLException;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use ShortURLBuilder;

class BuilderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function exception_is_thrown_in_the_constructor_if_the_config_variables_are_invalid()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The config URL length is not a valid integer.');

        Config::set('short-url.key_length', 'INVALID');

        new Builder();
    }

    /** @test */
    public function exception_is_thrown_if_the_destination_url_does_not_begin_with_http_or_https()
    {
        $this->expectException(ShortURLException::class);
        $this->expectExceptionMessage('The destination URL must begin with http:// or https://');

        $builder = new Builder();
        $builder->destinationUrl('INVALID');
    }

    /** @test */
    public function exception_is_thrown_if_no_destination_url_is_set()
    {
        $this->expectException(ShortURLException::class);
        $this->expectExceptionMessage('No destination URL has been set.');

        $builder = new Builder();
        $builder->make();
    }

    /** @test */
    public function destination_url_is_changed_to_https_if_secure_flag_has_been_set()
    {
        $builder = new Builder();
        $shortUrl = $builder->destinationUrl('http://domain.com')->secure()->make();
        $this->assertEquals('https://domain.com', $shortUrl->destination_url);
    }

    /** @test */
    public function destination_url_is_not_changed_to_https_if_secure_flag_has_not_been_set()
    {
        $builder = new Builder();
        $shortUrl = $builder->destinationUrl('http://domain.com')->secure(false)->make();
        $this->assertEquals('http://domain.com', $shortUrl->destination_url);
    }

    /** @test */
    public function track_visits_flag_is_set_from_the_config_if_it_is_not_explicitly_set()
    {
        Config::set('short-url.tracking.default_enabled', true);

        $builder = new Builder();
        $shortUrl = $builder->destinationUrl('http://domain.com')->make();
        $this->assertTrue($shortUrl->track_visits);

        Config::set('short-url.tracking.default_enabled', false);

        $shortUrl = $builder->destinationUrl('http://domain.com')->make();
        $this->assertFalse($shortUrl->track_visits);
    }

    /** @test */
    public function track_visits_flag_is_not_set_from_the_config_if_it_is_explicitly_set()
    {
        Config::set('short-url.tracking.default_enabled', true);

        $builder = new Builder();
        $shortUrl = $builder->destinationUrl('http://domain.com')->trackVisits(false)->make();
        $this->assertFalse($shortUrl->track_visits);

        Config::set('short-url.tracking.default_enabled', false);

        $shortUrl = $builder->destinationUrl('http://domain.com')->trackVisits()->make();
        $this->assertTrue($shortUrl->track_visits);
    }

    /** @test */
    public function exception_is_thrown_if_the_url_key_is_explicitly_set_and_already_exists_in_the_db()
    {
        ShortURL::create([
            'default_short_url' => 'https://short.com/urlkey123',
            'destination_url'   => 'https://destination.com/ashallendesign',
            'url_key'           => 'urlkey123',
            'single_use'        => false,
            'track_visits'      => false,
        ]);

        $this->expectException(ShortURLException::class);
        $this->expectExceptionMessage('A short URL with this key already exists.');

        $builder = new Builder();
        $builder->destinationUrl('https://domain.com')->urlKey('urlkey123')->make();
    }

    /** @test */
    public function explicitly_defined_url_key_can_be_used_if_it_does_not_exist_in_the_db()
    {
        $builder = new Builder();
        $builder->destinationUrl('https://domain.com')->urlKey('urlkey123')->make();

        $this->assertDatabaseHas('short_urls', ['url_key' => 'urlkey123']);
    }

    /** @test */
    public function random_url_key_is_generated_if_one_is_not_explicitly_defined()
    {
        $builder = new Builder();
        $shortURL = $builder->destinationUrl('https://domain.com')->make();

        $this->assertNotNull($shortURL->url_key);
        $this->assertEquals(5, strlen($shortURL->url_key));
    }

    /** @test */
    public function short_url_can_be_created_and_stored_in_the_database()
    {
        $builder = new Builder();
        $shortURL = $builder->destinationUrl('http://domain.com')
            ->urlKey('customKey')
            ->secure()
            ->trackVisits(false)
            ->make();

        $this->assertDatabaseHas('short_urls', [
            'default_short_url' => config('app.url').'/short/customKey',
            'url_key'           => 'customKey',
            'destination_url'   => 'https://domain.com',
            'track_visits'      => false,
            'single_use'        => false,
        ]);
    }

    /** @test */
    public function short_url_can_be_created_and_stored_in_the_database_using_the_facade()
    {
        ShortURLBuilder::destinationUrl('http://domain.com')
            ->urlKey('customKey')
            ->secure()
            ->trackVisits(false)
            ->make();

        $this->assertDatabaseHas('short_urls', [
            'default_short_url' => config('app.url').'/short/customKey',
            'url_key'           => 'customKey',
            'destination_url'   => 'https://domain.com',
            'track_visits'      => false,
            'single_use'        => false,
        ]);
    }
}
