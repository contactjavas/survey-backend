<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\Question;
use App\Shared\Models\QuestionType;
use App\Shared\Models\QuestionChoice;

class QuestionController extends BaseController
{
    public function list(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $errorResponse = $this->response()->generateJsonError($request);

            $response->getBody()->write($errorResponse);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $token = $this->token()->verifyToken();

        // Check token
        if (!$token) {
            $errorResponse = $this->response()->generateJsonError('general', 'Invalid token');

            $response->getBody()->write($errorResponse);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $surveyId  = (int) $args['survey_id'];
        $questions = Question::where('survey_id', $surveyId)
        ->select('id', 'title', 'question_type_id', 'allow_add as allowAdd')
        ->get();

        $questions->makeHidden(['survey_id']);

        $payload = json_encode([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data'    => $questions
        ]);

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function addPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $surveyId = (int) $args['survey_id'];

        $data = [
            'questionTypes' => QuestionType::all(),
            'surveyId'      => $surveyId,
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/questions/add/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-question.js',
                ]
            ],
        ];

        return $this->view->render($response, "/question/add.php", $data);
    }

    public function editPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $surveyId = (int) $args['survey_id'];
        $question = Question::find($args['question_id']);

        $data = [
            'questionTypes' => QuestionType::all(),
            'surveyId'      => $surveyId,
            'question'      => $question,
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/questions/add/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-question.js',
                ]
            ],
        ];

        $questionChoices = QuestionChoice::where('question_id', $question->id)->get();

        if (!$questionChoices->isEmpty()) {
            $data['questionChoices'] = $questionChoices;
        }

        if (isset($args['saving_status'])) {
            if ($args['saving_status'] === 'saved') {
                $data['successMessage'] = 'Data pertanyaan berhasil disimpan';
            } elseif ($args['saving_status'] === 'updated') {
                $data['successMessage'] = 'Data pertanyaan berhasil diubah';
            }
        }

        return $this->view->render($response, '/question/edit.php', $data);
    }
    
    public function add(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $fields   = $request->getParsedBody();
        $surveyId = (int) $args['survey_id'];

        $addPageData = [
            'fields'        => $fields,
            'questionTypes' => QuestionType::all(),
            'surveyId'      => $surveyId,
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/questions/add/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-question.js',
                ]
            ],
        ];

        $questionChoices = [];

        foreach ($_POST as $postKey => $postVal) {
            if (stripos($postKey, 'question_choice_') !== false) {
                array_push($questionChoices, $postKey);
            }
        }

        if ($questionChoices) {
            $addPageData['questionChoices'] = $questionChoices;
        }

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $error = $this->response()->generateError();

            $addPageData['errorMessage'] = $error['message'];

            return $this->view->render($response, "/candidate/add.php", $addPageData);
        }
        
        $insertFields = ['title', 'survey_id', 'question_type_id', 'allow_add'];

        $data = [];

        foreach ($insertFields as $field) {
            if (isset($fields[$field])) {
                $data[$field] = $fields[$field];
            }
        }

        $questionId = Question::insertGetId($data);

        if ($data['question_type_id'] == 1 || $data['question_type_id'] == 2) {
            foreach ($questionChoices as $questionChoice) {
                $choiceModel = new QuestionChoice();
    
                $choiceModel->title       = $fields[$questionChoice];
                $choiceModel->question_id = $questionId;

                $choiceModel->save();
            }
        }

        $addPageData['question'] = Question::find($questionId);

        return $response
        ->withHeader('Location', '/manage/survey/' . $surveyId . '/questions/edit/' . $questionId . '/saved/')
        ->withStatus(302);
    }
    
    public function edit(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $fields     = $request->getParsedBody();
        $surveyId   = (int) $args['survey_id'];
        $questionId = (int) $args['question_id'];
        $question   = Question::find($questionId);

        $editPageData = [
            'fields'        => $fields,
            'questionTypes' => QuestionType::all(),
            'surveyId'      => $surveyId,
            'question'      => $question,
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/questions/add/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-question.js',
                ]
            ],
        ];

        $questionChoices = [];

        foreach ($_POST as $postKey => $postVal) {
            if (stripos($postKey, 'question_choice_') !== false) {
                array_push($questionChoices, $postKey);
            }
        }

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $error = $this->response()->generateError();

            $addPageData['errorMessage'] = $error['message'];

            return $this->view->render($response, "/candidate/add.php", $addPageData);
        }

        $updateFields = ['title', 'survey_id', 'question_type_id', 'allow_add'];
        
        foreach ($updateFields as $field) {
            if (isset($fields[$field])) {
                $question->{$field} = $fields[$field];
            }
        }

        $question->save();

        if ($fields['question_type_id'] == 1 || $fields['question_type_id'] == 2) {
            $savedQuestionChoices = QuestionChoice::where('question_id', $question->id)->get();
            $existingChoicesKeys  = [];

            foreach ($savedQuestionChoices as $savedQuestionChoice) {
                if (isset($questionChoices['question_choice_' . $savedQuestionChoice->id])) {
                    array_push($existingChoicesKeys, 'question_choice_' . $savedQuestionChoice->id);
                } else {
                    $choiceToDelete = QuestionChoice::find($savedQuestionChoice->id);
                    $choiceToDelete->delete();
                }
            }

            $choicesToSave = array_diff($questionChoices, $existingChoicesKeys);

            foreach ($choicesToSave as $choiceToSave) {
                $choiceModel = new QuestionChoice();
    
                $choiceModel->title       = $fields[$choiceToSave];
                $choiceModel->question_id = $questionId;

                $choiceModel->save();
            }
        }

        $questionChoices = QuestionChoice::where('question_id', $question->id)->get();

        if (!$questionChoices->isEmpty()) {
            $editPageData['questionChoices'] = $questionChoices;
        }

        $editPageData['successMessage'] = 'Data pertanyaan berhasil diubah';

        return $this->view->render($response, "/question/edit.php", $editPageData);
    }
    
    public function delete(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }
        
        $question = Question::find($args['question_id']);

        $question->delete();

        return $response
        ->withHeader('Location', '/manage/survey/' . $args['survey_id'] . '/questions/')
        ->withStatus(302);
    }
}
