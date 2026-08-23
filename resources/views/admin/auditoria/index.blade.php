<x-app-layout>
<x-slot name="title">Auditoría</x-slot>

<h1 class="text-xl font-bold text-gray-100 mb-4">Auditoría del sistema</h1>

<form method="GET" class="flex flex-wrap gap-3 mb-6 bg-gray-900 border border-gray-800 rounded-xl p-4">
    <div>
        <label class="text-xs text-gray-500 block mb-1">Modelo</label>
        <select name="modelo" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-600">
            <option value="">Todos</option>
            @foreach($modelos as $m)
            <option value="{{ $m }}" {{ request('modelo') === $m ? 'selected' : '' }}>{{ $m }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-xs text-gray-500 block mb-1">Acción</label>
        <select name="accion" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-600">
            <option value="">Todas</option>
            @foreach($acciones as $a)
            <option value="{{ $a }}" {{ request('accion') === $a ? 'selected' : '' }}>{{ $a }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-xs text-gray-500 block mb-1">Fecha</label>
        <input type="date" name="fecha" value="{{ request('fecha') }}"
               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-600">
    </div>
    <div class="flex items-end gap-2">
        <button type="submit" class="bg-primary hover:opacity-90 text-on-primary text-sm px-4 py-2 rounded-lg transition">Filtrar</button>
        <a href="{{ route('admin.auditoria.index') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm px-4 py-2 rounded-lg transition">Limpiar</a>
    </div>
</form>

<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[800px]">
        <thead>
            <tr class="border-b border-gray-800 text-xs text-gray-500 uppercase tracking-wide">
                <th class="text-left px-4 py-3">Fecha/Hora</th>
                <th class="text-left px-4 py-3">Usuario</th>
                <th class="text-left px-4 py-3">Acción</th>
                <th class="text-left px-4 py-3">Modelo</th>
                <th class="text-left px-4 py-3">IP</th>
                <th class="text-left px-4 py-3">Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition group">
                <td class="px-4 py-2.5 text-xs text-gray-400">{{ $log->realizado_en->format('d/m H:i:s') }}</td>
                <td class="px-4 py-2.5 text-gray-300 text-xs">{{ $log->user->name }}</td>
                <td class="px-4 py-2.5">
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $log->accion === 'crear' ? 'bg-green-900 text-green-300' : '' }}
                        {{ $log->accion === 'actualizar' ? 'bg-blue-900 text-blue-300' : '' }}
                        {{ str_contains($log->accion, 'elim') || $log->accion === 'anular' ? 'bg-red-900 text-red-300' : '' }}
                        {{ !in_array($log->accion, ['crear','actualizar','eliminar','anular']) ? 'bg-gray-700 text-gray-400' : '' }}">
                        {{ $log->accion }}
                    </span>
                </td>
                <td class="px-4 py-2.5 text-gray-400 text-xs">{{ $log->modelo }} #{{ $log->modelo_id }}</td>
                <td class="px-4 py-2.5 text-xs text-gray-600">{{ $log->ip_address }}</td>
                <td class="px-4 py-2.5">
                    @if($log->data_anterior || $log->data_nueva)
                    <button onclick="toggleDetalle({{ $log->id }})" class="text-xs text-gray-500 hover:text-gray-300 transition">ver ▾</button>
                    <div id="detalle-{{ $log->id }}" class="hidden mt-2">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            @if($log->data_anterior)
                            <div class="bg-red-950/30 rounded p-2 text-red-300 font-mono overflow-x-auto max-w-xs">
                                {{ json_encode($log->data_anterior, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                            </div>
                            @endif
                            @if($log->data_nueva)
                            <div class="bg-green-950/30 rounded p-2 text-green-300 font-mono overflow-x-auto max-w-xs">
                                {{ json_encode($log->data_nueva, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-8 text-gray-500">Sin registros.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $logs->links() }}</div>

@push('scripts')
<script>
function toggleDetalle(id) {
    const el = document.getElementById('detalle-' + id);
    el.classList.toggle('hidden');
}
</script>
@endpush
</x-app-layout>
