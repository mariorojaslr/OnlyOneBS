<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agea = \App\Models\Empresa::create([
            'nombre' => 'AGEA',
            'razon_social' => 'AGEA S.A.',
            'cuenta_corriente' => '1000',
            'uuid' => '5a5aad36-420e-4f71-8a8e-2d4f2381f2bf'
        ]);

        $cc600 = \App\Models\CentroCosto::create(['empresa_id' => $agea->id, 'numero' => '600', 'nombre' => 'Producción']);
        \App\Models\Pasajero::create(['centro_costo_id' => $cc600->id, 'nombre_completo' => 'Alejandra Pacheco', 'documento' => '27.345.889']);
        \App\Models\Pasajero::create(['centro_costo_id' => $cc600->id, 'nombre_completo' => 'Ramiro Cernadas', 'documento' => '30.112.556']);
        \App\Models\Pasajero::create(['centro_costo_id' => $cc600->id, 'nombre_completo' => 'Julieta Montalvo', 'documento' => '29.443.210']);

        $cc601 = \App\Models\CentroCosto::create(['empresa_id' => $agea->id, 'numero' => '601', 'nombre' => 'Vestuario']);
        \App\Models\Pasajero::create(['centro_costo_id' => $cc601->id, 'nombre_completo' => 'Luciano Ferreyra', 'documento' => '32.118.774']);
        \App\Models\Pasajero::create(['centro_costo_id' => $cc601->id, 'nombre_completo' => 'Maria Sol Ledesma', 'documento' => '28.774.552']);

        $remis = \App\Models\Empresa::create([
            'nombre' => 'Remis On Time S.A.',
            'razon_social' => 'Remis On Time S.A.',
            'cuenta_corriente' => '1',
            'uuid' => '988fcbd7-e780-4032-abbc-c1a4f30518b4'
        ]);

        $ccCentral = \App\Models\CentroCosto::create(['empresa_id' => $remis->id, 'numero' => '1', 'nombre' => 'Central']);
        \App\Models\Pasajero::create(['centro_costo_id' => $ccCentral->id, 'nombre_completo' => 'Anahi', 'telefono' => '+5491158948869']);
    }
}
