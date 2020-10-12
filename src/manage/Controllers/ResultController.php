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
use App\Shared\Models\Gender;
use App\Shared\Models\Religion;
use App\Shared\Models\Education;

class ResultController extends BaseController
{
    public function result(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $surveyId  = (int) $args['survey_id'];
        $questions = Question::where('survey_id', $surveyId)
        ->select('id', 'title', 'question_type_id')
        ->get();

        $questions->makeHidden(['survey_id']);

        $questions->each(function ($question) {
            $choices = QuestionChoice::where('question_id', $question->id)->get();
            $choices->makeHidden(['question_id']);

            $choices->each(function ($choice) {
                $answers = Answer::where('question_choice_id', $choice->id)->get();
                $answers->makeHidden(['question_id', 'question_choice_id']);

                $choice->totalVotes = $answers->count();
            });

            $question->choices = $choices;

            $votes = Vote::where('survey_id', $surveyId)->get();
            $question->totalVotes = $votes->count();

            if ($question->question_type_id == 1) {
                //
            } elseif ($question->question_type_id == 2) {
                //
            } else {
                //
            }
        });

        $data = [
            'currentUser'   => $this->user()->get(),
            'genders'       => Gender::all(),
            'educations'    => Education::all(),
            'religions'     => Religion::all(),
            'surveyId'      => $surveyId,
            'questionTypes' => QuestionType::all(),
            'questions'     => $questions,
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/result/',
            'css'           => [
                'styles' => [
                    '/public/assets/manage/css/result-charts.css',
                ]
            ],
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/result-charts.js',
                ]
            ],
        ];

        return $this->view->render($response, "/result/charts.php", $data);
    }
}
