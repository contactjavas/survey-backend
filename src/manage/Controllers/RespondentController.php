<?php

declare(strict_types=1);

namespace App\Manage\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\Respondent;
use App\Shared\Models\Gender;
use App\Shared\Models\Religion;
use App\Shared\Models\Education;

class RespondentController extends BaseController
{
    public function listPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $data = [
            'currentUser'   => $this->user()->get(),
            'respondents'   => Respondent::all(),
            'activeMenu'    => '/manage/respondents/',
            'activeSubmenu' => '/manage/respondents/',
        ];

        return $this->view->render($response, "/respondent/list.php", $data);
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
            'genders'       => Gender::all(),
            'educations'    => Education::all(),
            'religions'     => Religion::all(),
            'activeMenu'    => '/manage/respondents/',
            'activeSubmenu' => '/manage/respondents/add/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-respondent.js',
                ]
            ],
        ];

        return $this->view->render($response, "/respondent/add.php", $data);
    }

    public function editPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $respondentId = $args['respondent_id'];
        $respondent   = Respondent::find($respondentId);

        $data = [
            'genders'       => Gender::all(),
            'educations'    => Education::all(),
            'religions'     => Religion::all(),
            'respondent'    => $respondent,
            'activeMenu'    => '/manage/respondents/',
            'activeSubmenu' => '/manage/respondents/edit/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-respondent.js',
                ]
            ],
        ];

        if (isset($args['saving_status'])) {
            if ($args['saving_status'] === 'saved') {
                $data['successMessage'] = 'Data responden berhasil disimpan';
            } elseif ($args['saving_status'] === 'updated') {
                $data['successMessage'] = 'Data responden berhasil diubah';
            }
        }

        return $this->view->render($response, '/respondent/edit.php', $data);
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
        
        $addPagedata = [
            'fields'        => $fields,
            'genders'       => Gender::all(),
            'educations'    => Education::all(),
            'religions'     => Religion::all(),
            'activeMenu'    => '/manage/respondents/',
            'activeSubmenu' => '/manage/respondents/add/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-respondent.js',
                ]
            ],
        ];

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $error = $this->response()->generateError();

            $addPagedata['errorMessage'] = $error['message'];

            return $this->view->render($response, "/respondent/add.php", $addPagedata);
        }

        // Check if NIK is already registered.
        if ($this->nikAlreadyRegistered($fields['nik'])) {
            $error = $this->response()->generateError('nik', 'NIK sudah digunakan');

            $addPagedata['errorMessage'] = $error['message'];

            return $this->view->render($response, "/respondent/add.php", $addPagedata);
        }
        
        $insertFields = [
            'name', 'photo', 'age', 'gender_id', 'job', 'religion_id', 'education_id', 'phone', 'nik', 'kk', 'address'
        ];

        $data = [];

        foreach ($insertFields as $field) {
            if (isset($fields[$field])) {
                $data[$field] = $fields[$field];
            }
        }

        $respondentId = Respondent::insertGetId($data);

        return $response
        ->withHeader('Location', '/manage/respondents/edit/' . $respondentId . '/saved/')
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

        $fields       = $request->getParsedBody();
        $respondentId = (int) $fields['id'];
        $respondent   = Respondent::find($respondentId);
        
        $editPageData = [
            'genders'       => Gender::all(),
            'educations'    => Education::all(),
            'religions'     => Religion::all(),
            'respondent'    => $respondent,
            'activeMenu'    => '/manage/respondents/',
            'activeSubmenu' => '/manage/respondents/edit/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                    '/public/assets/manage/js/edit-respondent.js',
                ]
            ],
        ];

        $updateFields = [
            'name', 'photo', 'age', 'gender_id', 'job', 'religion_id', 'education_id', 'phone', 'nik', 'kk', 'address'
        ];
        
        foreach ($updateFields as $field) {
            if (isset($fields[$field])) {
                $respondent->{$field} = $fields[$field];
            }
        }

        $respondent->save();

        $editPageData['successMessage'] = 'Data respondent berhasil diubah';

        return $this->view->render($response, "/respondent/edit.php", $editPageData);
    }
    
    public function delete(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }
        
        $respondent = Respondent::find($args['respondent_id']);

        $respondent->forceDelete();

        return $response
        ->withHeader('Location', '/manage/respondents/')
        ->withStatus(302);
    }

    public function nikAlreadyRegistered($nik)
    {
        $respondent = Respondent::where('nik', '=', $nik)->first();
        return (!$respondent ? false : true);
    }
}
