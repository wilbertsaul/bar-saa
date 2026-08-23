<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\CajaGasto;
use App\Models\CajaMerma;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CajaController extends Controller
{
    public function index(): View
    {
        $cajas = Caja::with(['usuario', 'cerradaPor'])
            ->orderByDesc('id')
            ->paginate(20);

        $cajaAbierta = Caja::abierta()->first();

        return view('admin.cajas.index', compact('cajas', 'cajaAbierta'));
    }

    public function abrir(): View
    {
        $cajaAbierta = Caja::abierta()->first();
        $ultimaCaja = Caja::cerrada()->orderByDesc('id')->first();

        return view('admin.cajas.abrir', compact('cajaAbierta', 'ultimaCaja'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'monto_inicial' => 'required|numeric|min:0',
        ]);

        $cajaAbierta = Caja::abierta()->first();
        if ($cajaAbierta) {
            return back()->with('error', 'Ya hay una caja abierta.');
        }

        $fechaTrabajo = now()->format('Y-m-d');
        if ($request->has('fecha_trabajo')) {
            $fechaTrabajo = $request->fecha_trabajo;
        }

        Caja::create([
            'user_id' => auth()->id(),
            'fecha_trabajo' => $fechaTrabajo,
            'hora_apertura' => now()->format('H:i'),
            'monto_inicial' => $request->monto_inicial,
            'estado' => 'abierta',
        ]);

        return redirect()->route('admin.cajas.index')
            ->with('success', 'Caja abierta con S/ '.number_format($request->monto_inicial, 2));
    }

    public function cerrar(Caja $caja): View
    {
        abort_if($caja->estado === 'cerrada', 400, 'La caja ya está cerrada.');

        $ventas = Venta::where('caja_id', $caja->id)
            ->where('estado', 'activa')
            ->with('detalles.producto')
            ->get();

        $gastos = CajaGasto::where('caja_id', $caja->id)->get();
        $mermas = CajaMerma::where('caja_id', $caja->id)->get();

        $totalVentas = (float) Venta::where('caja_id', $caja->id)->where('estado', 'activa')->sum('total');
        $totalGastos = (float) $gastos->sum('monto');
        $totalMermas = (float) $mermas->sum('monto');

        $arqueoEsperado = (float) $caja->monto_inicial + $totalVentas - $totalGastos - $totalMermas;

        return view('admin.cajas.cerrar', compact(
            'caja', 'ventas', 'gastos', 'mermas',
            'totalVentas', 'totalGastos', 'totalMermas', 'arqueoEsperado'
        ));
    }

    public function cerrarStore(Request $request, Caja $caja): RedirectResponse
    {
        abort_if($caja->estado === 'cerrada', 400, 'La caja ya está cerrada.');

        $request->validate([
            'monto_arqueo' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($caja, $request) {
            $ventas = Venta::where('caja_id', $caja->id)
                ->where('estado', 'activa')
                ->sum('total');

            $gastos = $caja->gastos()->sum('monto');
            $mermas = $caja->mermas()->sum('monto');

            $arqueoEsperado = $caja->monto_inicial + $ventas - $gastos - $mermas;
            $diferencia = $request->monto_arqueo - $arqueoEsperado;

            $caja->update([
                'ventas' => $ventas,
                'gastos' => $gastos,
                'mermas' => $mermas,
                'hora_cierre' => now()->format('H:i'),
                'monto_arqueo' => $request->monto_arqueo,
                'diferencia' => $diferencia,
                'estado' => 'cerrada',
                'cerrada_por_id' => auth()->id(),
            ]);
        });

        return redirect()->route('admin.cajas.index')
            ->with('success', 'Caja cerrada.');
    }

    public function agregarGasto(Request $request): RedirectResponse
    {
        $request->validate([
            'caja_id' => 'required|exists:cajas,id',
            'tipo' => 'required|in:entrega_dueño,compra,gasto,otro',
            'descripcion' => 'required|string|max:200',
            'monto' => 'required|numeric|min:0.01',
        ]);

        $caja = Caja::findOrFail($request->caja_id);
        abort_if($caja->estado === 'cerrada', 400, 'La caja está cerrada.');

        CajaGasto::create($request->only(['caja_id', 'tipo', 'descripcion', 'monto']));

        return back()->with('success', 'Gasto registrado.');
    }

    public function agregarMerma(Request $request): RedirectResponse
    {
        $request->validate([
            'caja_id' => 'required|exists:cajas,id',
            'tipo' => 'required|in:producto,efectivo',
            'producto_id' => 'nullable|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'monto' => 'required|numeric|min:0.01',
            'descripcion' => 'required|string|max:200',
        ]);

        if ($request->tipo === 'producto' && ! $request->producto_id) {
            return back()->withErrors(['producto_id' => 'Selecciona un producto.']);
        }

        $caja = Caja::findOrFail($request->caja_id);
        abort_if($caja->estado === 'cerrada', 400, 'La caja está cerrada.');

        CajaMerma::create($request->only(['caja_id', 'tipo', 'producto_id', 'cantidad', 'monto', 'descripcion']));

        if ($request->tipo === 'producto' && $request->producto_id) {
            $producto = Producto::findOrFail($request->producto_id);
            $producto->descontarStock($request->cantidad, auth()->user(), 'Merma: '.$request->descripcion);

            MovimientoInventario::where('producto_id', $producto->id)
                ->latest()
                ->first()
                ?->update([
                    'es_merma' => true,
                    'caja_id' => $caja->id,
                ]);
        }

        return back()->with('success', 'Merma registrada.');
    }

    public function verMerma(Caja $caja): View
    {
        abort_if($caja->estado === 'cerrada', 400, 'La caja está cerrada.');

        $productos = Producto::where('stock_actual', '>', 0)->orderBy('nombre')->get();

        return view('admin.cajas.merma', compact('caja', 'productos'));
    }

    public function show(Caja $caja): View
    {
        $ventas = Venta::where('caja_id', $caja->id)
            ->where('estado', 'activa')
            ->with('detalles.producto')
            ->get();

        $gastos = $caja->gastos;
        $mermas = $caja->mermas;

        return view('admin.cajas.show', compact('caja', 'ventas', 'gastos', 'mermas'));
    }

    public function destroy(Caja $caja): RedirectResponse
    {
        abort_if($caja->estado === 'cerrada', 400, 'No se puede eliminar una caja cerrada.');

        $caja->delete();

        return back()->with('success', 'Caja eliminada.');
    }
}
