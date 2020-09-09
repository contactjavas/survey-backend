<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\App;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
    });

    // Auth routes.
    $app->get('/login[/]', 'AuthController:loginPage');
    $app->post('/login[/]', 'AuthController:login');
    $app->get('/logout[/]', 'AuthController:logout');
};
