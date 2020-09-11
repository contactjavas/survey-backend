<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\App;

return function (App $app, ContainerInterface $container, array $settings) {
    // Slim-jwt-auth
    $app->add(
        new Tuupola\Middleware\JwtAuthentication(
            [
                'secret' => $settings['jwt']['secret'],
                'secure' => false,
                'path'   => ['/api'],
                'ignore' => [
                    // Open route for auths
                    '/api/login',

                    // Open route for select2 & autocomplete
                    '/api/select2/*',
                    '/api/autocomplete/*',

                    // open route for other routes
                    '/api/blank',
                    '/api/mail/send',
                    '/api/token/instant'
                ]
            ]
        )
    );

    /**
     * Add "Authorization" header support.
     *
     * This was necessary on divren.co.id server.
     * We can remove this if not necessary.
     */
    $app->add(
        function ($request, $handler) {
            $request  = $request->withHeader('Authorization', $_SERVER['HTTP_AUTHORIZATION']);
            $response = $handler->handle($request);
            return $response;
        }
    );

    // The following code should enable lazy CORS.
    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });
};
