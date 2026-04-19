<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Actualizar clave Dueño
$user = User::where('email', 'mario.rojas.coach@gmail.com')->first();
if ($user) {
    $user->update([
        'password' => Hash::make('Rojas*250007'),
        'role' => 'admin'
    ]);
    echo "Clave de Dueño actualizada correctamente.";
} else {
    User::create([
        'name' => 'Mario Rojas',
        'email' => 'mario.rojas.coach@gmail.com',
        'password' => Hash::make('Rojas*250007'),
        'role' => 'admin'
    ]);
    echo "Usuario Dueño creado y clave configurada.";
}
