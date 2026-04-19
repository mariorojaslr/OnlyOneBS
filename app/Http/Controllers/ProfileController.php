<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('perfil', [
            'user' => auth()->user()
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'telegram_id' => 'nullable|string',
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->telegram_id = $request->telegram_id;
        $user->use_2fa = $request->has('use_2fa');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', __('Perfil actualizado correctamente.'));
    }

    public function show2fa()
    {
        return view('auth.2fa');
    }

    public function resend2fa()
    {
        $user = auth()->user();
        $code = rand(100000, 999999);
        session(['2fa_code' => $code]);

        $token = "8275647710:AAEo2GysmcMvPOmh9zDioOeh3mde6FrcJvE";
        $chatId = "488424438"; // Usamos tu ID directo para asegurar
        $message = "🔹 Código OnlyOneBS: " . $code;

        $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($message);
        @file_get_contents($url);

        return redirect()->route('2fa.show')->with('success', __('Código enviado directamente. Revisa @CeferojasBot.'));
    }

    public function verify2fa(Request $request)
    {
        $request->validate(['code' => 'required']);
        
        if ($request->code == session('2fa_code')) {
            session(['2fa_verified' => true]);
            return redirect()->route('dashboard');
        }

        return back()->with('error', __('Código incorrecto.'));
    }
}
