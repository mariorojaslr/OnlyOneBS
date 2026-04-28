@extends('layouts.admin')

@section('title', __('Detalle del Cierre de Facturación'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-10 flex items-center justify-between">
        <div class="flex items-center gap-6">
            <a href="{{ route('facturacion.index') }}" class="p-3 bg-slate-900 border border-white/5 rounded-2xl text-slate-400 hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Reporte de Cierre') }} <span class="text-indigo-400">#{{ str_pad($facturacion->id, 5, '0', STR_PAD_LEFT) }}</span></h1>
                <p class="text-slate-500 mt-2 font-medium">{{ __('Detalle del ciclo facturado para') }} <span class="text-white">{{ $facturacion->empresa->nombre }}</span></p>
            </div>
        </div>
        
        <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-700 text-white px-6 py-3 rounded-2xl font-bold transition-all border border-white/5 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            {{ __('Imprimir / PDF') }}
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-900 rounded-[2rem] p-8 border border-white/5 shadow-2xl">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">{{ __('Monto Facturado') }}</p>
            <p class="text-3xl font-black text-white">{{ number_format($facturacion->monto_total, 2) }} <span class="text-indigo-400 text-xl">{{ $facturacion->moneda }}</span></p>
        </div>
        <div class="bg-slate-900 rounded-[2rem] p-8 border border-white/5 shadow-2xl">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">{{ __('Viajes Totales') }}</p>
            <p class="text-3xl font-black text-white">{{ $facturacion->cantidad_viajes }}</p>
        </div>
        <div class="bg-slate-900 rounded-[2rem] p-8 border border-white/5 shadow-2xl">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">{{ __('Período') }}</p>
            <p class="text-sm font-bold text-white mt-1">{{ \Carbon\Carbon::parse($facturacion->fecha_inicio)->format('d/m/Y') }}<br>{{ \Carbon\Carbon::parse($facturacion->fecha_fin)->format('d/m/Y') }}</p>
        </div>
        <div class="bg-slate-900 rounded-[2rem] p-8 border border-white/5 shadow-2xl">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">{{ __('Estado') }}</p>
            <div class="mt-2">
                @if($facturacion->estado == 'emitido')
                    <span class="px-4 py-2 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-xl text-xs font-black uppercase tracking-widest">{{ __('Emitido') }}</span>
                @elseif($facturacion->estado == 'pagado')
                    <span class="px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl text-xs font-black uppercase tracking-widest">{{ __('Pagado') }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Trips List -->
    <div class="bg-slate-900 border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
        <div class="p-8 border-b border-white/5 flex items-center justify-between bg-slate-950/30">
            <h3 class="text-lg font-black text-white">{{ __('Detalle de Viajes Incluidos') }}</h3>
            <span class="text-xs font-bold text-slate-500 bg-slate-800 px-3 py-1 rounded-full">{{ $facturacion->viajes->count() }} viajes</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-950/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Fecha / Hora') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Pasajero') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Centro de Costo') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Recorrido') }}</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">{{ __('Monto') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($facturacion->viajes as $viaje)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-8 py-5">
                                <div class="font-bold text-white">{{ \Carbon\Carbon::parse($viaje->fecha_inicio)->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($viaje->fecha_inicio)->format('H:i') }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="font-bold text-white">{{ $viaje->pasajero->nombre_completo ?? 'N/A' }}</div>
                                @if($viaje->pasajero && $viaje->pasajero->documento)
                                    <div class="text-[10px] font-mono text-slate-500 mt-1">DNI: {{ $viaje->pasajero->documento }}</div>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-slate-800 text-slate-300 rounded-lg text-[10px] font-bold">{{ $viaje->centroCosto->nombre ?? 'N/A' }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                    <span class="text-sm font-medium text-slate-300 truncate max-w-[150px]" title="{{ $viaje->origen }}">{{ $viaje->origen }}</span>
                                </div>
                                <div class="flex items-center gap-3 mt-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                                    <span class="text-sm font-medium text-slate-300 truncate max-w-[150px]" title="{{ $viaje->destino }}">{{ $viaje->destino }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="font-black text-white">{{ number_format($viaje->monto, 2) }} {{ $facturacion->moneda }}</div>
                                <div class="text-[10px] text-slate-500 mt-1">{{ number_format($viaje->distancia, 1) }} km</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
