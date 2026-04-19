<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportAgeaCsv extends Command
{
    protected $signature = 'db:import-agea';
    protected $description = 'Importa AGEA y sus pasajeros desde CSV y crea la empresa On Time';

    public function handle()
    {
        // 1. Wipe DB (safely)
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Viaje::truncate();
        \App\Models\Pasajero::truncate();
        \App\Models\CentroCosto::truncate();
        \App\Models\Empresa::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create the two requested companies
        $agea = \App\Models\Empresa::create([
            'nombre' => 'AGEA',
            'razon_social' => 'Artes Graficas Editorial Argentino',
            'cuenta_corriente' => '294',
            'uuid' => '96f30df9-cece-4bd0-b302-31fa435e0520', // This uuid is assumed from earlier discussions, or it can be updated
        ]);

        $onTime = \App\Models\Empresa::create([
            'nombre' => 'On Time',
            'razon_social' => 'Remis On Time',
            'cuenta_corriente' => '100',
            'uuid' => 'dummy-uuid-ontime', 
        ]);
        
        $this->info("Empresas AGEA y On Time creadas.");

        // 3. Parse CSV
        $path = base_path('Exportacion Agea.csv');
        if (!file_exists($path)) {
            $this->error("No se encontró $path");
            return;
        }

        $file = fopen($path, 'r');
        fgetcsv($file, 1000, ';'); // skip header

        $count = 0;
        while (($row = fgetcsv($file, 1000, ';')) !== false) {
            if (!isset($row[0])) continue;

            // 2: CC NRO, 3: CENTRO DE COSTO
            // 5: AUTORIZADO (name), 6: DNI
            // 8: CODIGO UUID (if exists, update agea uuid)
            
            $ccNro = trim(mb_convert_encoding($row[2], 'UTF-8', 'ISO-8859-1'));
            $ccNombre = trim(mb_convert_encoding($row[3], 'UTF-8', 'ISO-8859-1'));
            $pasajeroNombre = trim(mb_convert_encoding($row[5], 'UTF-8', 'ISO-8859-1'));
            $dni = trim(mb_convert_encoding($row[6], 'UTF-8', 'ISO-8859-1'));
            $uuid = trim($row[8] ?? '');
            
            if(!empty($uuid) && $agea->uuid != $uuid) {
                $agea->update(['uuid' => $uuid]);
            }

            if(empty($ccNombre) || empty($pasajeroNombre)) continue;

            $centro = \App\Models\CentroCosto::firstOrCreate(
                ['empresa_id' => $agea->id, 'nombre' => $ccNombre],
                ['numero' => $ccNro ?: null]
            );

            \App\Models\Pasajero::create([
                'centro_costo_id' => $centro->id,
                'nombre_completo' => $pasajeroNombre,
                'documento' => $dni,
                'telefono' => null, // empty until user manually updates them
            ]);
            $count++;
        }
        fclose($file);

        $this->info("Se importaron $count pasajeros y sus centros de costo de AGEA.");
    }
}
