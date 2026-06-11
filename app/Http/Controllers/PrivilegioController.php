<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Privilegio;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class PrivilegioController extends Controller
{
    public function show($rolId)
    {
        $rol = Rol::find($rolId);

        if (!$rol) {
            return response()->json([
                'success' => false,
                'message' => 'Rol no encontrado'
            ], 404);
        }

        $activePrivilegeIds = $rol->privilegios->pluck('id')->toArray();
        $allPrivileges = Privilegio::all()->map(function ($priv) use ($activePrivilegeIds) {
            return [
                'id' => $priv->id,
                'nombre' => $priv->nombre,
                'descripcion' => $priv->descripcion,
                'activo' => in_array($priv->id, $activePrivilegeIds),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $allPrivileges
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rol_id' => 'required|exists:rols,id',
            'privilegio_ids' => 'present|array',
            'privilegio_ids.*' => 'exists:privilegios,id',
        ]);

        $rol = Rol::findOrFail($request->rol_id);

        // Safeguard: do not modify Admin role privileges
        if ($rol->nombre === 'Administrador') {
            return response()->json([
                'success' => false,
                'message' => 'No se pueden modificar los privilegios del Administrador Principal.'
            ], 403);
        }

        $rol->privilegios()->sync($request->privilegio_ids);

        // Registrar en Bitácora
        $privilegiosNombres = Privilegio::whereIn('id', $request->privilegio_ids)->pluck('nombre')->toArray();
        Bitacora::registrar(
            'Asignación de privilegios', 
            'Rol: ' . $rol->nombre . ', Privilegios asignados: [' . implode(', ', $privilegiosNombres) . ']'
        );

        return response()->json([
            'success' => true,
            'message' => 'Privilegios actualizados con éxito'
        ]);
    }
}
