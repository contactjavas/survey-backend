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

    $app->group('/api/awesomplete/respondent', function (Group $group) {
        $group->get('[/]', 'AwesompleteController:getAllRespondents');
        $group->get('/search/{query}[/]', 'AwesompleteController:getRespondentsBySearch');
    });

    $app->group('/api/awesomplete/surveyor', function (Group $group) {
        $group->get('[/]', 'AwesompleteController:getAllSurveyors');
        $group->get('/search/{query}[/]', 'AwesompleteController:getSurveyorsBySearch');
    });

    $app->group('/api/surveys', function (Group $group) {
        $group->get('[/]', 'SurveyController:list');
        $group->get('/add[/]', 'SurveyController:addPage');
        $group->post('/add[/]', 'SurveyController:add');
        $group->get('/edit/{survey_id}[/]', 'SurveyController:editPage');
        $group->get('/edit/{survey_id}/{saving_status}[/]', 'SurveyController:editPage');
        $group->post('/edit[/]', 'SurveyController:edit');
        $group->get('/delete/{survey_id}[/]', 'SurveyController:delete');
        $group->get('/profile/edit[/]', 'UserController:editPage');
    });

    $app->group('/api/survey/{survey_id}', function (Group $group) {
        $group->get('/questions-choices[/]', 'QuestionController:withChoices');

        $group->get('/questions[/]', 'QuestionController:list');
        $group->get('/questions/add[/]', 'QuestionController:addPage');
        $group->post('/questions/add[/]', 'QuestionController:add');
        $group->get('/questions/edit/{question_id}[/]', 'QuestionController:editPage');
        $group->get('/questions/edit/{question_id}/{saving_status}[/]', 'QuestionController:editPage');
        $group->post('/questions/edit/{question_id}[/]', 'QuestionController:edit');
        $group->get('/questions/delete/{question_id}[/]', 'QuestionController:delete');
    });

    $app->group('/api/survey/{survey_id}/votes', function (Group $group) {
        $group->get('[/]', 'VoteController:listPage');
        $group->get('/add[/]', 'VoteController:addPage');
        $group->post('/add[/]', 'VoteController:add');
        $group->get('/edit/{vote_id}[/]', 'VoteController:editPage');
        $group->get('/edit/{vote_id}/{saving_status}[/]', 'VoteController:editPage');
        $group->post('/edit/{vote_id}[/]', 'VoteController:edit');
        $group->get('/delete/{vote_id}[/]', 'VoteController:delete');
    });

    $app->group('/api/survey/result/{survey_id}', function (Group $group) {
        $group->post('/filter[/]', 'ResultController:filter');
    });

    $app->group('/api/question/{question_id}/choices', function (Group $group) {
        $group->get('[/]', 'QuestionChoiceController:list');
    });

    $app->group('/api/respondents', function (Group $group) {
        $group->get('[/]', 'RespondentController:list');
        $group->get('/add[/]', 'RespondentController:addPage');
        $group->post('/add[/]', 'RespondentController:add');
        $group->post('/add-upload[/]', 'RespondentController:addUpload');
        $group->get('/edit/{respondent_id}[/]', 'RespondentController:editPage');
        $group->get('/edit/{respondent_id}/{saving_status}[/]', 'RespondentController:editPage');
        $group->post('/edit/{respondent_id}[/]', 'RespondentController:edit');
        $group->delete('/{respondent_id}[/]', 'RespondentController:delete');
        $group->get('/search/{data_format}/{query}[/]', 'RespondentController:search');
    });

    $app->group('/api', function (Group $group) {
        $group->post('/login[/]', 'AuthController:login');
    });
};
