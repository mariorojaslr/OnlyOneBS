<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasajero;
use App\Models\CentroCosto;
use App\Models\Empresa;

class PasajeroController extends Controller
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
            if ($mySocio && $mySocio->nivel == 1) {
                $socioIds = $mySocio->children()->pluck('id')->toArray();
                $socioIds[] = $mySocio->id;
                return Empresa::whereIn('socio_id', $socioIds)->pluck('id')->toArray();
            } else {
                return Empresa::where('socio_id', $mySocio->id)->pluck('id')->toArray();
            }
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
        $centroIds = CentroCosto::whereIn('empresa_id', $empresaIds)->pluck('id');
        $pasajeros = Pasajero::with('centroCosto.empresa')
            ->whereIn('centro_costo_id', $centroIds)
            ->get();

        return view('pasajeros.index', compact('pasajeros'));
    }

    public function create()
    {
        $empresaIds = $this->getEmpresaIds();
        $centros = CentroCosto::with('empresa')
            ->whereIn('empresa_id', $empresaIds)
            ->get();

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

        Pasajero::create($validated);

        return redirect()->route('pasajeros.index')->with('success', 'Pasajero cargado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit(Pasajero $pasajero)
    {
        $empresaIds = $this->getEmpresaIds();
        $centros = CentroCosto::with('empresa')
            ->whereIn('empresa_id', $empresaIds)
            ->get();

        return view('pasajeros.edit', compact('pasajero', 'centros'));
    }

    public function update(Request $request, Pasajero $pasajero)
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
