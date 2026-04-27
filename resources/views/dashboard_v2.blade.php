@extends('layouts.admin')

@section('title', __('Panel de Control Jerárquico'))

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumbs / Navegación -->
    <div class="mb-10 flex items-center gap-4 text-sm font-bold uppercase tracking-widest">
        <a href="{{ route('dashboard', ['view' => 'apps']) }}" class="text-indigo-400 hover:text-white transition-colors">{{ __('Inicio') }}</a>
        @if($parent)
            <span class="text-slate-700">/</span>
            <span class="text-white">{{ $parent->nombre }}</span>
        @endif
    </div>

    <div class="mb-12">
        <h1 class="text-5xl font-black text-white tracking-tighter leading-tight">
            @if($level === 'apps') {{ __('Mis Aplicaciones') }}
            @elseif($level === 'fleets') {{ __('Flotas de') }} {{ $parent->nombre }}
            @elseif($level === 'companies') {{ __('Cuentas Corporativas de') }} {{ $parent->nombre }}
            @endif
        </h1>
        <p class="text-slate-500 mt-2 text-xl font-medium">{{ __('Selecciona un elemento para ver más detalles.') }}</p>
    </div>

    <!-- Rejilla de Tarjetas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($items as $item)
            @php
                $nextView = '';
                $params = [];
                if($level === 'apps') {
                    $nextView = 'fleets';
                    $params = ['view' => 'fleets', 'l1_id' => $item->id];
                } elseif($level === 'fleets') {
                    $nextView = 'companies';
                    $params = ['view' => 'companies', 'l2_id' => $item->id];
                } elseif($level === 'companies') {
                    $nextView = 'details';
                    $params = ['view' => 'details', 'empresa_id' => $item->id];
                }
            @endphp
            
            <a href="{{ route('dashboard', $params) }}" class="group block bg-slate-900 rounded-[2.5rem] border border-white/5 p-8 hover:bg-indigo-600 transition-all duration-500 shadow-2xl relative overflow-hidden">
                <!-- Efecto de fondo -->
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/20 transition-all"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mb-6 group-hover:bg-white/20 transition-colors">
                        @if($level === 'apps')
                            <svg class="w-8 h-8 text-emerald-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        @elseif($level === 'fleets')
                            <svg class="w-8 h-8 text-amber-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                        @else
                            <svg class="w-8 h-8 text-cyan-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        @endif
                    </div>
                    
                    <h3 class="text-2xl font-black text-white tracking-tight mb-2 uppercase">{{ $item->nombre }}</h3>
                    <p class="text-slate-500 group-hover:text-white/60 font-bold text-xs uppercase tracking-widest">
                        @if($level === 'apps') {{ __('Dueño de Aplicación') }}
                        @elseif($level === 'fleets') {{ __('Dueño de Flota') }}
                        @else {{ __('Cuenta Corporativa') }}
                        @endif
                    </p>
                </div>
                
                <div class="mt-10 flex items-center justify-between relative z-10">
                    <span class="text-indigo-400 group-hover:text-white text-xs font-black uppercase tracking-widest flex items-center gap-2">
                        {{ __('Explorar') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </span>
                </div>
            </a>
        @endforeach
    </div>

    @if(count($items) === 0)
        <div class="bg-slate-900/50 border border-white/5 rounded-[2.5rem] p-20 text-center">
            <p class="text-slate-500 text-xl font-medium">{{ __('No hay elementos registrados en este nivel.') }}</p>
        </div>
    @endif
</div>
@endsection
