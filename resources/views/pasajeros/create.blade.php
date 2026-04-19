@extends('layouts.admin')

@section('title', 'Nuevo Pasajero')

@section('content')
<div class="glass-panel p-6 rounded-xl max-w-2xl mx-auto mt-8">
    <h3 class="text-xl font-semibold mb-6">Agregar Pasajero Manualmente</h3>
    
    @if ($errors->any())
        <div class="mb-4 bg-red-900/30 border border-red-800 text-red-400 px-4 py-3 rounded relative">
            <strong class="font-bold">Error en la carga:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('pasajeros.store') }}" method="POST">
        @csrf
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300">Nombre Completo del Pasajero</label>
                <input type="text" name="nombre_completo" value="{{ old('nombre_completo') }}" placeholder="Ej: Anahi" class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300">Número de Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}" placeholder="+54911..." class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2 font-mono text-emerald-300">
                <p class="text-xs text-emerald-500 mt-1">Ésta es la "llave". Debe ser EXACTAMENTE igual a como aparece en el reporte de viajes.</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300">DNI / Documento</label>
                <input type="text" name="documento" value="{{ old('documento') }}" class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">¿A qué Centro de Costo y Empresa pertenece?</label>
                <select name="centro_costo_id" class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2">
                    @foreach($centros as $centro)
                        <option value="{{ $centro->id }}" {{ old('centro_costo_id') == $centro->id ? 'selected' : '' }}>
                            {{ $centro->nombre }} (pertenece a: {{ $centro->empresa->nombre ?? 'Fantasma' }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-md font-semibold transition">Guardar Pasajero</button>
            <a href="{{ route('pasajeros.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-6 py-2 rounded-md font-semibold transition">Cancelar</a>
        </div>
    </form>
</div>
@endsection
