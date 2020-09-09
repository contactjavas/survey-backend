<?php
namespace App\Shared\Helpers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ResponseHelper extends BaseHelper
{
    public function generateError(string $field = '', string $msg = '')
    {
        if ($field && $msg) {
            return [
                'success' => false,
                'field'   => $field,
                'label'   => str_ireplace('_', ' ', $field),
                'message' => $msg
            ];
        }

        if ($this->request->getAttribute('has_errors')) {
            $errors = $this->request->getAttribute('errors');
            $error  = reset($errors);
            $field  = key($errors);
            $label  = str_ireplace('_', ' ', $field);

            return [
                'success' => false,
                'field'   => $field,
                'label'   => $label,
                'message' => $error[0]
            ];
        }

        return [];
    }
}
