@extends('layouts.admin')

@section('title', __('Facturación y Cierres'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Facturación y Cierres') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Gestiona los cierres de facturación y archiva los viajes procesados.') }}</p>
        </div>
        <a href="{{ route('facturacion.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-2xl shadow-indigo-600/30 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Nuevo Cierre') }}
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-emerald-900/30 border border-emerald-800 text-emerald-400 px-6 py-4 rounded-2xl font-bold flex items-center gap-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-slate-900 border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
        @if($cierres->isEmpty())
            <div class="p-20 text-center flex flex-col items-center justify-center">
                <div class="w-24 h-24 bg-slate-800 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">{{ __('Sin cierres históricos') }}</h3>
                <p class="text-slate-400">{{ __('No se han generado ciclos de facturación todavía.') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-950/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('ID Cierre') }}</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Cuenta Corporativa') }}</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Período') }}</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Viajes') }}</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Monto Total') }}</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Estado') }}</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($cierres as $cierre)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-5 font-mono text-xs text-indigo-400">#{{ str_pad($cierre->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-white">{{ $cierre->empresa->nombre }}</div>
                                    <div class="text-xs text-slate-500">{{ $cierre->empresa->uuid }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-white">{{ \Carbon\Carbon::parse($cierre->fecha_inicio)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($cierre->fecha_fin)->format('d M, Y') }}</div>
                                </td>
                                <td class="px-8 py-5 text-slate-300 font-bold">{{ $cierre->cantidad_viajes }}</td>
                                <td class="px-8 py-5">
                                    <span class="font-black {{ $cierre->moneda == 'USD' ? 'text-emerald-400' : 'text-amber-400' }}">
                                        {{ number_format($cierre->monto_total, 2) }} {{ $cierre->moneda }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    @if($cierre->estado == 'emitido')
                                        <span class="px-3 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full text-[10px] font-black uppercase tracking-widest">{{ __('Emitido') }}</span>
                                    @elseif($cierre->estado == 'pagado')
                                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-[10px] font-black uppercase tracking-widest">{{ __('Pagado') }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('facturacion.show', $cierre->id) }}" class="text-indigo-400 hover:text-indigo-300 font-bold text-sm bg-indigo-500/10 hover:bg-indigo-500/20 px-4 py-2 rounded-xl transition-all">
                                        {{ __('Ver Detalle') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
