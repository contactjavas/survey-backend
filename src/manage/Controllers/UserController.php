<?php

declare(strict_types=1);

namespace App\Manage\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\User;
use App\Shared\Models\Role;
use App\Shared\Models\UserRoleRelationship;

class UserController extends BaseController
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
            'users'         => User::all(),
            'activeMenu'    => '/manage/users/',
            'activeSubmenu' => '/manage/users/',
        ];

        return $this->view->render($response, "/user/list.php", $data);
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
            'roles'         => Role::all(),
            'activeMenu'    => '/manage/users/',
            'activeSubmenu' => '/manage/users/add/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                ]
            ],
        ];

        return $this->view->render($response, "/user/add.php", $data);
    }

    public function editPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }

        $userId   = (int) $args['user_id'];
        $screen   = stripos(getUrlPath(), '/profile/edit') !== false ? 'edit_profile' : 'edit_user';
        $template = $screen === 'edit_profile' ? 'user/edit-profile.php' : 'user/edit.php';

        $data = [
            'roles'         => Role::all(),
            'user'          => ($screen === 'edit_profile' ? $this->user()->get() : User::find($args['user_id'])),
            'activeMenu'    => '/manage/users/',
            'activeSubmenu' => ($screen === 'edit_profile' ? '/manage/profile/edit/' : '/manage/users/edit/'),
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                ]
            ],
        ];

        if (isset($args['saving_status'])) {
            $messagePrefix = $screen === 'edit_profile' ? 'Profile' : 'Data user';

            if ($args['saving_status'] === 'saved') {
                $data['successMessage'] = $messagePrefix . ' berhasil disimpan';
            } elseif ($args['saving_status'] === 'updated') {
                $data['successMessage'] = $messagePrefix . ' berhasil diubah';
            }
        }

        return $this->view->render($response, $template, $data);
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
        $data   = [];

        $addPagedata = [
            'roles'         => Role::all(),
            'fields'        => $fields,
            'activeMenu'    => '/manage/users/',
            'activeSubmenu' => '/manage/users/add/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                ]
            ],
        ];

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $error = $this->response()->generateError();

            $addPagedata['errorMessage'] = $error['message'];

            return $this->view->render($response, "/user/add.php", $addPagedata);
        }

        // Check if email is already registered.
        if ($this->emailAlreadyRegistered($fields['email'])) {
            $error = $this->response()->generateError('email', 'Email sudah digunakan');

            $addPagedata['errorMessage'] = $error['message'];

            return $this->view->render($response, "/user/add.php", $addPagedata);
        }

        // Check if phone is already registered.
        if ($this->phoneAlreadyRegistered($fields['phone'])) {
            $error = $this->response()->generateError('phone', 'Nomor telepon sudah digunakan');

            $addPagedata['errorMessage'] = $error['message'];

            return $this->view->render($response, "/user/add.php", $addPagedata);
        }
        
        $insertFields = ['email', 'password', 'first_name', 'last_name', 'phone', 'avatar', 'address'];

        foreach ($insertFields as $field) {
            if (isset($fields[$field])) {
                $data[$field] = $fields[$field];
            }
        }

        $data['username'] = $data['email'];
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $userId = User::insertGetId($data);

        $userRoleRelationship = new UserRoleRelationship();

        $userRoleRelationship->user_id = $userId;
        $userRoleRelationship->role_id = (int) $fields['role_id'];

        $userRoleRelationship->save();

        return $response
        ->withHeader('Location', '/manage/users/edit/' . $userId . '/saved/')
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

        $screen = stripos(getUrlPath(), '/profile/edit') !== false ? 'edit_profile' : 'edit_user';
        $fields = $request->getParsedBody();
        $userId = (int) $fields['id'];
        $user   = User::find($userId);

        $editPageData = [
            'roles'         => Role::all(),
            'user'          => ($screen === 'edit_profile' ? $this->user()->get() : User::find($args['user_id'])),
            'activeMenu'    => '/manage/users/',
            'activeSubmenu' => '/manage/users/edit/',
            'js'            => [
                'scripts' => [
                    '/public/assets/manage/js/edit-screen.js',
                ]
            ],
        ];

        $redirectPath = $screen === 'edit_profile' ? '/manage/profile/edit/' : '/manage/users/edit/' . $fields['id'] . '/updated/';
        $updateFields = ['email', 'first_name', 'last_name', 'phone', 'avatar', 'address'];
        
        foreach ($updateFields as $field) {
            if (isset($fields[$field])) {
                $user->{$field} = $fields[$field];
            }
        }

        if ($screen === 'edit_profile') {
            $user->password = password_hash($fields['password'], PASSWORD_DEFAULT);
        }

        $user->save();

        $userRoleRelationship = UserRoleRelationship::where('user_id', '=', $fields['id'])->first();

        $userRoleRelationship->role_id = (int) $fields['role_id'];

        $userRoleRelationship->save();

        $messagePrefix = $screen === 'edit_profile' ? 'Profile' : 'Data user';

        $editPageData['successMessage'] = $messagePrefix . ' berhasil diubah';
        $editPageData['user'] = User::find($editPageData['user']->id);

        return $this->view->render($response, "/user/edit.php", $editPageData);
    }
    
    public function delete(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if (!$this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/login/')
            ->withStatus(302);
        }
        
        $user = User::find($args['user_id']);

        $user->forceDelete();

        return $response
        ->withHeader('Location', '/manage/users/')
        ->withStatus(302);
    }

    public function emailAlreadyRegistered($email)
    {
        $user = User::where('email', '=', $email)->first();
        return (!$user ? false : true);
    }

    public function phoneAlreadyRegistered($phone)
    {
        $user = User::where('phone', '=', $phone)->first();
        return (!$user ? false : true);
    }
}
