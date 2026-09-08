<?php

namespace Tests\Feature;

use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReporteVentasExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_exporta_todas_las_ventas_del_periodo_en_csv(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        $producto = Producto::create([
            'nombre'          => 'Coca Cola 600ML',
            'categoria'       => 'sin_alcohol',
            'stock_actual'    => 100,
            'precio_venta'    => 5.00,
            'precio_tarjeta'  => 6.00,
            'stock_minimo'    => 5,
            'unidades_por_caja' => 1,
            'activo'          => true,
        ]);

        $venta = Venta::create([
            'user_id'    => $admin->id,
            'caja_id'    => null,
            'total'      => 10.00,
            'tipo_pago'  => 'efectivo',
            'fecha_hora' => now(),
            'estado'     => 'activa',
        ]);

        DetalleVenta::create([
            'venta_id'       => $venta->id,
            'producto_id'    => $producto->id,
            'cantidad'       => 2,
            'precio_unitario' => 5.00,
            'subtotal'       => 10.00,
        ]);

        $response = $this->actingAs($admin)->get(
            route('admin.reportes.ventas.exportar', ['desde' => today()->toDateString(), 'hasta' => today()->toDateString()])
        );

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertStringContainsString('2 × Coca Cola 600ML', $response->streamedContent());
        $this->assertStringContainsString('Efectivo', $response->streamedContent());
        $this->assertStringContainsString('TOTAL', $response->streamedContent());
    }

    public function test_admin_sin_ventas_obtiene_csv_con_encabezado_y_total(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.reportes.ventas.exportar'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertStringContainsString('N°,Fecha/Hora', $response->streamedContent());
        $this->assertStringContainsString('TOTAL', $response->streamedContent());
    }
}