#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use App\Kernel;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;

(new Dotenv())->bootEnv('.env');

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$container = $kernel->getContainer();

$application = new Application();
$application->add($container->get('console.command.print_city_forecast'));
$application->run();
