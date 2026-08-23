<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'categoria',
        'stock_actual',
        'stock_minimo',
        'precio_venta',
        'precio_tarjeta',
        'precio_costo',
        'unidades_por_caja',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio_venta'     => 'decimal:2',
            'precio_tarjeta'   => 'decimal:2',
            'precio_costo'     => 'decimal:2',
            'activo'           => 'boolean',
        ];
    }

    public function detalleVentas(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function movimientosInventario(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function tieneStockBajo(): bool
    {
        return $this->stock_actual <= $this->stock_minimo;
    }

    public function ingresarCaja(int $cajas, User $user): void
    {
        $unidades = $cajas * $this->unidades_por_caja;
        $stockAntes = $this->stock_actual;
        $this->increment('stock_actual', $unidades);

        MovimientoInventario::create([
            'producto_id'   => $this->id,
            'user_id'       => $user->id,
            'tipo'          => 'entrada',
            'cantidad'      => $unidades,
            'stock_antes'   => $stockAntes,
            'stock_despues' => $this->stock_actual,
            'motivo'        => "Ingreso de {$cajas} caja(s)",
        ]);
    }

    public function descontarStock(int $cantidad, User $user, string $motivo = 'Venta'): void
    {
        $stockAntes = $this->stock_actual;
        $this->decrement('stock_actual', $cantidad);

        MovimientoInventario::create([
            'producto_id'   => $this->id,
            'user_id'       => $user->id,
            'tipo'          => 'salida',
            'cantidad'      => $cantidad,
            'stock_antes'   => $stockAntes,
            'stock_despues' => $this->stock_actual,
            'motivo'        => $motivo,
        ]);
    }
}
