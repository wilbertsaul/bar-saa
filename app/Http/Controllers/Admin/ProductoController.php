<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    public const CATEGORIAS = ['ron', 'whisky', 'cerveza', 'sin_alcohol'];

    public function index(): View
    {
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->paginate(20);
        return view('admin.productos.index', compact('productos'));
    }

    public function create(): View
    {
        return view('admin.productos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'           => 'required|string|max:100',
            'categoria'        => 'required|in:ron,whisky,cerveza,sin_alcohol',
            'precio_venta'     => 'required|numeric|min:0',
            'precio_tarjeta'   => 'required|numeric|min:0|gte:precio_venta',
            'precio_costo'     => 'nullable|numeric|min:0',
            'stock_actual'     => 'required|integer|min:0',
            'stock_minimo'     => 'required|integer|min:0',
            'unidades_por_caja' => 'required|integer|min:1',
        ]);

        Producto::create($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado.');
    }

    public function edit(Producto $producto): View
    {
        return view('admin.productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $data = $request->validate([
            'nombre'           => 'required|string|max:100',
            'categoria'        => 'required|in:ron,whisky,cerveza,sin_alcohol',
            'precio_venta'     => 'required|numeric|min:0',
            'precio_tarjeta'   => 'required|numeric|min:0|gte:precio_venta',
            'precio_costo'     => 'nullable|numeric|min:0',
            'stock_minimo'     => 'required|integer|min:0',
            'unidades_por_caja' => 'required|integer|min:1',
            'activo'           => 'boolean',
        ]);

        $producto->update($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado.');
    }

    public function ingresarStock(Request $request, Producto $producto): RedirectResponse
    {
        $request->validate(['cajas' => 'required|integer|min:1']);
        $producto->ingresarCaja($request->cajas, auth()->user());

        return back()->with('success', "Stock actualizado: +{$request->cajas} caja(s) = " . ($request->cajas * $producto->unidades_por_caja) . " unidades.");
    }
}
