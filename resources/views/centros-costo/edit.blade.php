@extends('layouts.admin')

@section('title', 'Editar Centro de Costo')

@section('content')
<div class="glass-panel p-6 rounded-xl max-w-2xl mx-auto mt-8">
    <h3 class="text-xl font-semibold mb-6">Editando Centro: {{ $centro->nombre }}</h3>
    
    @if ($errors->any())
        <div class="mb-4 bg-red-900/30 border border-red-800 text-red-400 px-4 py-3 rounded relative">
            <strong class="font-bold">Hay errores:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('centros-costo.update', $centro->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300">Nombre del Centro de Costo ('Central', 'RRHH', etc)</label>
                <input type="text" name="nombre" value="{{ $centro->nombre }}" class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300">Número</label>
                <input type="text" name="numero" value="{{ $centro->numero }}" class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300">Pertenece a la Empresa</label>
                <select name="empresa_id" class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2">
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}" {{ $centro->empresa_id == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-emerald-400 mt-1">Acá podés reasignar este centro a "On Time" u otra empresa.</p>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-semibold transition">Guardar Cambios</button>
            <a href="{{ route('centros-costo.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-6 py-2 rounded-md font-semibold transition">Cancelar</a>
        </div>
    </form>
</div>
@endsection
