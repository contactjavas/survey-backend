<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use App\Api\Controllers\WilayahController;
use App\Api\Controllers\AutocompleteController;

return function (ContainerInterface $container, array $settings) {
    $container->set('WilayahController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new WilayahController($container, $db);
    });

    $container->set('AutocompleteController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new AutocompleteController($container, $db);
    });
};
