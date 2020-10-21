<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use App\Manage\Controllers\SurveyController;
use App\Manage\Controllers\UserController;
use App\Manage\Controllers\RespondentController;
use App\Manage\Controllers\CandidateController;
use App\Manage\Controllers\QuestionController;
use App\Manage\Controllers\VoteController;
use App\Manage\Controllers\ResultController;
use App\Manage\Controllers\DebugController;

return function (ContainerInterface $container, array $settings) {
    $container->set('SurveyController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new SurveyController($container, $db);
    });

    $container->set('UserController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new UserController($container, $db);
    });

    $container->set('RespondentController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new RespondentController($container, $db);
    });

    $container->set('CandidateController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new CandidateController($container, $db);
    });

    $container->set('QuestionController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new QuestionController($container, $db);
    });

    $container->set('VoteController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new VoteController($container, $db);
    });

    $container->set('ResultController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new ResultController($container, $db);
    });

    $container->set('DebugController', function () use ($container, $settings) {
        $db = $container->get('db');
        return new DebugController($container, $db);
    });
};
