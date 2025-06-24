<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gate;
use App\Models\GateHasPermission;
use App\Models\GatePermission;
use Illuminate\Http\Request;

class GateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $searchQuery = request('query');
        $gate = Gate::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%");
            })
            ->with('permissions.gate_permission')
            ->orderBy('name', 'asc')
            ->paginate(50);

        return response()->json([
            'data' => $gate
        ]);
    }

    public function gatepermissions()
    {
        //
        $searchQuery = request('query');
        $gatepermissions = GatePermission::orderBy('name', 'asc')
            ->get();

        return response()->json([
            'data' => $gatepermissions
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
                'description' => 'nullable|string',
                'permissions' => 'array',
                'navis_gate_id' => 'string',
                'chassis_profile' => 'string',
            ]);


            $gate = Gate::create([
                'name' => $registerApplicationData['name'],
                'description' => $registerApplicationData['description'],
                'navis_gate_id' => $registerApplicationData['navis_gate_id'],
                'chassis_profile' => $registerApplicationData['chassis_profile'],
            ]);

            foreach ($registerApplicationData['permissions'] as $permission) {
                GateHasPermission::create([
                    'gate_id' => $gate->id,
                    'gate_permission_id' => $permission['gate_permission_id'],
                ]);
            }

        
            return response()->json([
                'data' => $gate
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
        $gate = Gate::findOrFail($id);

        return response()->json([
            'data' => $gate
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $gate = Gate::findOrFail($id);

        return response()->json([
            'data' => $gate
        ]);
    }


    public function update(Request $request, Gate $gate)
    {
        try {
            // Validação
            $validatedData = $request->validate([
                'name' => 'nullable|string',
                'description' => 'nullable|string',
                'permissions' => 'array',
                'navis_gate_id' => 'nullable|string',
                'chassis_profile' => 'nullable|string',
            ]);

            // Atualizar o usuário
            $gate->update([
                'name' => $validatedData['name'] ?? $gate->name,
                'description' => $validatedData['description'] ?? $gate->description,
                'navis_gate_id' => $validatedData['navis_gate_id'] ?? $gate->navis_gate_id,
                'chassis_profile' => $validatedData['chassis_profile'] ?? $gate->chassis_profile,
            ]);
            // Atualizar as permissões
            if (isset($validatedData['permissions'])) {
                // Limpar permissões existentes
                GateHasPermission::where('gate_id', $gate->id)->delete();

                // Adicionar novas permissões
                foreach ($validatedData['permissions'] as $permission) {
                    GateHasPermission::create([
                        'gate_id' => $gate->id,
                        'gate_permission_id' => $permission['gate_permission_id'],
                    ]);
                }
            }

            return response()->json([
                'data' => $gate
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
        $gate = Gate::findOrFail($id);

        // $gate->delete();

        return response()->noContent();
    }
}
