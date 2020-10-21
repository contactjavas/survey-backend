<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\App;

return function (App $app) {
    $app->group('/manage', function (Group $group) {
        $group->get('[/]', 'SurveyController:listPage');
        $group->get('/add[/]', 'SurveyController:addPage');
        $group->post('/add[/]', 'SurveyController:add');
        $group->get('/edit/{survey_id}[/]', 'SurveyController:editPage');
        $group->get('/edit/{survey_id}/{saving_status}[/]', 'SurveyController:editPage');
        $group->post('/edit[/]', 'SurveyController:edit');
        $group->get('/delete/{survey_id}[/]', 'SurveyController:delete');
        $group->get('/profile/edit[/]', 'UserController:editPage');
    });

    $app->group('/manage/users', function (Group $group) {
        $group->get('[/]', 'UserController:listPage');
        $group->get('/add[/]', 'UserController:addPage');
        $group->post('/add[/]', 'UserController:add');
        $group->get('/edit/{user_id}[/]', 'UserController:editPage');
        $group->get('/edit/{user_id}/{saving_status}[/]', 'UserController:editPage');
        $group->post('/edit/{user_id}[/]', 'UserController:edit');
        $group->get('/delete/{user_id}[/]', 'UserController:delete');
    });

    $app->group('/manage/respondents', function (Group $group) {
        $group->get('[/]', 'RespondentController:listPage');
        $group->get('/add[/]', 'RespondentController:addPage');
        $group->post('/add[/]', 'RespondentController:add');
        $group->get('/edit/{respondent_id}[/]', 'RespondentController:editPage');
        $group->get('/edit/{respondent_id}/{saving_status}[/]', 'RespondentController:editPage');
        $group->post('/edit/{respondent_id}[/]', 'RespondentController:edit');
        $group->get('/delete/{respondent_id}[/]', 'RespondentController:delete');
    });

    $app->group('/manage/survey/{survey_id}/candidates', function (Group $group) {
        $group->get('[/]', 'CandidateController:listPage');
        $group->get('/add[/]', 'CandidateController:addPage');
        $group->post('/add[/]', 'CandidateController:add');
        $group->get('/edit/{candidate_id}[/]', 'CandidateController:editPage');
        $group->get('/edit/{candidate_id}/{saving_status}[/]', 'CandidateController:editPage');
        $group->post('/edit/{candidate_id}[/]', 'CandidateController:edit');
        $group->get('/delete/{candidate_id}[/]', 'CandidateController:delete');
    });

    $app->group('/manage/survey/{survey_id}/questions', function (Group $group) {
        $group->get('[/]', 'QuestionController:listPage');
        $group->get('/add[/]', 'QuestionController:addPage');
        $group->post('/add[/]', 'QuestionController:add');
        $group->get('/edit/{question_id}[/]', 'QuestionController:editPage');
        $group->get('/edit/{question_id}/{saving_status}[/]', 'QuestionController:editPage');
        $group->post('/edit/{question_id}[/]', 'QuestionController:edit');
        $group->get('/delete/{question_id}[/]', 'QuestionController:delete');
    });

    $app->group('/manage/survey/{survey_id}/votes', function (Group $group) {
        $group->get('[/]', 'VoteController:listPage');
        $group->get('/add[/]', 'VoteController:addPage');
        $group->post('/add[/]', 'VoteController:add');
        $group->get('/edit/{vote_id}[/]', 'VoteController:editPage');
        $group->get('/edit/{vote_id}/{saving_status}[/]', 'VoteController:editPage');
        $group->post('/edit/{vote_id}[/]', 'VoteController:edit');
        $group->get('/delete/{vote_id}[/]', 'VoteController:delete');
    });

    $app->group('/manage/survey/{survey_id}/result', function (Group $group) {
        $group->get('[/]', 'ResultController:result');
    });

    $app->group('/manage/debug', function (Group $group) {
        // $group->get('/insert-respondents-added-by[/]', 'DebugController:insertRespondentsAddedBy');
    });
};
