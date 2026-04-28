@extends('layouts.admin')

@section('title', __('Nuevo Cierre de Facturación'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10 flex items-center gap-6">
        <a href="{{ route('facturacion.index') }}" class="p-3 bg-slate-900 border border-white/5 rounded-2xl text-slate-400 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Nuevo Cierre de Facturación') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Selecciona la cuenta corporativa para facturar sus viajes pendientes.') }}</p>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-8 bg-red-900/30 border border-red-800 text-red-400 px-6 py-4 rounded-2xl font-bold flex items-center gap-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-slate-900 rounded-[2.5rem] border border-white/5 shadow-2xl p-10 mb-8">
        <form action="{{ route('facturacion.create') }}" method="GET" class="flex items-end gap-6">
            <div class="flex-1">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Cuenta Corporativa a Facturar') }}</label>
                <select name="empresa_id" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required>
                    <option value="">{{ __('Seleccione una empresa...') }}</option>
                    @foreach($empresas as $emp)
                        <option value="{{ $emp->id }}" {{ request('empresa_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->nombre }} ({{ ucfirst($emp->ciclo_facturacion) }} - {{ $emp->moneda }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-8 py-4 rounded-2xl font-bold transition-all border border-white/5">
                {{ __('Ver Pendientes') }}
            </button>
        </form>
    </div>

    @if($empresaSeleccionada)
        @if($totales['cantidad'] > 0)
            <div class="bg-indigo-900/20 border border-indigo-500/30 rounded-[2.5rem] p-10 shadow-2xl">
                <h3 class="text-2xl font-black text-white mb-6">{{ __('Resumen del Ciclo') }}: <span class="text-indigo-400">{{ $empresaSeleccionada->nombre }}</span></h3>
                
                <div class="grid grid-cols-3 gap-6 mb-10">
                    <div class="bg-slate-900 rounded-2xl p-6 border border-white/5">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">{{ __('Viajes Pendientes') }}</p>
                        <p class="text-3xl font-black text-white">{{ $totales['cantidad'] }}</p>
                    </div>
                    <div class="bg-slate-900 rounded-2xl p-6 border border-white/5">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">{{ __('Ciclo') }}</p>
                        <p class="text-3xl font-black text-white capitalize">{{ $empresaSeleccionada->ciclo_facturacion }}</p>
                    </div>
                    <div class="bg-slate-900 rounded-2xl p-6 border border-indigo-500/30 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10"></div>
                        <div class="relative">
                            <p class="text-xs font-bold text-indigo-300 uppercase tracking-widest mb-1">{{ __('Monto a Facturar') }}</p>
                            <p class="text-3xl font-black text-white">{{ number_format($totales['monto'], 2) }} <span class="text-indigo-400 text-xl">{{ $totales['moneda'] }}</span></p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('facturacion.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="empresa_id" value="{{ $empresaSeleccionada->id }}">
                    
                    <div class="grid grid-cols-2 gap-6 mb-10">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Fecha Inicio del Período') }}</label>
                            <input type="date" name="fecha_inicio" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" class="w-full bg-slate-900 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Fecha Fin del Período') }}</label>
                            <input type="date" name="fecha_fin" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="w-full bg-slate-900 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-12 py-5 rounded-2xl font-black transition-all shadow-2xl shadow-emerald-600/30 flex items-center gap-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ __('Generar Cierre y Archivar Viajes') }}
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-slate-900 border border-white/5 rounded-[2.5rem] p-20 text-center shadow-2xl">
                <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-white mb-2">{{ __('Todo al día') }}</h3>
                <p class="text-slate-400 font-medium">{{ __('Esta cuenta corporativa no tiene viajes pendientes de facturación.') }}</p>
            </div>
        @endif
    @endif
</div>
@endsection
