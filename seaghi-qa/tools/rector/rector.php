<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

$rootDir = dirname(__DIR__, 3);

return RectorConfig::configure()
    ->withPaths([
        $rootDir . '/seaghi-account',
        $rootDir . '/seaghi-battle',
        $rootDir . '/seaghi-shop',
    ])
    ->withSkip([
        '*/var/*',
        '*/vendor/*',
        '*/config/reference.php',
    ])
    ->withPhpSets(php84: true)
    ->withTypeCoverageLevel(0);
