<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContainerTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContainerTransactionController extends Controller
{
    public function index()
    {
        //
        $searchQuery = request('query');
        $gateQuery = request('gate');
        $noPagination = request('no_pagination');

        $transactionQuery = ContainerTransaction::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('driver_license_number', 'like', "{$searchQuery}%")
                ->orWhere('driver_license_number_overwrite', 'like', "{$searchQuery}%")
                ->orWhere('main_plate', 'like', "{$searchQuery}%")
                ->orWhere('main_plate_overwrite', 'like', "{$searchQuery}%")
                ->orWhere('trailer_1_license_plate_number', 'like', "{$searchQuery}%")
                ->orWhere('trailer_2_license_plate_number', 'like', "{$searchQuery}%")
                ->orWhere('container_number_1', 'like', "{$searchQuery}%")
                ->orWhere('container_seal_number_1', 'like', "{$searchQuery}%")
                ->orWhere('container_number_2', 'like', "{$searchQuery}%")
                ->orWhere('container_seal_number_2', 'like', "{$searchQuery}%")
                ->orWhere('container_number_3', 'like', "{$searchQuery}%")
                ->orWhere('container_seal_number_3', 'like', "{$searchQuery}%");
            })
             ->when(request('startdatetime') && request('enddatetime'), function ($query) {
                    $startDateTimeSearch = Carbon::parse(request('startdatetime'))->format('Y-m-d H:i:s');
                    $endDateTimeSearch = Carbon::parse(request('enddatetime'))->format('Y-m-d H:i:s');
        
                    $query->whereBetween('created_at', [$startDateTimeSearch, $endDateTimeSearch]);
            })
            ->when(request('gate'), function ($query, $gateQuery) {
                $query->where('gate','like', "%{$gateQuery}%");
            })
            ->orderBy('created_at', 'desc');
            // ->paginate(50);
            if ($noPagination) {
                $transactions = $transactionQuery->get();
            } else {
                $transactions = $transactionQuery->paginate(50);
            }

        return response()->json([
            'data' => $transactions
        ]);
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
    // public function store(Request $request)
    // {
    //     //
    //     // $randomPassword = Str::random(8) . '!@#';
    //     $registerUserData = $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|string|email|unique:users',
    //         'password'=>'required|min:8',
    //         'mobile' => 'required',
    //         'roles' => 'required|array',
    //         'roles.*.name' => 'required|string',
    //     ]);

    //     // return $registerUserData['roles'];
    //     $user = ContainerTransaction::create([
    //         'name' => $registerUserData['name'],
    //         'email' => strtolower($registerUserData['email']),
    //         'is_active' => 1,
    //         'mobile' => $registerUserData['mobile'] ?? null,
    //         'password' => Hash::make($registerUserData['password']),
    //     ]);

    //     $roleNames = collect($registerUserData['roles'])->pluck('name')->toArray();


    //     $user->syncRoles($roleNames);

    //     return response()->json([
    //         'data' => $user
    //     ]);
    // }

    public function store(Request $request)
    {
        // try {
        //     $registerUserData = $request->validate([
        //         'user_full_name' => 'required|string',
        //         'user_name' => 'required|string|unique:users',
        //         'email' => 'nullable|string|email|unique:users',
        //         'password' => 'required|min:8',
        //         'roles' => 'required|array',
        //         'roles.*.name' => 'required|string',
        //     ]);


        //     $user = ContainerTransaction::create([
        //         'user_full_name' => $registerUserData['user_full_name'],
        //         'user_name' => $registerUserData['user_name'],
        //         'email' => $registerUserData['email'] ? strtolower($registerUserData['email']) : null,
        //         'is_active' => 1,
        //         'password' => Hash::make($registerUserData['password']),
        //     ]);

        //     $roleNames = collect($registerUserData['roles'])->pluck('name')->toArray();
        //     $user->syncRoles($roleNames);

        //     return response()->json([
        //         'data' => $user
        //     ]);
        // } catch (\Exception $e) {
        //     // Retorna o erro com a mensagem detalhada
        //     return response()->json([
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $transaction = ContainerTransaction::findOrFail($id);

        return response()->json([
            'data' => $transaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user = ContainerTransaction::with(['permissions', 'roles'])->findOrFail($id);

        return response()->json([
            'data' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, User $user)
    // {
    //     //
    //     $data = $request->all();
    //     $user->load('area');

    //     $user->update($data);
    //     if ($request->has('roles')) {
    //         $user->syncRoles($data['roles']);
    //     }
    //     if ($request->has('permission')) {
    //         $user->syncPermissions($data['permission']);
    //     }

    //     return response()->json([
    //         'data' => $user
    //     ]);
    // }

    public function update(Request $request, ContainerTransaction $user)
    {
        try {
            // Validação
            $validatedData = $request->validate([
                'user_full_name' => 'nullable|string|max:255',
                'user_name' => 'nullable|max:255|unique:users,user_name,' . $user->id,
                'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
                'gate_id' => 'nullable',
                'is_active' => 'nullable',
                'roles' => 'nullable|array',
                'roles.*.name' => 'required|string',
            ]);

            // Atualizar o usuário
            $user->update([
                'user_full_name' => $validatedData['user_full_name'] ?? $user->user_full_name,
                'user_name' => $validatedData['user_name'] ?? $user->user_name,
                'is_active' => $validatedData['is_active'] ?? $user->is_active,
                'email' => $validatedData['email'] ?? $user->email,

            ]);

            // Sincronizar roles, se fornecido
            if (isset($validatedData['roles'])) {
                $user->syncRoles($validatedData['roles']);
            }
            if (isset($validatedData['gate_id'])) {
                $user->gate()->updateOrCreate(
                    ['user_id' => $user->id], // Condição para verificar se já existe
                    ['gate_id' => $validatedData['gate_id']] // Dados a serem atualizados ou criados
                );
            }

            return response()->json([
                'data' => $user
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
        $user = ContainerTransaction::findOrFail($id);

        $user->delete();

        return response()->noContent();
    }

}
