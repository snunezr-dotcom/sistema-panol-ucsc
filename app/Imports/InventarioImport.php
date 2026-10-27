<?php

namespace App\Imports;

use App\Models\Inventario;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InventarioImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Si la fila no tiene SKU se ignora
        if (!isset($row['sku'])) {
            return null;
        }

        return new Inventario([
            'sku'                 => $row['sku'],
            'nombre'              => $row['nombre'] ?? 'Sin nombre',
            'cantidad_disponible' => $row['cantidad_disponible'] ?? 0,
            'ubicacion'           => $row['ubicacion'] ?? null,
        ]);
    }
}