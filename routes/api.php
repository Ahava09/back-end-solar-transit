<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\GpsCoordinateController;
use App\Http\Controllers\UserController;


Route::get('/users/{id}', [UserController::class, 'index']);
Route::get('/users/', [UserController::class, 'tracking']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/gps-coordinates/{id}', [GpsCoordinateController::class, 'syncGpsPerson']);
Route::post('/gps-coordinates/', [GpsCoordinateController::class, 'store']);
Route::get('/sync-users', [UserController::class, 'syncPersons']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
