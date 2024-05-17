<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AseguradorasController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\PolizaController;
use App\Http\Controllers\RenovacionController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\PolizasVencimientoController;
use App\Http\Controllers\UsuarioController;

$abilities = [
    'clientes' => getAbilitiesString('clientes'),
    'aseguradoras' => getAbilitiesString('aseguradoras'),
    'polizas' => getAbilitiesString('polizas'),
    'usuarios' => getAbilitiesString('usuarios'),
    'dashboard' => getAbilitiesString('dashboard'),
    'polizas-vencimiento' => getAbilitiesString('polizas-vencimiento'),
    'pagos' => getAbilitiesString('pagos'),
    'archivos' => getAbilitiesString('archivos'),
];

Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
})->name('home');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () use ($abilities) {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::resource('aseguradoras', AseguradorasController::class)->middleware(['auth:sanctum', "ability:{$abilities['aseguradoras']}"]);
    Route::resource('clientes', ClienteController::class)->middleware(['auth:sanctum', "ability:{$abilities['clientes']}"]);
    Route::resource('polizas', PolizaController::class)->middleware(['auth:sanctum', "ability:{$abilities['polizas']}"]);
    Route::resource('renovacion', RenovacionController::class)->middleware(['auth:sanctum', "ability:{$abilities['clientes']}"]);
    Route::resource('pagos', PagosController::class)->middleware(['auth:sanctum', "ability:{$abilities['pagos']}"]);
    Route::resource('polizas-vencimiento', PolizasVencimientoController::class)->middleware(['auth:sanctum', "ability:{$abilities['polizas-vencimiento']}"]);
    Route::resource('dashboard', DashboardController::class)->middleware(['auth:sanctum', "ability:{$abilities['dashboard']}"]);
    Route::resource('usuarios', UsuarioController::class)->middleware(['auth:sanctum', "ability:{$abilities['usuarios']}"]);
    Route::delete('archivos', [FilesController::class, 'destroy'])->middleware(['auth:sanctum', "ability:{$abilities['archivos']}"]);
});
