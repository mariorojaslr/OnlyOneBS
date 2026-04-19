<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Dueño / Admin Principal
User::updateOrCreate(['email' => 'mario.rojas.coach@gmail.com'], [
    'name' => 'Mario Rojas',
    'password' => Hash::make('mario123'),
    'role' => 'admin'
]);

echo "Su cuenta de Dueño (mario.rojas.coach@gmail.com) ha sido creada correctamente.";
