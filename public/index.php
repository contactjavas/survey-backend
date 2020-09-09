<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Carbon\Carbon;

require __DIR__ . '/../vendor/autoload.php';
Carbon::setLocale('id');

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Should be set to true in production
if (false) {
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up functions
require __DIR__ . '/../src/shared/functions.php';

// Set up constants
require __DIR__ . '/../src/shared/constants.php';

// Getting platform path
$path       = getUrlPath();
$path_arr   = explode('/', $path);
$first_path = $path_arr[1];
$src_dir    = __DIR__ . '/../src';
$work_path  = $first_path === 'manage' || $first_path === 'api' ? $first_path : 'front';

// Set up settings
$settings     = require $src_dir  . '/shared/settings.php';
$templatePath = __DIR__ . '/../src/' . $work_path . '/templates/';
$settings($containerBuilder, $templatePath);

// Set up dependencies
$dependencies = require $src_dir  . '/shared/dependencies.php';
$dependencies($containerBuilder);

$dependencies = require $src_dir  . '/' . $work_path . '/dependencies.php';
$dependencies($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Get the settings.
$settings = $container->get('settings');

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

// Register middleware
$middleware = require $src_dir  . '/shared/middlewares.php';
$middleware($app, $container, $settings);

// App services
$services = require $src_dir . '/shared/services.php';
$services($container, $settings);

// Controller services
$services = require $src_dir . '/' . $work_path . '/services.php';
$services($container, $settings);

// Request validators
require $src_dir . '/' . $work_path . '/validators.php';

// CORS Pre-Flight OPTIONS Request Handler
$app->options('/{routes:.*}', function (Request $request, Response $response) {
    return $response;
});

// Register routes
$routes = require $src_dir . '/' . $work_path . '/routes.php';
$routes($app);

/**
 * Catch-all route to serve a 404 Not Found page if none of the routes match
 * NOTE: make sure this route is defined last
 */
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($settings['displayErrorDetails'], true, true);

// Run app
$app->run();
