<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use App\Api\Controllers\Select2Controller;
use App\Api\Controllers\AutocompleteController;
use App\Api\Controllers\AuthController;

return function (ContainerInterface $container, array $settings) {
    $container->set('Select2Controller', function () use ($container, $settings) {
        $db = $container->get('db');
        return new Select2Controller($container, $db);
    });

    $container->set('AutocompleteController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new AutocompleteController($container, $db);
    });

    $container->set('AuthController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new AuthController($container, $db);
    });
};
