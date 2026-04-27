@extends('layouts.admin')

@section('title', $empresa->nombre . ' - ' . __('Reporte Detallado'))

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Navegación -->
    <div class="mb-10 flex items-center justify-between">
        <div class="flex items-center gap-4 text-sm font-bold uppercase tracking-widest">
            <a href="{{ route('dashboard', ['view' => 'apps']) }}" class="text-indigo-400 hover:text-white transition-colors">{{ __('Inicio') }}</a>
            <span class="text-slate-700">/</span>
            <a href="{{ route('dashboard', ['view' => 'companies', 'l2_id' => $empresa->socio_id]) }}" class="text-indigo-400 hover:text-white transition-colors">{{ $empresa->socio->nombre ?? __('Flota') }}</a>
            <span class="text-slate-700">/</span>
            <span class="text-white">{{ $empresa->nombre }}</span>
        </div>
        
        <button onclick="window.print()" class="p-4 bg-slate-900 border border-white/5 rounded-2xl text-slate-400 hover:text-white transition-all shadow-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span class="text-xs font-black uppercase tracking-widest">{{ __('Imprimir Reporte') }}</span>
        </button>
    </div>

    <!-- Cabecera Empresa -->
    <div class="bg-slate-900 rounded-[3rem] border border-white/5 p-12 mb-12 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-12 opacity-10">
            <svg class="w-64 h-64 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
                <div>
                    <span class="text-indigo-400 text-xs font-black uppercase tracking-[0.3em] mb-4 block">{{ __('Cuenta Corporativa') }}</span>
                    <h1 class="text-6xl font-black text-white tracking-tighter leading-none">{{ $empresa->nombre }}</h1>
                    <p class="text-slate-500 mt-4 text-lg font-medium">{{ __('Reporte de consumos y trazabilidad por Centro de Costo.') }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-12 bg-black/20 p-8 rounded-[2.5rem] border border-white/5 backdrop-blur-xl">
                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">{{ __('Kilometraje Total') }}</p>
                        <p class="text-4xl font-black text-white leading-none">{{ number_format($km_total, 1, ',', '.') }} <span class="text-indigo-400 text-sm italic">KM</span></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">{{ __('Inversión Total') }}</p>
                        <p class="text-5xl font-black text-emerald-400 tracking-tighter leading-none">${{ number_format($monto_total, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Centros de Costo (Acordeón) -->
    <div class="space-y-6">
        @foreach($centros as $c)
            <div x-data="{ open: false }" class="bg-slate-900 rounded-[2.5rem] border border-white/5 shadow-xl overflow-hidden group">
                <div @click="open = !open" class="px-10 py-8 flex items-center justify-between cursor-pointer hover:bg-white/[0.02] transition-colors">
                    <div class="flex items-center gap-8">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-600/10 flex items-center justify-center border border-indigo-500/20 group-hover:scale-110 transition-transform">
                            <span class="text-indigo-400 font-black text-xl">#{{ $c['model']->numero }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-white tracking-tight uppercase">{{ $c['model']->nombre }}</h2>
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-1">{{ count($c['pasajeros']) }} {{ __('Empleados') }} • {{ $c['viajes_count'] }} {{ __('Viajes') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-12">
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ __('Total Centro') }}</p>
                            <p class="text-2xl font-black text-emerald-400 leading-none">${{ number_format($c['monto_total'], 2, ',', '.') }}</p>
                        </div>
                        <svg class="w-6 h-6 text-slate-700 transition-transform duration-500" :class="{'rotate-180 text-white': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <div x-show="open" x-collapse>
                    <div class="px-10 pb-10 space-y-4">
                        @foreach($c['pasajeros'] as $p)
                            <div x-data="{ openP: false }" class="bg-black/20 rounded-3xl border border-white/5 overflow-hidden">
                                <div @click="openP = !openP" class="px-8 py-5 flex items-center justify-between cursor-pointer hover:bg-white/[0.03] transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-xs font-black text-indigo-300 border border-white/5">
                                            {{ strtoupper(substr($p['nombre'], 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-white uppercase">{{ $p['nombre'] }}</p>
                                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">{{ $p['viajes_count'] }} {{ __('Viajes') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-8">
                                        <p class="text-sm font-black text-slate-300">${{ number_format($p['monto_total'], 2, ',', '.') }}</p>
                                        <svg class="w-4 h-4 text-slate-700 transition-transform" :class="{'rotate-180': openP}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>

                                <div x-show="openP" x-collapse>
                                    <div class="px-8 py-4 bg-slate-900/50">
                                        <table class="w-full text-left">
                                            <thead>
                                                <tr class="text-[9px] font-black text-slate-600 uppercase tracking-widest border-b border-white/5">
                                                    <th class="pb-3">{{ __('Fecha') }}</th>
                                                    <th class="pb-3">{{ __('Origen') }}</th>
                                                    <th class="pb-3">{{ __('Destino') }}</th>
                                                    <th class="pb-3 text-right">{{ __('KM') }}</th>
                                                    <th class="pb-3 text-right">{{ __('Monto') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-white/[0.02]">
                                                @foreach($p['lista_viajes'] as $v)
                                                    <tr class="text-[10px] text-slate-400 hover:text-white transition-colors">
                                                        <td class="py-3 font-mono text-indigo-400">{{ $v['fecha'] }}</td>
                                                        <td class="py-3 max-w-xs truncate">{{ $v['origen'] }}</td>
                                                        <td class="py-3 max-w-xs truncate">{{ $v['destino'] }}</td>
                                                        <td class="py-3 text-right font-bold">{{ $v['distancia'] }}</td>
                                                        <td class="py-3 text-right font-black text-emerald-400">${{ number_format($v['monto'], 2, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
