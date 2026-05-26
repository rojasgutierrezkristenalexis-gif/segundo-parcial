<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
    // CU10: Registrar pago de postulante
    public function store(Request $request)
    {
        // 1. Validar los datos del pago
        $validador = Validator::make($request->all(), [
            'postulante_id'   => 'required|integer',
            'monto'           => 'required|numeric|min:0',
            'nro_transaccion' => 'required|string|max:30|unique:pagos,nro_transaccion',
        ]);

        if ($validador->fails()) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => $validador->errors()->first()
            ], 422);
        }

        // 2. Crear el registro del pago en PostgreSQL
        $pago = Pago::create([
            'postulante_id'   => $request->postulante_id,
            'monto'           => $request->monto,
            'nro_transaccion' => $request->nro_transaccion,
            'estado'          => 'confirmado' 
        ]);

        // 3. Respuesta exitosa pactada
        return response()->json([
            'success' => true,
            'data'    => $pago,
            'message' => 'Operación exitosa'
        ], 200);
    }
}