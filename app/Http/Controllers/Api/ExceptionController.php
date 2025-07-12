<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExceptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listar(Request $request)
    {
        $response = Http::timeout(120)->get('http://20.87.9.35/api/v1/excepcoes/lista', $request->query());

        return response()->json($response->json(), $response->status());
    }

    public function actualizar(Request $request, $id)
    {
        
        $response = Http::post('http://20.87.9.35/api/v1/excepcoes/actualizar/'.$id, $request->all());

        return response()->json($response->json(), $response->status());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
