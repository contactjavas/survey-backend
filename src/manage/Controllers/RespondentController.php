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
    /**
     * How many respondents should shown per page.
     *
     * @var integer
     */
    public $per_page = 100;

    public function listPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $pageNumber  = 1;
        $offset      = ($pageNumber - 1) * $this->per_page;
        $respondents = Respondent::orderBy('id', 'desc')
        ->offset($offset)
        ->limit($this->per_page)
        ->get();

        $totalRespondents = Respondent::select('id')->get()->count();
        $totalPages       = ceil($totalRespondents / $this->per_page);

        $data = [
            'currentUser'      => $this->user()->get(),
            'respondents'      => $respondents,
            'totalRespondents' => $totalRespondents,
            'totalPages'       => $totalPages,
            'currentPage'      => $pageNumber,
            'activeMenu'       => '/manage/respondents/',
            'activeSubmenu'    => '/manage/respondents/',
        ];

        return $this->view->render($response, "/respondent/list.php", $data);
    }

    public function pagedListPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $pageNumber  = (int) $args['page_number'];
        $offset      = ($pageNumber - 1) * $this->per_page;
        $respondents = Respondent::orderBy('id', 'desc')
        ->offset($offset)
        ->limit($this->per_page)
        ->get();

        $totalRespondents = Respondent::select('id')->get()->count();
        $totalPages       = ceil($totalRespondents / $this->per_page);

        $data = [
            'currentUser'      => $this->user()->get(),
            'respondents'      => $respondents,
            'totalRespondents' => $totalRespondents,
            'totalPages'       => $totalPages,
            'currentPage'      => $pageNumber,
            'activeMenu'       => '/manage/respondents/',
            'activeSubmenu'    => '/manage/respondents/',
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

        $fields['added_by'] = $this->user()->getId();
        
        $insertFields = [
            'name', 'photo', 'gender_id', 'age_range', 'religion_id', 'education_id',
            'job', 'income_range', 'active_on_social_media', 'address', 'added_by'
        ];

        $data = [];

        foreach ($insertFields as $field) {
            if (isset($fields[$field])) {
                $value        = is_numeric($fields[$field]) ? (int) $fields[$field] : $fields[$field];
                $data[$field] = $value;
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
            'name', 'photo', 'gender_id', 'age_range', 'religion_id', 'education_id', 'job', 'income_range', 'active_on_social_media', 'address'
        ];
        
        foreach ($updateFields as $field) {
            if (isset($fields[$field])) {
                $value = is_numeric($fields[$field]) ? (int) $fields[$field] : $fields[$field];
                $respondent->{$field} = $value;
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
}
