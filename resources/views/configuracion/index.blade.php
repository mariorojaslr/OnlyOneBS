@extends('layouts.admin')

@section('title', 'Configuración del Sistema para Taxis')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="glass-panel p-8 rounded-xl shadow-2xl">
        <div class="mb-8">
            <h2 class="text-3xl font-black text-white bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 to-orange-500">Consola de Conectividad</h2>
            <p class="text-slate-400 mt-2">Gestioná las llaves de acceso para la automatización del Sistema para Taxis.</p>
        </div>

        <form action="{{ route('configuracion.update') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Onde API Section -->
            <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-indigo-900/50 flex items-center justify-center text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white uppercase tracking-tight">Onde API (Sincronización de Viajes)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Operator API Token</label>
                        <input type="password" name="onde_api_token" value="{{ $config['onde_api_token'] }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl py-3 px-4 text-emerald-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono text-sm" placeholder="Paste token here...">
                        <p class="text-[10px] text-slate-500 mt-2 italic px-1">Generado en Onde > Company Management > Operators.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Company ID</label>
                        <input type="text" name="onde_company_id" value="{{ $config['onde_company_id'] }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="e.g. fd13d28d-...">
                    </div>
                </div>
            </div>

            <!-- Telegram Section -->
            <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700 opacity-60">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-900/50 flex items-center justify-center text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight text-slate-300">Notificaciones & 2FA (Próximamente)</h3>
                    </div>
                    <span class="text-[10px] font-black bg-blue-500 text-white px-2 py-1 rounded-full uppercase">Work in Progress</span>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Telegram Bot Token</label>
                    <input type="text" readonly name="telegram_bot_token" value="{{ $config['telegram_bot_token'] }}" class="w-full bg-slate-900/50 border border-slate-800 rounded-xl py-3 px-4 text-slate-600 cursor-not-allowed text-xs" placeholder="BOT_TOKEN_FROM_FATHERBOT">
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-slate-800">
                <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black px-10 py-4 rounded-2xl shadow-xl transition-all uppercase tracking-widest text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
