<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    protected $table = 'privilegios';

    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Relación de muchos a muchos con Rol.
     */
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_privilegio', 'privilegio_id', 'rol_id');
    }
}
