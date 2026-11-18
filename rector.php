<?php

use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector;
use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withPhpVersion(PhpVersion::PHP_82)
    ->withRules([
        LogicalToBooleanRector::class,
        SimplifyUselessVariableRector::class,
        ClosureToArrowFunctionRector::class,
        RemoveUnusedVariableInCatchRector::class,
    ]);
