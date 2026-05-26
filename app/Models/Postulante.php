<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    // Campos que permitimos rellenar
    protected $fillable = ['ci', 'nombre', 'apellido', 'celular', 'carrera_id', 'estado'];
}