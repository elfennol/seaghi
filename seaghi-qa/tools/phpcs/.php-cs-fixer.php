<?php

$rootDir = dirname(__DIR__, 3);

$finder = (new PhpCsFixer\Finder())
    ->in([
        $rootDir . '/seaghi-account',
        $rootDir . '/seaghi-battle',
        $rootDir . '/seaghi-shop',
    ])
    ->exclude('var')
    ->notPath('config/reference.php');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
