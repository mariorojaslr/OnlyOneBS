@extends('layouts.admin')

@section('title', 'Sincronizador de Viajes')

@section('content')
<div class="glass-panel p-8 rounded-xl max-w-3xl mx-auto mt-10">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-900/50 text-indigo-400 mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
        </div>
        <h3 class="text-2xl font-bold text-white">Importador de Reporte (CSV)</h3>
        <p class="text-gray-400 mt-2">Subí el archivo maestro de viajes extraído del sistema original para actualizar la base de datos y distribuir los costos.</p>
    </div>
    
    <div class="bg-slate-800/50 p-6 rounded-lg border border-slate-700">
        <form action="{{ route('importar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Asignar Reporte al Socio / Sucursal</label>
                <select name="socio_id" required class="w-full bg-slate-900 border border-slate-700 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Seleccione un socio...</option>
                    @foreach($socios as $s)
                        <option value="{{ $s->id }}">{{ $s->nombre }} ({{ $s->ciudad }})</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-widest font-bold">Todos los viajes cargados se asociarán a las empresas de este socio.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Archivo ORDENES.csv</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-600 border-dashed rounded-md hover:border-indigo-500 transition cursor-pointer relative">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-400 justify-center">
                            <label for="file-upload" class="relative cursor-pointer bg-slate-900 rounded-md font-medium text-indigo-400 hover:text-indigo-300 focus-within:outline-none px-2 py-1">
                                <span>Seleccionar archivo CSV</span>
                                <input id="file-upload" name="csv_file" type="file" accept=".csv" class="sr-only" required>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">Solo archivos con extensión .csv</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-700">
                <button type="submit" class="px-6 py-3 rounded-md bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold transition-all shadow-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Procesar y Sincronizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('file-upload').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            let label = e.target.parentElement.querySelector('span');
            label.textContent = e.target.files[0].name;
            label.classList.add('text-emerald-400');
        }
    });
</script>
@endsection
