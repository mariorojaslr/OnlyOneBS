<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $config = [
            'onde_api_token' => Setting::get('onde_api_token'),
            'onde_company_id' => Setting::get('onde_company_id'),
            'telegram_bot_token' => Setting::get('telegram_bot_token'),
        ];

        return view('configuracion.index', compact('config'));
    }

    public function update(Request $request)
    {
        $this->authorizeAdmin();

        foreach ($request->except('_token') as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Configuraciones guardadas correctamente.');
    }

    private function authorizeAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tiene permisos para acceder a esta sección.');
        }
    }
}
