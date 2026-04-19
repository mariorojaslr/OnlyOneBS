@extends('layouts.admin')

@section('title', __('Editar Usuario'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10 flex items-center gap-6">
        <a href="{{ route('users.index') }}" class="p-3 bg-slate-900 border border-white/5 rounded-2xl text-slate-400 hover:text-white transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Editar Usuario') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Modifica los permisos o credenciales del usuario.') }}</p>
        </div>
    </div>

    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-8" x-data="{ selectedRole: '{{ $user->role }}' }">
        @csrf @method('PUT')
        
        <div class="bg-slate-900 rounded-[2.5rem] border border-white/5 shadow-2xl p-10">
            <h3 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                {{ __('Información de Cuenta') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Nombre Completo') }}</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Correo Electrónico') }}</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Nueva Contraseña') }} <span class="text-slate-600">(mín. 8 carácteres - dejar vacío para mantener)</span></label>
                    <input type="password" name="password" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Rol de Usuario') }}</label>
                    <select name="role" x-model="selectedRole" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                        @foreach($roles as $key => $value)
                            <option value="{{ $key }}" {{ $user->role === $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Selección Dinámica -->
                <template x-if="selectedRole === 'socio'">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Vincular a Socio/Flota') }}</label>
                        <select name="socio_id" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                            @foreach($socios as $s)
                                <option value="{{ $s->id }}" {{ $user->socio_id == $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>

                <template x-if="selectedRole === 'empresa' || selectedRole === 'admin_view'">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Vincular a Empresa Cliente') }}</label>
                        <select name="empresa_id" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                            @foreach($empresas as $e)
                                <option value="{{ $e->id }}" {{ $user->empresa_id == $e->id ? 'selected' : '' }}>{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-12 py-5 rounded-2xl font-black transition-all shadow-2xl shadow-indigo-600/30 flex items-center gap-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-7M16.242 19.242L19.242 16.242M19.242 16.242L21.364 14.121a2.828 2.828 0 10-4.001-4.001L15.242 12.242M19.242 16.242l-3 3"></path></svg>
                    {{ __('Actualizar Usuario') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
