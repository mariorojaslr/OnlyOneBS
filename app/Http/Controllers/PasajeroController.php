<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasajeroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasajeros = \App\Models\Pasajero::with('centroCosto.empresa')->get();
        return view('pasajeros.index', compact('pasajeros'));
    }

    public function create()
    {
        $centros = \App\Models\CentroCosto::with('empresa')->get();
        return view('pasajeros.create', compact('centros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'telefono' => 'required|string|max:50|unique:pasajeros,telefono',
            'documento' => 'nullable|string|max:50',
            'centro_costo_id' => 'required|exists:centro_costos,id',
        ], [
            'telefono.unique' => 'Este número de teléfono ya está registrado con otro pasajero.',
            'telefono.required' => 'El teléfono es obligatorio para matchear con la remisería.',
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
        ]);

        \App\Models\Pasajero::create($validated);

        return redirect()->route('pasajeros.index')->with('success', 'Pasajero cargado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(\App\Models\Pasajero $pasajero)
    {
        $centros = \App\Models\CentroCosto::with('empresa')->get();
        return view('pasajeros.edit', compact('pasajero', 'centros'));
    }

    public function update(Request $request, \App\Models\Pasajero $pasajero)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'telefono' => 'required|string|max:50|unique:pasajeros,telefono,'.$pasajero->id,
            'documento' => 'nullable|string|max:50',
            'centro_costo_id' => 'required|exists:centro_costos,id',
        ], [
            'telefono.unique' => 'Este número de teléfono ya está registrado con otro pasajero.',
            'telefono.required' => 'El teléfono es obligatorio para matchear con la remisería.',
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
        ]);

        $pasajero->update($validated);

        return redirect()->route('pasajeros.index')->with('success', 'Pasajero actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
