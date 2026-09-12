<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

$rootDir = dirname(__DIR__, 3);

$projects = [
    'account' => $rootDir . '/seaghi-account',
    'battle' => $rootDir . '/seaghi-battle',
    'shop' => $rootDir . '/seaghi-shop',
];

foreach ($projects as $projectDir) {
    if (file_exists($projectDir . '/tests/bootstrap.php')) {
        require_once $projectDir . '/tests/bootstrap.php';
    } elseif (file_exists($projectDir . '/vendor/autoload.php')) {
        require_once $projectDir . '/vendor/autoload.php';
    }
}
