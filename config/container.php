<?php

declare(strict_types=1);

use Elie\PHPDI\Config\Config;
use Elie\PHPDI\Config\ContainerFactory;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

// The check is to ensure we don't use .env in production
if (!isset($_SERVER['APP_ENV']) && file_exists(__DIR__ . '/../.env')) {
    (new Dotenv())->load(__DIR__ . '/../.env');
}

// Protect variables from global scope
return (static function (): ContainerInterface {
    $config  = require __DIR__ . '/config.php';
    $factory = new ContainerFactory();

    return $factory(new Config($config));
})();
