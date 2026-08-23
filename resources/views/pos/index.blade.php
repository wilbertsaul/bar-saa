<x-app-layout>
<x-slot name="title">Punto de Venta — Midnight Concierge</x-slot>

@php
    $productosJs = $productos->map(fn ($p) => [
        'id' => $p->id,
        'nombre' => $p->nombre,
        'categoria' => $p->categoria,
        'precio_venta' => (float) $p->precio_venta,
        'precio_tarjeta' => (float) $p->precio_tarjeta,
        'stock' => $p->stock_actual,
    ])->values();
@endphp

<script>
    window.productos = @json($productosJs);
    window.cajaAbierta = @json($cajaAbierta ? true : false);
    window.tiposPago = {
        'efectivo': { label: 'Efectivo', icono: 'payments', precio: 'venta', color: 'tertiary' },
        'yape': { label: 'Yape', icono: 'smartphone', precio: 'venta', color: 'primary' },
        'plin': { label: 'Plin', icono: 'smartphone', precio: 'venta', color: 'primary' },
        'tarjeta_credito': { label: 'T. Crédito', icono: 'credit_card', precio: 'tarjeta', color: 'primary' },
        'tarjeta_debito': { label: 'T. Débito', icono: 'credit_card', precio: 'tarjeta', color: 'primary' }
    };
</script>

<!-- Header móvil -->
<div class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-surface/90 backdrop-blur-xl border-b border-outline-variant/10 px-4 py-3">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">liquor</span>
            <div>
                <h1 class="text-lg font-bold tracking-tight text-primary">NOIR POS</h1>
                <p id="header-caja-status" class="text-[10px] {{ $cajaAbierta ? 'text-on-surface/50' : 'text-error' }}">
                    {{ $cajaAbierta ? 'Caja #' . $cajaAbierta->id . ' abierta' : 'Sin caja' }}
                </p>
            </div>
        </div>
        <button onclick="abrirCarrito()" class="bg-primary-container text-white px-4 py-2 rounded-xl flex items-center gap-2 relative active:scale-95 transition-transform">
            <span class="material-symbols-outlined text-lg">shopping_cart</span>
            <span id="cart-count-mobile" class="text-xs font-bold">0</span>
            <span id="cart-total-mobile" class="text-xs font-bold">S/ 0.00</span>
            <span id="cart-badge" class="hidden absolute -top-1 -right-1 w-5 h-5 bg-tertiary rounded-full text-[10px] font-bold text-on-tertiary items-center justify-center">0</span>
        </button>
    </div>
</div>

<div class="pt-16 lg:pt-0 pb-28 lg:pb-0">

