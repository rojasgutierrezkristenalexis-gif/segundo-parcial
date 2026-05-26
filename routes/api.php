<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\PagoController;

Route::middleware([\Illuminate\Http\Middleware\HandleCors::class])->group(function () {
    
    // ==========================================
    // RUTAS PERSONA 2 (FLUJO POSTULANTES)
    // ==========================================
    Route::post('/postulantes', [PostulanteController::class, 'store']);       // CU11: Crear
    Route::get('/postulantes/{ci}', [PostulanteController::class, 'show']);    // CU14: Buscar por CI
    Route::put('/postulantes/{id}', [PostulanteController::class, 'update']);   // CU12: Modificar
    Route::delete('/postulantes/{id}', [PostulanteController::class, 'destroy']); // CU13: Eliminar

    Route::post('/pagos', [PagoController::class, 'store']);                  // CU10: Pago
    
});