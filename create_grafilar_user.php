<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Socio;

// Cargar el entorno de Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'grafilar@gmail.com';
$password = 'Cele-0920';

// 1. Crear el Socio de Nivel 1 (Owner/Dueño)
$socio = Socio::updateOrCreate(
    ['nombre' => 'Grafilar Nivel 1'],
    [
        'email' => $email, // Se guardará si la columna existiera, pero por ahora evitamos el error en el WHERE
        'nivel' => 1,
        'uuid' => (string) \Illuminate\Support\Str::uuid(),
        'pais' => 'Argentina'
    ]
);

// 2. Crear el Usuario vinculado a ese Socio
$user = User::updateOrCreate(
    ['email' => $email],
    [
        'name' => 'Grafilar',
        'password' => Hash::make($password),
        'role' => 'owner',
        'socio_id' => $socio->id
    ]
);

echo "Usuario creado exitosamente:\n";
echo "Email: $email\n";
echo "Rol: owner\n";
echo "Socio ID: {$socio->id} (Nivel 1)\n";
