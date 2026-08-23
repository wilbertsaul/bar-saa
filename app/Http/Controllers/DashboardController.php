<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaLog;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hoy = today();

        $totalVentasHoy = Venta::where('estado', 'activa')->whereDate('fecha_hora', $hoy)->sum('total');
        $totalVentasMes = Venta::where('estado', 'activa')->whereMonth('fecha_hora', $hoy->month)->sum('total');
        $cantidadVentasHoy = Venta::where('estado', 'activa')->whereDate('fecha_hora', $hoy)->count();

        $ventasPorPago = Venta::where('estado', 'activa')
            ->whereDate('fecha_hora', $hoy)
            ->selectRaw("tipo_pago, count(*) as cantidad, sum(total) as total")
            ->groupBy('tipo_pago')
            ->pluck('total', 'tipo_pago');

        $productosStockBajo = Producto::where('activo', true)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual', 'asc')
            ->limit(5)
            ->get();

        $stockCriticoCount = Producto::where('activo', true)->whereColumn('stock_actual', '<=', 'stock_minimo')->count();

        $cortePendiente = false;

        $ultimaActividad = AuditoriaLog::with('user')
            ->whereDate('realizado_en', $hoy)
            ->orderBy('realizado_en', 'desc')
            ->limit(5)
            ->get();

        $notificacionesCount = $stockCriticoCount + ($cortePendiente ? 1 : 0);

        return view('dashboard', compact(
            'totalVentasHoy',
            'totalVentasMes',
            'cantidadVentasHoy',
            'ventasPorPago',
            'productosStockBajo',
            'stockCriticoCount',
            'cortePendiente',
            'ultimaActividad',
            'notificacionesCount'
        ));
    }
}
