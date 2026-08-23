<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorteCaja extends Model
{
    protected $table = 'cortes_caja';

    protected $fillable = [
        'user_id',
        'fecha',
        'caja_ids',
        'total_ventas',
        'total_neto',
        'diferencia_inventario',
        'observaciones',
        'cerrado_en',
    ];

    protected function casts(): array
    {
        return [
            'fecha'            => 'date',
            'caja_ids'         => 'array',
            'total_ventas'     => 'decimal:2',
            'total_neto'       => 'decimal:2',
            'cerrado_en'       => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function estaCerrado(): bool
    {
        return $this->cerrado_en !== null;
    }
}
