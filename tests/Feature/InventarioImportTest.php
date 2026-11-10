<?php

namespace Tests\Feature;

use App\Models\Inventario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventarioImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_csv_inventory_is_imported_and_reimport_updates_existing_skus(): void
    {
        $this->get('/importar-excel')
            ->assertOk()
            ->assertSeeText('1 fila sin SKU omitida y 6 cantidades no numéricas asignadas a 0.');

        $this->assertSame(658, Inventario::count());
        $this->assertDatabaseHas('inventarios', [
            'sku' => 'Pañol-0001',
            'nombre' => 'motor electrico monofasico imatesa mod.cdf 802-4',
            'cantidad_disponible' => 2,
            'ubicacion' => 'Pañol, Estante AP',
        ]);
        $this->assertDatabaseHas('inventarios', [
            'sku' => 'Pañol-0146',
            'cantidad_disponible' => 0,
        ]);

        $this->get('/importar-excel')->assertOk();

        $this->assertSame(658, Inventario::count());
    }
}
