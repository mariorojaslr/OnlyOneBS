<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguridad 2FA - OnlyOneBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #020617; }
        .glass { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-emerald-500/10 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-emerald-500/20 shadow-[0_0_30px_rgba(16,185,129,0.1)]">
                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase">{{ __('Verificación de Seguridad') }}</h1>
            <p class="text-slate-500 mt-2 font-medium">{{ __('Hemos enviado un código de 6 dígitos a tu Telegram.') }}</p>
        </div>

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-2xl mb-6 text-sm font-bold text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('2fa.verify') }}" method="POST" class="space-y-6">
            @csrf
            <div class="glass p-8 rounded-[2.5rem] shadow-2xl">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 text-center">{{ __('Ingresar Código 2FA') }}</label>
                <input type="text" name="code" maxlength="6" autofocus placeholder="000000" class="w-full bg-slate-800 border border-white/5 rounded-2xl px-6 py-5 text-center text-4xl font-black text-white tracking-[0.5em] focus:ring-4 focus:ring-emerald-500/20 focus:outline-none transition-all placeholder:text-slate-700">
                
                <button type="submit" class="w-full mt-8 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white py-5 rounded-2xl font-black transition-all shadow-xl shadow-emerald-900/40 uppercase tracking-widest text-xs">
                    {{ __('Verificar y Entrar') }}
                </button>

                <div class="mt-6 text-center">
                    <a href="{{ route('2fa.resend') }}" class="text-[10px] font-black text-slate-500 hover:text-indigo-400 uppercase tracking-widest transition-colors tracking-tighter">
                        {{ __('¿No recibiste el código? Reenviar') }}
                    </a>
                </div>
            </div>
        </form>

        <div class="mt-10 text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-white text-xs font-black uppercase tracking-widest transition-colors flex items-center justify-center gap-2 mx-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    {{ __('Cancelar e ir al Inicio') }}
                </button>
            </form>
        </div>
    </div>
</body>
</html>
