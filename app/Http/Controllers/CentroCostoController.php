<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CentroCostoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centros = \App\Models\CentroCosto::with('empresa')->get();
        return view('centros-costo.index', compact('centros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(\App\Models\CentroCosto $centros_costo)
    {
        $empresas = \App\Models\Empresa::all();
        return view('centros-costo.edit', ['centro' => $centros_costo, 'empresas' => $empresas]);
    }

    public function update(Request $request, \App\Models\CentroCosto $centros_costo)
    {
        $validated = $request->validate([
            'numero' => 'nullable|string|max:255',
            'nombre' => 'required|string|max:255',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        $centros_costo->update($validated);

        return redirect()->route('centros-costo.index')->with('success', 'Centro de costo actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
