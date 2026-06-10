<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolController extends Controller
{
    /**
     * Listar todos los roles.
     */
    public function index()
    {
        $roles = Rol::all();
        return response()->json([
            'success' => true,
            'data'    => $roles,
            'message' => 'Roles listados con éxito'
        ], 200);
    }

    /**
     * Crear un nuevo rol.
     */
    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'nombre'      => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:250',
        ]);

        if ($validador->fails()) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => $validador->errors()->first()
            ], 422);
        }

        try {
            $rol = Rol::create([
                'nombre'      => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);

            Bitacora::registrar('GESTION_ROLES');

            return response()->json([
                'success' => true,
                'data'    => $rol,
                'message' => 'Rol creado correctamente'
            ], 201);
        } catch (\Exception $e) {
            // Maneja excepciones de validación del modelo
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Modificar un rol existente.
     */
    public function update(Request $request, $id)
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'Rol no encontrado'
            ], 404);
        }

        $validador = Validator::make($request->all(), [
            'nombre'      => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:250',
        ]);

        if ($validador->fails()) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => $validador->errors()->first()
            ], 422);
        }

        try {
            $rol->update([
                'nombre'      => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);

            Bitacora::registrar('GESTION_ROLES');

            return response()->json([
                'success' => true,
                'data'    => $rol,
                'message' => 'Rol modificado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Maneja excepciones de validación del modelo
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Eliminar un rol.
     */
    public function destroy($id)
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'Rol no encontrado'
            ], 404);
        }

        // Regla de negocio crítica: no eliminar el Administrador Principal
        if ($rol->isAdminPrincipal()) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'No se puede eliminar el Rol Administrador Principal.'
            ], 422);
        }

        $rol->delete();

        Bitacora::registrar('GESTION_ROLES');

        return response()->json([
            'success' => true,
            'data'    => null,
            'message' => 'Rol eliminado correctamente'
        ], 200);
    }
}
