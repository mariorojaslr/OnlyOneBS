<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FictitiousDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresa = \App\Models\Empresa::create([
            'nombre' => 'Remis de Prueba S.A.',
            'razon_social' => 'Remisería Test & Demo',
            'cuenta_corriente' => '001-TEST',
            'uuid' => 'demo-empresa-test-uuid'
        ]);

        $nombresCentros = [
            'Administración Central', 'Recursos Humanos', 'Gerencia Comercial', 
            'Logística I', 'Logística II', 'Mantenimiento', 
            'Depósito Quilmes', 'Depósito Avellaneda', 'Sistemas', 'Atención al Cliente'
        ];

        foreach ($nombresCentros as $index => $nombreCC) {
            $cc = \App\Models\CentroCosto::create([
                'empresa_id' => $empresa->id,
                'nombre' => $nombreCC,
                'numero' => 100 + $index
            ]);

            for ($i = 0; $i < 3; $i++) {
                $pasajero = \App\Models\Pasajero::create([
                    'centro_costo_id' => $cc->id,
                    'nombre_completo' => 'Empleado ' . ($index * 3 + $i + 1),
                    'telefono' => '+54911' . rand(10000000, 99999999)
                ]);

                // Crear 2 a 5 viajes por pasajero
                $cantViajes = rand(2, 5);
                for ($j = 0; $j < $cantViajes; $j++) {
                    \App\Models\Viaje::create([
                        'pasajero_id' => $pasajero->id,
                        'centro_costo_id' => $cc->id,
                        'empresa_id' => $empresa->id,
                        'uuid' => \Illuminate\Support\Str::uuid(),
                        'origen' => 'Dirección de Origen ' . rand(1, 100),
                        'destino' => 'Dirección de Destino ' . rand(1, 100),
                        'fecha_inicio' => \Carbon\Carbon::now()->subDays(rand(1, 30)),
                        'monto' => rand(5000, 35000),
                        'distancia' => rand(5, 50) . ' km',
                        'estado' => 'FINISHED_PAID'
                    ]);
                }
            }
        }
    }
}