<!-- Desktop Header -->
<div class="hidden lg:block mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-on-surface tracking-tight">Punto de Venta</h1>
            @if(!$cajaAbierta)
            <p class="text-sm text-error flex items-center gap-2">⚠️ Debes abrir una caja para operar.
                <a href="{{ route('admin.cajas.abrir') }}" class="underline hover:text-primary">Abrir Caja</a>
            </p>
            @else
            <p class="text-sm text-on-surface/50">Caja #{{ $cajaAbierta->id }} · {{ $cajaAbierta->usuario->name }} · Apertura {{ $cajaAbierta->hora_apertura }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs text-on-surface/40 uppercase tracking-widest">{{ $productos->count() }} productos en catálogo</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- ── Catálogo ─────────────────────────────── -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Búsqueda y filtros -->
        <div class="flex gap-3 overflow-x-auto no-scrollbar py-1 lg:flex-wrap lg:overflow-visible">
            <div class="bg-surface-container rounded-xl flex items-center px-4 gap-3 border border-outline-variant/15 flex-shrink-0 min-w-[200px] lg:flex-1">
                <span class="material-symbols-outlined text-on-surface-variant text-lg">search</span>
                <input id="buscar-producto" class="bg-transparent border-none focus:ring-0 text-sm w-full placeholder:text-on-surface-variant/50 text-on-surface" placeholder="Buscar trago..." type="text"/>
            </div>
            <button class="filtro-btn px-4 py-1.5 rounded-full bg-primary-container border border-primary text-white text-xs font-medium whitespace-nowrap" data-filtro="todos">Todos</button>
            <button class="filtro-btn px-4 py-1.5 rounded-full bg-surface-container text-on-surface-variant text-xs font-medium whitespace-nowrap" data-filtro="whisky">Whisky</button>
            <button class="filtro-btn px-4 py-1.5 rounded-full bg-surface-container text-on-surface-variant text-xs font-medium whitespace-nowrap" data-filtro="ron">Ron</button>
            <button class="filtro-btn px-4 py-1.5 rounded-full bg-surface-container text-on-surface-variant text-xs font-medium whitespace-nowrap" data-filtro="cerveza">Cerveza</button>
            <button class="filtro-btn px-4 py-1.5 rounded-full bg-surface-container text-on-surface-variant text-xs font-medium whitespace-nowrap" data-filtro="sin_alcohol">Sin alcohol</button>
        </div>

        <!-- Grid productos -->
        <div id="grid-productos" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @foreach($productos as $prod)
            <button type="button"
                    onclick="agregarAlCarrito({{ $prod->id }})"
                    class="producto-card bg-surface-container-low rounded-xl p-4 border border-outline-variant/15 text-left flex flex-col gap-2 hover:border-primary/40 hover:bg-surface-container transition-all active:scale-[0.98] {{ $prod->stock_actual <= 0 ? 'opacity-40' : '' }}"
                    data-id="{{ $prod->id }}"
                    data-nombre="{{ strtolower($prod->nombre) }}"
                    data-categoria="{{ $prod->categoria }}">
                <div class="flex items-start justify-between gap-2">
                    <span class="material-symbols-outlined text-on-surface-variant">{{ match($prod->categoria) { 'whisky' => 'local_bar', 'ron' => 'wine_bar', 'cerveza' => 'sports_bar', default => 'local_drink' } }}</span>
                    <span class="text-[9px] uppercase tracking-widest px-2 py-0.5 rounded-full {{ $prod->tieneStockBajo() ? 'bg-primary/20 text-primary' : 'bg-surface-container-high text-on-surface/40' }}">Stock {{ $prod->stock_actual }}</span>
                </div>
                <p class="text-sm font-bold text-on-surface leading-tight flex-1">{{ $prod->nombre }}</p>
                <div class="flex items-baseline justify-between">
                    <span class="text-lg font-extrabold text-tertiary">S/ {{ number_format($prod->precio_venta, 2) }}</span>
                    @if((float) $prod->precio_tarjeta > (float) $prod->precio_venta)
                    <span class="text-[10px] text-primary">Tarj. S/ {{ number_format($prod->precio_tarjeta, 2) }}</span>
                    @endif
                </div>
            </button>
            @endforeach
        </div>
        <div id="sin-resultados" class="hidden text-center py-12">
            <span class="material-symbols-outlined text-on-surface/30 text-5xl mb-2 block">search_off</span>
            <p class="text-on-surface/40 text-sm">Sin resultados para tu búsqueda.</p>
        </div>

        <!-- Ventas del día -->
        <div class="mt-8">
            <h3 class="text-sm font-bold text-on-surface mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-on-surface-variant">receipt_long</span>
                Ventas de hoy
            </h3>
            <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                @forelse($ventasHoy as $venta)
                <div class="bg-surface-container-low border border-outline-variant/10 rounded-xl p-3 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs text-on-surface/60 truncate">
                            @foreach($venta->detalles as $d){{ $d->cantidad }}× {{ $d->producto->nombre }}@if(!$loop->last), @endif@endforeach
                        </p>
                        <p class="text-[10px] text-on-surface/40 mt-0.5">{{ $venta->fecha_hora->format('H:i') }} · {{ \App\Models\Venta::TIPOS_PAGO[$venta->tipo_pago] ?? $venta->tipo_pago }} · {{ $venta->user->name }}</p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="font-bold text-tertiary text-sm">S/ {{ number_format($venta->total, 2) }}</span>
                        <form onsubmit="anularVenta(event, {{ $venta->id }}, this)">
                            @csrf
                            @method('PATCH')
                            <button type="submit" title="Anular venta" class="text-error/70 hover:text-error transition-colors p-1">
                                <span class="material-symbols-outlined text-base">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-on-surface/40 text-sm text-center py-4">Aún no hay ventas registradas hoy.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ── Carrito (desktop) ─────────────────────────────── -->
    <div class="hidden lg:block">
        <div class="sticky top-24 bg-surface-container-low rounded-xl border border-outline-variant/15 overflow-hidden">
            <div class="px-5 py-4 border-b border-outline-variant/10 flex items-center justify-between">
                <h3 class="text-sm font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">shopping_cart</span> Venta Actual
                </h3>
                <button onclick="vaciarCarrito()" class="text-xs text-on-surface/40 hover:text-error transition-colors">Vaciar</button>
            </div>

            <div id="carrito-items-desktop" class="max-h-[45vh] overflow-y-auto divide-y divide-outline-variant/5"></div>
            <div id="carrito-vacio" class="py-14 text-center">
                <span class="material-symbols-outlined text-on-surface/20 text-5xl mb-2 block">add_shopping_cart</span>
                <p class="text-on-surface/40 text-sm">Toca un producto para agregarlo</p>
            </div>

            <div class="p-5 space-y-4 border-t border-outline-variant/10">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-2">Método de pago</p>
                    <div id="pagos-grid" class="grid grid-cols-2 gap-2"></div>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-outline-variant/10">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-on-surface/40">Total a cobrar</p>
                        <p id="precio-nota" class="text-[10px] text-on-surface/40"></p>
                    </div>
                    <p id="total-carrito" class="text-2xl font-extrabold text-tertiary">S/ 0.00</p>
                </div>
                <button id="btn-confirmar" onclick="confirmarVenta()" disabled
                        class="w-full py-4 bg-tertiary text-on-tertiary font-extrabold rounded-xl text-base disabled:opacity-30 disabled:cursor-not-allowed hover:bg-tertiary/90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">point_of_sale</span> Confirmar Venta
                </button>
            </div>
        </div>
    </div>
