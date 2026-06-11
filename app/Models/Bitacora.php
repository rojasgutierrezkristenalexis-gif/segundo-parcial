<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacoras';

    protected $fillable = ['usuario', 'accion', 'detalle'];

    /**
     * Método auxiliar para registrar entradas de bitácora
     */
    public static function registrar($accion, $detalle)
    {
        $usuario = 'invitado';
        if (auth('sanctum')->check()) {
            $usuario = auth('sanctum')->user()->email;
        } elseif (request()->has('email')) {
            $usuario = request()->input('email');
        }

        self::create([
            'usuario' => $usuario,
            'accion' => $accion,
            'detalle' => $detalle
        ]);
    }
}
