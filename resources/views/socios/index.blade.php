@extends('layouts.admin')

@section('title', __('Gestión de Dueños y Flotas'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Dueños y Flotas') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Administra la jerarquía de Dueños y Provincias.') }}</p>
        </div>
        <a href="{{ route('socios.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-9-1s-1 0-1 1v5a1 1 0 001 1h10a1 1 0 001-1V8l-3-3H6z"></path></svg>
            {{ __('Nuevo Dueño / Flota') }}
        </a>
    </div>

    <div class="bg-slate-900 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-white/5">
                    <th class="px-8 py-6">{{ __('Identificación') }}</th>
                    <th class="px-8 py-6">{{ __('Nivel') }}</th>
                    <th class="px-8 py-6">{{ __('Dependencia') }}</th>
                    <th class="px-8 py-6 text-center">{{ __('Acciones') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/[0.03]">
                @foreach($socios as $s)
                <tr class="group hover:bg-white/[0.02] transition-all">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 flex items-center justify-center border border-indigo-500/20 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <p class="text-base font-black text-white tracking-tight uppercase">{{ $s->nombre }}</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">UUID: {{ substr($s->uuid ?? 'N/A', 0, 8) }}...</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @if($s->nivel == 1)
                            <span class="bg-purple-500/10 text-purple-400 border border-purple-500/20 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider">
                                {{ __('Dueño de Empresa') }} (L1)
                            </span>
                        @else
                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider">
                                {{ __('Provincia / Sucursal') }} (L2)
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        @if($s->parent)
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-300 uppercase leading-none">{{ $s->parent->nombre }}</span>
                                <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest mt-1">{{ __('Superior Directo') }}</span>
                            </div>
                        @else
                            <span class="text-xs text-slate-600 italic">{{ __('Nivel Raíz') }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('socios.edit', $s) }}" class="p-2.5 rounded-xl bg-slate-800 text-blue-400 hover:bg-indigo-600 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-7M16.242 19.242L19.242 16.242M19.242 16.242L21.364 14.121a2.828 2.828 0 10-4.001-4.001L15.242 12.242M19.242 16.242l-3 3"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
