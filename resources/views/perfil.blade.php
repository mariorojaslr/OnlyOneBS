@extends('layouts.admin')

@section('title', __('Mi Perfil'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10 text-center">
        <div class="inline-block w-24 h-24 rounded-[2rem] bg-gradient-to-tr from-indigo-600 to-emerald-500 p-1 shadow-2xl mb-4">
            <div class="w-full h-full rounded-[1.8rem] bg-slate-900 flex items-center justify-center border border-white/10">
                <span class="text-3xl font-black text-white">MR</span>
            </div>
        </div>
        <h1 class="text-4xl font-black text-white tracking-tighter">{{ __('Configuración de Perfil') }}</h1>
        <p class="text-slate-500 mt-2 font-medium">{{ __('Gestiona tu información personal y la seguridad de tu cuenta.') }}</p>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
        @csrf
        
        <!-- Datos Personales -->
        <div class="bg-slate-900 rounded-[2.5rem] border border-white/5 shadow-2xl p-10">
            <h3 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                {{ __('Información Personal') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Nombre Completo') }}</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Correo Electrónico') }}</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Teléfono de Contacto') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+54 9..." class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Rol en el Sistema') }}</label>
                    <input type="text" value="{{ strtoupper($user->role) }}" class="w-full bg-slate-800/50 border border-white/5 rounded-2xl px-6 py-4 text-slate-500 font-black cursor-not-allowed" disabled>
                </div>
            </div>
        </div>

        <!-- Seguridad 2FA Telegram -->
        <div class="bg-slate-900 rounded-[2.5rem] border border-white/5 shadow-2xl p-10 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <svg class="w-32 h-32 text-indigo-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.12.02-1.96 1.25-5.54 3.69-.52.35-.99.53-1.41.52-.46-.01-1.35-.26-2.01-.48-.81-.27-1.45-.42-1.39-.89.03-.25.38-.51 1.07-.78 4.2-1.82 7-3.03 8.4-3.61 4-.1.17 2.49.17 2.49.52.4.52.81 0 1.2z"/></svg>
            </div>
            
            <h3 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                {{ __('Seguridad Telegram 2FA') }}
            </h3>
            
            <div class="flex flex-col md:flex-row gap-10 items-center">
                <div class="flex-1 space-y-6">
                    <p class="text-sm text-slate-400 font-medium leading-relaxed">
                        {{ __('Protege tu cuenta activando la autenticación de dos pasos. Recibirás un código de acceso único en tu Telegram cada vez que inicies sesión.') }}
                    </p>
                    
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">{{ __('Tu Telegram ID') }}</label>
                        <input type="text" name="telegram_id" value="{{ old('telegram_id', $user->telegram_id) }}" placeholder="Ej: 123456789" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all">
                        <p class="text-[9px] text-slate-600 font-bold italic">{{ __('Puedes obtener tu ID hablando con @userinfobot en Telegram.') }}</p>
                    </div>

                    <div class="flex items-center gap-4 bg-slate-800/50 p-6 rounded-3xl border border-white/5">
                        <div x-data="{ useAuth: {{ $user->use_2fa ? 'true' : 'false' }} }">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="use_2fa" class="sr-only peer" x-model="useAuth" {{ $user->use_2fa ? 'checked' : '' }}>
                                <div class="w-14 h-7 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-white uppercase tracking-wider">{{ __('Activar Autenticación 2FA') }}</span>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">{{ __('Solo disponible para SuperAdmin') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="w-full md:w-64 bg-slate-950 p-6 rounded-3xl border border-white/5 text-center">
                    <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-emerald-500/20">
                        <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest leading-relaxed">
                        {{ __('Tu cuenta está protegida por encriptación de grado militar.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Cambio de Contraseña -->
        <div class="bg-slate-900 rounded-[2.5rem] border border-white/5 shadow-2xl p-10">
            <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-slate-700 rounded-full"></span>
                {{ __('Cambiar Contraseña') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Nueva Contraseña') }}</label>
                    <input type="password" name="password" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">{{ __('Confirmar Nueva Contraseña') }}</label>
                    <input type="password" name="password_confirmation" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-6">
            <button type="submit" class="bg-gradient-to-r from-indigo-600 to-emerald-600 hover:from-indigo-700 hover:to-emerald-700 text-white px-12 py-5 rounded-2xl font-black transition-all shadow-2xl shadow-indigo-600/30 flex items-center gap-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ __('Guardar Todos los Cambios') }}
            </button>
        </div>
    </form>
</div>
@endsection
