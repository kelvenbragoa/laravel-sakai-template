<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //
        $searchQuery = request('query');
        $user = User::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('user_full_name', 'like', "%{$searchQuery}%")->orWhere('user_name', 'like', "%{$searchQuery}%");
            })
            ->with(['permissions', 'roles','gate'])
            ->orderBy('user_full_name', 'asc')
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
    //     $user = User::create([
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
        try {
            $registerUserData = $request->validate([
                'user_full_name' => 'required|string',
                'user_name' => 'required|string|unique:users',
                'email' => 'nullable|string|email|unique:users',
                'password' => 'required|min:8',
                'roles' => 'required|array',
                'company_id' => 'nullable|string',
                'roles.*.name' => 'required|string',
            ]);


            $user = User::create([
                'user_full_name' => $registerUserData['user_full_name'],
                'company_id' => $registerUserData['company_id'] ?? null,
                'user_name' => $registerUserData['user_name'],
                'email' => $registerUserData['email'] ? strtolower($registerUserData['email']) : null,
                'is_active' => 1,
                'password' => Hash::make($registerUserData['password']),
            ]);

            $roleNames = collect($registerUserData['roles'])->pluck('name')->toArray();
            $user->syncRoles($roleNames);

            return response()->json([
                'data' => $user
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
        $user = User::with(['permissions', 'roles','gate'])->findOrFail($id);

        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user = User::with(['permissions', 'roles'])->findOrFail($id);

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

    public function update(Request $request, User $user)
    {
        try {
            // Validação
            $validatedData = $request->validate([
                'user_full_name' => 'nullable|string|max:255',
                'user_name' => 'nullable|max:255|unique:users,user_name,' . $user->id,
                'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
                'gate_id' => 'nullable',
                'company_id' => 'nullable',
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
                'company_id' => $validatedData['company_id'] ?? $user->company_id,


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
        $user = User::findOrFail($id);

        $user->delete();

        return response()->noContent();
    }

    public function getUserByRole($roleId)
    {

        $id = DB::table('model_has_roles')->where('role_id', $roleId)->get()->pluck('model_id');

        $users = User::whereIn('id', $id)->get();

        $filteredUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'email' => $user->email,
            ];
        });

        return response()->json([
            'data' => $filteredUsers
        ]);
    }
}
