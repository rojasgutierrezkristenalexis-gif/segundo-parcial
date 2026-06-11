<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PrivilegioController;
use App\Http\Controllers\DashboardController;

Route::middleware([\Illuminate\Http\Middleware\HandleCors::class])->group(function () {
    
    // ==========================================
    // RUTAS FLUJO POSTULANTES Y PAGOS
    // ==========================================
    Route::post('/postulantes', [PostulanteController::class, 'store']);       // CU11: Crear
    Route::get('/postulantes/{ci}', [PostulanteController::class, 'show']);    // CU14: Buscar por CI
    Route::put('/postulantes/{id}', [PostulanteController::class, 'update']);   // CU12: Modificar
    Route::delete('/postulantes/{id}', [PostulanteController::class, 'destroy']); // CU13: Eliminar

    Route::post('/pagos', [PagoController::class, 'store']);                  // CU10: Pago
    
    // ==========================================
    // RUTAS AUTENTICACIÓN
    // ==========================================
    Route::post('/login', [AuthController::class, 'login']);

    // ==========================================
    // RUTAS ROLES Y PRIVILEGIOS
    // ==========================================
    Route::get('/roles', [RolController::class, 'index']);
    Route::post('/roles', [RolController::class, 'store']);
    Route::put('/roles/{id}', [RolController::class, 'update']);
    Route::delete('/roles/{id}', [RolController::class, 'destroy']);

    Route::get('/privilegios/{rolId}', [PrivilegioController::class, 'show']);
    Route::post('/privilegios', [PrivilegioController::class, 'store']);

    // ==========================================
    // RUTAS DASHBOARD / ESTADÍSTICAS
    // ==========================================
    Route::get('/dashboard', [DashboardController::class, 'getStats']);
});