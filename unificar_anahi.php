<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Borrar el duplicado manual (ID 18)
App\Models\Pasajero::where('id', 18)->delete();

// Actualizar el importado (ID 17) con el teléfono correcto
$anahi = App\Models\Pasajero::find(17);
if ($anahi) {
    $anahi->telefono = '+5491158948869';
    $anahi->save();
    echo "Anahi (ID 17) unificada y actualizada con el teléfono +5491158948869.\n";
} else {
    echo "No se encontró a Anahi (ID 17).\n";
}
