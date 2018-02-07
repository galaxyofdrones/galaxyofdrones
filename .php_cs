<?php

$cacheDir = getenv('TRAVIS')
    ? getenv('HOME').'/.php-cs-fixer'
    : __DIR__;

$finder = PhpCsFixer\Finder::create()->in([
    'app',
    'database',
    'tests',
]);

return PhpCsFixer\Config::create()
    ->setCacheFile("{$cacheDir}/.php_cs.cache")
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'no_short_echo_tag' => true,
        'no_unused_imports' => true,
        'not_operator_with_successor_space' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'yoda_style' => false,
    ])
    ->setFinder($finder);
