<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\App;

return function (App $app) {
    $app->group('/api/select2', function (Group $group) {
        $group->get('[/]', 'Select2Controller:getAllProvinces');
        $group->get('/province/{province_id}/regencies[/]', 'Select2Controller:getRegenciesByProvinceId');
        $group->get('/regency/{regency_id}/districts[/]', 'Select2Controller:getDistrictsByRegencyId');
        $group->get('/district/{district_id}/villages[/]', 'Select2Controller:getVillagesByDistrictId');
    });

    $app->group('/api/autocomplete/respondent', function (Group $group) {
        $group->get('[/]', 'AutocompleteController:getAllRespondents');
        $group->get('/search/{query}[/]', 'AutocompleteController:getRespondentsBySearch');
    });

    $app->group('/api/autocomplete/surveyor', function (Group $group) {
        $group->get('[/]', 'AutocompleteController:getAllSurveyors');
        $group->get('/search/{query}[/]', 'AutocompleteController:getSurveyorsBySearch');
    });

    $app->group('/api', function (Group $group) {
        $group->post('/login[/]', 'AuthController:login');
    });
};
