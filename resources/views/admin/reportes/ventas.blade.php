<x-app-layout>
<x-slot name="title">Reporte de Ventas</x-slot>

<h1 class="text-xl font-bold text-gray-100 mb-4">Reporte de Ventas</h1>

<form method="GET" class="flex flex-wrap gap-3 mb-6 bg-gray-900 border border-gray-800 rounded-xl p-4">
    <div>
        <label class="text-xs text-gray-500 block mb-1">Desde</label>
        <input type="date" name="desde" value="{{ $desde }}"
               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-600">
    </div>
    <div>
        <label class="text-xs text-gray-500 block mb-1">Hasta</label>
        <input type="date" name="hasta" value="{{ $hasta }}"
               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-600">
    </div>
    <div class="flex items-end">
        <button type="submit" class="bg-primary hover:opacity-90 text-on-primary text-sm px-4 py-2 rounded-lg transition">Filtrar</button>
    </div>
    <div class="flex items-end ml-auto">
        <a href="{{ route('admin.reportes.ventas.exportar', ['desde' => $desde, 'hasta' => $hasta]) }}"
           class="bg-emerald-600 hover:opacity-90 text-white text-sm px-4 py-2 rounded-lg transition inline-flex items-center gap-1.5 shrink-0">
            <span class="material-symbols-outlined text-base">download</span> Exportar a Excel
        </a>
        <span class="text-sm text-gray-400 ml-3">Total: <strong class="text-white">S/ {{ number_format($totalPeriodo, 2) }}</strong></span>
    </div>
</form>

<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[700px]">
        <thead>
            <tr class="border-b border-gray-800 text-xs text-gray-500 uppercase tracking-wide">
                <th class="text-left px-4 py-3">Fecha/Hora</th>
                <th class="text-left px-4 py-3">Productos</th>
                <th class="text-left px-4 py-3">Pago</th>
                <th class="text-right px-4 py-3">Total</th>
                <th class="text-right px-4 py-3">Registró</th>
                <th class="text-right px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventas as $venta)
            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition">
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $venta->fecha_hora->format('d/m H:i') }}</td>
                <td class="px-4 py-3 text-xs text-gray-400">
                    @foreach($venta->detalles as $d)
                        <span>{{ $d->cantidad }}× {{ $d->producto->nombre }} (S/ {{ number_format($d->precio_unitario, 2) }})</span>{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ \App\Models\Venta::TIPOS_PAGO[$venta->tipo_pago] ?? $venta->tipo_pago }}</td>
                <td class="px-4 py-3 text-right font-semibold text-gray-100">S/ {{ number_format($venta->total, 2) }}</td>
                <td class="px-4 py-3 text-right text-xs text-gray-500">{{ $venta->user->name }}</td>
                <td class="px-4 py-3 text-right">
                    <form method="POST" action="{{ route('pos.venta.anular', $venta) }}"
                          onsubmit="return confirm('¿Extornar la venta #{{ $venta->id }} por S/ {{ number_format($venta->total, 2) }}? Se restaurará el stock.')">
                        @csrf
                        @method('PATCH')
                        <button class="text-xs text-error hover:underline transition">Extornar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-8 text-gray-500">Sin ventas en el período.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $ventas->links() }}</div>
</x-app-layout>
