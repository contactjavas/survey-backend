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

        $respondents = Respondent::select('id', 'name', 'gender_id', 'age_range as ageRange', 'job', 'income_range as incomeRange', 'active_on_social_media as activeOnSocialMedia')
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
        $column = 'name';

        if ($format === 'simple') {
            $respondents = Respondent::where($column, 'LIKE', '%' . $query . '%')
            ->select('id', 'name', 'job')
            ->get();

            $respondents->each(function ($respondent) {
                $respondent->setAppends([]);
            });
        } else {
            $respondents = Respondent::where($column, 'LIKE', '%' . $query . '%')
            ->select('id', 'name', 'gender_id', 'age_range as ageRange', 'religion_id', 'education_id', 'job')
            ->get();
            
            $respondents->each(function ($respondent) {
                $respondent->setAppends(['gender', 'religion', 'education']);
                $respondent->setHidden(['gender_id', 'religion_id', 'education_id']);
            });
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

        $respondentId = $args['respondent_id'];
        $respondent   = Respondent::select(
            [
                'id', 'name', 'photo', 'age_range as ageRange', 'income_range as incomeRange', 'gender_id as genderId', 'job', 'religion_id as religionId', 'education_id as educationId', 'active_on_social_media as activeOnSocialMedia', 'address'
            ]
        )
        ->find($respondentId);

        $data = [
            'respondent' => $respondent,
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
            // 'name', 'photo', 'age', 'gender_id', 'job', 'religion_id', 'education_id', 'phone', 'nik', 'kk', 'address'
            'name', 'photo', 'gender_id', 'age_range', 'religion_id', 'education_id', 'job', 'income_range', 'active_on_social_media', 'address'
        ];
        
        $data = [];

        foreach ($insertFields as $field) {
            if (isset($fields[$field])) {
                $value        = is_numeric($fields[$field]) ? (int) $fields[$field] : $fields[$field];
                $data[$field] = $value;
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

        $uploadDir = __DIR__ . '/../../../public/uploads/respondents';
        $photoDir  = '';

        if (!empty($files) && isset($files['photo'])) {
            $files = $request->getUploadedFiles();
            $photo = $files['photo'];
    
            if ($photo->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile($uploadDir, $photo);
                $photoDir = getBaseUrl() . '/public/uploads/respondents/' . $filename;
            }
        }
        
        $fields['photo'] = $photoDir;

        $insertFields = [
            // 'name', 'photo', 'age', 'gender_id', 'job', 'religion_id', 'education_id', 'phone', 'nik', 'kk', 'address'
            'name', 'photo', 'gender_id', 'age_range', 'religion_id', 'education_id', 'job', 'income_range', 'active_on_social_media', 'address'
        ];
        
        $data = [];

        foreach ($insertFields as $field) {
            if (isset($fields[$field])) {
                $value        = is_numeric($fields[$field]) ? (int) $fields[$field] : $fields[$field];
                $data[$field] = $value;
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

        $respondentId = (int) $args['respondent_id'];
        $respondent   = Respondent::find($respondentId);

        $updateFields = [
            'name', 'gender_id', 'age_range', 'religion_id', 'education_id', 'job', 'income_range', 'active_on_social_media', 'address'
        ];

        $uploadDir = __DIR__ . '/../../../public/uploads/respondents';
        $photoDir  = '';

        if (!empty($files) && isset($files['photo'])) {
            $files = $request->getUploadedFiles();
            $photo = $files['photo'];
    
            if ($photo->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile($uploadDir, $photo);
                $photoDir = getBaseUrl() . '/public/uploads/respondents/' . $filename;
            }

            array_push($updateFields, 'photo');
            $fields['photo'] = $photoDir;
        }

        $data = [];
        
        foreach ($updateFields as $field) {
            if (isset($fields[$field])) {
                $value        = is_numeric($fields[$field]) ? (int) $fields[$field] : $fields[$field];
                $data[$field] = $value;

                $respondent->{$field} = $value;
            }
        }

        $respondent->save();

        $data['id'] = $respondentId;

        $payload = json_encode([
            'success' => true,
            'message' => 'Data berhasil diubah',
            'data'    => $data
        ]);

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
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
