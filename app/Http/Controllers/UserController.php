<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Socio;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $users = [];

        if ($user->isSuperAdmin()) {
            $users = User::with(['socio', 'empresa'])->orderBy('role')->get();
        } elseif ($user->isSocio()) {
            $users = User::where('socio_id', $user->socio_id)->orderBy('name')->get();
        } else {
             // Si el rol es Empresa o AdminView, solo se ve a sí mismo por seguridad
             $users = User::where('id', $user->id)->get();
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $user = auth()->user();
        
        $roles = [
            User::ROLE_ADMIN => 'Administrador',
            User::ROLE_SOCIO => 'Socio (Flota)',
            User::ROLE_EMPRESA => 'Cliente (Empresa)',
            User::ROLE_ADMIN_VIEW => 'Auditor (Solo Lectura)'
        ];

        if ($user->isSuperAdmin()) {
            $socios = Socio::all();
            $empresas = Empresa::all();
        } else {
            $socios = Socio::where('id', $user->socio_id)->get();
            $empresas = Empresa::where('socio_id', $user->socio_id)->get();
            unset($roles[User::ROLE_ADMIN]); // Un socio no puede crear un Admin global
        }

        return view('users.create', compact('socios', 'empresas', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|string',
            'socio_id' => 'nullable|exists:socios,id',
            'empresa_id' => 'nullable|exists:empresas,id',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        // Forzar socio_id si el creador es un Socio
        if (auth()->user()->isSocio()) {
            $data['socio_id'] = auth()->user()->socio_id;
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', __('Usuario creado correctamente.'));
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();
        
        // Seguridad: Un socio solo puede editar sus propios usuarios
        if ($currentUser->isSocio() && $user->socio_id != $currentUser->socio_id) {
            abort(403);
        }

        $roles = [
            User::ROLE_ADMIN => 'Administrador',
            User::ROLE_SOCIO => 'Socio (Flota)',
            User::ROLE_EMPRESA => 'Cliente (Empresa)',
            User::ROLE_ADMIN_VIEW => 'Auditor (Solo Lectura)'
        ];

        if ($currentUser->isSuperAdmin()) {
            $socios = Socio::all();
            $empresas = Empresa::all();
        } else {
            $socios = Socio::where('id', $currentUser->socio_id)->get();
            $empresas = Empresa::where('socio_id', $currentUser->socio_id)->get();
            unset($roles[User::ROLE_ADMIN]);
        }

        return view('users.edit', compact('user', 'socios', 'empresas', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8',
            'role' => 'required|string',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', __('Usuario actualizado correctamente.'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', __('No puedes eliminarte a ti mismo.'));
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', __('Usuario eliminado.'));
    }
}
