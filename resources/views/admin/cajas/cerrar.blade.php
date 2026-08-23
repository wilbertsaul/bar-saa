<x-app-layout>
<x-slot name="title">Cerrar Caja</x-slot>

<style>
.glass-panel { background: rgba(32, 31, 32, 0.4); backdrop-filter: blur(20px); border: 1px solid rgba(89, 65, 62, 0.15); }
</style>

<div class="max-w-lg lg:max-w-3xl w-full px-4 lg:px-0">
    <div class="mb-6 lg:mb-8 flex items-center gap-4">
        <a href="{{ route('admin.cajas.index') }}" class="p-2 rounded-lg hover:bg-surface-container transition">
            <span class="material-symbols-outlined text-on-surface">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-on-surface">Cerrar Caja</h2>
            <p class="text-on-surface/40 text-sm">Registra el arqueo y resumen del turno.</p>
        </div>
    </div>

    <div class="glass-panel rounded-xl p-6 mb-6">
        <h3 class="text-[10px] font-bold text-primary tracking-[0.2em] uppercase mb-4">Resumen del Turno</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-surface-container-high rounded-xl p-4">
                <p class="text-xs text-on-surface/40 uppercase tracking-wider">Fecha</p>
                <p class="text-lg font-bold text-on-surface">{{ $caja->fecha_trabajo->format('d/m/Y') }}</p>
            </div>
            <div class="bg-surface-container-high rounded-xl p-4">
                <p class="text-xs text-on-surface/40 uppercase tracking-wider">Operador</p>
                <p class="text-lg font-bold text-on-surface">{{ $caja->usuario->name }}</p>
            </div>
            <div class="bg-surface-container-high rounded-xl p-4">
                <p class="text-xs text-on-surface/40 uppercase tracking-wider">Hora Apertura</p>
                <p class="text-lg font-bold text-on-surface">{{ $caja->hora_apertura }}</p>
            </div>
            <div class="bg-surface-container-high rounded-xl p-4">
                <p class="text-xs text-on-surface/40 uppercase tracking-wider">Hora Cierre</p>
                <p class="text-lg font-bold text-on-surface">{{ now()->format('H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="glass-panel rounded-xl p-6 mb-6">
        <h3 class="text-[10px] font-bold text-tertiary tracking-[0.2em] uppercase mb-4">Cálculo Automático</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-on-surface/60">Monto Inicial</span>
                <span class="text-on-surface font-medium">S/ {{ number_format($caja->monto_inicial, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-on-surface/60">+ Ventas</span>
                <span class="text-tertiary font-medium">S/ {{ number_format($totalVentas, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-on-surface/60">- Gastos</span>
                <span class="text-error font-medium">-S/ {{ number_format($totalGastos, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-on-surface/60">- Mermas</span>
                <span class="text-error font-medium">-S/ {{ number_format($totalMermas, 2) }}</span>
            </div>
            <hr class="border-outline-variant/20">
            <div class="flex justify-between text-lg">
                <span class="text-on-surface font-bold">Arqueo Esperado</span>
                <span class="text-on-surface font-bold">S/ {{ number_format($arqueoEsperado, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="glass-panel rounded-xl p-6 mb-6">
        <h3 class="text-[10px] font-bold text-primary tracking-[0.2em] uppercase mb-4">Arqueo Real</h3>
        <form method="POST" action="{{ route('admin.cajas.cerrar.store', $caja) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1 mb-2">Efectivo en Caja</label>
                <input type="number" name="monto_arqueo" step="0.01" min="0" required placeholder="0.00"
                       class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-lg text-on-surface focus:ring-1 focus:ring-primary outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1 mb-2">Observaciones</label>
                <textarea name="observaciones" rows="2" placeholder="Notas adicionales..."
                          class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary outline-none transition-all"></textarea>
            </div>
            <button type="submit" id="btn-cerrar-caja" class="w-full py-4 bg-tertiary hover:bg-tertiary/80 text-on-tertiary font-bold rounded-xl text-lg transition-all">
                Cerrar Caja
            </button>
        </form>
    </div>

    @if($gastos->count() > 0)
    <div class="glass-panel rounded-xl p-6 mb-6">
        <h3 class="text-[10px] font-bold text-error tracking-[0.2em] uppercase mb-4">Gastos Registrados</h3>
        <div class="space-y-2">
            @foreach($gastos as $gasto)
            <div class="flex justify-between bg-surface-container-high rounded-lg p-3">
                <div>
                    <p class="text-sm text-on-surface">{{ $gasto->descripcion }}</p>
                    <p class="text-xs text-on-surface/40">{{ $gasto->tipo }}</p>
                </div>
                <p class="text-error font-medium">-S/ {{ number_format($gasto->monto, 2) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.querySelector('form[action*="cerrar"]').addEventListener('submit', function(e) {
    const btn = document.getElementById('btn-cerrar-caja');
    btn.disabled = true;
    btn.textContent = 'Cerrando...';
});
</script>
@endpush
</x-app-layout>