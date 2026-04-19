@extends('layouts.admin')

@section('title', 'Editar Empresa')

@section('content')
<div class="glass-panel p-6 rounded-xl max-w-2xl mx-auto mt-8">
    <h3 class="text-xl font-semibold mb-6">Editando: {{ $empresa->nombre }}</h3>
    
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
    
    <form action="{{ route('empresas.update', $empresa->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300">Nombre Corto</label>
                <input type="text" name="nombre" value="{{ $empresa->nombre }}" class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300">Código corporativo UUID (Identificador Largo)</label>
                <input type="text" name="uuid" value="{{ $empresa->uuid }}" class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2 font-mono text-sm text-indigo-300">
                <p class="text-xs text-gray-400 mt-1">Este código es esencial para matchear con el CSV.</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300">Razón Social</label>
                <input type="text" name="razon_social" value="{{ $empresa->razon_social }}" class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Cuenta Corriente</label>
                <input type="text" name="cuenta_corriente" value="{{ $empresa->cuenta_corriente }}" class="mt-1 block w-full rounded-md bg-slate-900 border-slate-700 text-white shadow-sm px-4 py-2">
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-semibold transition">Guardar Cambios</button>
            <a href="{{ route('empresas.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-6 py-2 rounded-md font-semibold transition">Cancelar</a>
        </div>
    </form>
</div>
@endsection
