<?php

declare(strict_types=1);

namespace App\Manage\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\CandidateType;
use App\Shared\Models\Candidate;

class CandidateController extends BaseController
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
            'surveyId'      => $args['survey_id'],
            'candidates'    => Candidate::where('survey_id', $surveyId)->get(),
            'activeMenu'    => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu' => '/manage/survey/' . $surveyId . '/candidates/',
        ];

        return $this->view->render($response, "/candidate/list.php", $data);
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
            'candidateTypes' => CandidateType::all(),
            'surveyId'       => $surveyId,
            'activeMenu'     => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu'  => '/manage/survey/' . $surveyId . '/candidates/add/',
            'js'             => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                ]
            ],
        ];

        return $this->view->render($response, "/candidate/add.php", $data);
    }

    public function editPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $surveyId    = (int) $args['survey_id'];
        $candidateId = (int) $args['candidate_id'];
        $candidate   = Candidate::find($candidateId);

        $data = [
            'candidateTypes' => CandidateType::all(),
            'surveyId'       => $surveyId,
            'candidate'      => $candidate,
            'activeMenu'     => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu'  => '/manage/survey/' . $surveyId . '/candidates/edit/',
            'js'             => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                ]
            ],
        ];

        if (isset($args['saving_status'])) {
            if ($args['saving_status'] === 'saved') {
                $data['successMessage'] = 'Data calon berhasil disimpan';
            } elseif ($args['saving_status'] === 'updated') {
                $data['successMessage'] = 'Data calon berhasil diubah';
            }
        }

        return $this->view->render($response, '/candidate/edit.php', $data);
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
            'fields'         => $fields,
            'candidateTypes' => CandidateType::all(),
            'surveyId'       => $surveyId,
            'activeMenu'     => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu'  => '/manage/survey/' . $surveyId . '/candidates/add/',
            'js'             => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                ]
            ],
        ];

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $error = $this->response()->generateError();

            $addPageData['errorMessage'] = $error['message'];

            return $this->view->render($response, "/candidate/add.php", $addPageData);
        }
        
        $insertFields = ['name', 'candidate_type_id', 'survey_id'];

        $data = [];

        foreach ($insertFields as $field) {
            if (isset($fields[$field])) {
                $data[$field] = $fields[$field];
            }
        }

        $candidateId = Candidate::insertGetId($data);

        $addPageData['candidate'] = Candidate::find($candidateId);

        return $response
        ->withHeader('Location', '/manage/survey/' . $surveyId . '/candidates/edit/' . $candidateId . '/saved/')
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

        $fields      = $request->getParsedBody();
        $surveyId    = (int) $args['survey_id'];
        $candidateId = (int) $args['candidate_id'];
        $candidate   = Candidate::find($candidateId);

        $editPageData = [
            'candidateTypes' => CandidateType::all(),
            'surveyId'       => $surveyId,
            'candidate'      => $candidate,
            'activeMenu'     => '/manage/survey/' . $surveyId . '/',
            'activeSubmenu'  => '/manage/survey/' . $surveyId . '/candidates/edit/',
            'js'             => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                ]
            ],
        ];

        $updateFields = ['name', 'candidate_type_id', 'survey_id'];
        
        foreach ($updateFields as $field) {
            if (isset($fields[$field])) {
                $candidate->{$field} = $fields[$field];
            }
        }

        $candidate->save();

        $editPageData['successMessage'] = 'Data calon berhasil diubah';

        return $this->view->render($response, "/candidate/edit.php", $editPageData);
    }
    
    public function delete(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }
        
        $candidate = Candidate::find($args['candidate_id']);

        $candidate->delete();

        return $response
        ->withHeader('Location', '/manage/survey/' . $args['survey_id'] . '/candidates/')
        ->withStatus(302);
    }
}
