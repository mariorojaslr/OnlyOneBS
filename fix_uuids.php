<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$agea = App\Models\Empresa::where('nombre', 'AGEA')->first();
if ($agea) {
    $agea->uuid = 'este-uuid-es-el-de-agea';
    $agea->save();
}

$ontime = App\Models\Empresa::where('nombre', 'like', '%On Time%')->first();
if ($ontime) {
    $ontime->uuid = '988fcbd7-e780-4032-abbc-c1a4f30518b4';
    $ontime->save();
}
echo 'UUIDs actualizados correctamente.';
