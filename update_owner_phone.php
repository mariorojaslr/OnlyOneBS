<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Actualizar teléfono del Dueño
$user = User::where('email', 'mario.rojas.coach@gmail.com')->first();
if ($user) {
    $user->update([
        'phone' => '+5493804250007'
    ]);
    echo "Teléfono de Mario Rojas actualizado: +5493804250007";
} else {
    echo "Usuario no encontrado.";
}
