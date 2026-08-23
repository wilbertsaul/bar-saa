<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caja extends Model
{
    protected $fillable = [
        'user_id',
        'fecha_trabajo',
        'hora_apertura',
        'monto_inicial',
        'ventas',
        'gastos',
        'mermas',
        'hora_cierre',
        'monto_arqueo',
        'diferencia',
        'estado',
        'cerrada_por_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha_trabajo' => 'date',
            'hora_apertura' => 'datetime:H:i',
            'hora_cierre' => 'datetime:H:i',
            'monto_inicial' => 'decimal:2',
            'ventas' => 'decimal:2',
            'gastos' => 'decimal:2',
            'mermas' => 'decimal:2',
            'monto_arqueo' => 'decimal:2',
            'diferencia' => 'decimal:2',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cerradaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cerrada_por_id');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function gastos(): HasMany
    {
        return $this->hasMany(CajaGasto::class);
    }

    public function mermas(): HasMany
    {
        return $this->hasMany(CajaMerma::class);
    }

    public function scopeAbierta($query)
    {
        return $query->where('estado', 'abierta');
    }

    public function scopeCerrada($query)
    {
        return $query->where('estado', 'cerrada');
    }

    public function estaAbierta(): bool
    {
        return $this->estado === 'abierta';
    }

    public function cerrar(array $data): void
    {
        $this->update([
            'hora_cierre' => now()->format('H:i'),
            'monto_arqueo' => $data['monto_arqueo'],
            'diferencia' => $data['diferencia'],
            'estado' => 'cerrada',
            'cerrada_por_id' => auth()->id(),
        ]);
    }

    public function totalIngresos(): float
    {
        return $this->ventas;
    }

    public function totalEgresos(): float
    {
        return $this->gastos + $this->mermas;
    }

    public function arqueoEsperado(): float
    {
        return $this->monto_inicial + $this->ventas - $this->gastos - $this->mermas;
    }
}
