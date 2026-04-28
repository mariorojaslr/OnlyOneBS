<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Socio;
use App\Models\User;
use App\Models\Empresa;
use App\Models\CentroCosto;
use App\Models\Pasajero;
use App\Models\Viaje;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserStructureSeeder extends Seeder
{
    public function run(): void
    {
        // Limpieza de datos antiguos de prueba (Opcional, pero para estar limpios)
        // Empresa::where('nombre', 'Remis de Prueba S.A.')->delete();
        Socio::where('nombre', 'like', '%Italia%')->delete();
        Socio::where('nombre', 'like', '%Grafilar Nivel 1%')->delete();
        
        // 1. Nivel 1: App "Only One" (Color Verde)
        $onlyOneApp = Socio::updateOrCreate(
            ['uuid' => 'onlyone-l1'],
            [
                'nombre' => 'Only One',
                'ciudad' => 'Buenos Aires',
                'pais' => 'Argentina',
                'nivel' => 1,
                'parent_id' => null
            ]
        );

        // Actualizar el nombre si ya existía como "Only One Grafilar"
        $onlyOneApp->update(['nombre' => 'Only One']);

        // Asegurar que el usuario grafilar@gmail.com esté atado a Only One
        User::updateOrCreate(
            ['email' => 'grafilar@gmail.com'],
            [
                'name' => 'Grafilar',
                'password' => Hash::make('Cele-0920'),
                'role' => User::ROLE_OWNER,
                'socio_id' => $onlyOneApp->id
            ]
        );

        // 2. Nivel 2: Dueños de Flota (Color Amarillo) - Todos atados a Only One
        $flotas = [
            ['uuid' => 'pascual-ba', 'nombre' => 'Pascual (Buenos Aires)', 'ciudad' => 'Buenos Aires'],
            ['uuid' => 'oscar-cba', 'nombre' => 'Oscar (Córdoba)', 'ciudad' => 'Córdoba'],
            ['uuid' => 'rodrigo-lr', 'nombre' => 'Rodrigo (La Rioja)', 'ciudad' => 'La Rioja'],
            ['uuid' => 'walter-cat', 'nombre' => 'Walter (Catamarca)', 'ciudad' => 'Catamarca']
        ];

        $sociosL2 = [];
        foreach ($flotas as $flota) {
            $socioL2 = Socio::updateOrCreate(
                ['uuid' => $flota['uuid']],
                [
                    'nombre' => $flota['nombre'],
                    'ciudad' => $flota['ciudad'],
                    'pais' => 'Argentina',
                    'nivel' => 2,
                    'parent_id' => $onlyOneApp->id
                ]
            );
            $sociosL2[$flota['uuid']] = $socioL2;

            // Crear un usuario para cada dueño de flota
            $email = explode('-', $flota['uuid'])[0] . '@onlyone.com';
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => explode(' ', $flota['nombre'])[0],
                    'password' => Hash::make('12345678'),
                    'role' => User::ROLE_SOCIO,
                    'socio_id' => $socioL2->id
                ]
            );
        }

        // 3. Nivel 3: Cuentas Corporativas (Color Celeste) para Pascual
        $pascual = $sociosL2['pascual-ba'];

        $cuentasCorporativas = [
            'AGEA' => [
                'centros' => ['Producción', 'Vestuario', 'Arte & Escenografía', 'Postproducción']
            ],
            'On Time' => [
                'centros' => ['General', 'Logística']
            ],
            'ACME - Empresa de Pruebas' => [
                'centros' => ['Producción', 'Empaque y Logística', 'Marketing', 'Recursos Humanos', 'Finanzas']
            ]
        ];

        foreach ($cuentasCorporativas as $nombreEmpresa => $data) {
            $empresa = Empresa::firstOrCreate(
                ['nombre' => $nombreEmpresa],
                [
                    'razon_social' => $nombreEmpresa . ' S.A.',
                    'uuid' => Str::uuid(),
                    'socio_id' => $pascual->id
                ]
            );
            
            // Forzar actualización por si ya existía y estaba mal asignada
            $empresa->update(['socio_id' => $pascual->id]);

            // 4. Nivel 4 y 5: Centros de Costo (Rosado) y Empleados (Negro)
            foreach ($data['centros'] as $index => $nombreCC) {
                $cc = CentroCosto::firstOrCreate(
                    ['empresa_id' => $empresa->id, 'nombre' => $nombreCC],
                    ['numero' => 100 + $index]
                );

                // Crear 2 empleados por centro de costo si no tiene viajes
                if ($cc->pasajeros()->count() == 0) {
                    for ($i = 1; $i <= 2; $i++) {
                        $pasajero = Pasajero::create([
                            'centro_costo_id' => $cc->id,
                            'nombre_completo' => 'Empleado ' . $i . ' de ' . $nombreCC,
                            'telefono' => '+54911' . rand(10000000, 99999999)
                        ]);

                        // Crear viajes aleatorios
                        $cantViajes = rand(2, 4);
                        for ($j = 0; $j < $cantViajes; $j++) {
                            Viaje::create([
                                'pasajero_id' => $pasajero->id,
                                'centro_costo_id' => $cc->id,
                                'empresa_id' => $empresa->id,
                                'uuid' => Str::uuid(),
                                'origen' => 'Origen ' . rand(1, 10),
                                'destino' => 'Destino ' . rand(1, 10),
                                'fecha_inicio' => Carbon::now()->subDays(rand(1, 30)),
                                'monto' => rand(15000, 45000),
                                'distancia' => rand(5, 30) . ' km',
                                'estado' => 'FINISHED_PAID'
                            ]);
                        }
                    }
                }
            }
        }
    }
}
