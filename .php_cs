<?php
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        // addtional rules
        'array_syntax' => ['syntax' => 'short'],
        'no_multiline_whitespace_before_semicolons' => true,
        'no_unused_imports' => true,
        'not_operator_with_successor_space' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->in(__DIR__)
            ->name('*.php')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    )
;