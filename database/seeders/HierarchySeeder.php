<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Socio;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HierarchySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Nivel 1: Marca Blanca "OnlyOne Argentina" (Mayorista)
        $onlyOneArg = Socio::updateOrCreate(
            ['id' => 4],
            [
                'nombre' => 'OnlyOne Argentina',
                'ciudad' => 'Buenos Aires',
                'pais' => 'Argentina',
                'nivel' => 1,
                'parent_id' => null
            ]
        );

        // 2. Nivel 2: Socios Minoristas (Argentina)
        $pascual = Socio::updateOrCreate(
            ['id' => 1],
            [
                'nombre' => 'Pascual (Buenos Aires)',
                'ciudad' => 'Buenos Aires',
                'pais' => 'Argentina',
                'nivel' => 2,
                'parent_id' => $onlyOneArg->id
            ]
        );

        $oscar = Socio::updateOrCreate(
            ['id' => 2],
            [
                'nombre' => 'Oscar (Córdoba)',
                'ciudad' => 'Córdoba',
                'pais' => 'Argentina',
                'nivel' => 2,
                'parent_id' => $onlyOneArg->id
            ]
        );

        // 3. Nivel 1: Ejemplo "Brand Italia" (Otro Mayorista)
        $brandItaly = Socio::updateOrCreate(
            ['uuid' => 'italy-brand'],
            [
                'nombre' => 'OnlyOne Italia',
                'ciudad' => 'Roma',
                'pais' => 'Italia',
                'nivel' => 1,
                'parent_id' => null
            ]
        );

        // 4. Usuarios de Nivel 0 y 1
        $mario = User::where('email', 'mario.rojas.coach@gmail.com')->first();
        if ($mario) {
            $mario->update([
                'role' => User::ROLE_SUPERADMIN,
                'socio_id' => $onlyOneArg->id
            ]);
        }

        // 5. Usuario para Italia (Nivel 1)
        User::updateOrCreate(
            ['email' => 'italy@onlyone.com'],
            [
                'name' => 'Gino Italia',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SOCIO, // Actúa como Brand Owner
                'socio_id' => $brandItaly->id
            ]
        );

        // 6. Colaboradora de Pascual (Mayra)
        User::updateOrCreate(
            ['email' => 'mayra@test.com'],
            [
                'name' => 'Mayra (Admin)',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SOCIO,
                'socio_id' => $pascual->id
            ]
        );

        // 7. Nivel 3: Empresas (Clientes)
        $agea = \App\Models\Empresa::where('nombre', 'AGEA')->first();
        if ($agea) {
            $agea->update(['socio_id' => $pascual->id]);
        }
    }
}
