<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Shared\Models\User;
use App\Shared\Models\Province;
use App\Shared\Models\Regency;
use App\Shared\Models\District;
use App\Shared\Models\Village;

class WilayahController extends BaseController
{
    public function getAllProvinces(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        $payload = json_encode([
            'success' => true,
            'message' => 'Berhasil mengambil daftar provinsi',
            'data'    => Province::all()
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function getRegenciesByProvinceId(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        $provinceId = $args['province_id'];
        $regencies  = Regency::where('province_id', '=', $provinceId)
        ->select('id', 'name as text')
        ->get();

        $payload = json_encode([
            'success' => true,
            'message' => 'Berhasil mengambil daftar kabupaten',
            'data'    => $regencies
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function getDistrictsByRegencyId(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        $regencyId = $args['regency_id'];
        $districts = District::where('regency_id', '=', $regencyId)
        ->select('id', 'name as text')
        ->get();

        $payload = json_encode([
            'success' => true,
            'message' => 'Berhasil mengambil daftar kecamatan',
            'data'    => $districts
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function getVillagesByDistrictId(Request $request, Response $response, array $args)
    {
        $this->shareRequest($request);

        $districtId = $args['district_id'];
        $villages   = Village::where('district_id', '=', $districtId)
        ->select('id', 'name as text')
        ->get();

        $payload = json_encode([
            'success' => true,
            'message' => 'Berhasil mengambil daftar desa / kelurahan',
            'data'    => $villages
        ]);

        $response->getBody()->write($payload);

        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}
