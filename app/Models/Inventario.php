<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    // Se le da permiso a estas columnas masivamente
    protected $fillable = [
        'sku', 
        'nombre', 
        'cantidad_disponible', 
        'ubicacion'
    ];
}