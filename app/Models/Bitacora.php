<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'bitacoras';

    protected $fillable = ['usuario_id', 'ip', 'accion'];

    /**
     * Registra una acción en la bitácora de auditoría.
     */
    public static function registrar($accion)
    {
        self::create([
            'usuario_id' => auth()->id() ?? null, // Puede ser null ya que aún no hay login obligatorio
            'ip'         => request()->ip() ?? '127.0.0.1',
            'accion'     => $accion
        ]);
    }
}
