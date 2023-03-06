<?php

use App\Http\Controllers\DeviceApiController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum','verified')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [ApiAuthController::class, 'register']);
Route::post('login', [ApiAuthController::class, 'login']);
Route::post('logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('devices', [DeviceApiController::class ,'index']);
Route::get('show/{id}', [DeviceApiController::class ,'show']);
Route::Post('create', [DeviceApiController::class ,'store']);
Route::Post('read', [DeviceApiController::class ,'read']);
Route::Post('read1', [DeviceApiController::class ,'read1']);
Route::put('update/{id}', [DeviceApiController::class ,'update']);
Route::delete('delete/{id}', [DeviceApiController::class ,'delete']);
Route::Post('readT',[DeviceApiController::class ,'readTestData']);

