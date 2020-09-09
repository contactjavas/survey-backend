<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\App;

return function (App $app) {
    $app->group('/api/wilayah', function (Group $group) {
        $group->get('[/]', 'WilayahController:getAllProvinces');
        $group->get('/province/{province_id}/regencies[/]', 'WilayahController:getRegenciesByProvinceId');
        $group->get('/regency/{regency_id}/districts[/]', 'WilayahController:getDistrictsByRegencyId');
        $group->get('/district/{district_id}/villages[/]', 'WilayahController:getVillagesByDistrictId');
    });

    $app->group('/api/autocomplete/respondent', function (Group $group) {
        $group->get('[/]', 'AutocompleteController:getAllRespondents');
        $group->get('/search/{query}[/]', 'AutocompleteController:getRespondentsBySearch');
    });

    $app->group('/api/autocomplete/surveyor', function (Group $group) {
        $group->get('[/]', 'AutocompleteController:getAllSurveyors');
        $group->get('/search/{query}[/]', 'AutocompleteController:getSurveyorsBySearch');
    });
};
