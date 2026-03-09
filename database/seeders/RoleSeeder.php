<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Création des rôles principaux pour Gestock
        Role::create([
            'nom' => 'Administrateur',
            'description' => 'Accès complet au système',
            'statut' => true
        ]);

        Role::create([
            'nom' => 'Gestionnaire Stock',
            'description' => 'Peut gérer les stocks et les achats',
            'statut' => true
        ]);

        Role::create([
            'nom' => 'Commercial',
            'description' => 'Peut gérer les clients et devis/factures',
            'statut' => true
        ]);

    }
}
