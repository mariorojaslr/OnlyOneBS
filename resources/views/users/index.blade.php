@extends('layouts.admin')

@section('title', __('Gestión de Usuarios'))

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Usuarios del Sistema') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Administra los accesos para Socios y Empresas Clientes.') }}</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            {{ __('Nuevo Usuario') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl mb-8 flex items-center gap-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-slate-900 rounded-[2rem] border border-white/5 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-white/5">
                        <th class="px-8 py-6">{{ __('Usuario') }}</th>
                        <th class="px-8 py-6">{{ __('Rol / Permisos') }}</th>
                        <th class="px-8 py-6">{{ __('Vinculación') }}</th>
                        <th class="px-8 py-6">{{ __('Seguridad') }}</th>
                        <th class="px-8 py-6 text-center">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.03]">
                    @foreach($users as $u)
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center font-black text-xs text-indigo-400 border border-white/5 group-hover:border-indigo-500 transition-all">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-white group-hover:text-indigo-400 transition-colors">{{ $u->name }}</p>
                                    <p class="text-xs text-slate-500 font-medium">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider
                                @if($u->role === 'superadmin') bg-purple-500/10 text-purple-400 border border-purple-500/20
                                @elseif($u->role === 'admin') bg-blue-500/10 text-blue-400 border border-blue-500/20
                                @elseif($u->role === 'socio') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                @else bg-slate-500/10 text-slate-400 border border-slate-500/20 @endif">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            @if($u->empresa)
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-indigo-400 truncate max-w-[150px]">{{ $u->empresa->nombre }}</span>
                                    <span class="text-[9px] text-slate-600 font-bold uppercase tracking-wider">{{ __('Empresa') }}</span>
                                </div>
                            @elseif($u->socio)
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-emerald-400 truncate max-w-[150px]">{{ $u->socio->nombre }}</span>
                                    <span class="text-[9px] text-slate-600 font-bold uppercase tracking-wider">{{ __('Socio / Flota') }}</span>
                                </div>
                            @else
                                <span class="text-xs text-slate-600 italic">{{ __('Todo el Sistema') }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                @if($u->use_2fa)
                                    <div class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]"></div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Telegram 2FA</span>
                                @else
                                    <div class="w-2 h-2 rounded-full bg-slate-700"></div>
                                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">{{ __('Estándar') }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('users.edit', $u) }}" class="p-2.5 rounded-xl bg-slate-800 text-blue-400 hover:bg-indigo-600 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-7M16.242 19.242L19.242 16.242M19.242 16.242L21.364 14.121a2.828 2.828 0 10-4.001-4.001L15.242 12.242M19.242 16.242l-3 3"></path></svg>
                                </a>
                                @if($u->id !== auth()->id())
                                <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2.5 rounded-xl bg-slate-800 text-red-400 hover:bg-red-600 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endif
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
