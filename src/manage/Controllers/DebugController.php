<?php

declare(strict_types=1);

namespace App\Manage\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\Respondent;
use App\Shared\Models\Vote;
use App\Shared\Models\User;
use App\Shared\Models\Gender;
use App\Shared\Models\Religion;
use App\Shared\Models\Education;

class DebugController extends BaseController
{
    public function insertRespondentsAddedBy(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $voteResult = Vote::where('survey_id', 2)->get();

        $votes = [];
        
        foreach ($voteResult as $key => $vote) {
            $votes[$vote->id] = [
                'surveyor'   => User::find($vote->user_id),
                'respondent' => Respondent::find($vote->respondent_id),
                'vote'       => $vote
            ];

            // $respondent = Respondent::find($vote->respondent_id);

            // $respondent->added_by = $vote->user_id;
            // $respondent->save();

            $votes[$vote->id]['added_by'] = $respondent->added_by;
        }

        $data = [
            'debugType'     => 'check-respondents',
            'votes'         => $votes,
            'respondents'   => Respondent::all(),
            'currentUser'   => $this->user()->get(),
            'activeMenu'    => '/manage/respondents/',
            'activeSubmenu' => '/manage/respondents/',
        ];

        return $this->view->render($response, "/debug.php", $data);
    }
}
