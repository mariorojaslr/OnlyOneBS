@extends('layouts.admin')

@section('title', __('Gestión de Empresas Corporativas'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Empresas Corporativas') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Administra los clientes finales de cada sucursal o dueño.') }}</p>
        </div>
        <a href="{{ route('empresas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            {{ __('Nueva Empresa') }}
        </a>
    </div>

    <div class="bg-slate-900 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-white/5">
                    <th class="px-8 py-6">{{ __('Empresa') }}</th>
                    <th class="px-8 py-6">{{ __('Código Corporativo') }}</th>
                    <th class="px-8 py-6">{{ __('Propietario / Flota') }}</th>
                    <th class="px-8 py-6 text-center">{{ __('Acciones') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/[0.03]">
                @foreach($empresas as $e)
                <tr class="group hover:bg-white/[0.02] transition-all">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 group-hover:scale-110 transition-transform shadow-inner">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-base font-black text-white px-1 tracking-tight uppercase">{{ $e->nombre }}</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $e->razon_social ?? __('Sin Razón Social') }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="bg-indigo-600/10 text-indigo-400 border border-indigo-500/20 px-4 py-2 rounded-xl text-xs font-mono font-bold tracking-widest">
                            {{ $e->uuid }}
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        @if($e->socio)
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-300 uppercase whitespace-nowrap">{{ $e->socio->nombre }}</span>
                                <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest mt-1">{{ $e->socio->nivel == 1 ? __('Propietario WL') : __('Sucursal') }}</span>
                            </div>
                        @else
                            <span class="text-xs text-red-500/70 italic">{{ __('Sin Vincular') }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('empresas.edit', $e) }}" class="p-2.5 rounded-xl bg-slate-800 text-blue-400 hover:bg-indigo-600 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-7M16.242 19.242L19.242 16.242M19.242 16.242L21.364 14.121a2.828 2.828 0 10-4.001-4.001L15.242 12.242M19.242 16.242l-3 3"></path></svg>
                            </a>
                            <form action="{{ route('empresas.destroy', $e) }}" method="POST" onsubmit="return confirm('¿Seguro quieres eliminar esta empresa?')">
                                @csrf @method('DELETE')
                                <button class="p-2.5 rounded-xl bg-slate-800 text-red-400 hover:bg-red-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
