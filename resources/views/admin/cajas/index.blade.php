<x-app-layout>
<x-slot name="title">Cajas — Turnos</x-slot>

<style>
.glass-panel { background: rgba(32, 31, 32, 0.4); backdrop-filter: blur(20px); border: 1px solid rgba(89, 65, 62, 0.15); }
</style>

<div class="max-w-6xl w-full">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-on-surface mb-1">Cajas</h2>
            <p class="text-on-surface/40 text-sm">Control de turnos y Arqueo de caja.</p>
        </div>
        @if($cajaAbierta)
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cajas.cerrar', $cajaAbierta) }}" class="bg-tertiary hover:bg-tertiary/80 text-on-tertiary font-bold px-5 py-3 rounded-xl transition flex items-center gap-2">
                <span class="material-symbols-outlined">logout</span>
                Cerrar Caja
            </a>
        </div>
        @else
        <a href="{{ route('admin.cajas.abrir') }}" class="bg-error hover:opacity-90 text-on-error font-bold px-5 py-3 rounded-xl transition flex items-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Abrir Caja
        </a>
        @endif
    </div>

    @if($cajaAbierta)
    <div class="glass-panel rounded-xl p-6 mb-8 border-l-4 border-tertiary">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-3 h-3 rounded-full bg-tertiary animate-pulse"></span>
            <span class="text-tertiary font-bold text-sm uppercase tracking-wider">Caja Abierta</span>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <p class="text-xs text-on-surface/40 uppercase tracking-wider">Operador</p>
                <p class="text-lg font-bold text-on-surface">{{ $cajaAbierta->usuario->name }}</p>
            </div>
            <div>
                <p class="text-xs text-on-surface/40 uppercase tracking-wider">Fecha Trabajo</p>
                <p class="text-lg font-bold text-on-surface">{{ $cajaAbierta->fecha_trabajo->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-on-surface/40 uppercase tracking-wider">Apertura</p>
                <p class="text-lg font-bold text-on-surface">{{ $cajaAbierta->hora_apertura }}</p>
            </div>
            <div>
                <p class="text-xs text-on-surface/40 uppercase tracking-wider">Monto Inicial</p>
                <p class="text-lg font-bold text-tertiary">S/ {{ number_format($cajaAbierta->monto_inicial, 2) }}</p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-outline-variant/20">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-xs font-bold text-on-surface/60 uppercase tracking-wider">Registrar Gasto</h4>
                <a href="{{ route('admin.cajas.merma', $cajaAbierta) }}" class="text-xs text-primary hover:underline">
                    Registrar Merma
                </a>
            </div>
            <form method="POST" action="{{ route('admin.cajas.gasto') }}" class="flex flex-wrap gap-3 items-end">
                @csrf
                <input type="hidden" name="caja_id" value="{{ $cajaAbierta->id }}">
                <div class="flex-1 min-w-[140px]">
                    <select name="tipo" required class="w-full bg-surface-container-high rounded-lg px-3 py-2 text-sm text-on-surface">
                        <option value="compra">Compra insumo</option>
                        <option value="entrega_dueño">Entrega al dueño</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[180px]">
                    <input type="text" name="descripcion" required placeholder="Descripción" class="w-full bg-surface-container-high rounded-lg px-3 py-2 text-sm text-on-surface">
                </div>
                <div class="w-28">
                    <input type="number" name="monto" step="0.01" min="0.01" required placeholder="Monto" class="w-full bg-surface-container-high rounded-lg px-3 py-2 text-sm text-on-surface">
                </div>
                <button type="submit" class="px-4 py-2 bg-error hover:opacity-90 text-on-error font-bold rounded-lg text-sm transition">
                    Registrar
                </button>
            </form>
        </div>

        @php $gastosDelDia = $cajaAbierta->gastos()->get(); @endphp
        @if($gastosDelDia->count() > 0)
        <div class="mt-4 pt-4 border-t border-outline-variant/20">
            <h4 class="text-xs font-bold text-on-surface/40 uppercase tracking-wider mb-3">Gastos del turno</h4>
            <div class="space-y-2 max-h-32 overflow-y-auto">
                @foreach($gastosDelDia as $gasto)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-on-surface/60">{{ $gasto->descripcion }}</span>
                    <span class="text-error font-medium">-S/ {{ number_format($gasto->monto, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    <div class="glass-panel rounded-xl overflow-hidden">
        <div class="hidden md:table w-full">
            <table class="w-full text-sm min-w-[700px]">
                <thead>
                    <tr class="border-b border-outline-variant/10 text-xs text-on-surface/40 uppercase tracking-wider">
                        <th class="text-left px-6 py-4">Fecha</th>
                        <th class="text-left px-6 py-4 hidden lg:table-cell">Operador</th>
                        <th class="text-right px-6 py-4">Inicial</th>
                        <th class="text-right px-6 py-4 hidden lg:table-cell">Ventas</th>
                        <th class="text-right px-6 py-4 hidden lg:table-cell">Gastos</th>
                        <th class="text-right px-6 py-4 hidden xl:table-cell">Arqueo</th>
                        <th class="text-right px-6 py-4 hidden xl:table-cell">Diff</th>
                        <th class="text-right px-6 py-4">Estado</th>
                        <th class="text-right px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cajas as $caja)
                    <tr class="border-b border-outline-variant/5 hover:bg-surface-container/30 transition">
                        <td class="px-6 py-4">
                            <p class="font-medium text-on-surface">{{ $caja->fecha_trabajo->format('d/m/Y') }}</p>
                            <p class="text-xs text-on-surface/40 md:hidden">{{ $caja->hora_apertura }} - {{ $caja->hora_cierre ?? '--:--' }}</p>
                        </td>
                        <td class="px-6 py-4 text-on-surface hidden lg:table-cell">{{ $caja->usuario->name }}</td>
                        @php
                            $ventasDelDia = $caja->estado === 'cerrada' 
                                ? $caja->ventas 
                                : \App\Models\Venta::where('caja_id', $caja->id)->where('estado', 'activa')->sum('total') ?? 0;
                            $gastosDelDia = $caja->estado === 'cerrada' 
                                ? $caja->gastos 
                                : $caja->gastos()->sum('monto') ?? 0;
                        @endphp
                        <td class="px-6 py-4 text-right text-on-surface">S/ {{ number_format($caja->monto_inicial, 2) }}</td>
                        <td class="px-6 py-4 text-right text-tertiary hidden lg:table-cell">S/ {{ number_format($ventasDelDia, 2) }}</td>
                        <td class="px-6 py-4 text-right text-error hidden lg:table-cell">S/ {{ number_format($gastosDelDia, 2) }}</td>
                        <td class="px-6 py-4 text-right text-on-surface hidden xl:table-cell">S/ {{ number_format($caja->monto_arqueo ?? 0, 2) }}</td>
                        <td class="px-6 py-4 text-right {{ $caja->diferencia >= 0 ? 'text-tertiary' : 'text-error' }} hidden xl:table-cell">
                            {{ $caja->diferencia >= 0 ? '+' : '' }}S/ {{ number_format($caja->diferencia, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $caja->estado === 'abierta' ? 'bg-tertiary/20 text-tertiary' : 'bg-surface-container text-on-surface/60' }}">
                                {{ $caja->estado === 'abierta' ? 'Abierta' : 'Cerrada' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.cajas.show', $caja) }}" class="text-primary hover:underline text-xs">Ver</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-on-surface/40">No hay cajas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="md:hidden divide-y divide-outline-variant/5">
            @forelse($cajas as $caja)
            @php
                $ventasDelDia = $caja->estado === 'cerrada' 
                    ? $caja->ventas 
                    : \App\Models\Venta::where('caja_id', $caja->id)->where('estado', 'activa')->sum('total') ?? 0;
                $gastosDelDia = $caja->estado === 'cerrada' 
                    ? $caja->gastos 
                    : $caja->gastos()->sum('monto') ?? 0;
            @endphp
            <a href="{{ route('admin.cajas.show', $caja) }}" class="block p-4 hover:bg-surface-container/30 transition">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-medium text-on-surface">{{ $caja->fecha_trabajo->format('d/m/Y') }}</p>
                        <p class="text-xs text-on-surface/40">{{ $caja->hora_apertura }} - {{ $caja->hora_cierre ?? '--:--' }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $caja->estado === 'abierta' ? 'bg-tertiary/20 text-tertiary' : 'bg-surface-container text-on-surface/60' }}">
                        {{ $caja->estado === 'abierta' ? 'Abierta' : 'Cerrada' }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div><span class="text-on-surface/40">Inicial:</span> <span class="text-on-surface">S/ {{ number_format($caja->monto_inicial, 2) }}</span></div>
                    <div><span class="text-on-surface/40">Ventas:</span> <span class="text-tertiary">S/ {{ number_format($ventasDelDia, 2) }}</span></div>
                    <div><span class="text-on-surface/40">Gastos:</span> <span class="text-error">S/ {{ number_format($gastosDelDia, 2) }}</span></div>
                    <div><span class="text-on-surface/40">Diff:</span> <span class="{{ $caja->diferencia >= 0 ? 'text-tertiary' : 'text-error' }}">{{ $caja->diferencia >= 0 ? '+' : '' }}S/ {{ number_format($caja->diferencia, 2) }}</span></div>
                </div>
            </a>
            @empty
            <p class="p-6 text-center text-on-surface/40">No hay cajas registradas.</p>
            @endforelse
        </div>
    </div>

    <div class="mt-6">{{ $cajas->links() }}</div>

    <div class="mt-8 text-center">
        <p class="text-[10px] text-on-surface/20 uppercase tracking-[0.3em]">Noir Concierge Digital Ledger v2.4.0</p>
    </div>
</div>
</x-app-layout>