<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\User;
use App\Shared\Models\UserRoleRelationship;
use App\Shared\Models\Respondent;

class AwesompleteController extends BaseController
{
    public function getAllRespondents(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        $results = Respondent::select('id', 'name', 'nik')->get();

        $respondents = [];

        foreach ($results as $result) {
            array_push($respondents, [
                'label' => $result->name . ' (' . $result->nik . ')',
                'value' => $result->id
            ]);
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

    public function getAllSurveyors(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        $userByRoles = UserRoleRelationship::where('role_id', 2)->get();
        $idByRoles   = [];

        foreach ($userByRoles as $userByRole) {
            array_push($idByRoles, $userByRole->user_id);
        }

        $surveyors = [];

        if ($idByRoles) {
            $users = User::whereIn('id', $idByRoles)->get();
            $users = !$users->isEmpty() ? $users : [];
            foreach ($users as $user) {
                array_push($surveyors, [
                    'label' => $user->first_name . ' ' . $user->last_name,
                    'value' => $user->id
                ]);
            }
        }

        $payload = json_encode([
            'success' => true,
            'message' => 'Berhasil mengambil daftar surveyor',
            'data'    => $surveyors
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function getRespondentsBySearch(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        $query  = $args['query'];
        $column = is_numeric($query) ? 'nik' : 'name';

        $results = Respondent::where($column, 'LIKE', '%' . $query . '%')
        ->select('id', 'name', 'nik')
        ->get();

        $respondents = [];

        foreach ($results as $result) {
            array_push($respondents, [
                'label' => $result->name . ' (' . $result->nik . ')',
                'value' => $result->id
            ]);
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

    public function getSurveyorsBySearch(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        $userByRoles = UserRoleRelationship::where('role_id', 2)->get();
        $idByRoles   = [];

        foreach ($userByRoles as $userByRole) {
            array_push($idByRoles, $userByRole->user_id);
        }

        $surveyors = [];

        if ($idByRoles) {
            $query = $args['query'];
            $users = User::whereIn('id', $idByRoles)
                ->where('first_name', 'LIKE', '%' . $query . '%')
                ->orWhere('last_name', 'LIKE', '%' . $query . '%')
                ->get();

            $users = !$users->isEmpty() ? $users : [];

            foreach ($users as $user) {
                array_push($surveyors, [
                    'label' => $user->first_name . ' ' . $user->last_name,
                    'value' => $user->id
                ]);
            }
        }

        $payload = json_encode([
            'success' => true,
            'message' => 'Berhasil mengambil daftar surveyor',
            'data'    => $surveyors
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}
