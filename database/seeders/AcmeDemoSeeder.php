<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\CentroCosto;
use App\Models\Pasajero;
use App\Models\Viaje;
use App\Models\Socio;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AcmeDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Vincular ACME al socio de Buenos Aires (Pascual, id=1)
        $socio = Socio::find(1);

        // 1. Crear la Empresa ACME
        $acme = Empresa::updateOrCreate(
            ['uuid' => 'acme-demo-001'],
            [
                'nombre' => 'ACME - Empresa de Pruebas',
                'razon_social' => 'ACME Corporación Demo S.A.',
                'cuenta_corriente' => '999',
                'socio_id' => $socio->id,
            ]
        );

        // 2. Crear 10 Centros de Costo
        $centros = [
            ['numero' => 'CC-001', 'nombre' => 'Producción'],
            ['numero' => 'CC-002', 'nombre' => 'Empaque y Logística'],
            ['numero' => 'CC-003', 'nombre' => 'Marketing'],
            ['numero' => 'CC-004', 'nombre' => 'Recursos Humanos'],
            ['numero' => 'CC-005', 'nombre' => 'Finanzas'],
            ['numero' => 'CC-006', 'nombre' => 'Tecnología e IT'],
            ['numero' => 'CC-007', 'nombre' => 'Ventas'],
            ['numero' => 'CC-008', 'nombre' => 'Atención al Cliente'],
            ['numero' => 'CC-009', 'nombre' => 'Dirección General'],
            ['numero' => 'CC-010', 'nombre' => 'Calidad y Auditoría'],
        ];

        $centroModels = [];
        foreach ($centros as $c) {
            $centroModels[] = CentroCosto::updateOrCreate(
                ['empresa_id' => $acme->id, 'numero' => $c['numero']],
                ['nombre' => $c['nombre']]
            );
        }

        // 3. Crear Pasajeros de prueba (3 por centro de costo = 30 pasajeros)
        $nombres = [
            'Juan Pérez', 'María López', 'Carlos García',
            'Ana Martínez', 'Pedro Rodríguez', 'Laura Fernández',
            'Diego Sánchez', 'Sofía Ramírez', 'Andrés Torres',
            'Valentina Díaz', 'Mateo Herrera', 'Camila Morales',
            'Sebastián Ortiz', 'Isabella Vargas', 'Santiago Castro',
            'Luciana Reyes', 'Nicolás Mendoza', 'Martina Flores',
            'Tomás Navarro', 'Emilia Rojas', 'Lucas Domínguez',
            'Paula Guerrero', 'Benjamín Medina', 'Catalina Ríos',
            'Gabriel Herrera', 'Valeria Cruz', 'Daniel Aguilar',
            'Renata Peña', 'Facundo Correa', 'Milagros Salazar',
        ];

        $telefonos = [];
        for ($i = 0; $i < 30; $i++) {
            $telefonos[] = '+549381' . str_pad($i + 100, 7, '0', STR_PAD_LEFT);
        }

        $pasajeroModels = [];
        $idx = 0;
        foreach ($centroModels as $centro) {
            for ($p = 0; $p < 3; $p++) {
                $pasajeroModels[] = Pasajero::updateOrCreate(
                    ['telefono' => $telefonos[$idx]],
                    [
                        'nombre_completo' => $nombres[$idx],
                        'centro_costo_id' => $centro->id,
                    ]
                );
                $idx++;
            }
        }

        // 4. Crear Viajes ficticios (entre 2 y 5 por pasajero)
        $origenes = [
            'Av. Corrientes 1234, CABA',
            'Av. Rivadavia 5678, CABA',
            'Av. Santa Fe 910, CABA',
            'Calle Florida 456, CABA',
            'Av. 9 de Julio 1100, CABA',
            'Av. Callao 789, CABA',
            'Av. Belgrano 2345, CABA',
            'Av. de Mayo 600, CABA',
        ];

        $destinos = [
            'Aeropuerto Ezeiza, Buenos Aires',
            'Aeroparque J. Newbery, CABA',
            'Retiro Terminal, CABA',
            'Planta Industrial Pacheco, GBA',
            'Centro de Convenciones, CABA',
            'Hotel Hilton Puerto Madero, CABA',
            'Oficinas Centrales ACME, Micro Centro',
            'Depósito Logístico Avellaneda, GBA',
        ];

        $estados = ['completed', 'completed', 'completed', 'completed', 'cancelled'];

        foreach ($pasajeroModels as $pasajero) {
            $numViajes = rand(2, 5);
            for ($v = 0; $v < $numViajes; $v++) {
                $fecha = Carbon::now()
                    ->subDays(rand(1, 60))
                    ->setHour(rand(6, 22))
                    ->setMinute(rand(0, 59));

                Viaje::updateOrCreate(
                    ['uuid' => 'acme-trip-' . $pasajero->id . '-' . ($v + 1)],
                    [
                        'pasajero_id' => $pasajero->id,
                        'centro_costo_id' => $pasajero->centro_costo_id,
                        'empresa_id' => $acme->id,
                        'origen' => $origenes[array_rand($origenes)],
                        'destino' => $destinos[array_rand($destinos)],
                        'fecha_inicio' => $fecha->format('Y-m-d H:i:s'),
                        'monto' => round(rand(1500, 25000) / 100 * 100, 2),
                        'distancia' => number_format(rand(20, 450) / 10, 1, ',', ''),
                        'estado' => $estados[array_rand($estados)],
                    ]
                );
            }
        }

        // 5. Crear un usuario de acceso para ACME (Nivel Empresa)
        User::updateOrCreate(
            ['email' => 'acme@demo.com'],
            [
                'name' => 'Gerencia ACME (Demo)',
                'password' => Hash::make('12345678'),
                'role' => User::ROLE_EMPRESA,
                'socio_id' => $socio->id,
                'empresa_id' => $acme->id,
            ]
        );

        $this->command->info('✅ ACME - Empresa de Pruebas creada con:');
        $this->command->info('   → 10 Centros de Costo');
        $this->command->info('   → 30 Pasajeros');
        $this->command->info('   → ~90 Viajes ficticios');
        $this->command->info('   → Usuario: acme@demo.com / password');
    }
}
