<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Privilegio;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrivilegioController extends Controller
{
    /**
     * Obtiene todos los privilegios e indica cuáles están activos para el rol dado.
     */
    public function getPrivilegiosByRol($rol_id)
    {
        $rol = Rol::find($rol_id);

        if (!$rol) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'Rol no encontrado'
            ], 404);
        }

        $todos = Privilegio::all();
        $asignadosIds = $rol->privilegios()->pluck('privilegios.id')->toArray();

        $matriz = $todos->map(function ($privilegio) use ($asignadosIds) {
            return [
                'id'          => $privilegio->id,
                'nombre'      => $privilegio->nombre,
                'descripcion' => $privilegio->descripcion,
                'activo'      => in_array($privilegio->id, $asignadosIds)
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $matriz,
            'message' => 'Privilegios del rol cargados con éxito'
        ], 200);
    }

    /**
     * Actualiza la lista de privilegios asociados a un rol.
     */
    public function updatePrivilegios(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'rol_id'           => 'required|integer|exists:rols,id',
            'privilegio_ids'   => 'present|array',
            'privilegio_ids.*' => 'integer|exists:privilegios,id'
        ]);

        if ($validador->fails()) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => $validador->errors()->first()
            ], 422);
        }

        $rolId = $request->rol_id;
        $privilegioIds = $request->privilegio_ids;

        // Regla de negocio crítica: validar que no se le quiten los privilegios al Administrador Principal
        if (!Rol::validarModificacionPrivilegios($rolId, $privilegioIds)) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'Regla Crítica: No se pueden quitar privilegios al Rol Administrador Principal para mantener un superusuario en el sistema.'
            ], 422);
        }

        $rol = Rol::find($rolId);
        $rol->privilegios()->sync($privilegioIds);

        Bitacora::registrar('ASIGNAR_PRIVILEGIOS');

        return response()->json([
            'success' => true,
            'data'    => null,
            'message' => 'Privilegios actualizados correctamente para el rol ' . $rol->nombre
        ], 200);
    }
}
