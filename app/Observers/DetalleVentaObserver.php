<?php

namespace App\Observers;

use App\Models\DetalleVenta;
use App\Models\MovimientoInventario;

class DetalleVentaObserver
{
    /**
     * Al crear un detalle, descuenta el stock automáticamente.
     */
    public function created(DetalleVenta $detalle): void
    {
        $producto   = $detalle->producto;
        $stockAntes = $producto->stock_actual;

        $producto->decrement('stock_actual', $detalle->cantidad);

        MovimientoInventario::create([
            'producto_id'   => $producto->id,
            'user_id'       => $detalle->venta->user_id,
            'tipo'          => 'salida',
            'cantidad'      => $detalle->cantidad,
            'stock_antes'   => $stockAntes,
            'stock_despues' => $producto->fresh()->stock_actual,
            'motivo'        => "Venta #{$detalle->venta_id}",
        ]);
    }

    /**
     * Al anular/eliminar un detalle, restaura el stock.
     */
    public function deleted(DetalleVenta $detalle): void
    {
        $producto   = $detalle->producto;
        $stockAntes = $producto->stock_actual;

        $producto->increment('stock_actual', $detalle->cantidad);

        MovimientoInventario::create([
            'producto_id'   => $producto->id,
            'user_id'       => auth()->id() ?? $detalle->venta->user_id,
            'tipo'          => 'ajuste',
            'cantidad'      => $detalle->cantidad,
            'stock_antes'   => $stockAntes,
            'stock_despues' => $producto->fresh()->stock_actual,
            'motivo'        => "Anulación venta #{$detalle->venta_id}",
        ]);
    }
}
