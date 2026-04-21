<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Socio;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HierarchySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Nivel 1: Marca Blanca "OnlyOne Argentina" (Mayorista)
        $onlyOneArg = Socio::updateOrCreate(
            ['uuid' => 'onlyone-argentina'],
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
            ['uuid' => 'pascual-ba'],
            [
                'nombre' => 'Pascual (Buenos Aires)',
                'ciudad' => 'Buenos Aires',
                'pais' => 'Argentina',
                'nivel' => 2,
                'parent_id' => $onlyOneArg->id
            ]
        );

        $oscar = Socio::updateOrCreate(
            ['uuid' => 'oscar-cba'],
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

        // 4. Usuarios de Nivel 0 (SuperAdmin)
        $mario = User::where('email', 'mario.rojas.coach@gmail.com')->first();
        if ($mario) {
            $mario->update([
                'role' => User::ROLE_SUPERADMIN,
                'socio_id' => $onlyOneArg->id
            ]);
        }

        // 5. Usuarios Socios (Nivel 2)
        User::updateOrCreate(
            ['email' => 'pascual@test.com'],
            [
                'name' => 'Pascual',
                'password' => Hash::make('12345678'),
                'role' => User::ROLE_SOCIO,
                'socio_id' => $pascual->id
            ]
        );

        User::updateOrCreate(
            ['email' => 'oscar@test.com'],
            [
                'name' => 'Oscar',
                'password' => Hash::make('12345678'),
                'role' => User::ROLE_SOCIO,
                'socio_id' => $oscar->id
            ]
        );

        // 6. Usuario para Italia (Nivel 1)
        User::updateOrCreate(
            ['email' => 'italy@onlyone.com'],
            [
                'name' => 'Gino Italia',
                'password' => Hash::make('12345678'),
                'role' => User::ROLE_SOCIO,
                'socio_id' => $brandItaly->id
            ]
        );

        // 7. Colaboradora de Pascual (Mayra)
        User::updateOrCreate(
            ['email' => 'mayra@test.com'],
            [
                'name' => 'Mayra (Admin)',
                'password' => Hash::make('12345678'),
                'role' => User::ROLE_SOCIO,
                'socio_id' => $pascual->id
            ]
        );

        // 8. Nivel 3: Vincular Empresas existentes
        $agea = \App\Models\Empresa::where('nombre', 'AGEA')->first();
        if ($agea) {
            $agea->update(['socio_id' => $pascual->id]);
        }

        $onTime = \App\Models\Empresa::where('nombre', 'On Time')->first();
        if ($onTime) {
            $onTime->update(['socio_id' => $pascual->id]);
        }
    }
}
