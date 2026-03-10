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
        $response = Http::withoutVerifying()->timeout(120)->get('https://cdmapi.cornelder.co.mz/cgate20/api/v1/transacoes/lista', $request->query());

        return response()->json($response->json(), $response->status());
    }

    public function dashboard(){
        
        $response = Http::withoutVerifying()->get('https://cdmapi.cornelder.co.mz/cgate20/api/v1/transacoes/dashboard');

        return response()->json($response->json(), $response->status());
    }

    public function changemanualcheck(Request $request, $id){
        
        $response = Http::withoutVerifying()->post('https://cdmapi.cornelder.co.mz/cgate20/api/v1/transacoes/update-check-manual/'.$id, $request->all());

        return response()->json($response->json(), $response->status());
    }


}
