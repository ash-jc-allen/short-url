<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Interfaces;

interface UrlKeyGenerator
{
    public function generateRandom(): string;

    public function generateKeyUsing(int $seed = null): string;

    public function generateIncrementalKey(int $id): string;
}
