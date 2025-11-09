<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'nom' => 'Médecine Interne',
                'localisation' => 'Bâtiment A - 1er étage',
                'description' => 'Service de médecine générale et spécialisée',
            ],
            [
                'nom' => 'Pédiatrie',
                'localisation' => 'Bâtiment B - Rez-de-chaussée',
                'description' => 'Service dédié aux soins des enfants',
            ],
            [
                'nom' => 'Cardiologie',
                'localisation' => 'Bâtiment A - 2ème étage',
                'description' => 'Service spécialisé dans les pathologies cardiaques',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}

