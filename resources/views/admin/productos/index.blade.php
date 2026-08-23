<x-app-layout>
<x-slot name="title">Productos e Inventario</x-slot>

<!-- Desktop Header -->
<div class="hidden lg:flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-on-surface tracking-tight">Productos e Inventario</h1>
        <p class="text-sm text-on-surface/50">Gestión de stock y precios</p>
    </div>
    <a href="{{ route('admin.productos.create') }}" class="bg-primary hover:opacity-90 text-on-primary font-bold px-5 py-3 rounded-xl transition flex items-center gap-2">
        <span class="material-symbols-outlined text-lg">add</span>
        Nuevo Producto
    </a>
</div>

<!-- Mobile: Cards -->
<div class="lg:hidden grid grid-cols-1 gap-3 pb-24">
    @forelse($productos as $prod)
    <div class="bg-surface-container-low rounded-xl p-4 border border-outline-variant/15">
        <div class="flex items-start justify-between mb-2">
            <div class="flex-1">
                <h3 class="font-bold text-on-surface">{{ $prod->nombre }}</h3>
                <span class="text-xs text-on-surface-variant capitalize">{{ str_replace('_', ' ', $prod->categoria) }}</span>
            </div>
            <span class="text-lg font-bold text-tertiary">S/ {{ number_format($prod->precio_venta, 2) }}</span>
        </div>
        
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="text-xs text-on-surface/60">Stock:</span>
                <span class="font-mono font-bold {{ $prod->tieneStockBajo() ? 'text-amber-400' : 'text-on-surface' }}">
                    {{ $prod->stock_actual }}
                </span>
                <span class="text-xs text-on-surface/40">/ mín {{ $prod->stock_minimo }}</span>
            </div>
            <span class="text-[10px] bg-surface-container-high text-on-surface-variant px-2 py-0.5 rounded-full">Tarj. S/ {{ number_format($prod->precio_tarjeta, 2) }}</span>
        </div>

        <div class="flex gap-2">
            <form method="POST" action="{{ route('admin.productos.stock', $prod) }}" class="flex-1 flex items-center gap-1">
                @csrf
                <input type="number" name="cajas" min="1" value="1" class="flex-1 bg-surface-container-high rounded-lg px-2 py-2 text-xs text-center text-on-surface">
                <button class="bg-tertiary-container text-on-tertiary px-3 py-2 rounded-lg text-xs font-bold">+</button>
            </form>
            <a href="{{ route('admin.productos.edit', $prod) }}" class="bg-surface-container-high px-4 py-2 rounded-lg text-xs text-on-surface">Editar</a>
        </div>
    </div>
    @empty
    <div class="text-center py-12">
        <span class="material-symbols-outlined text-on-surface-variant text-5xl mb-3">inventory_2</span>
        <p class="text-on-surface/40">No hay productos registrados</p>
    </div>
    @endforelse
</div>

<!-- Desktop: Table -->
<div class="hidden lg:block bg-surface-container-low rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-outline-variant/15 text-xs text-on-surface/50 uppercase tracking-wide">
                <th class="text-left px-4 py-3">Producto</th>
                <th class="text-left px-4 py-3">Categoría</th>
                <th class="text-right px-4 py-3">Precio Carta</th>
                <th class="text-right px-4 py-3">Precio Tarjeta</th>
                <th class="text-right px-4 py-3">Stock</th>
                <th class="text-right px-4 py-3">Ingreso</th>
                <th class="text-right px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $prod)
            <tr class="border-b border-outline-variant/10 hover:bg-surface-container-high transition">
                <td class="px-4 py-3">
                    <p class="font-medium text-on-surface">{{ $prod->nombre }}</p>
                    @if($prod->unidades_por_caja > 1)
                    <p class="text-xs text-on-surface/40">{{ $prod->unidades_por_caja }} u/caja</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-on-surface/60 capitalize">{{ str_replace('_', ' ', $prod->categoria) }}</td>
                <td class="px-4 py-3 text-right text-on-surface font-medium">S/ {{ number_format($prod->precio_venta, 2) }}</td>
                <td class="px-4 py-3 text-right text-on-surface/70">S/ {{ number_format($prod->precio_tarjeta, 2) }}</td>
                <td class="px-4 py-3 text-right">
                    <span class="font-mono font-bold {{ $prod->tieneStockBajo() ? 'text-amber-400' : 'text-on-surface' }}">
                        {{ $prod->stock_actual }}
                    </span>
                    <span class="text-xs text-on-surface/40"> / mín {{ $prod->stock_minimo }}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <form method="POST" action="{{ route('admin.productos.stock', $prod) }}" class="inline-flex items-center gap-1">
                        @csrf
                        <input type="number" name="cajas" min="1" value="1"
                               class="w-14 bg-surface-container-high border border-outline-variant/15 rounded px-2 py-1 text-xs text-center text-on-surface">
                        <button class="text-xs bg-tertiary-container text-on-tertiary px-2 py-1 rounded transition">
                            + Cajas
                        </button>
                    </form>
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.productos.edit', $prod) }}"
                       class="text-xs text-primary hover:underline">Editar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="hidden lg:mt-4">{{ $productos->links() }}</div>
</x-app-layout>