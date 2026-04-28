@extends('layouts.admin')

@section('title', __('Nueva Empresa Corporativa'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10 flex items-center gap-6">
        <a href="{{ route('empresas.index') }}" class="p-3 bg-slate-900 border border-white/5 rounded-2xl text-slate-400 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Crear Cliente Corporativo') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Configura una nueva empresa para auditoría de viajes.') }}</p>
        </div>
    </div>

    <form action="{{ route('empresas.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="bg-slate-900 rounded-[2.5rem] border border-white/5 shadow-2xl p-10">
            <h3 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                {{ __('Información del Cliente') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Nombre Comercial / Fantasía') }}</label>
                    <input type="text" name="nombre" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required placeholder="Ej: Tech Solutions S.A.">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Código Corporativo UUID') }}</label>
                    <input type="text" name="uuid" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required placeholder="Ej: TECH-SOL-2026">
                </div>

                @if(count($socios) > 1)
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Vincular a Flota Responsable') }}</label>
                        <select name="socio_id" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required>
                            <option value="">{{ __('Seleccione responsable...') }}</option>
                            @foreach($socios as $s)
                                <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <!-- Campo oculto porque estamos en una "Habitación" -->
                    <input type="hidden" name="socio_id" value="{{ $socios->first()->id ?? '' }}">
                @endif

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Razón Social (Opcional)') }}</label>
                    <input type="text" name="razon_social" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Cuenta Corriente / ID Ref (Opcional)') }}</label>
                    <input type="text" name="cuenta_corriente" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Ciclo de Facturación') }}</label>
                    <select name="ciclo_facturacion" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required>
                        <option value="semanal">{{ __('Semanal') }}</option>
                        <option value="quincenal">{{ __('Quincenal') }}</option>
                        <option value="mensual" selected>{{ __('Mensual') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Moneda') }}</label>
                    <select name="moneda" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required>
                        <option value="ARS" selected>{{ __('Pesos Argentinos (ARS)') }}</option>
                        <option value="USD">{{ __('Dólares Estadounidenses (USD)') }}</option>
                    </select>
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-12 py-5 rounded-2xl font-black transition-all shadow-2xl shadow-indigo-600/30 flex items-center gap-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Guardar Cliente') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
