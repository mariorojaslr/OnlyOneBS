<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Panel de Administración') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #020617;
            color: #f8fafc;
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
</head>
<body class="bg-slate-950 text-slate-200 font-sans selection:bg-indigo-500 selection:text-white overflow-hidden">
    <!-- Top Bar Global -->
    <header class="fixed top-0 right-0 left-64 h-20 bg-slate-900/60 backdrop-blur-xl border-b border-white/5 z-40 flex items-center justify-end px-12 gap-8 shadow-2xl">
        <div x-data="{ open: false }" class="relative">
            @php
                $locales = [
                    'es' => ['name' => 'Español', 'native' => 'Español', 'flag' => 'es'],
                    'en' => ['name' => 'English', 'native' => 'English', 'flag' => 'us'],
                    'pt' => ['name' => 'Portuguese', 'native' => 'Português', 'flag' => 'br'],
                    'ru' => ['name' => 'Russian', 'native' => 'Русский', 'flag' => 'ru'],
                    'fr' => ['name' => 'French', 'native' => 'Français', 'flag' => 'fr'],
                    'it' => ['name' => 'Italian', 'native' => 'Italiano', 'flag' => 'it'],
                    'de' => ['name' => 'German', 'native' => 'Deutsch', 'flag' => 'de'],
                    'zh' => ['name' => 'Chinese', 'native' => '中文', 'flag' => 'cn'],
                    'ja' => ['name' => 'Japanese', 'native' => '日本語', 'flag' => 'jp'],
                    'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'flag' => 'sa'],
                ];
                $current = $locales[app()->getLocale()] ?? $locales['es'];
            @endphp
            
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 hover:bg-white/5 px-4 py-2 rounded-2xl transition-all group">
                <img src="https://flagcdn.com/w40/{{ $current['flag'] }}.png" class="w-8 h-6 object-cover rounded shadow-md border border-white/10 group-hover:scale-110 transition-transform" alt="{{ $current['name'] }}">
                <span class="text-white text-sm font-black">{{ $current['native'] }}</span>
                <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="absolute right-0 mt-3 w-64 bg-slate-900/95 backdrop-blur-2xl border border-white/10 rounded-3xl shadow-[0_25px_60px_rgba(0,0,0,0.8)] overflow-hidden z-50">
                <div class="py-2 max-h-[30rem] overflow-y-auto no-scrollbar">
                    <p class="px-6 py-2 text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-white/5 mb-2">{{ __('Seleccionar Idioma') }}</p>
                    @foreach($locales as $code => $info)
                        <a href="{{ route('set-locale', $code) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-indigo-600 group transition-all {{ app()->getLocale() == $code ? 'bg-white/5 border-r-4 border-indigo-500' : '' }}">
                            <img src="https://flagcdn.com/w40/{{ $info['flag'] }}.png" class="w-8 h-6 object-cover rounded shadow-md border border-white/10 group-hover:scale-110 transition-transform">
                            <div class="flex flex-col">
                                <span class="text-white text-xs font-black">{{ $info['native'] }}</span>
                                <span class="text-slate-500 text-[9px] font-bold uppercase group-hover:text-white/70">{{ $info['name'] }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <a href="{{ route('profile') }}" class="flex items-center gap-4 pl-8 border-l border-white/10 h-10 group hover:opacity-80 transition-all">
            <div class="text-right">
                <p class="text-white text-xs font-black uppercase tracking-widest leading-none group-hover:text-indigo-400">{{ auth()->user()->name }}</p>
                <p class="text-indigo-400 text-[9px] font-black uppercase tracking-[0.2em] mt-1 group-hover:text-white">{{ auth()->user()->role }}</p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-emerald-400 flex items-center justify-center p-[2px] shadow-lg group-hover:scale-110 transition-transform">
                <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center border border-white/10">
                    <span class="text-white text-sm font-black">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                </div>
            </div>
        </a>
    </header>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 glass-panel border-r border-gray-800 flex flex-col h-full bg-slate-900/50 relative z-20">
            <div class="px-6 py-8 border-b border-gray-800 flex flex-col gap-4">
                <span class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-500 tracking-tighter">OnlyOne            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto no-scrollbar">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 001 1h3m-6-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="font-bold text-sm">{{ __('Dashboard') }}</span>
                </a>

                @if(!session('room_id') && (auth()->user()->isSuperAdmin() || auth()->user()->isOwner()))
                    <!-- Opciones Globales (Fuera de la habitación) -->
                    <a href="{{ route('socios.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('socios.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="font-bold text-sm">{{ __('Dueños / Flotas') }}</span>
                    </a>
                    
                    <a href="{{ route('users.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="font-bold text-sm">{{ __('Usuarios del Sistema') }}</span>
                    </a>

                    @if(auth()->user()->isAdmin())
                    <div class="my-8 border-t border-slate-800 mx-4"></div>
                    <a href="{{ route('configuracion.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 text-amber-400 hover:bg-amber-500/10">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="font-bold text-sm">{{ __('Configuración API') }}</span>
                    </a>
                    @endif
                @endif

                @if(session('room_id') || auth()->user()->isSocio())
                    <!-- Opciones Locales (Dentro de la habitación de la Flota) -->
                    @php
                        // Obtenemos el nombre de la flota actual para mostrarlo en el menú
                        $roomId = session('room_id') ?? auth()->user()->socio_id;
                        $roomName = \App\Models\Socio::find($roomId)->nombre ?? 'Flota';
                    @endphp
                    
                    <div class="pt-8 pb-3 px-4">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Habitación: {{ $roomName }}</p>
                    </div>
                    
                    <a href="{{ route('empresas.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('empresas.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="font-bold text-sm">{{ __('Cuentas Corporativas') }}</span>
                    </a>
                    <a href="{{ route('centros-costo.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('centros-costo.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span class="font-bold text-sm">{{ __('Centros de Costo') }}</span>
                    </a>
                    <a href="{{ route('pasajeros.index') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 {{ request()->routeIs('pasajeros.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="font-bold text-sm">{{ __('Pasajeros') }}</span>
                    </a>
                    
                    <div class="my-8 border-t border-slate-800 mx-4"></div>
                    
                    <a href="{{ route('sincronizar') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all duration-300 text-emerald-400 hover:bg-emerald-500/10">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <span class="font-bold text-sm">{{ __('Subir Archivo CSV') }}</span>
                    </a>
                @endif     </a>
                @endif
                
                <div class="mt-auto pt-10 px-4 pb-12">
                     <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 rounded-2xl text-red-500 hover:bg-red-900/20 transition duration-150 font-bold text-sm">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            {{ __('Cerrar Sesión') }}
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Contenido Principal -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-950 p-12 pt-12 mt-20 no-scrollbar relative z-10">
            @if(session('success'))
                <div class="max-w-7xl mx-auto mb-8 bg-emerald-500/10 border border-emerald-500 text-emerald-400 px-6 py-4 rounded-2xl font-bold">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <!-- AlpineJS scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
