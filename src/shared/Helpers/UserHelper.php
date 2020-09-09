<?php

namespace App\Shared\Helpers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Shared\Models\User;

class UserHelper extends BaseHelper
{
    public function token()
    {
        return new TokenHelper($this->container, $this->request);
    }

    public function getId()
    {
        $user_data = $this->get();

        if (isset($user_data->id) && !empty($user_data->id)) {
            return $user_data->id;
        }

        return 0;
    }

    public function get()
    {
        $user = $this->container->get('currentUser');

        if (!empty($user)) {
            return $user;
        }

        $payload = $this->request->getAttribute("token");

        // Check if this is coming from REST API request.
        if (!empty($payload) && isset($payload['token'])) {
            $payload = $this->token($this->request)->verify($payload, false);
        } else {
            $payload = $this->token($this->request)->verifyCookie();
        }

        if (!isset($payload['user_id'])) {
            return null;
        }

        // NOTE: Below is a repeated query, because we have queried the "user" when verifying the token/ cookie.
        // TODO: Adjust the return method below to prevent repeated query.
        $user = User::find($payload['user_id']);

        if ($user) {
            $this->container->set('currentUser', $user);
            return $user;
        }

        return null;
    }

    /**
     * Set current user in container.
     *
     * @param object|int $user The user object or user id.
     */
    public function setCurrent($user)
    {
        if (is_object($user) && !empty($user->id)) {
            $user_id = $user->id;
        } else {
            $user_id = (int) $user;
            $user    = User::find($user_id);
        }

        // Update the container.
        $this->container->set('currentUser', $user);
    }

    public function isLoggedIn()
    {
        $user_data = $this->get();

        return (isset($user_data->id) && !empty($user_data->id) ? true : false);
    }

    public function isAdmin($user)
    {
        if (!$user) {
            return false;
        }

        if (!is_object($user) || !isset($user->roles)) {
            $user = User::find($user);
        }

        if (!$user) {
            return false;
        }

        foreach ($user->roles as $role) {
            if ($role->slug === 'administrator') {
                return true;
                break;
            }
        }

        return false;
    }
}
