<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function ventas(Request $request): View
    {
        $desde = $request->filled('desde') ? $request->desde : today()->toDateString();
        $hasta = $request->filled('hasta') ? $request->hasta : today()->toDateString();

        $ventas = Venta::with(['user', 'detalles.producto'])
            ->where('estado', 'activa')
            ->whereBetween('fecha_hora', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->orderByDesc('fecha_hora')
            ->paginate(30)
            ->withQueryString();

        $totalPeriodo = Venta::where('estado', 'activa')
            ->whereBetween('fecha_hora', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->sum('total');

        return view('admin.reportes.ventas', compact('ventas', 'totalPeriodo', 'desde', 'hasta'));
    }

    public function inventario(): View
    {
        $productos = Producto::where('activo', true)
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get();

        $movimientos = MovimientoInventario::with(['producto', 'user'])
            ->whereDate('created_at', today())
            ->orderByDesc('created_at')
            ->get();

        return view('admin.reportes.inventario', compact('productos', 'movimientos'));
    }
}
