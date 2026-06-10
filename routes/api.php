<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PrivilegioController;

Route::middleware([\Illuminate\Http\Middleware\HandleCors::class])->group(function () {
    
    // ==========================================
    // RUTAS PERSONA 2 (FLUJO POSTULANTES)
    // ==========================================
    Route::post('/postulantes', [PostulanteController::class, 'store']);       // CU11: Crear
    Route::get('/postulantes/{ci}', [PostulanteController::class, 'show']);    // CU14: Buscar por CI
    Route::put('/postulantes/{id}', [PostulanteController::class, 'update']);   // CU12: Modificar
    Route::delete('/postulantes/{id}', [PostulanteController::class, 'destroy']); // CU13: Eliminar

    Route::post('/pagos', [PagoController::class, 'store']);                  // CU10: Pago

    // ==========================================
    // RUTAS ROLES Y PRIVILEGIOS (CU05 & CU06)
    // ==========================================
    Route::get('/roles', [RolController::class, 'index']);
    Route::post('/roles', [RolController::class, 'store']);
    Route::put('/roles/{id}', [RolController::class, 'update']);
    Route::delete('/roles/{id}', [RolController::class, 'destroy']);

    Route::get('/privilegios/{rol_id}', [PrivilegioController::class, 'getPrivilegiosByRol']);
    Route::post('/privilegios', [PrivilegioController::class, 'updatePrivilegios']);
    
});