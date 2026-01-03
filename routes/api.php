<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\DeviceApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and assigned the
| "api" middleware group. Build your API endpoints here.
|
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [ApiAuthController::class, 'register']);
Route::post('login', [ApiAuthController::class, 'login']);
Route::post('logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('devices', [DeviceApiController::class, 'index']);
Route::get('show/{id}', [DeviceApiController::class, 'show']);
Route::post('create', [DeviceApiController::class, 'store']);
Route::post('read', [DeviceApiController::class, 'read']);
Route::post('read1', [DeviceApiController::class, 'read1']);
Route::post('readT', [DeviceApiController::class, 'readTestData']);
Route::put('update/{id}', [DeviceApiController::class, 'update']);
Route::delete('delete/{id}', [DeviceApiController::class, 'delete']);
