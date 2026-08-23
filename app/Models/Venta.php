<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    public const TIPOS_PAGO = [
        'efectivo' => 'Efectivo',
        'yape' => 'Yape',
        'plin' => 'Plin',
        'tarjeta_credito' => 'Tarjeta de Crédito',
        'tarjeta_debito' => 'Tarjeta de Débito',
    ];

    protected $fillable = [
        'user_id',
        'caja_id',
        'total',
        'tipo_pago',
        'fecha_hora',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'fecha_hora' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function estaActiva(): bool
    {
        return $this->estado === 'activa';
    }

    public static function usaPrecioTarjeta(string $tipoPago): bool
    {
        return str_starts_with($tipoPago, 'tarjeta_');
    }

    public function recalcularTotal(): void
    {
        $this->update(['total' => $this->detalles()->sum('subtotal')]);
    }
}
