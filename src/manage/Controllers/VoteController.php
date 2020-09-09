<?php

declare(strict_types=1);

namespace App\Manage\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\Vote;
use App\Shared\Models\User;
use App\Shared\Models\Question;
use App\Shared\Models\QuestionType;
use App\Shared\Models\QuestionChoice;
use App\Shared\Models\Answer;
use App\Shared\Models\Respondent;

class VoteController extends BaseController
{
    public function listPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $surveyId = (int) $args['survey_id'];

        $data = [
            'currentUser'   => $this->user()->get(),
            'surveyId'      => $surveyId,
            'votes'         => Vote::where('survey_id', $surveyId)->get(),
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/votes/',
        ];

        return $this->view->render($response, "/vote/list.php", $data);
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
            'surveyId'      => $surveyId,
            'questionTypes' => QuestionType::all(),
            'questions'     => Question::where('survey_id', $surveyId)->get(),
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/votes/add/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-vote.js',
                ]
            ],
        ];

        return $this->view->render($response, "/vote/add.php", $data);
    }

    public function editPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $surveyId  = (int) $args['survey_id'];
        $voteId    = (int) $args['vote_id'];
        $vote      = Vote::find($voteId);
        $questions = Question::where('survey_id', $surveyId)->get();

        $data = [
            'surveyId'      => $surveyId,
            'questionTypes' => QuestionType::all(),
            'questions'     => $questions,
            'vote'          => $vote,
            'respondent'    => Respondent::find($vote->respondent_id),
            'surveyor'      => User::find($vote->user_id),
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/votes/edit/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-vote.js',
                ]
            ],
        ];

        $answers = [];

        foreach ($questions as $question) {
            if ($question->question_type_id == 1) {
                $answer = Answer::where('vote_id', $voteId)->where('question_id', $question->id)->first();
                $answer = isset($answer->question_choice_id) ? $answer->question_choice_id : 0;
            } elseif ($question->question_type_id == 2) {
                $results = Answer::where('vote_id', $voteId)
                    ->where('question_id', $question->id)
                    ->select('question_choice_id')
                    ->get();
                $results = !$results->isEmpty() ? $results : [];
                $answer  = [];

                foreach ($results as $result) {
                    array_push($answer, $result->question_choice_id);
                }
            } else {
                $answer = Answer::where('vote_id', $voteId)->where('question_id', $question->id)->first();
                $answer = isset($answer->content) ? $answer->content : '';
            }

            $answers[$question->id] = $answer;
        }

        if ($answers) {
            $data['answers'] = $answers;
        }

        if (isset($args['saving_status'])) {
            if ($args['saving_status'] === 'saved') {
                $data['successMessage'] = 'Data survey berhasil disimpan';
            } elseif ($args['saving_status'] === 'updated') {
                $data['successMessage'] = 'Data survey berhasil diubah';
            }
        }

        return $this->view->render($response, '/vote/edit.php', $data);
    }
    
    public function add(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $fields    = $request->getParsedBody();
        $surveyId  = (int) $args['survey_id'];
        $questions = Question::where('survey_id', $surveyId)->get();

        $addPageData = [
            'surveyId'      => $surveyId,
            'fields'        => $fields,
            'questionTypes' => QuestionType::all(),
            'questions'     => $questions,
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/votes/add/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-vote.js',
                ]
            ],
        ];

        $answers = [];

        foreach ($questions as $question) {
            if ($question->question_type_id == 1) {
                $answer = isset($fields['question_choice_' . $question->id]) ? $fields['question_choice_' . $question->id] : 0;
            } elseif ($question->question_type_id == 2) {
                $answer = isset($fields['question_choices_' . $question->id]) ? $fields['question_choices_' . $question->id] : [];

                foreach ($answer as &$choiceId) {
                    $choiceId = (int) $choiceId;
                }
            } else {
                $answer = isset($fields['question_answer_' . $question->id]) ? $fields['question_answer_' . $question->id] : '';
            }

            $answers[$question->id] = $answer;
        }

        if ($answers) {
            $addPageData['answers'] = $answers;
        }

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $error = $this->response()->generateError();

            $addPageData['errorMessage'] = $error['message'];

            return $this->view->render($response, "/vote/add.php", $addPageData);
        }
        
        $insertFields = ['survey_id', 'respondent_id', 'user_id'];

        $data = [];

        foreach ($insertFields as $field) {
            if (isset($fields[$field])) {
                $data[$field] = $fields[$field];
            }
        }

        $voteId = Vote::insertGetId($data);

        foreach ($questions as $question) {
            if ($question->question_type_id == 1) {
                $answerModel = new Answer();
                
                $answerModel->vote_id     = $voteId;
                $answerModel->question_id = $question->id;
                $answerModel->question_choice_id = $answers[$question->id];

                $answerModel->save();
            } elseif ($question->question_type_id == 2) {
                foreach ($answers[$question->id] as $answer) {
                    $answerModel = new Answer();
                
                    $answerModel->vote_id     = $voteId;
                    $answerModel->question_id = $question->id;
                    $answerModel->question_choice_id = $answer;

                    $answerModel->save();
                }
            } else {
                $answerModel = new Answer();
                
                $answerModel->vote_id     = $voteId;
                $answerModel->question_id = $question->id;
                $answerModel->content     = $answers[$question->id];

                $answerModel->save();
            }
        }

        return $response
        ->withHeader('Location', '/manage/survey/' . $surveyId . '/votes/edit/' . $voteId . '/saved/')
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

        $fields    = $request->getParsedBody();
        $surveyId  = (int) $args['survey_id'];
        $voteId    = (int) $args['vote_id'];
        $vote      = Vote::find($voteId);
        $questions = Question::where('survey_id', $surveyId)->get();

        $editPageData = [
            'surveyId'      => $surveyId,
            'fields'        => $fields,
            'questionTypes' => QuestionType::all(),
            'questions'     => $questions,
            'vote'          => $vote,
            'respondent'    => Respondent::find($vote->respondent_id),
            'surveyor'      => User::find($vote->user_id),
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/votes/edit/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-vote.js',
                ]
            ],
        ];

        $answers = [];

        foreach ($questions as $question) {
            if ($question->question_type_id == 1) {
                $answer = isset($fields['question_choice_' . $question->id]) ? $fields['question_choice_' . $question->id] : 0;
            } elseif ($question->question_type_id == 2) {
                $answer = isset($fields['question_choices_' . $question->id]) ? $fields['question_choices_' . $question->id] : [];

                foreach ($answer as &$choiceId) {
                    $choiceId = (int) $choiceId;
                }
            } else {
                $answer = isset($fields['question_answer_' . $question->id]) ? $fields['question_answer_' . $question->id] : '';
            }

            $answers[$question->id] = $answer;
        }

        if ($answers) {
            $editPageData['answers'] = $answers;
        }

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $error = $this->response()->generateError();

            $addPageData['errorMessage'] = $error['message'];

            return $this->view->render($response, "/vote/add.php", $addPageData);
        }

        $updateFields = ['survey_id', 'respondent_id', 'user_id'];
        
        foreach ($updateFields as $field) {
            if (isset($fields[$field])) {
                $vote->{$field} = $fields[$field];
            }
        }

        $vote->save();

        foreach ($questions as $question) {
            if ($question->question_type_id == 1) {
                $answerModel = Answer::where('vote_id', $voteId)->where('question_id', $question->id)->first();

                $answerModel->question_choice_id = $answers[$question->id];

                $answerModel->save();
            } elseif ($question->question_type_id == 2) {
                $savedAnswers      = Answer::where('vote_id', $voteId)->where('question_id', $question->id)->get();
                $existingChoiceIds = [];

                foreach ($savedAnswers as $savedAnswer) {
                    if (in_array($savedAnswer->question_choice_id, $answers[$question->id])) {
                        array_push($existingChoiceIds, $savedAnswer->question_choice_id);
                    } else {
                        $answerToDelete = Answer::find($savedAnswer->id);
                        $answerToDelete->delete();
                    }
                }

                $choicesToSave = array_diff($answers[$question->id], $existingChoiceIds);

                foreach ($choicesToSave as $choiceId) {
                    $answerModel = new Answer();

                    $answerModel->vote_id     = $voteId;
                    $answerModel->question_id = $question->id;
                    $answerModel->question_choice_id = $choiceId;

                    $answerModel->save();
                }
            } else {
                $answerModel = Answer::where('vote_id', $voteId)->where('question_id', $question->id)->first();

                $answerModel->content = $answers[$question->id];

                $answerModel->save();
            }
        }

        $editPageData['successMessage'] = 'Data survey berhasil diubah';

        return $this->view->render($response, "/vote/edit.php", $editPageData);
    }
    
    public function delete(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }
        
        $question = Vote::find($args['vote_id']);
        $question->delete();

        $answer = Answer::where('vote_id', $args['vote_id']);
        $answer->delete();

        return $response
        ->withHeader('Location', '/manage/survey/' . $args['survey_id'] . '/votes/')
        ->withStatus(302);
    }
}
