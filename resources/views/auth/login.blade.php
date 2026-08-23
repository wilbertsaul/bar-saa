<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Concierge - Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;400;600;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-primary-fixed": "#410002",
                        "tertiary-fixed-dim": "#68dba9",
                        "on-tertiary-fixed-variant": "#005137",
                        surface: "#131314",
                        "on-background": "#e5e2e3",
                        "outline-variant": "#59413e",
                        "error-container": "#93000a",
                        "on-secondary": "#303032",
                        "surface-container-low": "#1c1b1c",
                        "surface-bright": "#3a393a",
                        "secondary-fixed-dim": "#c8c6c8",
                        "inverse-on-surface": "#313031",
                        "surface-dim": "#131314",
                        "on-tertiary": "#003825",
                        "on-surface-variant": "#e1bfbb",
                        "on-secondary-fixed-variant": "#474649",
                        "inverse-primary": "#bf0715",
                        "secondary-fixed": "#e4e2e4",
                        "on-primary-container": "#ffaaa1",
                        outline: "#a88a86",
                        "surface-container": "#201f20",
                        "primary-fixed": "#ffdad6",
                        "on-error-container": "#ffdad6",
                        "on-primary-fixed-variant": "#93000b",
                        "surface-container-high": "#2a2a2b",
                        "on-error": "#690005",
                        "on-tertiary-fixed": "#002114",
                        "on-tertiary-container": "#61d5a3",
                        "inverse-surface": "#e5e2e3",
                        "secondary-container": "#474649",
                        tertiary: "#68dba9",
                        "on-secondary-container": "#b6b4b7",
                        "on-surface": "#e5e2e3",
                        "surface-variant": "#353436",
                        "surface-container-highest": "#353436",
                        primary: "#ffb4ab",
                        background: "#131314",
                        secondary: "#c8c6c8",
                        error: "#ffb4ab",
                        "surface-container-lowest": "#0e0e0f",
                        "on-secondary-fixed": "#1b1b1d",
                        "on-primary": "#690005",
                        "primary-fixed-dim": "#ffb4ab",
                        "tertiary-fixed": "#85f8c4",
                        "tertiary-container": "#005a3d",
                        "primary-container": "#a1000e",
                        "surface-tint": "#ffb4ab"
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
        body {
            font-family: 'Manrope', sans-serif;
            background-color: #0e0e0f;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-panel {
            background: rgba(32, 31, 32, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .ruby-gradient {
            background: linear-gradient(135deg, #a1000e 0%, #ffb4ab 100%);
        }
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #e5e2e3;
            -webkit-box-shadow: 0 0 0px 1000px #1c1b1c inset;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-surface-container-lowest min-h-screen flex flex-col items-center justify-center p-4">
    <main class="w-full max-w-[440px] flex flex-col gap-8">
        <div class="flex flex-col items-center text-center space-y-2">
            <div class="w-16 h-16 ruby-gradient rounded-xl flex items-center justify-center mb-4 shadow-[0_0_40px_rgba(161,0,14,0.3)]">
                <span class="material-symbols-outlined text-white text-4xl">liquor</span>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tighter text-on-surface uppercase">
                Midnight Concierge
            </h1>
            <p class="text-on-surface-variant font-medium tracking-tight opacity-70">
                Bar Management System
            </p>
        </div>

        <div class="glass-panel rounded-xl p-8 shadow-[0_40px_60px_-5px_rgba(0,0,0,0.6)] border border-outline-variant/15">
            <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
                @csrf

                @if ($errors->any())
                    <div class="bg-error-container/20 border border-error/30 rounded-lg p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-error text-xl">error</span>
                            <div class="text-sm text-error">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-tertiary-container/20 border border-tertiary/30 rounded-lg p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-tertiary text-xl">check_circle</span>
                            <p class="text-sm text-tertiary">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant/80 ml-1" for="email">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">alternate_email</span>
                        <input 
                            class="w-full bg-surface-container-lowest border border-outline-variant/15 text-on-surface rounded-lg py-4 pl-12 pr-4 focus:ring-1 focus:ring-primary focus:border-primary transition-all outline-none placeholder:text-outline/40 @error('email') border-error @enderror" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="nombre@midnight.com" 
                            type="email" 
                            required 
                            autofocus
                        />
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant/80" for="password">
                            Contraseña
                        </label>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">lock</span>
                        <input 
                            class="w-full bg-surface-container-lowest border border-outline-variant/15 text-on-surface rounded-lg py-4 pl-12 pr-12 focus:ring-1 focus:ring-primary focus:border-primary transition-all outline-none placeholder:text-outline/40 @error('password') border-error @enderror" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••" 
                            type="password" 
                            required
                        />
                        <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline/60 hover:text-on-surface transition-colors">
                            <span class="material-symbols-outlined text-lg" id="eyeIcon">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center space-x-3 px-1">
                    <input 
                        class="w-4 h-4 rounded border-outline-variant/30 bg-surface-container-lowest text-primary-container focus:ring-primary cursor-pointer" 
                        id="remember" 
                        name="remember" 
                        type="checkbox"
                        {{ old('remember') ? 'checked' : '' }}
                    />
                    <label class="text-sm text-on-surface-variant cursor-pointer" for="remember">Mantener sesión iniciada</label>
                </div>

                <button 
                    type="submit" 
                    id="submitBtn"
                    class="w-full py-4 rounded-xl ruby-gradient text-white font-extrabold tracking-tight hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center space-x-2 shadow-[0_10px_30px_-5px_rgba(161,0,14,0.4)] disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span id="btnText">Iniciar Sesión</span>
                    <span class="material-symbols-outlined text-lg" id="btnIcon">arrow_forward</span>
                    <svg id="btnSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-outline-variant/10 text-center">
                <p class="text-sm text-on-surface-variant">
                    ¿No tienes acceso? <a class="text-primary font-bold hover:underline" href="#">Contactar Soporte</a>
                </p>
            </div>
        </div>

        <div class="relative h-48 rounded-xl overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-t from-surface-container-lowest via-transparent to-transparent z-10"></div>
            <img 
                alt="Bar ambiance" 
                class="w-full h-full object-cover grayscale opacity-30 group-hover:opacity-50 group-hover:scale-105 transition-all duration-700" 
                src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?w=800&q=80"
            />
            <div class="absolute bottom-4 left-4 z-20">
                <p class="text-[10px] font-bold text-primary tracking-[0.2em] uppercase">Private Access Only</p>
                <p class="text-on-surface-variant text-xs opacity-60">System Version 4.2.1-Midnight</p>
            </div>
        </div>
    </main>

    <footer class="mt-auto py-8">
        <div class="flex items-center space-x-6 opacity-30">
            <span class="w-12 h-px bg-outline-variant"></span>
            <div class="flex space-x-4">
                <span class="material-symbols-outlined text-on-surface text-sm">shield</span>
                <span class="material-symbols-outlined text-on-surface text-sm">terminal</span>
                <span class="material-symbols-outlined text-on-surface text-sm">vaping_rooms</span>
            </div>
            <span class="w-12 h-px bg-outline-variant"></span>
        </div>
    </footer>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.textContent = 'visibility_off';
            } else {
                password.type = 'password';
                eyeIcon.textContent = 'visibility';
            }
        });

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const btnSpinner = document.getElementById('btnSpinner');
            
            btn.disabled = true;
            btnText.textContent = 'Iniciando sesión...';
            btnIcon.classList.add('hidden');
            btnSpinner.classList.remove('hidden');
        });
    </script>
</body>
</html>
