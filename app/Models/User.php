<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function isControlador(): bool
    {
        return $this->rol === 'controlador';
    }

    public function canAccess(string $route): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $operadorRoutes = [
            'admin.productos',
            'admin.inventario',
            'admin.cajas',
            'pos.',
        ];

        foreach ($operadorRoutes as $prefix) {
            if (str_starts_with($route, $prefix)) {
                return true;
            }
        }

        return false;
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function auditoriaLogs(): HasMany
    {
        return $this->hasMany(AuditoriaLog::class);
    }

    public function cortesCaja(): HasMany
    {
        return $this->hasMany(CorteCaja::class);
    }
}
