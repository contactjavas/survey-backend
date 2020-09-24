<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\UploadedFile;
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

    public function search(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        $query  = $args['query'];
        $format = $args['data_format'];
        $column = is_numeric($query) ? 'nik' : 'name';

        if ($format === 'simple') {
            $respondents = Respondent::where($column, 'LIKE', '%' . $query . '%')
            ->select('id', 'name', 'nik')
            ->get();
        } else {
            $respondents = Respondent::where($column, 'LIKE', '%' . $query . '%')
            ->select('id', 'name', 'gender_id', 'age', 'job', 'phone', 'nik')
            ->get();
        }

        $payload = json_encode([
            'success' => true,
            'message' => 'Berhasil mengambil daftar responden',
            'data'    => $respondents
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function addPage(Request $request, Response $response, array $args)
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

        $data = [
            'genders'    => Gender::all(),
            'educations' => Education::all(),
            'religions'  => Religion::all(),
        ];

        $payload = json_encode([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data'    => $data
        ]);

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
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

        $fields = $request->getParsedBody();
        $photo  = isset($fields['photo']) ? $fields['photo'] : '';
        $fields = isset($fields['data']) ? json_decode($fields['data'], true) : [];

        // Check if NIK is already registered.
        if ($this->nikAlreadyRegistered($fields['nik'])) {
            $errorResponse = $this->response()->generateJsonError('nik', 'NIK sudah digunakan');

            $response->getBody()->write($errorResponse);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/respondents';
        $photoDir  = '';

        if (!empty($photo)) {
            list($type, $photo) = explode(';', $photo);
            list(, $photo)      = explode(',', $photo);

            $type = explode('/', $type);
            $type = end($type);

            $photo = base64_decode($photo);
            
            $filename = strtolower($fields['name']);
            $filename = str_ireplace(' ', '-', $filename);
            $photoDir = getBaseUrl() . '/public/uploads/respondents/' . $filename . ".{$type}";

            file_put_contents($uploadDir . '/' . $filename . ".{$type}", $photo);
        }
        
        $fields['photo'] = $photoDir;

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

        $data['id'] = $respondentId;

        $payload = json_encode([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'data'    => $data
        ]);

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function addUpload(Request $request, Response $response, array $args)
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

        $fields = $request->getParsedBody();
        $fields = isset($fields['data']) ? json_decode($fields['data'], true) : [];

        // Check if NIK is already registered.
        if ($this->nikAlreadyRegistered($fields['nik'])) {
            $errorResponse = $this->response()->generateJsonError('nik', 'NIK sudah digunakan');

            $response->getBody()->write($errorResponse);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/respondents';
        $photoDir  = '';

        $files = $request->getUploadedFiles();
        $photo = $files['photo'];

        if ($photo->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($uploadDir, $photo);
            $photoDir = getBaseUrl() . '/public/uploads/respondents/' . $filename;
        }
        
        $fields['photo'] = $photoDir;

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

        $data['id'] = $respondentId;

        $payload = json_encode([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'data'    => $data
        ]);

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
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
        
        $respondent = Respondent::find($args['respondent_id']);

        $respondent->forceDelete();

        $payload = json_encode([
            'success' => true,
            'message' => 'Data responden telah dihapus',
            'data'    => [],
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function nikAlreadyRegistered($nik)
    {
        $respondent = Respondent::where('nik', '=', $nik)->first();
        return (!$respondent ? false : true);
    }

    /**
     * Moves the uploaded file to the upload directory and assigns it a unique name
     * to avoid overwriting an existing uploaded file.
     *
     * @param string $directory The directory to which the file is moved
     * @param UploadedFile $uploadedFile The file uploaded file to move
     *
     * @return string The filename of moved file
     */
    public function moveUploadedFile(string $directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        // see http://php.net/manual/en/function.random-bytes.php
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}
