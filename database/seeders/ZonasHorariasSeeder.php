<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ZonaHoraria;

class ZonasHorariasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zonas = [
            [
                'nombre' => 'Zona Central / Ciudad de Mexico',
                'codigo' => 'America/Mexico_City',
                'offset' => 'GMT-6',
                'offset_minutos' => -360,
                'activo' => true,
                'orden' => 1,
            ],
            [
                'nombre' => 'Zona Pacifico / Tijuana',
                'codigo' => 'America/Tijuana',
                'offset' => 'GMT-8',
                'offset_minutos' => -480,
                'activo' => true,
                'orden' => 2,
            ],
            [
                'nombre' => 'Zona Noroeste / Hermosillo',
                'codigo' => 'America/Hermosillo',
                'offset' => 'GMT-7',
                'offset_minutos' => -420,
                'activo' => true,
                'orden' => 3,
            ],
            [
                'nombre' => 'Tiempo Universal Coordinado',
                'codigo' => 'UTC',
                'offset' => 'UTC',
                'offset_minutos' => 0,
                'activo' => true,
                'orden' => 10,
            ],
        ];

        foreach ($zonas as $zona) {
            ZonaHoraria::updateOrCreate(
                ['codigo' => $zona['codigo']],
                $zona
            );
        }
    }
}
