<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    // public function login(Request $request)
    // {
    //     $loginUserData = $request->validate([
    //         'user_name' => 'required|string',
    //         'password' => 'required|min:8'
    //     ]);


    //         $user = User::where('user_name', strtolower($loginUserData['user_name']))->with('gate.gate_name')->with('company')->first();
    //         if($user){
    //         $token = $user->createToken($user->user_name . '-AuthToken')->plainTextToken;

    //         $userData = $user->toArray();
    //         $userData['roles'] = $user->roles->map(function ($role) {
    //             return [
    //                 'id' => $role->id,
    //                 'name' => $role->name,
    //             ];
    //         });
    //         $userData['permission'] = $user->getAllPermissions()->pluck('name');


    //         return response()->json([
    //             'user' => $userData,
    //             'access_token' => $token,
    //         ],200);
    //     }
           

    //     return response()->json([
    //         'message' => 'Usuario/Password incorrectos'
    //     ], 401);
    // }
    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'user_name' => 'required|string',
            'password' => 'required|min:8'
        ]);

        // Buscar usuário pelo nome de usuário (convertido para minúsculas)
        // $user = User::where('user_name', strtolower($loginUserData['user_name']))
        //     ->with([
        //         'gate.gate_name', 
        //         'company', 
        //         'applications.application_name',
        //         'applications.userApplicationPermissions.permission',
        //         ])
        //     ->first();
        $user = User::where('user_name', strtolower($loginUserData['user_name']))
            ->with([
                'gate.gate_name', 
                'company', 
                // 'applications.application_name',
                ])
            ->first();
        
        $userId = $user->id ?? null;

        
        

        if ($user && Hash::check($loginUserData['password'], $user->password)) {
            $user->load([
                'applications' => function ($query) use ($userId) {
                    $query->with(['application_name',
                        'userApplicationPermissions' => function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        },
                        'userApplicationPermissions.permission'
                    ]);
                }
            ]);
            // Criar token
            $token = $user->createToken($user->user_name . '-AuthToken')->plainTextToken;

            // Obter dados do usuário
            $userData = $user->toArray();
            $userData['roles'] = $user->roles->map(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ]);
            $userData['permissions'] = $user->getAllPermissions()->pluck('name');

            return response()->json([
                'user' => $userData,
                'access_token' => $token,
            ], 200);
        }

        return response()->json([
            'message' => 'Usuário ou senha incorretos'
        ], 401);
    }

    public function updatepassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);


        #Match The Old Password
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return response()->json([
                "message" => "Old Password Doesn't match!"
            ], 404);
        }


        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password),
            'password_changed_at ' => Carbon::now()
        ]);

        return response()->json([
            "data" => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "message" => "logged out"
        ],200);
    }
}
