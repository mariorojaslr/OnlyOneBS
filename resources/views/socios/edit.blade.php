@extends('layouts.admin')

@section('title', __('Editar Socio/Flota'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10 flex items-center gap-6">
        <a href="{{ route('socios.index') }}" class="p-3 bg-slate-900 border border-white/5 rounded-2xl text-slate-400 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Editar Socio / Flota') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Modifica los datos de la estructura jerárquica.') }}</p>
        </div>
    </div>

    <form action="{{ route('socios.update', $socio->id) }}" method="POST" class="space-y-8" x-data="{ nivel: {{ $socio->nivel }} }">
        @csrf
        @method('PUT')
        
        <div class="bg-slate-900 rounded-[2.5rem] border border-white/5 shadow-2xl p-10">
            <h3 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                {{ __('Parámetros de Estructura') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Nombre de la Flota / Dueño') }}</label>
                    <input type="text" name="nombre" value="{{ $socio->nombre }}" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required>
                </div>
                
                @if(auth()->user()->isSuperAdmin())
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Nivel de Jerarquía') }}</label>
                    <select name="nivel" x-model="nivel" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                        <option value="1" {{ $socio->nivel == 1 ? 'selected' : '' }}>{{ __('Dueño de Empresa (L1)') }}</option>
                        <option value="2" {{ $socio->nivel == 2 ? 'selected' : '' }}>{{ __('Provincia / Sucursal (L2)') }}</option>
                    </select>
                </div>

                <template x-if="nivel == 2">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Superior Responsable (L1)') }}</label>
                        <select name="parent_id" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" {{ $socio->parent_id == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>
                @else
                    <input type="hidden" name="nivel" value="{{ $socio->nivel }}">
                    <input type="hidden" name="parent_id" value="{{ $socio->parent_id }}">
                @endif

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Ciudad') }}</label>
                    <input type="text" name="ciudad" value="{{ $socio->ciudad }}" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('País') }}</label>
                    <input type="text" name="pais" value="{{ $socio->pais }}" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-12 py-5 rounded-2xl font-black transition-all shadow-2xl shadow-indigo-600/30 flex items-center gap-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Actualizar Socio') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
