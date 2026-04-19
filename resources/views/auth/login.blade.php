<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AdminPanel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            background-image: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 glass-card rounded-3xl shadow-2xl">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400 tracking-tighter mb-2">Ingreso al Sistema</h1>
            <p class="text-slate-400 text-sm uppercase tracking-widest font-bold">Panel de Auditoría Corporativa</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Correo Electrónico</label>
                <input type="email" name="email" required autofocus class="w-full bg-slate-900/50 border border-slate-700/50 rounded-2xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-inner" placeholder="ejemplo@email.com">
            </div>

            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Contraseña</label>
                <input type="password" name="password" required class="w-full bg-slate-900/50 border border-slate-700/50 rounded-2xl py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-inner" placeholder="••••••••">
            </div>

            @if($errors->any())
                <div class="p-4 bg-red-900/30 border border-red-800 rounded-xl text-red-400 text-xs font-bold">
                    {{ $errors->first() }}
                </div>
            @endif

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white font-black py-4 rounded-2xl shadow-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] uppercase tracking-widest text-sm">
                Entrar al Panel
            </button>
        </form>

        <div class="mt-10 pt-8 border-t border-slate-800 text-center">
            <p class="text-slate-600 text-[10px] font-bold uppercase tracking-widest">OnlyOne Audit & Billing Systems</p>
        </div>
    </div>

</body>
</html>
