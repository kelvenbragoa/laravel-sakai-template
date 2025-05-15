<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $searchQuery = request('query');
        $app = Application::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%");
            })
            ->with('application_permissions.permission')
            ->orderBy('name', 'asc')
            ->paginate(50);

        return response()->json([
            'data' => $app
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $registerApplicationData = $request->validate([
                'name' => 'nullable|string',
                'version' => 'nullable|string',
                'description' => 'nullable|string',
            ]);


            $app = Application::create([
                'name' => $registerApplicationData['name'],
                'version' => $registerApplicationData['version'] ?? null,
                'description' => $registerApplicationData['description'] ?? null,
            ]);

        
            return response()->json([
                'data' => $app
            ]);
        } catch (\Exception $e) {
            // Retorna o erro com a mensagem detalhada
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $app = Application::with('application_permissions.permission')->findOrFail($id);

        return response()->json([
            'data' => $app
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $app = Application::with('application_permissions.permission')->findOrFail($id);

        return response()->json([
            'data' => $app
        ]);
    }


    public function update(Request $request, string $id)
    {
        try {
            $app = Application::findOrFail($id);
            // Validação
            $validatedData = $request->validate([
                'name' => 'nullable|string',
                'version' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            

            // Atualizar a aplicacao
            $app->update([
                'name' => $validatedData['name'] ?? $app->name,
                'version' => $validatedData['version'] ?? $app->version,
                'description' => $validatedData['description'] ?? $app->description,
            ]);


            return response()->json([
                'data' => $app
            ], 200);
        } catch (\Exception $e) {
            // Retorna o erro detalhado para debugging
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $app = Application::findOrFail($id);

        // $app->delete();

        return response()->noContent();
    }
}
