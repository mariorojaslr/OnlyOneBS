<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$ontime = App\Models\Empresa::where('nombre', 'like', '%On Time%')->first();
$central = App\Models\CentroCosto::where('numero', '8')->orWhere('nombre', 'CENTRAL')->first();

if ($ontime && $central) {
    $central->empresa_id = $ontime->id;
    $central->save();
    echo 'Central asignado a On Time correctamente.';
} else {
    echo 'No se encontro On Time o Central';
}
