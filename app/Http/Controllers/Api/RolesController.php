<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index()
    {
        //
        $searchQuery = request('query');

        $role = Role::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%");
            })
            ->with('permissions')
            ->orderBy('name', 'asc')
            ->paginate(50);

        return response()->json([
            'data' => $role
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
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => 'required'
        ]);

        $data = $request->all();
        // dd($data);

        $role = Role::create($data);

        $role->syncPermissions($data['permissions']);

        $role->load('permissions');

        return response()->json([
            'data' => $role
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $role = Role::findOrFail($id);
        $rolepermissions = $role->permissions()->get();
        $userroles = DB::table('model_has_roles')->where('role_id', $role->id)->get();
        return response()->json([
            'role' => $role,
            'rolepermissions' => $rolepermissions,
            'userroles' => $userroles
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $role = Role::findOrFail($id);
        $rolepermissions = $role->permissions()->get();
        $userroles = DB::table('model_has_roles')->where('role_id', $role->id)->get();
        return response()->json([
            'role' => $role,
            'rolepermissions' => $rolepermissions,
            'userroles' => $userroles
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => 'required'
        ]);
        $data = $request->all();
        $role = Role::findOrFail($id);
        $role->update($data);
        // $collection = collect($data['permissions']);

        // $names = $collection->pluck('name');
        // dd($names);
        $role->syncPermissions($data['permissions']);
        $role->load('permissions');
        return response()->json([
            'data' => $role
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->noContent();
    }

    public function storeRolePermission(string $id, Request $request)
    {
        $request->validate([
            'permission' => 'required',
        ]);

        $data = $request->all();
        $role = Role::findOrFail($id);

        $role->syncPermissions($data['permission']);

        return response()->json([
            'data' => $role
        ]);
    }

    public function storeRoleToUser(string $id, Request $request)
    {
        $request->validate([
            'role' => 'required',
        ]);

        $data = $request->all();
        $user = User::findOrFail($id);

        $user->syncRoles($data['role']);

        return response()->json([
            'message' => 'Permissions Assigned Successfully'
        ]);
    }
}
