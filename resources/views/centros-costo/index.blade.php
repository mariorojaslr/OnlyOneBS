@extends('layouts.admin')

@section('title', __('Centros de Costo'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Centros de Costo') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Segmentación de gastos por departamentos, sucursales o proyectos.') }}</p>
        </div>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Nuevo Centro') }}
        </button>
    </div>

    <div class="bg-slate-900 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-white/5">
                        <th class="px-8 py-6">{{ __('Número de Centro') }}</th>
                        <th class="px-8 py-6">{{ __('Nombre del Departamento') }}</th>
                        <th class="px-8 py-6">{{ __('Empresa Asociada') }}</th>
                        <th class="px-8 py-6 text-center">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.03]">
                    @foreach($centros as $centro)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="px-8 py-6">
                            <span class="font-mono text-xs font-black text-indigo-400 bg-indigo-500/10 px-4 py-2 rounded-xl border border-indigo-500/20">
                                #{{ $centro->numero }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-sm font-black text-white group-hover:text-indigo-400 transition-colors uppercase tracking-wide">{{ $centro->nombre }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center border border-white/5">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <span class="text-sm text-slate-400 font-bold">{{ $centro->empresa->nombre ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('centros-costo.edit', $centro) }}" class="p-2.5 rounded-xl bg-slate-800 text-blue-400 hover:bg-indigo-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-7M16.242 19.242L19.242 16.242M19.242 16.242L21.364 14.121a2.828 2.828 0 10-4.001-4.001L15.242 12.242M19.242 16.242l-3 3"></path></svg>
                                </a>
                                <button class="p-2.5 rounded-xl bg-slate-800 text-red-400 hover:bg-red-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
