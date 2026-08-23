<x-app-layout>
    <x-slot name="title">Dashboard — Midnight Concierge</x-slot>

    <!-- KPIs Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Ventas Diarias -->
        <div class="bg-surface-container p-6 rounded-xl relative overflow-hidden group hover:bg-surface-container-high transition-colors">
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined text-primary bg-primary-container/20 p-2 rounded-lg">monetization_on</span>
                    @if($cantidadVentasHoy > 0)
                    <span class="text-[10px] font-bold text-tertiary bg-tertiary-container/20 px-2 py-1 rounded">Activo</span>
                    @else
                    <span class="text-[10px] font-bold text-on-surface/40 bg-surface-container-high px-2 py-1 rounded">Sin ventas</span>
                    @endif
                </div>
                <p class="text-xs font-bold uppercase tracking-widest text-on-surface/40">Ventas del Día</p>
                <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tighter mt-1">S/ {{ number_format($totalVentasHoy, 2) }}</h2>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-[10px] text-on-surface/50">{{ $cantidadVentasHoy }} transacciones</span>
                </div>
            </div>
        </div>

        <!-- Ventas del Mes -->
        <div class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-colors">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-tertiary bg-tertiary-container/20 p-2 rounded-lg">calendar_month</span>
            </div>
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface/40">Ventas del Mes</p>
            <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tighter mt-1">S/ {{ number_format($totalVentasMes, 2) }}</h2>
        </div>

        <!-- Ticket Promedio -->
        <div class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-colors">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-primary bg-primary-container/20 p-2 rounded-lg">receipt_long</span>
            </div>
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface/40">Ticket Promedio Hoy</p>
            <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tighter mt-1">S/ {{ number_format($cantidadVentasHoy > 0 ? $totalVentasHoy / $cantidadVentasHoy : 0, 2) }}</h2>
        </div>

        <!-- Stock Bajo -->
        <a href="{{ route('admin.inventario') }}" class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-colors block {{ $stockCriticoCount > 0 ? 'border-l-4 border-primary' : '' }}">
            <div class="flex justify-between items-start mb-4">
                <span class="material-symbols-outlined text-primary bg-primary-container/20 p-2 rounded-lg">inventory_2</span>
                @if($stockCriticoCount > 0)
                <span class="material-symbols-outlined text-primary text-xl animate-pulse">warning</span>
                @endif
            </div>
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface/40">Stock Bajo</p>
            <div class="flex items-baseline gap-2 mt-1">
                <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tighter {{ $stockCriticoCount > 0 ? 'text-primary' : '' }}">{{ $stockCriticoCount }}</h2>
                <span class="text-sm text-on-surface/40">productos</span>
            </div>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
        <!-- Ventas por Método de Pago -->
        <div class="xl:col-span-2 space-y-4">
            <div class="flex justify-between items-end">
                <div>
                    <h3 class="text-lg font-bold tracking-tight">Ventas del Día por Método de Pago</h3>
                    <p class="text-[10px] text-on-surface/40 uppercase tracking-widest">Precio carta: efectivo · Yape · Plin — Precio tarjeta: crédito y débito</p>
                </div>
                <a href="{{ route('admin.reportes.ventas') }}" class="text-[10px] font-bold text-primary uppercase tracking-widest hover:underline hidden sm:block">Ver Histórico</a>
            </div>
            <div class="bg-surface-container rounded-xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-high">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface/40">Método</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface/40">Total Vendido</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface/40 text-right">Proporción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @foreach(\App\Models\Venta::TIPOS_PAGO as $key => $label)
                        <tr class="{{ ($ventasPorPago[$key] ?? 0) > 0 ? 'hover:bg-surface-container-high/50 transition-colors' : 'opacity-40' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-on-surface/50">{{ match($key) { 'efectivo' => 'payments', 'yape' => 'smartphone', 'plin' => 'smartphone', default => 'credit_card' } }}</span>
                                    <span class="text-sm font-bold">{{ $label }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">S/ {{ number_format($ventasPorPago[$key] ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="w-full bg-surface-container-highest rounded-full h-1.5 max-w-[200px] ml-auto">
                                    <div class="ruby-gradient h-1.5 rounded-full" style="width: {{ $totalVentasHoy > 0 ? round((($ventasPorPago[$key] ?? 0) / $totalVentasHoy) * 100) : 0 }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inventario Crítico -->
        <div class="space-y-4">
            <div class="flex justify-between items-end">
                <div>
                    <h3 class="text-lg font-bold tracking-tight">Inventario Crítico</h3>
                    <p class="text-[10px] text-on-surface/40 uppercase tracking-widest">Productos bajo stock mínimo</p>
                </div>
                @if($stockCriticoCount > 0)
                <span class="material-symbols-outlined text-primary">warning</span>
                @endif
            </div>
            <div class="bg-surface-container rounded-xl p-4 space-y-3">
                @forelse($productosStockBajo as $producto)
                <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-lg {{ $producto->stock_actual <= 2 ? 'border-l-2 border-primary' : 'border-l-2 border-amber-500/50' }}">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-on-surface truncate">{{ $producto->nombre }}</p>
                        <p class="text-[9px] {{ $producto->stock_actual <= 2 ? 'text-primary' : 'text-amber-500' }} uppercase font-bold mt-0.5">
                            @if($producto->stock_actual <= 2)
                            ¡Quedan {{ $producto->stock_actual }} unidades!
                            @else
                            Quedan: {{ $producto->stock_actual }} unidades
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('admin.productos.index') }}" class="material-symbols-outlined text-primary/60 hover:text-primary transition-colors ml-2">shopping_cart</a>
                </div>
                @empty
                <div class="text-center py-6 text-on-surface/40">
                    <span class="material-symbols-outlined text-3xl">check_circle</span>
                    <p class="text-xs mt-2">Stock en niveles normales</p>
                </div>
                @endforelse
                @if($stockCriticoCount > 5)
                <a href="{{ route('admin.inventario') }}" class="w-full py-3 bg-surface-container-highest text-on-surface text-[10px] font-bold uppercase tracking-widest rounded-lg hover:bg-surface-bright transition-colors text-center block">
                    Ver {{ $stockCriticoCount - 5 }} productos más
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos (Mobile) -->
    <div class="lg:hidden mb-8">
        <h3 class="text-lg font-bold tracking-tight mb-4">Accesos Rápidos</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('pos.index') }}" class="flex flex-col items-center justify-center bg-surface-container p-6 rounded-xl ruby-gradient text-white transition-transform active:scale-95">
                <span class="material-symbols-outlined text-3xl mb-2">point_of_sale</span>
                <span class="text-sm font-bold">Punto de Venta</span>
            </a>
            <a href="{{ route('admin.cajas.index') }}" class="flex flex-col items-center justify-center bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-3xl mb-2 text-tertiary">point_of_sale</span>
                <span class="text-sm font-bold">Caja</span>
            </a>
        </div>
    </div>

    <!-- Auditoría Reciente -->
    @if(auth()->user()->isAdmin() && $ultimaActividad->count() > 0)
    <section class="space-y-4">
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-lg font-bold tracking-tight">Actividad Reciente</h3>
                <p class="text-[10px] text-on-surface/40 uppercase tracking-widest">Registro de eventos del sistema</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.auditoria.index') }}" class="bg-surface-container-high px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-surface-bright transition-colors">Ver Todo</a>
            </div>
        </div>
        <div class="bg-surface-container rounded-xl">
            <div class="p-2 space-y-1 max-h-64 overflow-y-auto">
                @foreach($ultimaActividad as $log)
                <div class="flex items-center gap-4 p-4 hover:bg-surface-container-high rounded-lg transition-colors">
                    <span class="text-[10px] font-bold text-on-surface/20 w-16 flex-shrink-0">
                        {{ $log->realizado_en->format('H:i') }}
                    </span>
                    @if($log->accion === 'crear' || $log->accion === 'finalizar')
                    <span class="material-symbols-outlined text-tertiary flex-shrink-0">check_circle</span>
                    @elseif($log->accion === 'actualizar')
                    <span class="material-symbols-outlined text-primary flex-shrink-0">edit_square</span>
                    @elseif($log->accion === 'anular')
                    <span class="material-symbols-outlined text-error flex-shrink-0">cancel</span>
                    @else
                    <span class="material-symbols-outlined text-on-surface/40 flex-shrink-0">info</span>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm">
                            <span class="capitalize font-bold">{{ $log->accion }}</span> 
                            <span class="text-on-surface/60">{{ $log->modelo }}</span>
                            @if($log->user)
                            <span class="text-on-surface/60">por</span>
                            <span class="font-bold">{{ $log->user->name }}</span>
                            @endif
                        </p>
                        <p class="text-[10px] text-on-surface/40 truncate">{{ $log->descripcion ?? 'Sin descripción' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Quick Action FAB -->
    <a href="{{ route('pos.index') }}" class="fixed bottom-6 right-6 w-14 h-14 ruby-gradient rounded-full shadow-[0_10px_30px_-5px_rgba(161,0,14,0.6)] flex items-center justify-center text-white active:scale-95 transition-transform z-30">
        <span class="material-symbols-outlined text-2xl">point_of_sale</span>
    </a>
</x-app-layout>
