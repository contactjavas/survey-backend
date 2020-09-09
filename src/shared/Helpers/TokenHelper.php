<?php

namespace App\Shared\Helpers;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use App\Shared\Models\Token;

class TokenHelper extends BaseHelper
{
    public function generate(array $args)
    {
        // The user_id arg is required.
        if (!isset($args['user_id'])) {
            return false;
        }

        $now    = new DateTime();
        $future = new DateTime("now +15 days");
        $token  = bin2hex(random_bytes(32));

        $payload = [
            'iat'   => $now->getTimeStamp(),
            'exp'   => $future->getTimeStamp(),
            'via'   => 'default',
            'token' => $token
        ];

        // Handle "mode" argument.
        if (isset($args['mode'])) {
            if ($args['mode'] === 'insert') {
                Token::insert([
                    'token'   => $token,
                    'user_id' => $args['user_id'],
                    'exp'     => date('Y-m-d H:i:s', $future->getTimeStamp())
                ]);
            } elseif ($args['mode'] === 'update') {
                // In update mode, old_token arg is required.
                if (!isset($args['old_token'])) {
                    return false;
                }

                Token::where('user_id', $args['user_id'])
                ->where('token', $args['old_token'])
                ->update([
                    'token' => $token,
                    'exp'   => date('Y-m-d H:i:s', $future->getTimeStamp())
                ]);
            }
        }

        $data               = isset($args['data']) ? $args['data'] : [];
        $payload            = array_merge($payload, $data);
        $payload['user_id'] = $args['user_id'];

        return $this->encode($payload);
    }

    /**
     * Verify Payload
     *
     * @param  array   $payload         The payload
     * @param  boolean $return_as_token Whether to return as token or not
     * @return string|array|bool        If success, it returns either token or payload (depends on $return_as_token)
     *                                  If failed, it always returns false.
     */
    public function verify(array $payload, bool $return_as_token = true)
    {
        if (!isset($payload['token']) || !isset($payload['user_id'])) {
            return false;
        }

        $user_id   = $payload['user_id'];
        $old_token = $payload['token'];
        $token_row = Token::where('user_id', $user_id)->where('token', $old_token)->first();

        if (!$token_row) {
            return false;
        }

        $user_helper = new UserHelper($this->container, $this->request);
        $user_helper->setCurrent($user_id);

        // If token is expired.
        if (time() > $payload['exp']) {
            $token = $this->generate([
                'user_id'   => $user_id,
                'via'       => $payload['via'],
                'mode'      => 'update',
                'old_token' => $old_token
            ]);

            return ($return_as_token ? $token : $this->decode($token));
        } else {
            return ($return_as_token ? $this->encode($payload) : $payload);
        }
    }

    /**
     * Verify payload via token.
     *
     * @return string|false If success, it returns the token. If failed, it returns false.
     */
    public function verifyToken()
    {
        $payload = $this->request->getAttribute("token");

        return (empty($payload) ? false : $this->verify($payload));
    }

    /**
     * Verify payload via cookie.
     *
     * @return array|false If success, it returns the payload. If failed, it returns false.
     */
    public function verifyCookie()
    {
        $cookies = $this->request->getCookieParams();
        $token   = isset($cookies['token']) ? $cookies['token'] : '';

        if (empty($token)) {
            return false;
        }

        $payload = $this->decode($token);

        return (empty($payload) ? false : $this->verify($payload, false));
    }

    public function parse()
    {
        return $this->request->getAttribute("token");
    }

    public function encode($payload)
    {
        $jwt = $this->container->get('settings')['jwt'];

        return JWT::encode($payload, $jwt['secret'], "HS256");
    }

    public function decode($token)
    {
        $jwt = $this->container->get('settings')['jwt'];

        $decoded = JWT::decode(
            $token,
            $jwt['secret'],
            (array) $jwt['algorithm']
        );

        return (array) $decoded;
    }
}
