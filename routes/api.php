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
use App\Http\Controllers\Api\Mobile\CGatev2\CDMS\GeneralIntegrationController;
use App\Http\Controllers\Api\Mobile\CGatev2\Exception\CGateExceptionController;
use App\Http\Controllers\Api\Mobile\CGatev2\GateTransaction\GateTransactionController;
use App\Http\Controllers\Api\Mobile\CGatev2\GeneralCargoTransaction\GeneralCargoTransactionController;
use App\Http\Controllers\Api\Mobile\CGatev2\ImageUpload\ImageUploadController;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\CGateAppointmentController;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\EmptyInController;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\EmptyOutController;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\ExportFullInController;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\ImportFullOutController;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\N4DocumentsController;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\N4GeneralIntegrationController;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\V2\EmptyInV2Controller;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\V2\EmptyOutV2Controller;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\V2\ExportFullInV2Controller;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\V2\ImportFullOutV2Controller;
use App\Http\Controllers\Api\Mobile\CGatev2\N4\V2\N4GeneralIntegrationV2Controller;
use App\Http\Controllers\Api\Mobile\CGatev2\Precheck\CGatePrecheckController;
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
    Route::get('users',[UserController::class, 'index'])->middleware('role:Super Admin|Admin|Manager|Security|Tally');
    Route::resource('users',UserController::class)->middleware('role:Super Admin|Admin');
    Route::get('getuserbyrole/{roleid}',[UserController::class, 'getUserByRole'])->middleware('role:Super Admin|Admin');
    Route::post('/users/{userId}/roles', [RolesController::class, 'storeRoleToUser'])->name('users.storeRoleUser')->middleware('role:Super Admin|Admin');
    Route::get('/users/{userId}/roles', [RolesController::class, 'addRoleToUser'])->name('users.addRoleUser')->middleware('role:Super Admin|Admin');
    Route::post('/users/{userId}/permissions', [PermissionsController::class, 'storePermissionToUser'])->name('users.storePermissionUser')->middleware('role:Super Admin|Admin');
    Route::get('/users/{userId}/permissions', [PermissionsController::class, 'addPermissionToUser'])->name('users.addPermissionUser')->middleware('role:Super Admin|Admin');




    Route::resource('containertransaction',ContainerTransactionController::class)->middleware('role:Super Admin|Admin|Manager|Security|Tally');
    Route::resource('companies',CompanyController::class)->middleware('role:Super Admin|Admin');
    Route::resource('gates',GateController::class)->middleware('role:Super Admin|Admin');
    Route::get('/applications/manual', [ApplicationController::class, 'application_manual'])->name('gate.application_manual');
    Route::get('/applications/manual/download', [ApplicationController::class, 'download_manual'])->name('application.manual.download');
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

    Route::prefix('cgatev2')->group(function () {
            Route::prefix('transacoes')->group(function () {
                Route::get('lista/', [GateTransactionController::class, 'index'])->name('v1.transactions.index');
                Route::post('salvar', [GateTransactionController::class, 'store'])->name('v1.transactions.store');
                Route::get('detalhes/{id}', [GateTransactionController::class, 'show'])->name('v1.transactions.show');
                Route::post('actualizar/{id}', [GateTransactionController::class, 'update'])->name('v1.transactions.update');
                Route::get('pesquisar/', [GateTransactionController::class, 'search'])->name('v1.transactions.search');
                Route::post('tallyout/{id}', [GateTransactionController::class, 'closeTransaction'])->name('v1.transactions.tallyout');
                Route::post('checktransactions/{id}', [GateTransactionController::class, 'checkTransaction'])->name('v1.transactions.checkTransaction');


                Route::get('jobtransactions', [GateTransactionController::class, 'jobTransaction'])->name('v1.transactions.jobTransaction');


                Route::get('dashboard/', [GateTransactionController::class, 'dashboard'])->name('v1.transactions.dashboard');
                Route::post('update-check-manual/{id}', [GateTransactionController::class, 'updatemanualcheck'])->name('v1.transactions.updatemanualcheck');
                Route::get('dashboarduser/', [GateTransactionController::class, 'dashboarduser'])->name('v1.transactions.dashboarduser');
            });

            Route::prefix('excepcoes')->group(function () {
                Route::get('lista', [CGateExceptionController::class, 'index'])->name('v1.exceptions.index');
                Route::post('salvar', [CGateExceptionController::class, 'store'])->name('v1.exceptions.store');
                Route::get('detalhes/{id}', [CGateExceptionController::class, 'show'])->name('v1.exceptions.show');
                Route::post('actualizar/{id}', [CGateExceptionController::class, 'update'])->name('v1.exceptions.update');
            });

            Route::group(['prefix' => 'images'], function () {
                Route::post('upload', [ImageUploadController::class, 'upload'])->name('v1.images.upload');
            });

            Route::prefix('general_cargo')->group(function () {
                //container appointment
                Route::get('/list_transactions', [GeneralCargoTransactionController::class, 'index'])->name('api.v1.c_gate.general_cargo.list_transactions');
                Route::post('/save_transaction', [GeneralCargoTransactionController::class, 'store'])->name('api.v1.c_gate.general_cargo.save_transaction');
                
                Route::post('/update_transaction/{id}', [GeneralCargoTransactionController::class, 'update'])->name('api.v1.c_gate.general_cargo.update_transaction');

                Route::get('/details_transaction/{id}', [GeneralCargoTransactionController::class, 'show'])->name('api.v1.c_gate.general_cargo.details_transaction');

                Route::get('/list_all_transactions', [GeneralCargoTransactionController::class, 'indexWithoutAuth'])->name('api.v1.c_gate.general_cargo.list_all_transactions');
                Route::get('/list_all_transactionstest', [GeneralCargoTransactionController::class, 'indexWithoutAuthTest'])->name('api.v1.c_gate.general_cargo.list_all_transactionstest');
            });

            Route::prefix('precheck')->group(function () {
                Route::get('/appointment/{number}', [CGatePrecheckController::class, 'appointment']);
                Route::post('/login', [CGatePrecheckController::class, 'login']);
                Route::post('/checkAppointment', [CGatePrecheckController::class, 'checkAppointment']);
                Route::post('/checkAppointmentByContainer', [CGatePrecheckController::class, 'checkAppointmentByContainer']);
                Route::post('/updateAppointmentDataByAppointmentNumber', [CGatePrecheckController::class, 'updateAppointmentDataByAppointmentNumber']);
                Route::post('/updateAppointment', [CGatePrecheckController::class, 'updateAppointment']);
                Route::post('/updateAppointmentDataByContainer', [CGatePrecheckController::class, 'updateAppointmentDataByContainer']);
                Route::post('/getAppointment', [CGatePrecheckController::class, 'getAppointment']);
            });

            Route::prefix('n4')->group(function () {
                Route::post('/checkImpediments', [CGateAppointmentController::class, 'checkImpediments']);
                Route::post('/checkHazardous', [CGateAppointmentController::class, 'checkHazardous']);
            });


            //N4 INTEGRATION

            Route::prefix('n4integration')->group(function () {
                Route::prefix('documents')->group(function () {
                    //return array of delivery notes documents
                    Route::get('/getdoc/{tv_key}', [N4DocumentsController::class, 'get_tvkey_xml_test']);
                    //return array of TID documents
                    Route::get('/gettiddoc/{tv_key}', [N4DocumentsController::class, 'get_tvkey_xml_test_tid']);
                    Route::post('/get/xml', [N4DocumentsController::class, 'get_xml_doc']);
                    Route::post('/send/appointment', [N4DocumentsController::class, 'send_appointment_email']);
                });
                //N4General API Integration
                Route::prefix('general_integration')->group(function () {
                    //container appointment
                    Route::post('/create_container_appointment', [N4GeneralIntegrationController::class, 'create_container_appointment'])->name('api.v1.n4integration.general_integration.create_container_appointment');
                    Route::post('/update_container_appointment', [N4GeneralIntegrationController::class, 'update_container_appointment'])->name('api.v1.n4integration.general_integration.update_container_appointment');
                    //truck visit appointment
                    Route::post('/create_truck_visit_appointment', [N4GeneralIntegrationController::class, 'create_truck_visit_appointment'])->name('api.v1.n4integration.general_integration.create_truck_visit_appointment');
                    Route::post('/update_truck_visit_appointment', [N4GeneralIntegrationController::class, 'update_truck_visit_appointment'])->name('api.v1.n4integration.general_integration.update_truck_visit_appointment');
                    Route::post('/cancel_truck_visit_appointment', [N4GeneralIntegrationController::class, 'cancel_truck_visit_appointment'])->name('api.v1.n4integration.general_integration.cancel_truck_visit_appointment');
                    //truck visit
                    Route::post('/create_truck_visit', [N4GeneralIntegrationController::class, 'create_truck_visit'])->name('api.v1.n4integration.general_integration.create_truck_visit');
                    Route::post('/create_truck_visit_exception', [N4GeneralIntegrationController::class, 'create_truck_visit_exception'])->name('api.v1.n4integration.general_integration.create_truck_visit_exception');
                    Route::post('/update_truck_visit', [N4GeneralIntegrationController::class, 'update_truck_visit'])->name('api.v1.n4integration.general_integration.update_truck_visit');
                    Route::post('/cancel_truck_visit', [N4GeneralIntegrationController::class, 'cancel_truck_visit'])->name('api.v1.n4integration.general_integration.cancel_truck_visit');
                    //pickup appointments
                    Route::post('/pickup_appointments', [N4GeneralIntegrationController::class, 'pickup_appointments'])->name('api.v1.n4integration.general_integration.pickup_appointments');
                    //create driver
                    Route::post('/create_driver', [N4GeneralIntegrationController::class, 'create_driver'])->name('api.v1.n4integration.general_integration.create_driver');
                    Route::post('/get_driver_by_license', [N4GeneralIntegrationController::class, 'get_driver_by_license'])->name('api.v1.n4integration.general_integration.get_driver_by_license');
                    Route::post('/update_seal', [N4GeneralIntegrationController::class, 'update_seal'])->name('api.v1.n4integration.general_integration.update_seal_number_based_on_container');
                    //CrossChecks

                    Route::prefix('v2')->group(function () {
                        //container appointment
                        Route::post('/create_container_appointment', [N4GeneralIntegrationV2Controller::class, 'create_container_appointment'])->name('api.v1.n4integration.general_integration_v2.create_container_appointment');
                        Route::post('/update_container_appointment', [N4GeneralIntegrationV2Controller::class, 'update_container_appointment'])->name('api.v1.n4integration.general_integration_v2.update_container_appointment');
                        //truck visit appointment
                        Route::post('/create_truck_visit_appointment', [N4GeneralIntegrationV2Controller::class, 'create_truck_visit_appointment'])->name('api.v1.n4integration.general_integration_v2.create_truck_visit_appointment');
                        Route::post('/update_truck_visit_appointment', [N4GeneralIntegrationV2Controller::class, 'update_truck_visit_appointment'])->name('api.v1.n4integration.general_integration_v2.update_truck_visit_appointment');
                        Route::post('/cancel_truck_visit_appointment', [N4GeneralIntegrationV2Controller::class, 'cancel_truck_visit_appointment'])->name('api.v1.n4integration.general_integration_v2.cancel_truck_visit_appointment');
                        //truck visit
                        Route::post('/create_truck_visit', [N4GeneralIntegrationV2Controller::class, 'create_truck_visit'])->name('api.v1.n4integration.general_integration_v2.create_truck_visit');
                        Route::post('/create_truck_visit_exception', [N4GeneralIntegrationV2Controller::class, 'create_truck_visit_exception'])->name('api.v1.n4integration.general_integration_v2.create_truck_visit_exception');
                        Route::post('/update_truck_visit', [N4GeneralIntegrationV2Controller::class, 'update_truck_visit'])->name('api.v1.n4integration.general_integration_v2.update_truck_visit');
                        Route::post('/cancel_truck_visit', [N4GeneralIntegrationV2Controller::class, 'cancel_truck_visit'])->name('api.v1.n4integration.general_integration_v2.cancel_truck_visit');
                        //pickup appointments
                        Route::post('/pickup_appointments', [N4GeneralIntegrationV2Controller::class, 'pickup_appointments'])->name('api.v1.n4integration.general_integration_v2.pickup_appointments');
                    });


                });
                //export full in
                Route::prefix('export_full_in')->group(function () {
                    //Gate In Stage
                    Route::post('/gate_in', [ExportFullInController::class, 'gate_in'])->name('api.v1.n4integration.export_full_in.gate_in');
                    //submit transaction
                    Route::post('/submit_transaction', [ExportFullInController::class, 'submit_transaction'])->name('api.v1.n4integration.export_full_in.submit_transaction');
                    //Stage Done
                    Route::post('/stage_done', [ExportFullInController::class, 'stage_done'])->name('api.v1.n4integration.export_full_in.stage_done');
                    //Gate Out
                    Route::post('/gate_out', [ExportFullInController::class, 'gate_out'])->name('api.v1.n4integration.export_full_in.gate_out');

                    Route::prefix('v2')->group(function () {
                         //Gate In Stage
                        Route::post('/gate_in', [ExportFullInV2Controller::class, 'gate_in'])->name('api.v1.n4integration.export_full_in_v2.gate_in');
                        //submit transaction
                        Route::post('/submit_transaction', [ExportFullInV2Controller::class, 'submit_transaction'])->name('api.v1.n4integration.export_full_in_v2.submit_transaction');
                        //Stage Done
                        Route::post('/stage_done', [ExportFullInV2Controller::class, 'stage_done'])->name('api.v1.n4integration.export_full_in_v2.stage_done');
                        //Gate Out
                        Route::post('/gate_out', [ExportFullInV2Controller::class, 'gate_out'])->name('api.v1.n4integration.export_full_in_v2.gate_out');
                    });
                });
                //Empty In
                Route::prefix('empty_in')->group(function () {
                    //Gate In Stage
                    Route::post('/gate_in', [EmptyInController::class, 'gate_in'])->name('api.v1.n4integration.empty_in.gate_in');
                    //submit transaction
                    Route::post('/submit_transaction', [ExportFullInController::class, 'submit_transaction'])->name('api.v1.n4integration.export_full_in.submit_transaction');
                    //Stage Done
                    Route::post('/stage_done', [EmptyInController::class, 'stage_done'])->name('api.v1.n4integration.empty_in.stage_done');
                    //Gate Out
                    Route::post('/gate_out', [EmptyInController::class, 'gate_out'])->name('api.v1.n4integration.empty_in.gate_out');

                    Route::prefix('v2')->group(function () {
                        //Gate In Stage
                        Route::post('/gate_in', [EmptyInV2Controller::class, 'gate_in'])->name('api.v1.n4integration.empty_in_v2.gate_in');
                        //submit transaction
                        Route::post('/submit_transaction', [ExportFullInV2Controller::class, 'submit_transaction'])->name('api.v1.n4integration.export_full_in.submit_transaction');
                        //Stage Done
                        Route::post('/stage_done', [EmptyInV2Controller::class, 'stage_done'])->name('api.v1.n4integration.empty_in_v2.stage_done');
                        //Gate Out
                        Route::post('/gate_out', [EmptyInV2Controller::class, 'gate_out'])->name('api.v1.n4integration.empty_in_v2.gate_out');
                    });
                });
                //Import Full Out
                Route::prefix('import_full_out')->group(function () {
                    //Gate In Stage
                    Route::post('/gate_in', [ImportFullOutController::class, 'gate_in'])->name('api.v1.n4integration.import_full_out.gate_in');
                    //submit transaction
                    Route::post('/submit_transaction', [ExportFullInController::class, 'submit_transaction'])->name('api.v1.n4integration.export_full_in.submit_transaction');
                    //Stage Done
                    Route::post('/stage_done', [ImportFullOutController::class, 'stage_done'])->name('api.v1.n4integration.import_full_out.stage_done');
                    //Gate Out
                    Route::post('/gate_out', [ImportFullOutController::class, 'gate_out'])->name('api.v1.n4integration.import_full_out.gate_out');

                    Route::prefix('v2')->group(function () {
                        Route::post('/gate_in', [ImportFullOutV2Controller::class, 'gate_in'])->name('api.v1.n4integration.import_full_out_v2.gate_in');
                        //submit transaction
                        Route::post('/submit_transaction', [ExportFullInV2Controller::class, 'submit_transaction'])->name('api.v1.n4integration.export_full_in_v2.submit_transaction');
                        //Stage Done
                        Route::post('/stage_done', [ImportFullOutV2Controller::class, 'stage_done'])->name('api.v1.n4integration.import_full_out_v2.stage_done');
                        //Gate Out
                        Route::post('/gate_out', [ImportFullOutV2Controller::class, 'gate_out'])->name('api.v1.n4integration.import_full_out_v2.gate_out');
                    });
                });
                //Empty Out
                Route::prefix('empty_out')->group(function () {
                    //Gate In Stage
                    Route::post('/gate_in', [EmptyOutController::class, 'gate_in'])->name('api.v1.n4integration.empty_out.gate_in');
                    //submit transaction
                    Route::post('/submit_transaction', [ExportFullInController::class, 'submit_transaction'])->name('api.v1.n4integration.export_full_in.submit_transaction');
                    //Stage Done
                    Route::post('/stage_done', [EmptyOutController::class, 'stage_done'])->name('api.v1.n4integration.empty_out.stage_done');
                    //Gate Out
                    Route::post('/gate_out', [EmptyOutController::class, 'gate_out'])->name('api.v1.n4integration.empty_out.gate_out');

                    Route::prefix('v2')->group(function () {
                        //Gate In Stage
                        Route::post('/gate_in', [EmptyOutV2Controller::class, 'gate_in'])->name('api.v1.n4integration.empty_out_v2.gate_in');
                        //submit transaction
                        Route::post('/submit_transaction', [ExportFullInV2Controller::class, 'submit_transaction'])->name('api.v1.n4integration.export_full_in_v2.submit_transaction');
                        //Stage Done
                        Route::post('/stage_done', [EmptyOutV2Controller::class, 'stage_done'])->name('api.v1.n4integration.empty_out_v2.stage_done');
                        //Gate Out
                        Route::post('/gate_out', [EmptyOutV2Controller::class, 'gate_out'])->name('api.v1.n4integration.empty_out_v2.gate_out');
                    });
                });

            });
            //CDMS Integration
            Route::prefix('cdms_integration')->group(function () {
                Route::prefix('appointment')->group(function () {
                    Route::get('/list', [CGateAppointmentController::class, 'appointments'])->name('api.v1.cdms.integration.appointment.list');
                    Route::post('/check_appointment_by_number', [CGateAppointmentController::class, 'check_appointment_by_number'])->name('api.v1.cdms.integration.check_appointment_by_number');
                    Route::post('/check_appointment_by_container_number', [CGateAppointmentController::class, 'check_appointment_by_container_number'])->name('api.v1.cdms.integration.check_appointment_by_container_number');
                    Route::post('/check_appointment_by_license_plate_number', [CGateAppointmentController::class, 'check_appointment_by_license_plate_number'])->name('api.v1.cdms.integration.check_appointment_by_license_plate_number');
                });
                Route::prefix('containers')->group(function() {
                    Route::post('/check_dummy_container_available', [CGateAppointmentController::class, 'check_dummy_container_available'])->name('api.v1.cdms.integration.check_dummy_container_available');
                });
                Route::prefix('precheck')->group(function() {
                    // Route::
                });
                Route::prefix('general')->group(function() {
                    Route::get('/trucking_companies', [GeneralIntegrationController::class, 'trucking_companies'])->name('api.v1.cdms.integration.appointment.list');
                    Route::get('/seals', [GeneralIntegrationController::class, 'seals'])->name('api.v1.cdms.integration.appointment.list');
                    Route::get('/iso_codes/{container_number}', [GeneralIntegrationController::class, 'iso_codes'])->name('api.v1.cdms.integration.appointment.list');
                    Route::get('/container_details/{container_number}', [GeneralIntegrationController::class, 'container_details'])->name('api.v1.cdms.integration.container.details');
                    Route::get('/bill_of_ladings/{billLadingNumber}', [GeneralIntegrationController::class, 'bill_of_ladings'])->name('api.v1.cdms.integration.bill.of.ladings');
                    Route::get('/bookings/{bookingNumber}', [GeneralIntegrationController::class, 'bookings'])->name('api.v1.cdms.integration.bookings');

                    //import bill of ladings CDMS - Import Full Out - bill of lading number
                    Route::post('/import/bill_of_ladings', [GeneralIntegrationController::class, 'cdms_import_bill_of_ladings'])->name('api.v1.cdms.integration.import_bill_of_ladings');
                    //export booking - Export Full In - booking number
                    Route::post('/export/booking', [GeneralIntegrationController::class, 'cdms_stack_order_container'])->name('api.v1.cdms.integration.export_bookings');
                    //Empty In - empty_in_release_order - ero_number - status complete
                    Route::post('/empty_in/release_order', [GeneralIntegrationController::class, 'cdms_empty_in_release_order'])->name('api.v1.cdms.integration.cdms_empty_in_release_order');
                    //Empty Out - empty_out_release_order - edo_number - status complete
                    Route::post('/empty_out/release_order', [GeneralIntegrationController::class, 'cdms_empty_out_release_order'])->name('api.v1.cdms.integration.cdms_empty_out_release_order');

                });

            });



    });

    

    // Route::resource('exceptions',ExceptionController::class);


});