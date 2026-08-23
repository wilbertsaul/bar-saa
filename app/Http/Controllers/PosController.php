<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $cajaAbierta = Caja::abierta()->first();

        $productos = Producto::where('activo', true)
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get();

        $ventasHoy = Venta::where('estado', 'activa')
            ->whereDate('fecha_hora', today())
            ->with('detalles.producto')
            ->orderByDesc('fecha_hora')
            ->limit(10)
            ->get();

        return view('pos.index', compact('productos', 'cajaAbierta', 'ventasHoy'));
    }

    public function registrarVenta(Request $request)
    {
        $isJson = $request->is('api/*') || $request->header('Accept') === 'application/json' || str_contains($request->header('Content-Type') ?? '', 'application/json');

        $cajaAbierta = Caja::abierta()->first();
        if (! $cajaAbierta) {
            $error = 'Debes abrir una caja antes de registrar ventas.';
            if ($isJson) {
                return response()->json(['success' => false, 'message' => $error]);
            }

            return back()->with('error', $error);
        }

        return $this->procesarVenta($request, $cajaAbierta, $isJson);
    }

    public function registrarVentaApi(Request $request): JsonResponse
    {
        $cajaAbierta = Caja::abierta()->first();
        if (! $cajaAbierta) {
            return response()->json(['success' => false, 'message' => 'Debes abrir una caja antes de registrar ventas.']);
        }

        return $this->procesarVenta($request, $cajaAbierta, true);
    }

    private function procesarVenta(Request $request, Caja $cajaAbierta, bool $isJson)
    {
        $rules = [
            'tipo_pago' => 'required|in:efectivo,yape,plin,tarjeta_credito,tarjeta_debito',
            'items' => 'required|array',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:0',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($isJson) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()->toArray()]);
            }

            return back()->withErrors($validator->errors());
        }

        // Descartar items con cantidad 0
        $itemsValidos = collect($request->items)->filter(fn ($i) => (int) $i['cantidad'] > 0);

        if ($itemsValidos->isEmpty()) {
            $error = 'Debes seleccionar al menos un producto.';
            if ($isJson) {
                return response()->json(['success' => false, 'message' => $error, 'errors' => ['items' => [$error]]]);
            }

            return back()->withErrors(['items' => $error]);
        }

        // Precio tarjeta solo para pagos con tarjeta; carta para efectivo/yape/plin
        $usaPrecioTarjeta = Venta::usaPrecioTarjeta($request->tipo_pago);

        try {
            $venta = DB::transaction(function () use ($itemsValidos, $cajaAbierta, $usaPrecioTarjeta, $request) {
                $venta = Venta::create([
                    'user_id' => auth()->id(),
                    'caja_id' => $cajaAbierta->id,
                    'total' => 0,
                    'tipo_pago' => $request->tipo_pago,
                    'fecha_hora' => now(),
                    'estado' => 'activa',
                ]);

                foreach ($itemsValidos as $item) {
                    $producto = Producto::findOrFail($item['producto_id']);
                    $precioUnitario = $usaPrecioTarjeta ? $producto->precio_tarjeta : $producto->precio_venta;

                    DetalleVenta::create([
                        'venta_id' => $venta->id,
                        'producto_id' => $producto->id,
                        'cantidad' => (int) $item['cantidad'],
                        'precio_unitario' => $precioUnitario,
                        'subtotal' => $precioUnitario * (int) $item['cantidad'],
                    ]);
                }

                $venta->recalcularTotal();

                return $venta;
            });
        } catch (\Exception $e) {
            $message = 'Error: '.$e->getMessage();
            if ($isJson) {
                return response()->json(['success' => false, 'message' => $message]);
            }

            return back()->with('error', $message);
        }

        $message = 'Venta registrada: S/ '.number_format((float) $venta->total, 2).' ('.(Venta::TIPOS_PAGO[$venta->tipo_pago] ?? $venta->tipo_pago).')';

        if ($isJson || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'venta' => [
                    'id' => $venta->id,
                    'total' => (float) $venta->total,
                    'tipo_pago' => $venta->tipo_pago,
                ],
            ]);
        }

        return redirect()->route('pos.index')->with('success', $message);
    }

    public function anularVenta(Venta $venta): RedirectResponse
    {
        abort_if(! $venta->estaActiva(), 403, 'La venta ya fue anulada.');

        DB::transaction(function () use ($venta) {
            // Restaurar stock: el observer de DetalleVenta lo hace al eliminar
            $venta->detalles()->each(fn ($d) => $d->delete());
            $venta->update(['estado' => 'anulada']);
        });

        return back()->with('success', 'Venta anulada.');
    }
}
