<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Pago;
use App\Models\Carrera;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getStats()
    {
        $totalPostulantes = Postulante::count();
        $totalPagos = Pago::count();
        $totalRecaudado = Pago::where('estado', 'confirmado')->sum('monto');

        // Postulantes por estado
        $estados = Postulante::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get()
            ->pluck('total', 'estado')
            ->toArray();

        $estadosStats = [
            'pendiente' => $estados['pendiente'] ?? 0,
            'aprobado' => $estados['aprobado'] ?? 0,
            'reprobado' => $estados['reprobado'] ?? 0,
        ];

        // Carreras y su avance de cupos
        $carreras = Carrera::all()->map(function ($c) {
            $postulantesCount = Postulante::where('carrera_id', $c->id)->count();
            return [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'cupo' => $c->cupo,
                'postulantes_count' => $postulantesCount,
                'disponibles' => max(0, $c->cupo - $postulantesCount)
            ];
        });

        // Cargar registros de Bitácora reales desde PostgreSQL
        $actividadesRecientes = Bitacora::orderBy('id', 'desc')
            ->take(8)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'usuario' => $log->usuario,
                    'accion' => $log->accion,
                    'detalle' => $log->detalle,
                    'fecha' => $log->created_at ? $log->created_at->toDateTimeString() : now()->toDateTimeString()
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_postulantes' => $totalPostulantes,
                'total_pagos' => $totalPagos,
                'total_recaudado' => floatval($totalRecaudado),
                'postulantes_por_estado' => $estadosStats,
                'carreras' => $carreras,
                'actividades_recientes' => $actividadesRecientes
            ]
        ]);
    }
}
