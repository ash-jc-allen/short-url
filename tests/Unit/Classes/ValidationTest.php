<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Classes;

use AshAllenDesign\ShortURL\Classes\Validation;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\Test;

final class ValidationTest extends TestCase
{
    #[Test]
    public function exception_is_thrown_if_the_key_length_is_not_an_integer(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.key_length field must be an integer.');

        Config::set('short-url.key_length', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }

    #[Test]
    public function exception_is_thrown_if_the_key_length_is_below_3(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.key_length field must be at least 3.');

        Config::set('short-url.key_length', 2);

        $validation = new Validation();
        $validation->validateConfig();
    }

    #[Test]
    public function exception_is_thrown_if_the_default_enabled_variable_is_not_a_boolean(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.tracking.default_enabled field must be true or false.');

        Config::set('short-url.tracking.default_enabled', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }

    #[Test]
    public function exception_is_thrown_if_any_of_the_tracking_options_are_not_null(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.tracking.fields.ip_address field must be true or false.');

        Config::set('short-url.tracking.fields.ip_address', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }

    #[Test]
    public function exception_is_thrown_if_the_disable_default_route_option_is_not_a_boolean(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.disable_default_route field must be true or false.');

        Config::set('short-url.disable_default_route', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }

    #[Test]
    public function exception_is_thrown_if_the_key_salt_is_not_a_string(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.key_salt field must be a string.');

        Config::set('short-url.key_salt', true);

        $validation = new Validation();
        $validation->validateConfig();
    }

    #[Test]
    public function exception_is_thrown_if_the_key_salt_is_less_than_one_character_long(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.key_salt field is required.');

        Config::set('short-url.key_salt', '');

        $validation = new Validation();
        $validation->validateConfig();
    }

    #[Test]
    public function exception_is_thrown_if_the_enforce_https_variable_is_not_a_boolean(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.enforce_https field must be true or false.');

        Config::set('short-url.enforce_https', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }

    #[Test]
    public function exception_is_thrown_if_the_forward_query_params_variable_is_not_a_boolean(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.forward_query_params field must be true or false.');

        Config::set('short-url.forward_query_params', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }

    #[Test]
    public function exception_is_thrown_if_the_default_url_is_not_a_string(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.default_url field must be a string.');

        Config::set('short-url.default_url', true);

        $validation = new Validation();
        $validation->validateConfig();
    }

    #[Test]
    public function exception_is_thrown_if_the_allowed_url_schemes_is_not_an_array(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The short-url.allowed_url_schemes field must be an array.');

        Config::set('short-url.allowed_url_schemes', 'INVALID');

        $validation = new Validation();
        $validation->validateConfig();
    }
}
