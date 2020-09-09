<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use App\Front\Controllers\AuthController;

return function (ContainerInterface $container, array $settings) {
    $container->set('AuthController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new AuthController($container, $db);
    });
};
