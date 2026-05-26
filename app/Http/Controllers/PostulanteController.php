<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostulanteController extends Controller
{
    // CU11: Registrar Postulante
    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'ci'         => 'required|string|max:15|unique:postulantes,ci',
            'nombre'     => 'required|string|max:50',
            'apellido'   => 'required|string|max:50',
            'celular'    => 'nullable|string|max:15',
            'carrera_id' => 'required|integer',
        ]);

        if ($validador->fails()) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => $validador->errors()->first()
            ], 422);
        }

        $postulante = Postulante::create([
            'ci'         => $request->ci,
            'nombre'     => $request->nombre,
            'apellido'   => $request->apellido,
            'celular'    => $request->celular,
            'carrera_id' => $request->carrera_id,
            'estado'     => 'pendiente' // Estado inicial pactado
        ]);

        return response()->json([
            'success' => true,
            'data'    => $postulante,
            'message' => '¡Postulante registrado con éxito en PostgreSQL!'
        ], 200);
    }

    // CU14: Buscar Postulante por CI
    public function show($ci)
    {
        $postulante = Postulante::where('ci', $ci)->first();

        if (!$postulante) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'Postulante no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $postulante,
            'message' => 'Operación exitosa'
        ], 200);
    }

    // CU12: Modificar Postulante
    public function update(Request $request, $id)
    {
        $postulante = Postulante::find($id);

        if (!$postulante) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'Postulante no encontrado'
            ], 404);
        }

        // Validar ignorando el CI del postulante actual para que deje guardar
        $validador = Validator::make($request->all(), [
            'ci'         => 'required|string|max:15|unique:postulantes,ci,' . $id,
            'nombre'     => 'required|string|max:50',
            'apellido'   => 'required|string|max:50',
            'celular'    => 'nullable|string|max:15',
            'carrera_id' => 'required|integer',
            'estado'     => 'required|string'
        ]);

        if ($validador->fails()) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => $validador->errors()->first()
            ], 422);
        }

        $postulante->update($request->all());

        return response()->json([
            'success' => true,
            'data'    => $postulante,
            'message' => 'Datos modificados correctamente'
        ], 200);
    }

    // CU13: Eliminar Postulante
    public function destroy($id)
    {
        $postulante = Postulante::find($id);

        if (!$postulante) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'Postulante no encontrado'
            ], 404);
        }

        $postulante->delete();

        return response()->json([
            'success' => true,
            'data'    => null,
            'message' => 'Postulante eliminado correctamente'
        ], 200);
    }
}