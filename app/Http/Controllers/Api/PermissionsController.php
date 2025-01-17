<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        //
        $searchQuery = request('query');

        $permission = Permission::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%");
            })
            // ->with('province')
            // ->with('employees')
            ->orderBy('id', 'asc')
            ->paginate(20);

        return response()->json([
            'data' => $permission
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
        $data = $request->all();
        $permission = Permission::create($data);

        return response()->json([
            'data' => $permission
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $permission = Permission::findOrFail($id);

        $roles = $permission->roles();
        // $users = $permission->users();
        return response()->json([
            'permission' => $permission,
            'roles' => $roles,
            // 'users' => $users
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $permission = Permission::findOrFail($id);
        $roles = $permission->roles();
        // $users = $permission->users();
        return response()->json([
            'permission' => $permission,
            'roles' => $roles,
            // 'users' => $users
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data = $request->all();
        $permission = Permission::findOrFail($id);
        $permission->update($data);
        return response()->json([
            'data' => $permission
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->noContent();
    }

    public function addPermissionToUser(string $id)
    {
        $user = User::findOrFail($id);
        $permissions = Permission::all();

        $permissionsuser = DB::table('model_has_permissions')->where('model_id', $user->id)->pluck('permission_id')->all();

        return response()->json([
            'user' => $user,
            'permissions' => $permissions,
            'permissionsuser' => $permissionsuser
        ], 200);
    }

    public function storePermissionToUser(string $id, Request $request)
    {
        $request->validate([
            'permission' => 'required',
        ]);

        $data = $request->all();
        $user = User::findOrFail($id);

        $user->syncPermissions($data['permission']);
        return response()->json([
            'data' => $user
        ]);
    }
}
