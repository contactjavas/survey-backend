<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder, string $templatePath) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production

            // PHP-View settings
            'view'            => [
                'template_path' => $templatePath,
            ],

            // Monolog settings
            'logger'              => [
                'name'  => 'slim-app',
                'path'  => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],

            // JWT settings
            'jwt'                 => [
                'secret'    => $_ENV['JWT_SECRET'],
                'algorithm' => "HS256"
            ],

            // Eloquent settings
            'db'                  => [
                'driver'    => $_ENV['DB_DRIVER'],
                'host'      => $_ENV['DB_HOST'],
                'database'  => $_ENV['DB_NAME'],
                'username'  => $_ENV['DB_USER'],
                'password'  => $_ENV['DB_PASSWORD'],
                'charset'   => $_ENV['DB_CHARSET'],
                'collation' => $_ENV['DB_COLLATION'],
                'prefix'    => $_ENV['DB_TABLE_PREFIX'],
            ]
        ],
    ]);
};
