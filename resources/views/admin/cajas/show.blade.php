<x-app-layout>
<x-slot name="title">Caja #{{ $caja->id }}</x-slot>

<style>
.glass-panel { background: rgba(32, 31, 32, 0.4); backdrop-filter: blur(20px); border: 1px solid rgba(89, 65, 62, 0.15); }
</style>

<div class="max-w-4xl w-full">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.cajas.index') }}" class="p-2 rounded-lg hover:bg-surface-container transition">
            <span class="material-symbols-outlined text-on-surface">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-on-surface">Caja #{{ $caja->id }}</h2>
            <p class="text-on-surface/40 text-sm">{{ $caja->fecha_trabajo->format('d/m/Y') }} · {{ $caja->usuario->name }} · {{ ucfirst($caja->estado) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="glass-panel rounded-xl p-4">
            <p class="text-xs text-on-surface/40 uppercase tracking-wider">Monto Inicial</p>
            <p class="text-xl font-bold text-on-surface">S/ {{ number_format($caja->monto_inicial, 2) }}</p>
        </div>
        <div class="glass-panel rounded-xl p-4">
            <p class="text-xs text-on-surface/40 uppercase tracking-wider">Ventas</p>
            <p class="text-xl font-bold text-tertiary">S/ {{ number_format($ventas->sum('total'), 2) }}</p>
        </div>
        <div class="glass-panel rounded-xl p-4">
            <p class="text-xs text-on-surface/40 uppercase tracking-wider">Arqueo</p>
            <p class="text-xl font-bold text-on-surface">S/ {{ number_format($caja->monto_arqueo ?? 0, 2) }}</p>
        </div>
        <div class="glass-panel rounded-xl p-4">
            <p class="text-xs text-on-surface/40 uppercase tracking-wider">Diferencia</p>
            <p class="text-xl font-bold {{ ($caja->diferencia ?? 0) >= 0 ? 'text-tertiary' : 'text-error' }}">
                {{ ($caja->diferencia ?? 0) >= 0 ? '+' : '' }}S/ {{ number_format($caja->diferencia ?? 0, 2) }}
            </p>
        </div>
    </div>

    <div class="glass-panel rounded-xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-outline-variant/10">
            <h3 class="text-xs font-bold text-on-surface/60 uppercase tracking-wider">Ventas del Turno</h3>
        </div>
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm min-w-[500px]">
                <thead>
                    <tr class="border-b border-outline-variant/10 text-xs text-on-surface/40 uppercase tracking-wider">
                        <th class="text-left px-6 py-4">Hora</th>
                        <th class="text-left px-6 py-4 hidden lg:table-cell">Productos</th>
                        <th class="text-right px-6 py-4">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    <tr class="border-b border-outline-variant/5">
                        <td class="px-6 py-3 text-on-surface/60">{{ $venta->fecha_hora->format('H:i') }}</td>
                        <td class="px-6 py-3 text-on-surface/60 hidden lg:table-cell">
                            @foreach($venta->detalles as $d)
                                {{ $d->cantidad }}× {{ $d->producto->nombre }}
                                @if(!$loop->last), @endif
                            @endforeach
                        </td>
                        <td class="px-6 py-3 text-right text-on-surface font-medium">S/ {{ number_format($venta->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-on-surface/40">Sin ventas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="md:hidden divide-y divide-outline-variant/5">
            @forelse($ventas as $venta)
            <div class="p-4">
                <div class="flex justify-between mb-1">
                    <span class="text-on-surface font-medium">{{ \App\Models\Venta::TIPOS_PAGO[$venta->tipo_pago] ?? $venta->tipo_pago }}</span>
                    <span class="text-on-surface font-medium">S/ {{ number_format($venta->total, 2) }}</span>
                </div>
                <p class="text-xs text-on-surface/40">{{ $venta->fecha_hora->format('H:i') }} ·
                    @foreach($venta->detalles as $d){{ $d->cantidad }}×{{ $d->producto->nombre }}@if(!$loop->last), @endif @endforeach
                </p>
            </div>
            @empty
            <p class="p-6 text-center text-on-surface/40">Sin ventas</p>
            @endforelse
        </div>
    </div>

    <div class="mt-8 text-center">
        <p class="text-[10px] text-on-surface/20 uppercase tracking-[0.3em]">Noir Concierge Digital Ledger v2.4.0</p>
    </div>
</div>
</x-app-layout>
