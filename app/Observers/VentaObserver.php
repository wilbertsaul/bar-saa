<?php

namespace App\Observers;

use App\Models\AuditoriaLog;
use App\Models\Venta;

class VentaObserver
{
    public function created(Venta $venta): void
    {
        AuditoriaLog::registrar($venta, 'crear', null, $venta->toArray());
    }

    public function updated(Venta $venta): void
    {
        AuditoriaLog::registrar($venta, 'actualizar', $venta->getOriginal(), $venta->getChanges());
    }

    public function deleted(Venta $venta): void
    {
        AuditoriaLog::registrar($venta, 'eliminar', $venta->toArray(), null);
    }
}

