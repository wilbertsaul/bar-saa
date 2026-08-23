<x-app-layout>
<x-slot name="title">Registrar Merma</x-slot>

<style>
.glass-panel { background: rgba(32, 31, 32, 0.4); backdrop-filter: blur(20px); border: 1px solid rgba(89, 65, 62, 0.15); }
</style>

<div class="max-w-md w-full">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.cajas.index') }}" class="p-2 rounded-lg hover:bg-surface-container transition">
            <span class="material-symbols-outlined text-on-surface">arrow_back</span>
        </a>
        <div>
            <h2 class="text-xl font-bold tracking-tight text-on-surface">Registrar Merma</h2>
            <p class="text-on-surface/40 text-sm">Productos perdidos o dañados.</p>
        </div>
    </div>

    <div class="glass-panel rounded-xl p-6">
        <form method="POST" action="{{ route('admin.cajas.merma.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="caja_id" value="{{ $caja->id }}">

            <div>
                <label class="block text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1 mb-1.5">Tipo</label>
                <select name="tipo" id="tipo-merma" required class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary outline-none transition-all">
                    <option value="producto">Producto</option>
                    <option value="efectivo">Efectivo</option>
                </select>
            </div>

            <div id="campo-producto">
                <label class="block text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1 mb-1.5">Producto</label>
                <select name="producto_id" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary outline-none transition-all">
                    <option value="">Seleccionar producto...</option>
                    @foreach($productos as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->nombre }} (Stock: {{ $prod->stock_actual }})</option>
                    @endforeach
                </select>
            </div>

            <div id="campo-cantidad">
                <label class="block text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1 mb-1.5">Cantidad</label>
                <input type="number" name="cantidad" min="1" value="1" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1 mb-1.5">Monto (S/)</label>
                <input type="number" name="monto" step="0.01" min="0.01" required placeholder="0.00"
                       class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1 mb-1.5">Descripción</label>
                <input type="text" name="descripcion" required placeholder="Razón de la merma"
                       class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary outline-none transition-all">
            </div>

            <button type="submit" class="w-full bg-error hover:opacity-90 text-on-error font-bold py-3 rounded-xl text-sm transition">
                Registrar Merma
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('tipo-merma').addEventListener('change', function() {
    const esProducto = this.value === 'producto';
    document.getElementById('campo-producto').style.display = esProducto ? '' : 'none';
    document.getElementById('campo-cantidad').style.display = esProducto ? '' : 'none';
});
</script>
@endpush
</x-app-layout>