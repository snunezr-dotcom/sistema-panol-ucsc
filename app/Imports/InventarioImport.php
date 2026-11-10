<?php

namespace App\Imports;

use App\Models\Inventario;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;

class InventarioImport implements ToModel, WithHeadingRow, WithCustomCsvSettings, WithUpserts, WithUpsertColumns
{
    private int $skippedRowsWithoutSku = 0;

    private int $rowsWithInvalidQuantity = 0;

    public function model(array $row)
    {
        $sku = trim((string) ($row['sku'] ?? ''));

        if ($sku === '') {
            $this->skippedRowsWithoutSku++;

            return null;
        }

        $quantity = trim((string) ($row['cantidad'] ?? ''));

        if ($quantity === '' || !ctype_digit($quantity)) {
            $quantity = 0;
            $this->rowsWithInvalidQuantity++;
        }

        return new Inventario([
            'sku'                 => $sku,
            'nombre'              => trim((string) ($row['item_especifico'] ?? '')) ?: 'Sin nombre',
            'cantidad_disponible' => (int) $quantity,
            'ubicacion'           => trim((string) ($row['espacio_fisico'] ?? '')) ?: null,
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'enclosure' => '"',
            'input_encoding' => 'UTF-8',
        ];
    }

    public function uniqueBy(): string
    {
        return 'sku';
    }

    public function upsertColumns(): array
    {
        return ['nombre', 'cantidad_disponible', 'ubicacion', 'updated_at'];
    }

    public function getSkippedRowsWithoutSku(): int
    {
        return $this->skippedRowsWithoutSku;
    }

    public function getRowsWithInvalidQuantity(): int
    {
        return $this->rowsWithInvalidQuantity;
    }
}