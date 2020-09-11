<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Laminas\Escaper\Escaper;
use App\Shared\Models\User;
use App\Shared\Models\Token;

class AuthController extends BaseController
{
    public function login(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        // General validations.
        if ($request->getAttribute('has_errors')) {
            $errorResponse = $this->response()->generateError($request);
            $errorResponse = json_encode($errorResponse);

            $response->getBody()->write($errorResponse);
            return $response->withHeader('Content-Type', 'application/json');
        }

        $fields = $request->getParsedBody();
        $user   = User::where('email', $fields['email'])->first();

        // Check if email doesn't exist.
        if (!is_object($user) || empty($user)) {
            $errorResponse = $this->response()->generateError('email', 'Email tidak terdaftar');
            $errorResponse = json_encode($errorResponse);
            
            $response->getBody()->write($errorResponse);
            return $response->withHeader('Content-Type', 'application/json');
        }

        // Check if password is wrong.
        if (!password_verify($fields['password'], $user->password)) {
            $errorResponse = $this->response()->generateError('password', 'Password salah');
            $errorResponse = json_encode($errorResponse);
            
            $response->getBody()->write($errorResponse);
            return $response->withHeader('Content-Type', 'application/json');
        }

        unset($user->password);

        $this->user()->setCurrent($user);

        $token = $this->token()->generate(
            [
            'user_id' => $user->id,
            'mode'    => 'insert'
            ]
        );

        // Remove un-necessary data.
        unset($user->username);
        unset($user->address);
        unset($user->map);
        unset($user->avatar);
        unset($user->created_at);
        unset($user->updated_at);
        unset($user->deleted_at);
        unset($user->roles);

        $payload = json_encode([
            'success' => true,
            'message' => 'Login berhasil',
            'token'   => $token,
            'data'    => $user
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
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
