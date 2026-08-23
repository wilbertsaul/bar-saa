<?php

namespace App\Providers;

use App\Models\DetalleVenta;
use App\Models\Venta;
use App\Observers\DetalleVentaObserver;
use App\Observers\VentaObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Venta::observe(VentaObserver::class);
        DetalleVenta::observe(DetalleVentaObserver::class);
    }
}