</div>
</div>

<!-- ── Modal carrito móvil ─────────────────────────────── -->
<div id="modal-carrito" class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="cerrarCarrito()"></div>
    <div class="relative w-full sm:max-w-md bg-surface-container-low rounded-t-2xl sm:rounded-2xl border border-outline-variant/15 max-h-[85vh] flex flex-col">
        <div class="px-5 py-4 border-b border-outline-variant/10 flex items-center justify-between">
            <h3 class="text-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">shopping_cart</span> Venta Actual
            </h3>
            <button onclick="cerrarCarrito()" class="material-symbols-outlined text-on-surface/40 hover:text-on-surface">close</button>
        </div>
        <div class="overflow-y-auto flex-1">
            <div id="carrito-items-mobile" class="divide-y divide-outline-variant/5"></div>
            <div id="carrito-vacio-mobile" class="py-14 text-center">
                <p class="text-on-surface/40 text-sm">El carrito está vacío</p>
            </div>
        </div>
        <div class="p-5 space-y-4 border-t border-outline-variant/10">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-2">Método de pago</p>
                <div id="pagos-grid-mobile" class="grid grid-cols-2 gap-2"></div>
            </div>
            <div class="flex justify-between items-center">
                <p class="text-[10px] uppercase tracking-widest text-on-surface/40">Total</p>
                <p id="total-carrito-mobile" class="text-2xl font-extrabold text-tertiary">S/ 0.00</p>
            </div>
            <button id="btn-confirmar-mobile" onclick="confirmarVenta()" disabled
                    class="w-full py-4 bg-tertiary text-on-tertiary font-extrabold rounded-xl disabled:opacity-30 hover:bg-tertiary/90 transition-all">
                Confirmar Venta
            </button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="hidden fixed bottom-4 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-xl shadow-lg text-sm font-semibold flex items-center gap-2"></div>

