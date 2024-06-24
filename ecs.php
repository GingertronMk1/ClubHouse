<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __FILE__,
        __DIR__.'/config',
        __DIR__.'/migrations',
        __DIR__.'/public',
        __DIR__.'/src',
        __DIR__.'/tests',
        __DIR__.'/_docker/plugins',
    ])
    // add sets - group of rules
    ->withPhpCsFixerSets(
        phpCsFixer: true
    )
;
