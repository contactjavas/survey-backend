<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use App\Api\Controllers\Select2Controller;
use App\Api\Controllers\AwesompleteController;
use App\Api\Controllers\AuthController;
use App\Api\Controllers\SurveyController;
use App\Api\Controllers\VoteController;
use App\Api\Controllers\QuestionController;
use App\Api\Controllers\QuestionChoiceController;
use App\Api\Controllers\RespondentController;
use App\Api\Controllers\ResultController;

return function (ContainerInterface $container, array $settings) {
    $container->set('Select2Controller', function () use ($container, $settings) {
        $db = $container->get('db');
        return new Select2Controller($container, $db);
    });

    $container->set('AwesompleteController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new AwesompleteController($container, $db);
    });

    $container->set('AuthController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new AuthController($container, $db);
    });

    $container->set('SurveyController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new SurveyController($container, $db);
    });

    $container->set('VoteController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new VoteController($container, $db);
    });

    $container->set('QuestionController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new QuestionController($container, $db);
    });

    $container->set('QuestionChoiceController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new QuestionChoiceController($container, $db);
    });

    $container->set('RespondentController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new RespondentController($container, $db);
    });

    $container->set('ResultController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new ResultController($container, $db);
    });
};
