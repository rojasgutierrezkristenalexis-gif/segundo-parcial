<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = ['postulante_id', 'monto', 'nro_transaccion', 'estado'];
}