@push('styles')
<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.filtro-btn.activo { background: #a1000e !important; border-color: #ffb4ab !important; color: white !important; }
.pago-btn { border: 1px solid rgba(89,65,62,.25); background: #201f20; color: #e5e2e3; padding: .55rem .5rem; border-radius: .65rem; display: flex; align-items: center; gap: .4rem; font-size: .75rem; font-weight: 700; transition: all .15s; }
.pago-btn:hover { background: #2a2a2b; }
.pago-btn.activo { background: #a1000e; border-color: #ffb4ab; color: white; box-shadow: 0 4px 14px -4px rgba(161,0,14,.7); }
.cart-item-qty { min-width: 2rem; text-align: center; font-weight: 800; }
.qty-btn { width: 1.9rem; height: 1.9rem; border-radius: .55rem; background: #353436; color: #e5e2e3; display: inline-flex; align-items: center; justify-content: center; transition: all .15s; }
.qty-btn:hover { background: #a1000e; color: white; }
.spin { animation: spin 1s linear infinite; }
@keyframes spin { from { transform: rotate(0deg);} to { transform: rotate(360deg);} }
</style>
@endpush

@push('scripts')
<script>
let carrito = {};          // { producto_id: cantidad }
let tipoPago = null;
let filtroActual = 'todos';
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// ── Pago ──
function renderPagos() {
    ['pagos-grid', 'pagos-grid-mobile'].forEach(id => {
        const cont = document.getElementById(id);
        if (!cont) return;
        cont.innerHTML = '';
        Object.entries(tiposPago).forEach(([key, cfg]) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'pago-btn' + (tipoPago === key ? ' activo' : '');
            btn.innerHTML = `<span class="material-symbols-outlined" style="font-size:1rem">${cfg.icono}</span>${cfg.label}`;
            btn.onclick = () => seleccionarPago(key);
            cont.appendChild(btn);
        });
    });
}

function seleccionarPago(key) {
    tipoPago = key;
    renderPagos();
    actualizarTotal();
}

// ── Carrito ──
function agregarAlCarrito(id) {
    if (!cajaAbierta) { mostrarToast('error', 'Debes abrir una caja primero.'); return; }
    const prod = productos.find(p => p.id === id);
    const actual = carrito[id] || 0;
    if (actual + 1 > prod.stock) { mostrarToast('error', `Stock insuficiente de ${prod.nombre}.`); return; }
    carrito[id] = actual + 1;
    renderCarrito();
}

function cambiarCantidad(id, delta) {
    const prod = productos.find(p => p.id === id);
    const nueva = (carrito[id] || 0) + delta;
    if (nueva <= 0) { delete carrito[id]; }
    else if (nueva > prod.stock) { mostrarToast('error', `Solo hay ${prod.stock} unidades.`); return; }
    else { carrito[id] = nueva; }
    renderCarrito();
}

function vaciarCarrito() { carrito = {}; renderCarrito(); }

function precioDe(prod) {
    return tiposPago[tipoPago]?.precio === 'tarjeta' ? prod.precio_tarjeta : prod.precio_venta;
}

function totalActual() {
    return Object.entries(carrito).reduce((sum, [id, qty]) => {
        const prod = productos.find(p => p.id == id);
        return sum + (prod ? precioDe(prod) * qty : 0);
    }, 0);
}

function itemHtml(id, qty) {
    const prod = productos.find(p => p.id == id);
    const precio = precioDe(prod);
    return `
    <div class="flex items-center gap-3 px-5 py-3">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-on-surface truncate">${prod.nombre}</p>
            <p class="text-[11px] text-on-surface/40">S/ ${precio.toFixed(2)} × ${qty} = <span class="text-tertiary font-bold">S/ ${(precio * qty).toFixed(2)}</span></p>
        </div>
        <div class="flex items-center gap-1.5 flex-shrink-0">
            <button type="button" class="qty-btn material-symbols-outlined" style="font-size:1rem" onclick="cambiarCantidad(${id}, -1)">remove</button>
            <span class="cart-item-qty text-sm">${qty}</span>
            <button type="button" class="qty-btn material-symbols-outlined" style="font-size:1rem" onclick="cambiarCantidad(${id}, 1)">add</button>
        </div>
    </div>`;
}

function renderCarrito() {
    const ids = Object.keys(carrito);
    const hayItems = ids.length > 0;
    const desktop = document.getElementById('carrito-items-desktop');
    const mobile = document.getElementById('carrito-items-mobile');

    [desktop, mobile].forEach(cont => { if (cont) cont.innerHTML = ids.map(id => itemHtml(id, carrito[id])).join(''); });

    document.getElementById('carrito-vacio')?.classList.toggle('hidden', hayItems);
    document.getElementById('carrito-vacio-mobile')?.classList.toggle('hidden', hayItems);

    const count = Object.values(carrito).reduce((a, b) => a + b, 0);
    document.getElementById('cart-count-mobile').textContent = count;
    document.getElementById('cart-badge')?.classList.toggle('hidden', count === 0);
    document.getElementById('cart-badge').textContent = count;

    actualizarTotal();
}

function actualizarTotal() {
    const total = totalActual();
    document.getElementById('total-carrito').textContent = 'S/ ' + total.toFixed(2);
    document.getElementById('total-carrito-mobile').textContent = 'S/ ' + total.toFixed(2);
    document.getElementById('cart-total-mobile').textContent = 'S/ ' + total.toFixed(2);

    const nota = tipoPago && tiposPago[tipoPago].precio === 'tarjeta'
        ? 'Precios con tarjeta aplicados'
        : 'Precios de carta aplicados';
    document.getElementById('precio-nota').textContent = nota;

    const habilitado = total > 0 && tipoPago !== null && cajaAbierta;
    document.getElementById('btn-confirmar').disabled = !habilitado;
    document.getElementById('btn-confirmar-mobile').disabled = !habilitado;
}

// ── Confirmar ──
async function confirmarVenta() {
    if (Object.keys(carrito).length === 0 || !tipoPago) return;
    const btns = [document.getElementById('btn-confirmar'), document.getElementById('btn-confirmar-mobile')];
    btns.forEach(b => { b.disabled = true; b.innerHTML = '<span class="material-symbols-outlined spin">progress_activity</span> Procesando...'; });

    const items = Object.entries(carrito).map(([id, cantidad]) => ({ producto_id: Number(id), cantidad }));

    try {
        const res = await fetch('{{ route("pos.venta.storeApi") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ tipo_pago: tipoPago, items })
        });
        const data = await res.json();

        if (data.success) {
            mostrarToast('success', data.message);
            setTimeout(() => location.reload(), 900);
        } else {
            mostrarToast('error', data.message || 'Error al registrar la venta.');
        }
    } catch (e) {
        mostrarToast('error', 'Error de conexión.');
    } finally {
        btns.forEach(b => { b.disabled = false; b.innerHTML = 'Confirmar Venta'; });
    }
}

async function anularVenta(e, id, form) {
    e.preventDefault();
    if (!confirm('¿Anular esta venta? El stock será restaurado.')) return;
    try {
        const res = await fetch(`{{ url('pos/venta') }}/${id}/anular`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': new FormData(form).get('_token'), 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (res.ok || res.status === 403) {
            if (res.redirected) { location.href = res.url; return; }
            const html = await res.text();
            location.reload();
        }
    } catch (err) { location.reload(); }
}

// ── UI helpers ──
function abrirCarrito() { document.getElementById('modal-carrito').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
function cerrarCarrito() { document.getElementById('modal-carrito').classList.add('hidden'); document.body.style.overflow = ''; }

function mostrarToast(tipo, msg) {
    const toast = document.getElementById('toast');
    toast.className = `fixed bottom-4 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-xl shadow-lg text-sm font-semibold flex items-center gap-2 ${tipo === 'success' ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-error-container text-on-error-container'} border ${tipo === 'success' ? 'border-tertiary' : 'border-error'}`;
    toast.innerHTML = `<span class="material-symbols-outlined">${tipo === 'success' ? 'check_circle' : 'error'}</span>${msg}`;
    toast.classList.remove('hidden');
    clearTimeout(toast._t);
    toast._t = setTimeout(() => toast.classList.add('hidden'), 3500);
}

// Filtros
document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo'));
        btn.classList.add('activo');
        filtroActual = btn.dataset.filtro;
        aplicarFiltros();
    });
});

document.getElementById('buscar-producto')?.addEventListener('input', aplicarFiltros);

function aplicarFiltros() {
    const texto = document.getElementById('buscar-producto')?.value.toLowerCase().trim() ?? '';
    let visibles = 0;
    document.querySelectorAll('.producto-card').forEach(card => {
        const okTexto = card.dataset.nombre.includes(texto);
        const okCat = filtroActual === 'todos' || card.dataset.categoria === filtroActual;
        const visible = okTexto && okCat;
        card.classList.toggle('hidden', !visible);
        if (visible) visibles++;
    });
    document.getElementById('sin-resultados').classList.toggle('hidden', visibles > 0);
}

renderPagos();
renderCarrito();
</script>
@endpush
</x-app-layout>
