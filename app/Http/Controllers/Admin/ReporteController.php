<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function exportarVentas(Request $request): StreamedResponse
    {
        $desde = $request->filled('desde') ? $request->desde : today()->toDateString();
        $hasta = $request->filled('hasta') ? $request->hasta : today()->toDateString();

        $ventas = Venta::with(['user', 'detalles.producto'])
            ->where('estado', 'activa')
            ->whereBetween('fecha_hora', [$desde . ' 00:00:00', $hasta . ' 23:59:59'])
            ->orderByDesc('fecha_hora')
            ->get();

        $nombre = 'ventas_' . $desde . '_al_' . $hasta . '.csv';

        return response()->streamDownload(function () use ($ventas) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['N°', 'Fecha/Hora', 'N° Caja', 'Productos', 'Pago', 'Total', 'Registró']);

            $total = 0;
            foreach ($ventas as $venta) {
                $productos = $venta->detalles->map(
                    fn ($d) => $d->cantidad . ' × ' . ($d->producto->nombre ?? 'Eliminado') . ' (S/ ' . number_format($d->precio_unitario, 2, '.', '') . ')'
                )->implode(' | ');

                $total += $venta->total;

                fputcsv($out, [
                    $venta->id,
                    $venta->fecha_hora->format('d/m/Y H:i'),
                    $venta->caja_id,
                    $this->csvSafe($productos),
                    Venta::TIPOS_PAGO[$venta->tipo_pago] ?? $venta->tipo_pago,
                    number_format($venta->total, 2, '.', ''),
                    $this->csvSafe($venta->user->name),
                ]);
            }

            fputcsv($out, ['', '', '', '', 'TOTAL', number_format($total, 2, '.', ''), '']);

            fclose($out);
        }, $nombre, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function csvSafe(string|null $value): string
    {
        if ($value !== null && strlen($value) > 0 && str_contains('=+-@', $value[0])) {
            return "'" . $value;
        }

        return (string) $value;
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
