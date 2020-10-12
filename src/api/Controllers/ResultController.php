<?php

declare(strict_types=1);

namespace App\Api\Controllers;

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
    public function filter(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);
        
        $fields   = $request->getParsedBody();
        $surveyId = (int) $args['survey_id'];
        $results  = [];

        $respondentQuery = Respondent::select('id');

        $filterFields = ['gender_id', 'age_range', 'religion_id', 'education_id', 'job', 'income_range', 'active_on_social_media'];

        foreach ($filterFields as $field) {
            if (isset($fields[$field])) {
                $respondentQuery = $respondentQuery->where($field, $fields[$field]);
            }
        }

        $respondents   = $respondentQuery->get();
        $respondentIds = [];

        foreach ($respondents as $key => $respondent) {
            array_push($respondentIds, $respondent->id);
        }

        $votes   = Vote::where('survey_id', $surveyId)->get();
        $voteIds = [];

        foreach ($votes as $key => $vote) {
            if (in_array($vote->respondent_id, $respondentIds, true)) {
                array_push($voteIds, $vote->id);
            }
        }

        $questions = Question::where('survey_id', $surveyId)
        ->select('id')
        ->get();

        foreach ($questions as $question) {
            $results['question_' . $question->id] = [];

            $choices = QuestionChoice::where('question_id', $question->id)
            ->select('id')
            ->get();

            foreach ($choices as $choice) {
                $answers = Answer::where('question_choice_id', $choice->id)
                ->whereIn('vote_id', $voteIds)
                ->get();

                array_push($results['question_' . $question->id], $answers->count());
            }
        }

        $payload = json_encode([
            'success' => true,
            'message' => 'Berhasil mengambil hasil survey',
            'data'    => $results
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}
