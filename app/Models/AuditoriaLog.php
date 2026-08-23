<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditoriaLog extends Model
{
    protected $table = 'auditoria_logs';

    protected $fillable = [
        'user_id',
        'modelo',
        'modelo_id',
        'accion',
        'data_anterior',
        'data_nueva',
        'ip_address',
        'realizado_en',
    ];

    protected function casts(): array
    {
        return [
            'data_anterior' => 'array',
            'data_nueva'    => 'array',
            'realizado_en'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function registrar(Model $modelo, string $accion, ?array $anterior = null, ?array $nueva = null): void
    {
        // No registrar si no hay sesión activa (seeders, comandos artisan)
        if (!auth()->id()) {
            return;
        }

        static::create([
            'user_id'       => auth()->id(),
            'modelo'        => class_basename($modelo),
            'modelo_id'     => $modelo->getKey(),
            'accion'        => $accion,
            'data_anterior' => $anterior,
            'data_nueva'    => $nueva,
            'ip_address'    => request()->ip(),
            'realizado_en'  => now(),
        ]);
    }
}
