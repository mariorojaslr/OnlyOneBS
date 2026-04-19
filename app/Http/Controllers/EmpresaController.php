<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $empresas = Empresa::with('socio')->get();
        } elseif ($user->isSocio()) {
            $mySocio = $user->socio;
            if ($mySocio->nivel == 1) {
                // Dueño: Ve todas las empresas de sus provincias
                $provincialIds = $mySocio->children()->pluck('id')->toArray();
                $provincialIds[] = $mySocio->id;
                $empresas = Empresa::whereIn('socio_id', $provincialIds)->get();
            } else {
                // Provincia: Solo sus empresas
                $empresas = Empresa::where('socio_id', $mySocio->id)->get();
            }
        } else {
            // Empresa: Solo ve su propia empresa
            $empresas = Empresa::where('id', $user->empresa_id)->get();
        }

        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        $user = auth()->user();
        $socios = [];

        if ($user->isAdmin()) {
            $socios = Socio::all();
        } elseif ($user->isSocio()) {
            $mySocio = $user->socio;
            if ($mySocio->nivel == 1) {
                $socios = Socio::where('parent_id', $mySocio->id)->orWhere('id', $mySocio->id)->get();
            } else {
                $socios = Socio::where('id', $mySocio->id)->get();
            }
        }

        return view('empresas.create', compact('socios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'uuid' => 'required|string|max:255|unique:empresas',
            'razon_social' => 'nullable|string|max:255',
            'cuenta_corriente' => 'nullable|string|max:255',
            'socio_id' => 'required|exists:socios,id'
        ]);

        Empresa::create($validated);

        return redirect()->route('empresas.index')->with('success', __('Empresa creada exitosamente.'));
    }

    public function edit(Empresa $empresa)
    {
        $user = auth()->user();
        $socios = Socio::all();
        return view('empresas.edit', compact('empresa', 'socios'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'uuid' => 'required|string|max:255|unique:empresas,uuid,'.$empresa->id,
            'razon_social' => 'nullable|string|max:255',
            'cuenta_corriente' => 'nullable|string|max:255',
            'socio_id' => 'required|exists:socios,id'
        ]);

        $empresa->update($validated);

        return redirect()->route('empresas.index')->with('success', __('Empresa actualizada exitosamente.'));
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('empresas.index')->with('success', __('Empresa eliminada.'));
    }
}
