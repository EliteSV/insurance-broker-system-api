<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AseguradorasController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PolizaController;
use App\Http\Controllers\RenovacionController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\PolizasVencimientoController;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::resource('aseguradoras', AseguradorasController::class)->middleware('auth:sanctum');

Route::resource('clientes', ClienteController::class)->middleware('auth:sanctum');

Route::resource('polizas', PolizaController::class)->middleware('auth:sanctum');

Route::resource('renovacion', RenovacionController::class)->middleware('auth:sanctum');

Route::resource('pagos', PagosController::class)->middleware('auth:sanctum');

Route::resource('dashboard', DashboardController::class)->middleware('auth:sanctum');

Route::resource('polizas-vencimiento', PolizasVencimientoController::class)->middleware('auth:sanctum');

Route::resource('usuarios', UsuarioController::class)->middleware('auth:sanctum');
