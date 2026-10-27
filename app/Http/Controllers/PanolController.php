<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Carrera;

class PanolController extends Controller
{
    public function index()
    {
        //Todos los ítems de la base de datos
        $items = Inventario::all();
        
        $carreras = Carrera::all();

        return view('carrito', compact('items', 'carreras'));
    }
}