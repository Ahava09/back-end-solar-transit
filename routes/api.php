<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\GpsCoordinateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GeocodingController;
use App\Http\Middleware\JwtMiddleware;


// Route::middleware(['jwt.auth'])->get('/users/{id}', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'index'])->middleware(JwtMiddleware::class);
Route::get('/users/', [UserController::class, 'tracking'])->middleware(JwtMiddleware::class);
Route::get('/users', [UserController::class, 'getAll'])->middleware(JwtMiddleware::class);
Route::post('/login', [UserController::class, 'login']);
Route::get('/gps-coordinates/{id}', [GpsCoordinateController::class, 'syncGpsPerson']);
Route::post('/gps-coordinates/', [GpsCoordinateController::class, 'store'])->middleware(JwtMiddleware::class);
Route::get('/sync-users', [UserController::class, 'syncPersons']);
Route::post('/geocoding', [GeocodingController::class, 'getCoordinates']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
