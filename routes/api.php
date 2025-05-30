<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Cgatev2Controller;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ContainerTransactionController;
use App\Http\Controllers\Api\GateController;
use App\Http\Controllers\Api\Mobile\MobileContainerTransactionController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\PreCheckController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login',[AuthController::class,'login']);
Route::post('updatepassword',[AuthController::class,'updatepassword'])->middleware('auth:sanctum');
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/users/{userId}/update', [UserController::class, 'updateuser'])->name('users.updateuser');
    Route::post('/users/{userId}/delete', [UserController::class, 'deleteuser'])->name('users.deleteuser');

    Route::post('/companies/{companyId}/update', [CompanyController::class, 'updatecompany'])->name('companies.update');
    Route::post('/companies/{companyId}/delete', [CompanyController::class, 'deletecompany'])->name('companies.delete');


    Route::resource('roles',RolesController::class);
    Route::post('/roles/{roleId}/rolepermission', [RolesController::class, 'storeRolePermission'])->name('roles.storeRolePermission');
    Route::get('/roles/{roleId}/rolepermission', [RolesController::class, 'addRolePermission'])->name('roles.addRolePermission');
    Route::resource('permissions',PermissionsController::class);
    Route::resource('users',UserController::class);
    Route::get('getuserbyrole/{roleid}',[UserController::class, 'getUserByRole']);
    Route::post('/users/{userId}/roles', [RolesController::class, 'storeRoleToUser'])->name('users.storeRoleUser');
    Route::get('/users/{userId}/roles', [RolesController::class, 'addRoleToUser'])->name('users.addRoleUser');
    Route::post('/users/{userId}/permissions', [PermissionsController::class, 'storePermissionToUser'])->name('users.storePermissionUser');
    Route::get('/users/{userId}/permissions', [PermissionsController::class, 'addPermissionToUser'])->name('users.addPermissionUser');




    Route::resource('containertransaction',ContainerTransactionController::class);
    Route::resource('companies',CompanyController::class);
    Route::resource('gates',GateController::class);
    Route::resource('applications',ApplicationController::class);

    Route::get('/gatepermissions', [GateController::class, 'gatepermissions'])->name('gate.gatepermissions');

    




    Route::prefix('container')->group(function () {
        //container appointment
        Route::get('/list_transactions', [MobileContainerTransactionController::class, 'index'])->name('api.v1.c_gate.container.list_transactions');
        Route::post('/save_transaction', [MobileContainerTransactionController::class, 'store'])->name('api.v1.c_gate.container.save_transaction');
        Route::get('/details_transaction/{id}', [MobileContainerTransactionController::class, 'show'])->name('api.v1.c_gate.container.details_transaction');
        Route::post('/update_transaction/{id}', [MobileContainerTransactionController::class, 'update'])->name('api.v1.c_gate.container.update_transaction');
        Route::post('/upload-image', [MobileContainerTransactionController::class, 'uploadimage'])->name('api.v1.c_gate.container.uploadimage');
    });

    Route::prefix('cgate2')->group(function () {
        Route::get('/transaction', [Cgatev2Controller::class, 'transaction'])->name('cgate2.transaction');
    });

    Route::prefix('cdms')->group(function () {
        Route::resource('precheck',PreCheckController::class);
        Route::post('/checkappointment', [PreCheckController::class, 'checkappointment'])->name('cdms.precheck.checkappointment');

    });


});