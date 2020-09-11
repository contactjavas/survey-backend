<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\User;
use App\Shared\Models\Survey;

class SurveyController extends BaseController
{
    public function list(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $errorResponse = $this->response()->generateError($request);
            $errorResponse = json_encode($errorResponse);

            $response->getBody()->write($errorResponse);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $token = $this->token()->verifyToken();

        // Check token
        if (!$token) {
            $errorResponse = $this->response()->generateError('general', 'Invalid token');
            $errorResponse = json_encode($errorResponse);

            $response->getBody()->write($errorResponse);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $surveys = Survey::where('is_active', 1)
        ->select('id', 'title', 'target', 'start_date as startDate', 'end_date as endDate')
        ->get();

        $payload = json_encode([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data'    => $surveys
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function addPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $data = [
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-survey.js'
                ]
            ],
            'activeMenu'    => '/manage/',
            'activeSubmenu' => '/manage/add/',
        ];

        return $this->view->render($response, "/survey/add.php", $data);
    }

    public function editPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $survey = Survey::find($args['survey_id']);

        $data = [
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-survey.js'
                ]
            ],
            'survey'        => $survey,
            'activeMenu'    => '/manage/',
            'activeSubmenu' => '/manage/edit/',
        ];

        if (isset($args['saving_status'])) {
            if ($args['saving_status'] === 'saved') {
                $data['successMessage'] = 'Data survey berhasil disimpan';
            } elseif ($args['saving_status'] === 'updated') {
                $data['successMessage'] = 'Data survey berhasil diubah';
            }
        }

        return $this->view->render($response, "/survey/edit.php", $data);
    }
    
    public function add(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $fields = $request->getParsedBody();
        
        $survey = new Survey();

        $survey->title      = $fields['title'];
        $survey->is_active  = (int) $fields['is_active'];
        $survey->target     = (int) $fields['target'];
        $survey->start_date = $fields['start_date'];
        $survey->end_date   = $fields['end_date'];

        $survey->save();

        return $response
        ->withHeader('Location', '/manage/edit/' . $survey->id . '/saved/')
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

        $fields = $request->getParsedBody();
        
        $survey = Survey::find($fields['id']);

        $survey->title      = $fields['title'];
        $survey->is_active  = (int) $fields['is_active'];
        $survey->target     = (int) $fields['target'];
        $survey->start_date = $fields['start_date'];
        $survey->end_date   = $fields['end_date'];

        $survey->save();

        return $response
        ->withHeader('Location', '/manage/edit/' . $fields['id'] . '/updated/')
        ->withStatus(302);
    }
    
    public function delete(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }
        
        $survey = Survey::find($args['survey_id']);

        $survey->forceDelete();

        return $response
        ->withHeader('Location', '/manage/')
        ->withStatus(302);
    }
}
