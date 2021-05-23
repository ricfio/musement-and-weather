<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('config')
    ->exclude('var')
    ->notPath('public/index.php')
    ->notPath('src/Kernel.php');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder);
