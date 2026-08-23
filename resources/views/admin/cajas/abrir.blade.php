<x-app-layout>
<x-slot name="title">Abrir Caja</x-slot>

<style>
.glass-panel { background: rgba(32, 31, 32, 0.4); backdrop-filter: blur(20px); border: 1px solid rgba(89, 65, 62, 0.15); }
</style>

<div class="max-w-lg w-full px-4 lg:px-0">
    <div class="mb-6 lg:mb-8 flex items-center gap-4">
        <a href="{{ route('admin.cajas.index') }}" class="p-2 rounded-lg hover:bg-surface-container transition">
            <span class="material-symbols-outlined text-on-surface">arrow_back</span>
        </a>
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-on-surface">Abrir Caja</h2>
            <p class="text-on-surface/40 text-sm">Inicia un nuevo turno de trabajo.</p>
        </div>
    </div>

    @if($cajaAbierta)
    <div class="glass-panel rounded-xl p-6 border-l-4 border-tertiary mb-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-3 h-3 rounded-full bg-tertiary animate-pulse"></span>
            <span class="text-tertiary font-bold">Ya hay una caja abierta</span>
        </div>
        <p class="text-on-surface/60 mb-4">Operador: {{ $cajaAbierta->usuario->name }}</p>
        <p class="text-on-surface/60">Monto actual: S/ {{ number_format($cajaAbierta->monto_inicial, 2) }}</p>
    </div>
    @endif

    <div class="glass-panel rounded-xl p-6">
        <form method="POST" action="{{ route('admin.cajas.store') }}" class="space-y-6">
            @csrf

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1 mb-2">Fecha de Trabajo</label>
                    <input type="date" name="fecha_trabajo" value="{{ now()->format('Y-m-d') }}" required
                           class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1 mb-2">Monto Inicial (S/)</label>
                    <input type="number" name="monto_inicial" step="0.01" min="0" required placeholder="0.00"
                           class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary outline-none transition-all">
                    @if($ultimaCaja)
                    <p class="text-xs text-on-surface/40 mt-2 ml-1">Última caja: S/ {{ number_format($ultimaCaja->monto_arqueo, 2) }}</p>
                    @endif
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="{{ route('admin.cajas.index') }}" class="flex-1 py-3 bg-surface-container-high hover:bg-surface-bright text-on-surface font-semibold rounded-xl text-sm text-center transition">
                    Cancelar
                </a>
                <button type="submit" class="flex-1 sm:flex-[1.5] py-3 bg-error hover:opacity-90 text-on-error font-bold rounded-xl text-sm transition-all shadow-lg" @if($cajaAbierta) disabled @endif>
                    Abrir Caja
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>