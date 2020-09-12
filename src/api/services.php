<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use App\Api\Controllers\Select2Controller;
use App\Api\Controllers\AutocompleteController;
use App\Api\Controllers\AuthController;
use App\Api\Controllers\SurveyController;
use App\Api\Controllers\RespondentController;

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

    $container->set('SurveyController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new SurveyController($container, $db);
    });

    $container->set('RespondentController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new RespondentController($container, $db);
    });
};
