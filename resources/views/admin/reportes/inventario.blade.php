<x-app-layout>
<x-slot name="title">Reporte de Inventario</x-slot>

<h1 class="text-lg lg:text-xl font-bold text-gray-100 mb-4 lg:mb-6">Inventario en tiempo real</h1>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
    {{-- Estado actual --}}
    <div>
        <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Stock actual</h2>
        <div class="lg:hidden space-y-2">
            @foreach($productos as $prod)
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-3 flex justify-between items-center">
                <div>
                    <p class="text-gray-200 text-sm">{{ $prod->nombre }}</p>
                    <p class="text-xs text-gray-500">Mín: {{ $prod->stock_minimo }}</p>
                </div>
                <span class="font-mono font-bold {{ $prod->tieneStockBajo() ? 'text-amber-400' : 'text-gray-100' }}">{{ $prod->stock_actual }}</span>
            </div>
            @endforeach
        </div>
        <div class="hidden lg:block bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[300px]">
                <thead><tr class="border-b border-gray-800 text-xs text-gray-500 uppercase tracking-wide">
                    <th class="text-left px-4 py-3">Producto</th>
                    <th class="text-right px-4 py-3">Stock</th>
                    <th class="text-right px-4 py-3">Mín</th>
                </tr></thead>
                <tbody>
                    @foreach($productos as $prod)
                    <tr class="border-b border-gray-800/50">
                        <td class="px-4 py-2.5 text-gray-200">{{ $prod->nombre }}</td>
                        <td class="px-4 py-2.5 text-right font-mono font-bold {{ $prod->tieneStockBajo() ? 'text-amber-400' : 'text-gray-100' }}">
                            {{ $prod->stock_actual }}
                        </td>
                        <td class="px-4 py-2.5 text-right text-xs text-gray-600">{{ $prod->stock_minimo }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>

    {{-- Movimientos de hoy --}}
    <div>
        <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">Movimientos hoy</h2>
        <div class="lg:hidden space-y-2 max-h-64 overflow-y-auto">
            @forelse($movimientos as $mov)
            <div class="bg-gray-900 border border-gray-800 rounded-lg p-3 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">{{ $mov->created_at->format('H:i') }}</span>
                    <span class="text-xs text-gray-300">{{ $mov->producto->nombre }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs px-1.5 py-0.5 rounded
                        {{ $mov->tipo === 'entrada' ? 'bg-green-900 text-green-300' : ($mov->tipo === 'salida' ? 'bg-red-900 text-red-300' : 'bg-gray-700 text-gray-400') }}">
                        {{ $mov->tipo }}
                    </span>
                    <span class="text-gray-200 font-mono">{{ $mov->cantidad }}</span>
                </div>
            </div>
            @empty
            <p class="text-center py-4 text-gray-500 text-sm">Sin movimientos hoy.</p>
            @endforelse
        </div>
        <div class="hidden lg:block bg-gray-900 border border-gray-800 rounded-xl overflow-hidden max-h-96 overflow-y-auto">
            <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[400px]">
                <thead><tr class="border-b border-gray-800 text-xs text-gray-500 uppercase tracking-wide">
                    <th class="text-left px-4 py-3">Hora</th>
                    <th class="text-left px-4 py-3">Producto</th>
                    <th class="text-left px-4 py-3">Tipo</th>
                    <th class="text-right px-4 py-3">Cant.</th>
                </tr></thead>
                <tbody>
                    @forelse($movimientos as $mov)
                    <tr class="border-b border-gray-800/50">
                        <td class="px-4 py-2 text-xs text-gray-500">{{ $mov->created_at->format('H:i') }}</td>
                        <td class="px-4 py-2 text-gray-300 text-xs">{{ $mov->producto->nombre }}</td>
                        <td class="px-4 py-2">
                            <span class="text-xs px-1.5 py-0.5 rounded
                                {{ $mov->tipo === 'entrada' ? 'bg-green-900 text-green-300' : ($mov->tipo === 'salida' ? 'bg-red-900 text-red-300' : 'bg-gray-700 text-gray-400') }}">
                                {{ $mov->tipo }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right font-mono text-gray-200">{{ $mov->cantidad }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-6 text-gray-500 text-sm">Sin movimientos hoy.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
