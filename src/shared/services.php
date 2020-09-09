<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;

return function (ContainerInterface $container, array $settings) {
    // Set view in container (already set in dependencies.php)
    /*$container->set('view', function () use ($container, $settings) {
        return new PhpRenderer(
            $settings['view']['template_path'],
            [
                // Add template's global variables.
                'baseUrl' => APP_BASE_URL
            ]
        );
    });*/
    $container->set('currentUser', function () use ($container, $settings) {
        return null;
    });
};
