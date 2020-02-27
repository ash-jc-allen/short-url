<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Classes;

use AshAllenDesign\ShortURL\Classes\Validation;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Support\Facades\Config;

class ValidationTest extends TestCase
{
    /** @test */
    public function exception_is_thrown_if_the_key_length_is_not_an_integer()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The config URL length is not a valid integer.');

        Config::set('short-url.key_length', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }

    /** @test */
    public function exception_is_thrown_if_the_key_length_is_below_3()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The config URL length must be 3 or above.');

        Config::set('short-url.key_length', 2);

        $validation = new Validation();
        $validation->validateConfig();
    }

    /** @test */
    public function exception_is_thrown_if_the_default_enabled_variable_is_not_a_boolean()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The default_enabled config variable must be a boolean.');

        Config::set('short-url.tracking.default_enabled', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }

    /** @test */
    public function exception_is_thrown_if_any_of_the_tracking_options_are_not_null()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The ip_address config variable must be a boolean.');

        Config::set('short-url.tracking.fields.ip_address', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }

    /** @test */
    public function exception_is_thrown_if_the_disable_default_route_option_is_not_a_boolean()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The disable_default_route config variable must be a boolean.');

        Config::set('short-url.disable_default_route', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }

    /** @test */
    public function exception_is_thrown_if_the_key_salt_is_not_a_string()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The config key salt must be a string.');

        Config::set('short-url.key_salt', true);

        $validation = new Validation();
        $validation->validateConfig();
    }

    /** @test */
    public function exception_is_thrown_if_the_key_salt_is_less_than_one_character_long()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The config key salt must be at least 1 character long.');

        Config::set('short-url.key_salt', '');

        $validation = new Validation();
        $validation->validateConfig();
    }

    /** @test */
    public function exception_is_thrown_if_the_enforce_https_variable_is_not_a_boolean()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The enforce_https config variable must be a boolean.');

        Config::set('short-url.enforce_https', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }
}
