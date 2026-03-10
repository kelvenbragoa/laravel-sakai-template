<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Cgatev2Controller;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ContainerTransactionController;
use App\Http\Controllers\Api\ErrorLogsController;
use App\Http\Controllers\Api\ExceptionController;
use App\Http\Controllers\Api\GateController;
use App\Http\Controllers\Api\Mobile\CfmSacController;
use App\Http\Controllers\Api\Mobile\MobileContainerTransactionController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\PreCheckController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\TerminalDashboardController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login',[AuthController::class,'login']);
Route::post('updatepassword',[AuthController::class,'updatepassword'])->middleware('auth:sanctum');
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::get('/me',[AuthController::class,'me'])->middleware('auth:sanctum');


Route::resource('errorlogs',ErrorLogsController::class);

Route::prefix('cfmsac')->group(function () {
        Route::post('/crosscheck', [CfmSacController::class, 'crosscheck'])->name('cfmsac.crosscheck');
});

Route::prefix('precheck')->group(function () {
        Route::get('/', [PreCheckController::class, 'index'])->name('cdms.precheck.index');
        Route::post('/save_transaction', [PreCheckController::class, 'savetransaction'])->name('cdms.precheck.savetransaction');
        Route::get('/oldprecheck', [PreCheckController::class, 'oldprecheck']);
        Route::post('/uploadimage', [PreCheckController::class, 'uploadprecheckimage']);     
});

Route::prefix('n4')->group(function () {
    Route::post('/tallyin/export', [MobileContainerTransactionController::class, 'tallyInForExport'])->name('api.v1.c_gate.n4.tallyinexport');
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/users/{userId}/update', [UserController::class, 'updateuser'])->name('users.updateuser')->middleware('role:Super Admin|Admin|Manager|Security|Tally');
    Route::post('/users/{userId}/delete', [UserController::class, 'deleteuser'])->name('users.deleteuser')->middleware('role:Super Admin|Admin');

    Route::post('/companies/{companyId}/update', [CompanyController::class, 'updatecompany'])->name('companies.update')->middleware('role:Super Admin|Admin');
    Route::post('/companies/{companyId}/delete', [CompanyController::class, 'deletecompany'])->name('companies.delete')->middleware('role:Super Admin|Admin');


    Route::resource('roles',RolesController::class)->middleware('role:Super Admin|Admin');
    Route::post('/roles/{roleId}/rolepermission', [RolesController::class, 'storeRolePermission'])->name('roles.storeRolePermission')->middleware('role:Super Admin|Admin');
    Route::get('/roles/{roleId}/rolepermission', [RolesController::class, 'addRolePermission'])->name('roles.addRolePermission')->middleware('role:Super Admin|Admin');
    Route::resource('permissions',PermissionsController::class)->middleware('role:Super Admin|Admin');
    Route::resource('users',UserController::class)->middleware('role:Super Admin|Admin');
    Route::get('getuserbyrole/{roleid}',[UserController::class, 'getUserByRole'])->middleware('role:Super Admin|Admin');
    Route::post('/users/{userId}/roles', [RolesController::class, 'storeRoleToUser'])->name('users.storeRoleUser')->middleware('role:Super Admin|Admin');
    Route::get('/users/{userId}/roles', [RolesController::class, 'addRoleToUser'])->name('users.addRoleUser')->middleware('role:Super Admin|Admin');
    Route::post('/users/{userId}/permissions', [PermissionsController::class, 'storePermissionToUser'])->name('users.storePermissionUser')->middleware('role:Super Admin|Admin');
    Route::get('/users/{userId}/permissions', [PermissionsController::class, 'addPermissionToUser'])->name('users.addPermissionUser')->middleware('role:Super Admin|Admin');




    Route::resource('containertransaction',ContainerTransactionController::class)->middleware('role:Super Admin|Admin|Manager|Security|Tally');
    Route::resource('companies',CompanyController::class)->middleware('role:Super Admin|Admin');
    Route::resource('gates',GateController::class)->middleware('role:Super Admin|Admin');
    Route::resource('applications',ApplicationController::class)->middleware('role:Super Admin|Admin');

    Route::get('/gatepermissions', [GateController::class, 'gatepermissions'])->name('gate.gatepermissions')->middleware('role:Super Admin|Admin');

    




    Route::prefix('container')->group(function () {
        //container appointment
        Route::get('/list_transactions', [MobileContainerTransactionController::class, 'index'])->name('api.v1.c_gate.container.list_transactions')->middleware('role:Super Admin|Admin|Manager|Security|Tally');
        Route::post('/save_transaction', [MobileContainerTransactionController::class, 'store'])->name('api.v1.c_gate.container.save_transaction')->middleware('role:Super Admin|Admin|Manager|Security|Tally');
        Route::get('/details_transaction/{id}', [MobileContainerTransactionController::class, 'show'])->name('api.v1.c_gate.container.details_transaction')->middleware('role:Super Admin|Admin|Manager|Security|Tally');
        Route::post('/update_transaction/{id}', [MobileContainerTransactionController::class, 'update'])->name('api.v1.c_gate.container.update_transaction')->middleware('role:Super Admin|Admin|Manager|Security|Tally');
        Route::post('/upload-image', [MobileContainerTransactionController::class, 'uploadimage'])->name('api.v1.c_gate.container.uploadimage')->middleware('role:Super Admin|Admin|Manager|Security|Tally');
        Route::post('/upload-image-cgatev2', [MobileContainerTransactionController::class, 'uploadv2image'])->name('api.v1.c_gate_v2.container.uploadv2image')->middleware('role:Super Admin|Admin|Manager|Security|Tally');
    });



    Route::prefix('cgate2')->group(function () {
        Route::get('/transaction', [Cgatev2Controller::class, 'transaction'])->name('cgate2.transaction')->middleware('role:Super Admin|Admin|Manager|Security|Tally');

        Route::get('/excepcoes/lista', [ExceptionController::class, 'listar'])->name('cgate2.listar')->middleware('role:Super Admin|Admin|Manager|Security|Tally');

        Route::post('/excepcoes/actualizar/{id}', [ExceptionController::class, 'actualizar'])->name('cgate2.actualizar')->middleware('role:Super Admin|Admin|Manager|Security|Tally');

        Route::get('/dashboard', [Cgatev2Controller::class, 'dashboard'])->name('cgate2.dashboard')->middleware('role:Super Admin|Admin|Manager|Security|Tally');

        Route::post('/update-check-manual/{id}', [Cgatev2Controller::class, 'changemanualcheck'])->name('cgate2.changemanualcheck')->middleware('role:Super Admin|Admin|Manager|Security|Tally');

        Route::get('/userdashboard', [TerminalDashboardController::class, 'dashboard'])->name('cgate2.user.dashboard')->middleware('role:Super Admin|Admin|Manager|Security|Tally');

    });

    Route::prefix('cdms')->group(function () {
        Route::resource('precheck',PreCheckController::class)->middleware('role:Super Admin|Admin|Manager|Security|Tally');
        Route::post('/checkappointment', [PreCheckController::class, 'checkappointment'])->name('cdms.precheck.checkappointment')->middleware('role:Super Admin|Admin|Manager|Security|Tally');
        Route::get('/listpreadvices', [PreCheckController::class, 'listpreadvices'])->name('cdms.precheck.listpreadvices')->middleware('role:Super Admin|Admin|Manager|Security|Tally');
    });

    

    // Route::resource('exceptions',ExceptionController::class);


});