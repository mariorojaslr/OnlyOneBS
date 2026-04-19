<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::where('email', 'mario.rojas.coach@gmail.com')->first();
if ($user) {
    $user->update([
        'role' => 'superadmin',
        'use_2fa' => true
    ]);
    echo "Usuario Mario Rojas actualizado a SuperAdmin con 2FA habilitado.";
}
