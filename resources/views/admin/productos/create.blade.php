<x-app-layout>
<x-slot name="title">Nuevo Producto</x-slot>

<div class="max-w-2xl w-full">
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('admin.productos.index') }}" class="lg:hidden text-on-surface-variant hover:text-on-surface transition mb-2 inline-block">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="text-xl lg:text-2xl font-bold tracking-tight text-on-surface">Nuevo Producto</h1>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.productos.store') }}"
          class="glass-panel rounded-xl p-4 lg:p-6 space-y-4">
        @csrf
        @include('admin.productos._form')

        <div class="flex gap-3 pt-2">
            <a href="{{ route('admin.productos.index') }}" class="flex-1 text-center bg-surface-container-high hover:bg-surface-bright text-on-surface/70 rounded-xl py-3 text-sm font-semibold transition">Cancelar</a>
            <button type="submit" class="flex-1 bg-primary hover:opacity-90 text-on-primary font-semibold rounded-xl py-3 text-sm transition">Guardar</button>
        </div>
    </form>
</div>
</x-app-layout>