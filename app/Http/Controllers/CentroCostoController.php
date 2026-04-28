<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CentroCosto;
use App\Models\Empresa;

class CentroCostoController extends Controller
{
    /**
     * Helper: obtener IDs de empresas visibles para el usuario actual.
     */
    private function getEmpresaIds()
    {
        $user = auth()->user();

        if (session('room_id')) {
            return Empresa::where('socio_id', session('room_id'))->pluck('id')->toArray();
        }

        if ($user->isSuperAdmin()) {
            return Empresa::pluck('id')->toArray();
        } elseif ($user->isOwner() || $user->isSocio()) {
            $mySocio = $user->socio;
            if ($mySocio) {
                if ($mySocio->nivel == 1) {
                    $socioIds = $mySocio->children()->pluck('id')->toArray();
                    $socioIds[] = $mySocio->id;
                    return Empresa::whereIn('socio_id', $socioIds)->pluck('id')->toArray();
                } else {
                    return Empresa::where('socio_id', $mySocio->id)->pluck('id')->toArray();
                }
            }
            return [];
        } elseif ($user->isEmpresa()) {
            return [$user->empresa_id];
        }

        return [];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresaIds = $this->getEmpresaIds();
        $centros = CentroCosto::with('empresa')
            ->whereIn('empresa_id', $empresaIds)
            ->get();

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

    public function edit(CentroCosto $centros_costo)
    {
        $empresaIds = $this->getEmpresaIds();
        $empresas = Empresa::whereIn('id', $empresaIds)->get();

        return view('centros-costo.edit', ['centro' => $centros_costo, 'empresas' => $empresas]);
    }

    public function update(Request $request, CentroCosto $centros_costo)
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
