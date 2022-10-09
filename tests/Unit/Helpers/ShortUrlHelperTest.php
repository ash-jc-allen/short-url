<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Helpers;

use AshAllenDesign\ShortURL\Tests\Unit\TestCase;

class ShortUrlHelperTest extends TestCase
{
    /** @test */
    public function short_url_helper_function_exists()
    {
        $this->assertTrue(function_exists('short_url'));
    }

    /** @test */
    public function short_url_helper_function_returns_builder_instance()
    {
        $this->assertInstanceOf('AshAllenDesign\ShortURL\Classes\Builder', short_url('https://google.com'));
    }
}
