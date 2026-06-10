<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rols';

    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Relación de muchos a muchos con Privilegio.
     */
    public function privilegios()
    {
        return $this->belongsToMany(Privilegio::class, 'rol_privilegio', 'rol_id', 'privilegio_id');
    }

    /**
     * Determina si este rol es el Administrador Principal.
     */
    public function isAdminPrincipal()
    {
        return in_array(strtolower($this->nombre), ['admin', 'administrador', 'administrador principal']);
    }

    /**
     * Regla de negocio crítica: Valida que no se le quiten privilegios al Administrador Principal.
     */
    public static function validarModificacionPrivilegios($rolId, $privilegioIds)
    {
        $rol = self::findOrFail($rolId);
        if ($rol->isAdminPrincipal()) {
            $totalPrivilegios = Privilegio::count();
            // Si intenta asociar menos privilegios que el total, significa que se le están quitando permisos
            if (count($privilegioIds) < $totalPrivilegios) {
                return false;
            }
        }
        return true;
    }

    /**
     * Regla de negocio crítica: Validación en el Modelo antes de guardar.
     */
    protected static function booted()
    {
        static::saving(function ($rol) {
            $query = static::where('nombre', $rol->nombre);
            if ($rol->exists) {
                $query->where('id', '!=', $rol->id);
            }
            if ($query->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'nombre' => 'El nombre del rol ya está duplicado (Validación desde el Modelo).'
                ]);
            }
        });
    }
}
