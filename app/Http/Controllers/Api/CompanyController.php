<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        //
        $searchQuery = request('query');
        $user = Company::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(50);

        return response()->json([
            'data' => $user
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
            $registerUserData = $request->validate([
                'name' => 'nullable|string',
                'mobile' => 'nullable|string',
                'email' => 'nullable|string|email',
                'address' => 'nullable|string',
            ]);


            $company = Company::create([
                'name' => $registerUserData['name'],
                'mobile' => $registerUserData['mobile'] ?? null,
                'email' => $registerUserData['email'] ?? null,
                'address' => $registerUserData['address'] ?? null,
            ]);

        
            return response()->json([
                'data' => $company
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
        $company = Company::findOrFail($id);

        return response()->json([
            'data' => $company
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $company = Company::findOrFail($id);

        return response()->json([
            'data' => $company
        ]);
    }


    public function update(Request $request, Company $company)
    {
        try {
            // Validação
            $validatedData = $request->validate([
                'name' => 'nullable|string',
                'mobile' => 'nullable|string',
                'email' => 'nullable|string|email',
                'address' => 'nullable|string',
            ]);

            // Atualizar o usuário
            $company->update([
                'name' => $validatedData['name'] ?? $company->name,
                'mobile' => $validatedData['mobile'] ?? $company->mobile,
                'email' => $validatedData['email'] ?? $company->email,
                'address' => $validatedData['address'] ?? $company->address,
            ]);

            return response()->json([
                'data' => $company
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
        $user = Company::findOrFail($id);

        // $user->delete();

        return response()->noContent();
    }
}
