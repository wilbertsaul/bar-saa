@php $p = $producto ?? null; @endphp

<div class="space-y-4">
    <div>
        <label class="text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1">Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre', $p?->nombre) }}" required
               class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all">
    </div>

    <div>
        <label class="text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1">Categoría</label>
        <select name="categoria" class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all">
            @foreach(['ron' => 'Ron', 'whisky' => 'Whisky', 'cerveza' => 'Cerveza', 'sin_alcohol' => 'Sin alcohol'] as $val => $label)
            <option value="{{ $val }}" {{ old('categoria', $p?->categoria) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1">Precio Carta (S/) — efectivo/Yape/Plin</label>
            <input type="number" name="precio_venta" value="{{ old('precio_venta', $p?->precio_venta) }}" step="0.10" min="0" required
                   class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all">
        </div>
        <div>
            <label class="text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1">Precio Tarjeta (S/) — crédito/débito</label>
            <input type="number" name="precio_tarjeta" value="{{ old('precio_tarjeta', $p?->precio_tarjeta) }}" step="0.10" min="0" required
                   class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all">
        </div>
        <div class="col-span-2">
            <label class="text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1">Precio costo (S/)</label>
            <input type="number" name="precio_costo" value="{{ old('precio_costo', $p?->precio_costo) }}" step="0.10" min="0"
                   class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        @if(!$p)
        <div>
            <label class="text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1">Stock inicial</label>
            <input type="number" name="stock_actual" value="{{ old('stock_actual', 0) }}" min="0" required
                   class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all">
        </div>
        @endif
        <div>
            <label class="text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1">Stock mínimo</label>
            <input type="number" name="stock_minimo" value="{{ old('stock_minimo', $p?->stock_minimo ?? 10) }}" min="0" required
                   class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all">
        </div>
    </div>

    <div>
        <label class="text-xs font-bold text-on-surface/60 uppercase tracking-wider ml-1">Unidades por caja</label>
        <input type="number" name="unidades_por_caja" value="{{ old('unidades_por_caja', $p?->unidades_por_caja ?? 1) }}" min="1" required
               class="w-full bg-surface-container-lowest border border-outline-variant/15 rounded-xl px-4 py-3 text-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all">
    </div>
</div>