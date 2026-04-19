<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CentroCostoController;
use App\Http\Controllers\PasajeroController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\ReporteController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\ReporteController::class, 'dashboard']); // Alias
    Route::get('/sincronizar', [App\Http\Controllers\ReporteController::class, 'sincronizar'])->name('sincronizar');
    Route::get('/set-locale/{lang}', [App\Http\Controllers\ReporteController::class, 'setLocale'])->name('set-locale');
    
    // Perfil y Seguridad 2FA
    Route::get('/perfil', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::post('/perfil', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/2fa', [App\Http\Controllers\ProfileController::class, 'show2fa'])->name('2fa.show');
    Route::post('/2fa', [App\Http\Controllers\ProfileController::class, 'verify2fa'])->name('2fa.verify');
    Route::get('/2fa/reenviar', [App\Http\Controllers\ProfileController::class, 'resend2fa'])->name('2fa.resend');
    Route::post('/importar', [ReporteController::class, 'importarCsv'])->name('importar');

    Route::resource('socios', SocioController::class);
    Route::resource('empresas', EmpresaController::class);
    Route::resource('centros-costo', CentroCostoController::class);
    Route::resource('pasajeros', PasajeroController::class);
    Route::resource('users', UserController::class);

    Route::get('/configuracion', [\App\Http\Controllers\ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion', [\App\Http\Controllers\ConfiguracionController::class, 'update'])->name('configuracion.update');
    
    Route::get('/set-locale/{lang}', function($lang) {
        if (in_array($lang, ['es', 'en', 'pt', 'ru', 'fr', 'it', 'de', 'zh', 'ja', 'ar'])) {
            session(['locale' => $lang]);
            if (auth()->check()) {
                auth()->user()->update(['locale' => $lang]);
            }
        }
        return back();
    })->name('set-locale');
});
