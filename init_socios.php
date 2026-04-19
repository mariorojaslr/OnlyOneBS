<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Crear Socios
$pascual = App\Models\Socio::updateOrCreate(['uuid' => 'pascual-ba'], ['nombre' => 'Pascual (Buenos Aires)', 'ciudad' => 'Buenos Aires']);
$oscar = App\Models\Socio::updateOrCreate(['uuid' => 'oscar-cba'], ['nombre' => 'Oscar (Córdoba)', 'ciudad' => 'Córdoba']);
$rodrigo = App\Models\Socio::updateOrCreate(['uuid' => 'rodrigo-lr'], ['nombre' => 'Rodrigo (La Rioja)', 'ciudad' => 'La Rioja']);

// Asignar empresas actuales a Pascual
App\Models\Empresa::whereNull('socio_id')->update(['socio_id' => $pascual->id]);

// Crear una empresa de demo para Oscar
$clienteOscar = App\Models\Empresa::create([
    'nombre' => 'Cliente de Oscar S.A.',
    'razon_social' => 'Logística Córdoba S.A.',
    'uuid' => 'cliente-oscar-uuid',
    'socio_id' => $oscar->id
]);

echo "Socios creados y empresas asignadas correctamente.";
