<?php

declare(strict_types=1);

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;
use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Mezzio\Container;
use Mezzio\Middleware\ErrorResponseGenerator;
use Psr\Container\ContainerInterface;
use ZenBox\Ddd\Infrastructure\Persistence\Doctrine\UuidType;
use ZenBox\Doctrine\Iterator\DirectoryPathIterator;

$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

$aggregator = new ConfigAggregator([
    \Mezzio\Twig\ConfigProvider::class,
    \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    \Laminas\HttpHandlerRunner\ConfigProvider::class,
    // Include cache configuration
    new ArrayProvider($cacheConfig),
    \Mezzio\Helper\ConfigProvider::class,
    \Mezzio\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \Laminas\Diactoros\ConfigProvider::class,

    // Default App module config
    App\ConfigProvider::class,

    new ArrayProvider([
        'dependencies' => [
            'factories' => [
                PDO::class => function (ContainerInterface $container) {
                    return new PDO(
                        'mysql:dbname=' . getenv('MYSQL_DATABASE') . ';host=' . getenv('MYSQL_HOST'),
                        getenv('MYSQL_USER'),
                        getenv('MYSQL_PASSWORD')
                    );
                },
                EntityManagerInterface::class => function () {
                    if (!Type::hasType(UuidType::NAME)) {
                        Type::addType(UuidType::NAME, UuidType::class);
                    }
                    $config = Setup::createYAMLMetadataConfiguration(
                        (new DirectoryPathIterator(realpath(__DIR__) . '/doctrine'))->toArray(),
                        true
                    );
                    $config->setNamingStrategy(new UnderscoreNamingStrategy());

                    return EntityManager::create(
                        [
                            'dbname' => getenv('MYSQL_DATABASE'),
                            'user' => getenv('MYSQL_USER'),
                            'password' => getenv('MYSQL_PASSWORD'),
                            'host' => getenv('MYSQL_HOST'),
                            'driver' => 'pdo_mysql',
                        ],
                        $config
                    );
                },
            ]
        ],
        'mezzio' => [
            // Provide templates for the error handling middleware to use when
            // generating responses.
            'error_handler' => [
                'template_404'   => 'error::404',
                'template_error' => 'error::error',
            ],
        ],
    ]),
    new ArrayProvider(getenv('APP_DEV_MODE') ? [
        'dependencies' => [
            'factories' => [
                ErrorResponseGenerator::class => Container\WhoopsErrorResponseGeneratorFactory::class,
                'Mezzio\Whoops' => Container\WhoopsFactory::class,
                'Mezzio\WhoopsPageHandler' => Container\WhoopsPageHandlerFactory::class,
            ],
        ],
        'whoops' => [
            'json_exceptions' => [
                'display' => true,
                'show_trace' => true,
                'ajax_only' => true,
            ],
        ],
        'debug' => true,
        ConfigAggregator::ENABLE_CACHE => false,
    ] : [
        'debug' => false,
        ConfigAggregator::ENABLE_CACHE => true,
    ]),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
