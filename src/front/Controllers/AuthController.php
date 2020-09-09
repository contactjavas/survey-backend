<?php

declare(strict_types=1);

namespace App\Front\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Laminas\Escaper\Escaper;
use App\Shared\Models\User;
use App\Shared\Models\Token;

class AuthController extends BaseController
{
    public function loginPage(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        if ($this->user()->isLoggedIn()) {
            return $response
            ->withHeader('Location', '/manage/')
            ->withStatus(302);
        }

        $data = [];

        return $this->view->render($response, "/login.php", $data);
    }

    public function login(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $errorResponse = $this->response()->generateError($request);
            return $this->view->render(
                $response,
                "/login.php",
                ['errorMessage' => $errorResponse['message']]
            );
        }

        $fields = $request->getParsedBody();
        $user   = User::where('email', $fields['email'])->first();

        // Check if email doesn't exist.
        if (!is_object($user) || empty($user)) {
            $errorResponse = $this->response()->generateError('email', 'Email tidak terdaftar');
            return $this->view->render(
                $response,
                "/login.php",
                ['errorMessage' => $errorResponse['message']]
            );
        }

        // Check if password is wrong.
        if (!password_verify($fields['password'], $user->password)) {
            $errorResponse = $this->response()->generateError('password', 'Password salah');
            return $this->view->render(
                $response,
                "/login.php",
                ['errorMessage' => $errorResponse['message']]
            );
        }

        unset($user->password);

        $token = $this->token()->generate(
            [
            'user_id' => $user->id,
            'mode'    => 'insert'
            ]
        );

        $result = [
            'success' => true,
            'message' => 'Login berhasil',
            'token'   => $token,
            'data'    => $user
        ];

        if (!$this->user()->isAdmin($user)) {
            return $this->view->render(
                $response,
                "/login.php",
                ['errorMessage' => 'Wajib menggunakan akun admin']
            );
        }

        $this->user()->setCurrent($user);
        setcookie("token", $token, strtotime('+15 days'), '/');

        return $response
        ->withHeader('Location', '/manage/')
        ->withStatus(302);
    }

    public function logout(Request $request, Response $response, array $args)
    {
        if (isset($_COOKIE['token'])) {
            unset($_COOKIE['token']);
            setcookie('token', '', time() - 3600, '/'); // empty value and old timestamp
        }

        $this->container->set('currentUser', null);

        return $response
        ->withHeader('Location', '/login/')
        ->withStatus(302);
    }
}
