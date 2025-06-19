<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//Last route to overlap every route hitting laravel route

Route::get('{view}', ApplicationController::class)->where('view','(.*)');

Route::options('/{any}', function () {
    return response()->json([], 204);
})->where('any', '.*');