<?php

namespace AshAllenDesign\ShortURL\Classes;

use Hashids\HashidsInterface;

abstract class AbstractKeyGenerator
{
    /**
     * The library class that is used for generating
     * the unique hash.
     *
     * @var HashidsInterface
     */
    private $hashids;

    /**
     * KeyGenerator constructor.
     */
    abstract public function __construct(HashidsInterface $hashids = null);

    /**
     * Generate a unique and random URL key
     *
     * @return string
     */
    abstract public function generateRandom(): string;

    /**
     * Generate a key for the short URL using a seed value.
     *
     * @param  int|null  $seed
     * @return string
     */
    abstract public function generateKeyUsing(int $seed = null): string;

}
