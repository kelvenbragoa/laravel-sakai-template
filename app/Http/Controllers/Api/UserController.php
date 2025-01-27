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
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|min:8',
                'mobile' => 'required',
                'roles' => 'required|array',
                'roles.*.name' => 'required|string',
            ]);

            $user = User::create([
                'name' => $registerUserData['name'],
                'email' => strtolower($registerUserData['email']),
                'is_active' => 1,
                'mobile' => $registerUserData['mobile'] ?? null,
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
        $user = User::with(['permissions', 'roles'])->findOrFail($id);

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
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'mobile' => 'nullable|string|max:15',
                'roles' => 'nullable|array',
                'roles.*' => 'string',
            ]);

            // Atualizar o usuário
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'mobile' => $validatedData['mobile'] ?? $user->mobile,
            ]);

            // Sincronizar roles, se fornecido
            if (isset($validatedData['roles'])) {
                $user->syncRoles($validatedData['roles']);
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
