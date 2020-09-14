<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\QuestionChoice;

class QuestionChoiceController extends BaseController
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

        $questionId = (int) $args['question_id'];
        $choices    = QuestionChoice::where('question_id', $questionId)->get();

        $choices->makeHidden(['question_id']);

        $payload = json_encode([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data'    => $choices
        ]);

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
