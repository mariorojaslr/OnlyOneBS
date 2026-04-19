<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Socio;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;

// Admin
User::updateOrCreate(['email' => 'admin@admin.com'], [
    'name' => 'Mario Admin',
    'password' => Hash::make('mario123'),
    'role' => 'admin'
]);

// Socio: Pascual
$pascual = Socio::where('uuid', 'pascual-ba')->first();
if ($pascual) {
    User::updateOrCreate(['email' => 'pascual@test.com'], [
        'name' => 'Pascual BA',
        'password' => Hash::make('pascual123'),
        'role' => 'socio',
        'socio_id' => $pascual->id
    ]);
}

// Empresa: AGEA
$agea = Empresa::where('nombre', 'LIKE', '%AGEA%')->first();
if ($agea) {
    User::updateOrCreate(['email' => 'agea@test.com'], [
        'name' => 'Gerencia AGEA',
        'password' => Hash::make('agea123'),
        'role' => 'empresa',
        'empresa_id' => $agea->id,
        'socio_id' => $agea->socio_id
    ]);
}

echo "Usuarios de prueba creados correctamente.";
