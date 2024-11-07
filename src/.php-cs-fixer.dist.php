<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database/factories',
        __DIR__ . '/database/seeders',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ]);

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'binary_operator_spaces' => [
            'default' => 'align_single_space_minimal',
        ],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'concat_space' => ['spacing' => 'one'],
        'date_time_immutable' => true,
        'declare_parentheses' => true,
        'declare_strict_types' => true,
        'final_public_method_for_abstract_class' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'linebreak_after_opening_tag' => true,
        'mb_str_functions' => true,
        'method_chaining_indentation' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                // 'parenthesis_brace_block',
                // 'square_brace_block',
                'throw',
                'use',
            ]
        ],
        'no_superfluous_phpdoc_tags' => false,
        'no_unused_imports' => true,
        'no_whitespace_in_blank_line' => true,
        'not_operator_with_successor_space' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => [
                'class',
                'function',
                'const',
            ],
        ],
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'this',
        ],
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'none',
        ],
        'simplified_if_return' => true,
        'simplified_null_return' => true,
        'single_quote' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments']],
    ])
    ->setFinder($finder);
