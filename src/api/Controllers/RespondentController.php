<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\Respondent;
use App\Shared\Models\Gender;
use App\Shared\Models\Religion;
use App\Shared\Models\Education;
use App\Shared\Models\Province;
use App\Shared\Models\Regency;
use App\Shared\Models\District;
use App\Shared\Models\Village;

class RespondentController extends BaseController
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

        $respondents = Respondent::select('id', 'name', 'gender_id', 'age', 'job', 'phone', 'nik')
        ->get();

        $respondents->each(function ($respondent) {
            $respondent->setAppends(['gender']);
            $respondent->setHidden(['gender_id']);
        });

        $payload = json_encode([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data'    => $respondents
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

        $provinces = Province::all();
        $regencies = Regency::where('province_id', '=', $provinces[0]->id)->get();
        $districts = District::where('regency_id', '=', $regencies[0]->id)->get();
        $villages  = Village::where('district_id', '=', $districts[0]->id)->get();

        $data = [
            'genders'       => Gender::all(),
            'educations'    => Education::all(),
            'religions'     => Religion::all(),
            'provinces'     => $provinces,
            'regencies'     => $regencies,
            'districts'     => $districts,
            'villages'      => $villages,
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
        $provinces    = Province::all();
        $regencies    = Regency::where('province_id', '=', $respondent->province_id)->get();
        $districts    = District::where('regency_id', '=', $respondent->regency_id)->get();
        $villages     = Village::where('district_id', '=', $respondent->district_id)->get();

        $data = [
            'genders'       => Gender::all(),
            'educations'    => Education::all(),
            'religions'     => Religion::all(),
            'provinces'     => $provinces,
            'regencies'     => $regencies,
            'districts'     => $districts,
            'villages'      => $villages,
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
            
        $provinces = Province::all();
        $regencies = Regency::where('province_id', '=', $fields['province_id'])->get();
        $districts = District::where('regency_id', '=', $fields['regency_id'])->get();
        $villages  = Village::where('district_id', '=', $fields['district_id'])->get();
        
        $addPagedata = [
            'fields'        => $fields,
            'genders'       => Gender::all(),
            'educations'    => Education::all(),
            'religions'     => Religion::all(),
            'provinces'     => $provinces,
            'regencies'     => $regencies,
            'districts'     => $districts,
            'villages'      => $villages,
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
            'name', 'age', 'gender_id', 'job', 'religion_id', 'education_id', 'phone', 'nik', 'kk',
            'province_id', 'regency_id', 'district_id', 'village_id', 'rw', 'rt', 'address'
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

        $provinces = Province::all();
        $regencies = Regency::where('province_id', '=', $respondent->province_id)->get();
        $districts = District::where('regency_id', '=', $respondent->regency_id)->get();
        $villages  = Village::where('district_id', '=', $respondent->district_id)->get();
        
        $editPageData = [
            'genders'       => Gender::all(),
            'educations'    => Education::all(),
            'religions'     => Religion::all(),
            'provinces'     => $provinces,
            'regencies'     => $regencies,
            'districts'     => $districts,
            'villages'      => $villages,
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
            'name', 'age', 'gender_id', 'job', 'religion_id', 'education_id', 'phone', 'nik', 'kk',
            'province_id', 'regency_id', 'district_id', 'village_id', 'rw', 'rt', 'address'
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
