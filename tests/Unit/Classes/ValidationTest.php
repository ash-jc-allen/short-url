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
    public function exception_is_thrown_if_the_key_length_is_zero_or_below()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The config URL length must be above 0.');

        Config::set('short-url.key_length', 0);

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
}
