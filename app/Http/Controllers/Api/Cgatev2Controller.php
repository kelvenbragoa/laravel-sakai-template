<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Cgatev2Controller extends Controller
{
    //
    public function transaction(Request $request)
    {
        $response = Http::get('http://20.87.9.35/api/v1/transacoes/lista', $request->query());

        return response()->json($response->json(), $response->status());
    }
}
