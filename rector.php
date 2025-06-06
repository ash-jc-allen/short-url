<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/routes',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    // uncomment to reach your current PHP version
    //->withPhpSets(php80: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true
    )
    ->withTypeCoverageLevel(0);
