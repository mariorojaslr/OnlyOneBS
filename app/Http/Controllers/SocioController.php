<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SocioController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            $socios = Socio::with('parent')->get();
        } elseif ($user->isSocio()) {
            $mySocio = $user->socio;
            // Un dueño de nivel 1 ve sus sucursales
            $socios = Socio::where('id', $mySocio->id)->orWhere('parent_id', $mySocio->id)->get();
        } else {
            abort(403);
        }

        return view('socios.index', compact('socios'));
    }

    public function create()
    {
        $user = auth()->user();
        $parents = [];
        
        if ($user->isSuperAdmin()) {
            $parents = Socio::where('nivel', 1)->get();
        } elseif ($user->isSocio()) {
            if ($user->socio->nivel != 1) abort(403);
            $parents = Socio::where('id', $user->socio_id)->get();
        }

        return view('socios.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nivel' => 'required|integer',
            'parent_id' => 'nullable|exists:socios,id',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['uuid'] = (string) Str::uuid();

        // Forzar parent_id si es Socio Nivel 1 creando nivel 2
        if (auth()->user()->isSocio()) {
            $data['parent_id'] = auth()->user()->socio_id;
            $data['nivel'] = 2;
        }

        Socio::create($data);

        return redirect()->route('socios.index')->with('success', __('Socio/Flota creado correctamente.'));
    }

    public function edit(Socio $socio)
    {
        $user = auth()->user();
        if ($user->isSocio() && $socio->id != $user->socio_id && $socio->parent_id != $user->socio_id) {
            abort(403);
        }

        $parents = Socio::where('nivel', 1)->where('id', '!=', $socio->id)->get();
        return view('socios.edit', compact('socio', 'parents'));
    }

    public function update(Request $request, Socio $socio)
    {
        $request->validate([
            'nombre' => 'required',
            'nivel' => 'required',
        ]);

        $socio->update($request->all());

        return redirect()->route('socios.index')->with('success', __('Datos actualizados.'));
    }

    public function destroy(Socio $socio)
    {
        $socio->delete();
        return redirect()->route('socios.index')->with('success', __('Socio eliminado.'));
    }
}
