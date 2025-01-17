<?php

use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::resource('roles',RolesController::class);;
Route::post('/roles/{roleId}/rolepermission', [RolesController::class, 'storeRolePermission'])->name('roles.storeRolePermission');
Route::get('/roles/{roleId}/rolepermission', [RolesController::class, 'addRolePermission'])->name('roles.addRolePermission');
Route::resource('permissions',PermissionsController::class);
Route::resource('users',UserController::class);
Route::get('getuserbyrole/{roleid}',[UserController::class, 'getUserByRole']);
Route::post('/users/{userId}/roles', [RolesController::class, 'storeRoleToUser'])->name('users.storeRoleUser');
Route::get('/users/{userId}/roles', [RolesController::class, 'addRoleToUser'])->name('users.addRoleUser');
Route::post('/users/{userId}/permissions', [PermissionsController::class, 'storePermissionToUser'])->name('users.storePermissionUser');
Route::get('/users/{userId}/permissions', [PermissionsController::class, 'addPermissionToUser'])->name('users.addPermissionUser');
