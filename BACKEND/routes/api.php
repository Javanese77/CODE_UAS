<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KotaController;
use App\Http\Controllers\Api\PropinsiController;
use App\Http\Controllers\Api\BarangController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Using apiResource for consistent RESTful routes
Route::apiResource('kota', KotaController::class);
Route::get('propinsi', [PropinsiController::class, 'index']);
Route::get('/test', function() { return response()->json(['status' => 'ok']); });
Route::apiResource('barang', BarangController::class);