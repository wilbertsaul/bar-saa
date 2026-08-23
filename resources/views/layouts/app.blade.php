<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-container-lowest": "#0e0e0f",
                        "surface-container-low": "#1c1b1c",
                        "surface-container": "#201f20",
                        "surface-container-high": "#2a2a2b",
                        "surface-container-highest": "#353436",
                        "surface-bright": "#3a393a",
                        "surface": "#131314",
                        "background": "#131314",
                        "on-surface": "#e5e2e3",
                        "on-surface-variant": "#e1bfbb",
                        "primary": "#ffb4ab",
                        "primary-container": "#a1000e",
                        "on-primary": "#690005",
                        "on-primary-container": "#ffaaa1",
                        "secondary": "#c8c6c8",
                        "tertiary": "#68dba9",
                        "tertiary-container": "#005a3d",
                        "on-tertiary": "#003825",
                        "on-tertiary-container": "#61d5a3",
                        "outline": "#a88a86",
                        "outline-variant": "#59413e",
                        "error": "#ffb4ab",
                        "error-container": "#93000a",
                        "on-error": "#690005",
                        "on-error-container": "#ffdad6",
                        "inverse-primary": "#bf0715",
                        "inverse-surface": "#e5e2e3",
                        "inverse-on-surface": "#313031",
                        "surface-variant": "#353436",
                        "surface-tint": "#ffb4ab",
                        "surface-dim": "#131314",
                        "secondary-fixed": "#e4e2e4",
                        "on-secondary-fixed": "#1b1b1d",
                        "on-secondary": "#303032",
                        "secondary-container": "#474649",
                        "on-secondary-container": "#b6b4b7",
                        "secondary-fixed-dim": "#c8c6c8",
                        "on-secondary-fixed-variant": "#474649",
                        "tertiary-fixed": "#85f8c4",
                        "tertiary-fixed-dim": "#68dba9",
                        "on-tertiary-fixed": "#002114",
                        "on-tertiary-fixed-variant": "#005137",
                        "on-primary-fixed": "#410002",
                        "on-primary-fixed-variant": "#93000b",
                        "primary-fixed": "#ffdad6",
                        "primary-fixed-dim": "#ffb4ab",
                        "on-background": "#e5e2e3"
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                    fontFamily: {
                        headline: ["Manrope"],
                        body: ["Manrope"],
                        label: ["Manrope"]
                    }
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Manrope', sans-serif; background-color: #0e0e0f; color: #e5e2e3; }
        .glass { background: rgba(53, 52, 54, 0.7); backdrop-filter: blur(20px); }
        .ruby-gradient { background: linear-gradient(135deg, #a1000e, #ffb4ab); }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .sidebar-active { background: linear-gradient(to right, rgba(161, 0, 14, 0.2), transparent); border-right: 4px solid #a1000e; font-weight: 700; color: #ffb4ab; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0e0e0f; }
        ::-webkit-scrollbar-thumb { background: #2a2a2b; border-radius: 10px; }
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-surface-container-lowest text-on-surface overflow-x-hidden">
    <!-- Sidebar - Desktop -->
    <aside class="sidebar fixed left-0 top-0 h-full w-72 flex-col bg-[#0e0e0f] shadow-[40px_0_60px_-5px_rgba(0,0,0,0.4)] z-50 hidden lg:flex">
        <div class="p-8">
            <h1 class="text-2xl font-black tracking-tighter text-primary">Midnight</h1>
            <p class="font-manrope text-[10px] uppercase tracking-widest text-on-surface/40 mt-1">Bar Management</p>
        </div>
        <nav class="flex-1 overflow-y-auto px-4 space-y-1">
            <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'text-on-surface/60 hover:text-on-surface hover:bg-[#2a2a2b]' }} transition-all duration-300 rounded-lg" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">analytics</span>
                <span class="text-sm tracking-tight">Dashboard</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('pos.*') ? 'sidebar-active' : 'text-on-surface/60 hover:text-on-surface hover:bg-[#2a2a2b]' }} transition-all duration-300 rounded-lg" href="{{ route('pos.index') }}">
                <span class="material-symbols-outlined">point_of_sale</span>
                <span class="text-sm tracking-tight">Punto de Venta</span>
            </a>
            @if(auth()->user()->isAdmin() || auth()->user()->isControlador())
            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-on-surface/30">Administración</p>
            </div>
            <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.productos.*') ? 'sidebar-active' : 'text-on-surface/60 hover:text-on-surface hover:bg-[#2a2a2b]' }} transition-all duration-300 rounded-lg" href="{{ route('admin.productos.index') }}">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="text-sm tracking-tight">Productos</span>
            </a>
            @if(auth()->user()->isAdmin())
            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-on-surface/30">Reportes</p>
            </div>
            <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.reportes.ventas') ? 'sidebar-active' : 'text-on-surface/60 hover:text-on-surface hover:bg-[#2a2a2b]' }} transition-all duration-300 rounded-lg" href="{{ route('admin.reportes.ventas') }}">
                <span class="material-symbols-outlined">analytics</span>
                <span class="text-sm tracking-tight">Reporte Ventas</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.inventario') ? 'sidebar-active' : 'text-on-surface/60 hover:text-on-surface hover:bg-[#2a2a2b]' }} transition-all duration-300 rounded-lg" href="{{ route('admin.inventario') }}">
                <span class="material-symbols-outlined">summarize</span>
                <span class="text-sm tracking-tight">Reporte Inventario</span>
            </a>
            @endif
            <div class="pt-6 pb-2 px-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-on-surface/30">Operaciones</p>
            </div>
            <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.cajas.*') ? 'sidebar-active' : 'text-on-surface/60 hover:text-on-surface hover:bg-[#2a2a2b]' }} transition-all duration-300 rounded-lg" href="{{ route('admin.cajas.index') }}">
                <span class="material-symbols-outlined">point_of_sale</span>
                <span class="text-sm tracking-tight">Caja / Turnos</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.inventario') ? 'sidebar-active' : 'text-on-surface/60 hover:text-on-surface hover:bg-[#2a2a2b]' }} transition-all duration-300 rounded-lg" href="{{ route('admin.inventario') }}">
                <span class="material-symbols-outlined">inventory</span>
                <span class="text-sm tracking-tight">Inventario</span>
            </a>
            @if(auth()->user()->isAdmin())
            <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.auditoria.*') ? 'sidebar-active' : 'text-on-surface/60 hover:text-on-surface hover:bg-[#2a2a2b]' }} transition-all duration-300 rounded-lg" href="{{ route('admin.auditoria.index') }}">
                <span class="material-symbols-outlined">verified_user</span>
                <span class="text-sm tracking-tight">Auditoría</span>
            </a>
            @endif
            @endif
        </nav>
        <div class="p-6 mt-auto border-t border-outline-variant/10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-bold text-on-surface">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-on-surface/40 uppercase tracking-wider">{{ auth()->user()->rol }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 py-3 border border-outline-variant/15 text-on-surface text-sm font-semibold rounded-xl hover:bg-surface-container-high transition-all">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:ml-72 min-h-screen">
        <!-- Top Header -->
        <header class="h-16 flex justify-between items-center px-4 lg:px-8 sticky top-0 z-40 bg-surface/70 backdrop-blur-xl border-b border-outline-variant/5">
            <!-- Mobile Menu Button -->
            <div class="flex items-center gap-4">
                <button id="mobileMenuBtn" class="lg:hidden material-symbols-outlined text-on-surface hover:text-primary transition-colors">menu</button>
                <span class="text-lg font-bold text-on-surface">Midnight Concierge</span>
            </div>
            <div class="flex items-center gap-4 lg:gap-8">
                <div class="relative group hidden sm:block">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface/40 text-lg group-focus-within:text-primary transition-colors">search</span>
                    <input class="bg-surface-container-lowest border-none text-[10px] tracking-widest py-2 pl-10 pr-4 rounded-full w-48 lg:w-64 focus:ring-1 focus:ring-primary placeholder:text-on-surface/20" placeholder="BUSCAR..." type="text"/>
                </div>
                <div class="flex items-center gap-4">
                    <button class="relative">
                        <span class="material-symbols-outlined text-on-surface/60 hover:text-primary transition-colors">notifications</span>
                        @if($notificacionesCount ?? 0 > 0)
                        <span class="absolute top-0 right-0 w-2 h-2 bg-primary rounded-full border-2 border-surface"></span>
                        @endif
                    </button>
                    <div class="hidden sm:flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-on-surface tracking-tighter uppercase">{{ auth()->user()->name }}</p>
                            <p class="text-[9px] text-tertiary font-bold tracking-widest uppercase">En Línea</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center text-on-primary font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden"></div>

        <!-- Mobile Sidebar -->
        <aside id="mobileSidebar" class="sidebar fixed left-0 top-0 h-full w-80 flex-col bg-[#0e0e0f] shadow-[40px_0_60px_-5px_rgba(0,0,0,0.4)] z-50 lg:hidden">
            <div class="p-8 flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-black tracking-tighter text-primary">Midnight</h1>
                    <p class="font-manrope text-[10px] uppercase tracking-widest text-on-surface/40 mt-1">Bar Management</p>
                </div>
                <button id="closeSidebarBtn" class="material-symbols-outlined text-on-surface hover:text-primary">close</button>
            </div>
            <nav class="flex-1 overflow-y-auto px-4 space-y-1">
                <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'text-on-surface/60' }} transition-all rounded-lg" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined">analytics</span>
                    <span class="text-sm">Dashboard</span>
                </a>
                <a class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('pos.*') ? 'sidebar-active' : 'text-on-surface/60' }} transition-all rounded-lg" href="{{ route('pos.index') }}">
                    <span class="material-symbols-outlined">point_of_sale</span>
                    <span class="text-sm">Punto de Venta</span>
                </a>
                @if(auth()->user()->isAdmin())
                <div class="pt-6 pb-2 px-4">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-on-surface/30">Administración</p>
                </div>
                <a class="flex items-center gap-4 px-4 py-3 text-on-surface/60 transition-all rounded-lg" href="{{ route('admin.productos.index') }}">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <span class="text-sm">Productos</span>
                </a>
                <div class="pt-6 pb-2 px-4">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-on-surface/30">Reportes</p>
                </div>
                <a class="flex items-center gap-4 px-4 py-3 text-on-surface/60 transition-all rounded-lg" href="{{ route('admin.reportes.ventas') }}">
                    <span class="material-symbols-outlined">analytics</span>
                    <span class="text-sm">Reporte Ventas</span>
                </a>
                <a class="flex items-center gap-4 px-4 py-3 text-on-surface/60 transition-all rounded-lg" href="{{ route('admin.inventario') }}">
                    <span class="material-symbols-outlined">summarize</span>
                    <span class="text-sm">Reporte Inventario</span>
                </a>
                <div class="pt-6 pb-2 px-4">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-on-surface/30">Operaciones</p>
                </div>
                <a class="flex items-center gap-4 px-4 py-3 text-on-surface/60 transition-all rounded-lg" href="{{ route('admin.auditoria.index') }}">
                    <span class="material-symbols-outlined">verified_user</span>
                    <span class="text-sm">Auditoría</span>
                </a>
                @endif
            </nav>
            <div class="p-6 mt-auto border-t border-outline-variant/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 py-3 text-error hover:bg-error-container/10 rounded-xl transition-all">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="text-sm">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Page Content -->
        <div class="p-4 lg:p-8 max-w-7xl mx-auto">
            {{ $slot }}
        </div>
    </main>

    <!-- Flash Messages -->
    @if(session('success'))
    <div id="flash-msg" class="fixed bottom-4 right-4 z-50 bg-tertiary-container border border-tertiary text-tertiary px-6 py-4 rounded-xl shadow-lg text-sm max-w-xs flex items-center gap-3">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div id="flash-msg" class="fixed bottom-4 right-4 z-50 bg-error-container border border-error text-error px-6 py-4 rounded-xl shadow-lg text-sm max-w-xs flex items-center gap-3">
        <span class="material-symbols-outlined">error</span>
        {{ session('error') }}
    </div>
    @endif

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        function openSidebar() {
            mobileSidebar.classList.add('open');
            sidebarOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            mobileSidebar.classList.remove('open');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openSidebar);
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

        // Auto-hide flash message
        const flash = document.getElementById('flash-msg');
        if (flash) setTimeout(() => flash.remove(), 4000);
    </script>
    @stack('scripts')
</body>
</html>
