<?php

namespace AshAllenDesign\ShortURL\Tests\Unit\Classes;

use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Tests\Unit\TestCase;
use AshAllenDesign\ShortURL\Classes\KeyGenerator;
use PHPUnit\Framework\Attributes\Test;

final class KeyGeneratorTest extends TestCase
{
    #[Test]
    public function generates_expected_random_keys_based_on_last_inserted_id(): void
    {
        $generator = app(KeyGenerator::class);

        $first = $generator->generateRandom();
        $second = $generator->generateRandom();

        ShortURL::factory()->create();

        $third = $generator->generateRandom();

        $this->assertEquals('NKYWm', $first);
        $this->assertEquals('NKYWm', $second);
        $this->assertEquals('N3LMR', $third);
    }

    #[Test]
    public function generates_expected_key_using_seed(): void
    {
        $generator = app(KeyGenerator::class);

        $first = $generator->generateKeyUsing(123);
        $second = $generator->generateKeyUsing(123);
        $third = $generator->generateKeyUsing(1234);

        $this->assertEquals('4ZRw4', $first);
        $this->assertEquals('4ZRw4', $second);
        $this->assertEquals('4Ow94', $third);
    }
}
