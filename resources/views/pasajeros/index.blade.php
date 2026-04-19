@extends('layouts.admin')

@section('title', __('Pasajeros'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Gestión de Pasajeros') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Directorio de usuarios autorizados para el sistema de traslados.') }}</p>
        </div>
        <div class="flex gap-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-4 rounded-2xl font-black transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path></svg>
                {{ __('Importar CSV') }}
            </button>
            <a href="{{ route('pasajeros.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-3 text-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                {{ __('Nuevo Pasajero') }}
            </a>
        </div>
    </div>

    <div class="bg-slate-900 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-white/5">
                        <th class="px-8 py-6">{{ __('Nombre y Apellido') }}</th>
                        <th class="px-8 py-6">{{ __('Teléfono (ID Único)') }}</th>
                        <th class="px-8 py-6">{{ __('Documento') }}</th>
                        <th class="px-8 py-6">{{ __('Centro y Empresa') }}</th>
                        <th class="px-8 py-6 text-center">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.03]">
                    @foreach($pasajeros as $pasajero)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center border border-white/10 group-hover:border-indigo-500 transition-all font-black text-xs text-indigo-400">
                                    {{ strtoupper(substr($pasajero->nombre_completo, 0, 2)) }}
                                </div>
                                <p class="text-sm font-black text-white group-hover:text-indigo-400 transition-colors uppercase tracking-tight">{{ $pasajero->nombre_completo }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-mono text-xs font-black text-emerald-400 bg-emerald-500/10 px-4 py-2 rounded-xl border border-emerald-500/20">
                                {{ $pasajero->telefono ?? 'S/N' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-400 font-medium">{{ $pasajero->documento ?? '---' }}</td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-indigo-400 truncate max-w-[150px]">{{ $pasajero->centroCosto->nombre ?? 'N/A' }}</span>
                                <span class="text-[9px] text-slate-600 font-black uppercase tracking-wider">{{ $pasajero->centroCosto->empresa->nombre ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('pasajeros.edit', $pasajero) }}" class="p-2.5 rounded-xl bg-slate-800 text-blue-400 hover:bg-indigo-600 hover:text-white transition-all">
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
