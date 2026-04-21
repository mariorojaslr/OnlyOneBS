@extends('layouts.admin')

@section('title', __('Reporte Consolidado Corporativo'))

@section('content')
<div x-data="{ view: 'list' }" class="max-w-7xl mx-auto">
    
    <!-- Filtros de Dashboard y Toggle de Vista -->
    <div class="flex flex-col lg:flex-row justify-between items-center mb-12 gap-8">
        <div class="text-left w-full lg:w-auto">
            <h1 class="text-5xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400 tracking-tighter leading-tight">{{ __('Panel de Consumos') }}</h1>
            <p class="text-slate-500 mt-2 text-xl font-medium">{{ __('Auditoría con Trazabilidad Completa') }}</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-4 justify-end w-full lg:w-auto">
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isOwner())
            <div class="bg-slate-900 border border-white/5 px-5 py-2.5 rounded-2xl flex items-center gap-4 shadow-xl">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ auth()->user()->isOwner() ? __('Sucursal / Provincia') : __('Socio / Sucursal') }}:</span>
                <form action="{{ route('dashboard') }}" method="GET" id="socioForm">
                    <select name="socio_id" onchange="document.getElementById('socioForm').submit()" class="bg-transparent text-sm font-black text-white focus:outline-none cursor-pointer">
                        @if(auth()->user()->isSuperAdmin() || auth()->user()->isOwner())
                            <option value="all" {{ $selectedSocioId == 'all' ? 'selected' : '' }} class="bg-slate-900 font-bold text-indigo-400">-- {{ __('TODAS LAS PROVINCIAS') }} --</option>
                        @endif
                        @foreach($socios as $s)
                            <option value="{{ $s->id }}" {{ $selectedSocioId == $s->id ? 'selected' : '' }} class="bg-slate-900">{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            @endif

            <div class="flex bg-slate-900 p-1.5 rounded-2xl border border-white/5 shadow-2xl">
                <button @click="view = 'list'" :class="view === 'list' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:text-slate-300'" class="px-8 py-3 rounded-xl font-black transition-all text-xs flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    {{ __('Auditoría') }}
                </button>
                <button @click="view = 'charts'" :class="view === 'charts' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:text-slate-300'" class="px-8 py-3 rounded-xl font-black transition-all text-xs flex items-center gap-3 border-l border-white/5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    {{ __('Gráficos') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Contenido en Vista Lista -->
    <div x-show="view === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
        @foreach($datos['empresas'] as $empresaId => $empresaInfo)
            @php $empresa = $empresaInfo['model']; @endphp
            <div x-data="{ open: false }" class="bg-slate-900 rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden group">
                <!-- CABECERA EMPRESA -->
                <div @click="open = !open" class="px-10 py-10 flex items-center gap-8 cursor-pointer hover:bg-white/[0.02] transition-colors">
                    <div class="w-20 h-20 rounded-3xl bg-indigo-600/10 flex items-center justify-center border border-indigo-500/20 group-hover:scale-105 transition-transform shadow-inner">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-4xl font-black text-white tracking-tighter">{{ $empresa->nombre }}</h2>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-[0.3em] mt-2">UUID: {{ $empresa->uuid }}</p>
                    </div>
                    <div class="flex items-center gap-16 text-right">
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ __('Recorrido Total') }}</p>
                            <p class="text-3xl font-black text-white leading-none">{{ number_format($empresaInfo['km_total'], 1, ',', '.') }} <span class="text-indigo-400 text-sm">KM</span></p>
                        </div>
                        <div class="pl-16 border-l border-white/10">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ __('Costo Consolidado') }}</p>
                            <p class="text-4xl font-black text-emerald-400 tracking-tighter leading-none">${{ number_format($empresaInfo['monto_total'], 2, ',', '.') }}</p>
                        </div>
                        <svg class="w-8 h-8 text-slate-700 transition-transform duration-500" :class="{'rotate-180 text-white': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- DETALLE DE CENTROS (SUB-ACORDEÓN) -->
                <div x-show="open" x-collapse>
                    <div class="px-10 pb-12 space-y-6">
                        @foreach($empresaInfo['centros'] as $centroId => $centroInfo)
                            @php $centro = $centroInfo['model']; @endphp
                            <div x-data="{ openCentro: false }" class="bg-black/30 rounded-[2rem] border border-white/5 overflow-hidden">
                                <div @click="openCentro = !openCentro" class="px-8 py-6 flex items-center justify-between cursor-pointer hover:bg-white/[0.03] transition-colors group/centro">
                                    <div class="flex items-center gap-6">
                                        <span class="bg-indigo-600/20 text-indigo-400 px-4 py-2 rounded-xl text-xs font-black border border-indigo-500/20 group-hover/centro:bg-indigo-600 group-hover/centro:text-white transition-all">#{{ $centro->numero }}</span>
                                        <div>
                                            <h3 class="text-lg font-black text-slate-200 uppercase tracking-tight">{{ $centro->nombre }}</h3>
                                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $centroInfo['viajes_count'] }} {{ __('Viajes Realizados') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-12 text-right">
                                        <div>
                                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">{{ __('Monto Acumulado') }}</p>
                                            <p class="text-xl font-black text-emerald-400">${{ number_format($centroInfo['monto_total'], 2, ',', '.') }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-slate-600 group-hover/centro:text-white transition-transform" :class="{'rotate-180': openCentro}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>

                                <!-- DETALLE DE PASAJEROS (SUB-SUB-ACORDEÓN) -->
                                <div x-show="openCentro" x-collapse>
                                    <div class="px-8 pb-8 pt-2 space-y-3">
                                        <div class="bg-slate-900/50 rounded-3xl border border-white/5 p-2">
                                            @foreach($centroInfo['pasajeros'] as $pasajeroInfo)
                                            <div x-data="{ openPasajero: false }" class="border-b border-white/[0.03] last:border-0">
                                                <div @click="openPasajero = !openPasajero" class="px-6 py-4 flex items-center justify-between cursor-pointer hover:bg-indigo-600/10 rounded-2xl transition-all group/pas">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center font-black text-xs text-indigo-400 border border-white/5 group-hover/pas:border-indigo-500 transition-all">
                                                            {{ strtoupper(substr($pasajeroInfo['nombre'], 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-black text-white tracking-wide uppercase">{{ $pasajeroInfo['nombre'] }}</p>
                                                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">{{ $pasajeroInfo['viajes_count'] }} {{ __('Viajes') }} • {{ number_format($pasajeroInfo['km_total'], 1, ',', '.') }} KM</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-6">
                                                        <p class="text-sm font-bold text-emerald-400/80">${{ number_format($pasajeroInfo['monto_total'], 2, ',', '.') }}</p>
                                                        <svg class="w-4 h-4 text-slate-700 transition-transform" :class="{'rotate-90 text-indigo-400': openPasajero}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                    </div>
                                                </div>

                                                <!-- HISTORIAL DE VIAJES FINAL -->
                                                <div x-show="openPasajero" x-collapse>
                                                    <div class="px-6 py-4">
                                                        <table class="w-full text-left">
                                                            <thead>
                                                                <tr class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em] border-b border-white/5">
                                                                    <th class="pb-3 text-center w-32">{{ __('Fecha / Hora') }}</th>
                                                                    <th class="pb-3">{{ __('Desde') }}</th>
                                                                    <th class="pb-3">{{ __('Hasta') }}</th>
                                                                    <th class="pb-3 text-right">{{ __('KM') }}</th>
                                                                    <th class="pb-3 text-right pr-4">{{ __('Monto') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-white/[0.02]">
                                                                @foreach($pasajeroInfo['lista_viajes'] as $trip)
                                                                <tr class="text-[11px] font-medium text-slate-400 hover:text-white transition-colors group/row">
                                                                    <td class="py-3 text-center font-mono text-indigo-300 font-bold">{{ $trip['fecha'] }}</td>
                                                                    <td class="py-3 pr-4 max-w-xs truncate">{{ $trip['origen'] }}</td>
                                                                    <td class="py-3 pr-4 max-w-xs truncate">{{ $trip['destino'] }}</td>
                                                                    <td class="py-3 text-right font-black text-slate-300">{{ number_format($trip['distancia'], 1, ',', '.') }}</td>
                                                                    <td class="py-3 text-right pr-4 font-black text-emerald-400">${{ number_format($trip['monto'], 2, ',', '.') }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Contenido en Vista Gráficos (SE MANTIENE IGUAL) -->
    <div x-show="view === 'charts'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-slate-900 p-10 rounded-[2.5rem] border border-white/5 shadow-2xl">
            <h3 class="text-xl font-black text-white tracking-tight mb-8 flex items-center gap-3 uppercase text-xs">
                <span class="w-1.5 h-8 bg-indigo-500 rounded-full"></span>
                {{ __('Costos por Empresa') }}
            </h3>
            <canvas id="costosEmpresaChart" height="300"></canvas>
        </div>
        <div class="bg-slate-900 p-10 rounded-[2.5rem] border border-white/5 shadow-2xl">
            <h3 class="text-xl font-black text-white tracking-tight mb-8 flex items-center gap-3 uppercase text-xs">
                <span class="w-1.5 h-8 bg-emerald-500 rounded-full"></span>
                {{ __('Recorrido por Empresa') }} (KM)
            </h3>
            <canvas id="kmEmpresaChart" height="300"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctxCostos = document.getElementById('costosEmpresaChart').getContext('2d');
        const labels = {!! json_encode($datos['grafico_labels']) !!};
        const costosData = {!! json_encode($datos['grafico_costos']) !!};
        const kmData = {!! json_encode($datos['grafico_km']) !!};

        new Chart(ctxCostos, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '{{ __("Monto Total") }} ($)',
                    data: costosData,
                    backgroundColor: 'rgba(99, 102, 241, 0.4)',
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    borderRadius: 12,
                    hoverBackgroundColor: '#6366f1',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b', font: { weight: 'bold' } } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { weight: 'bold' } } }
                }
            }
        });

        const ctxKM = document.getElementById('kmEmpresaChart').getContext('2d');
        new Chart(ctxKM, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: kmData,
                    backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#475569'],
                    borderWidth: 0,
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8', padding: 20, font: { weight: 'bold' } } } },
                cutout: '70%'
            }
        });
    });
</script>
@endsection
