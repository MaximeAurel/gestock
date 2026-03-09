<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Utilisateur administrateur
        User::create([
            'nom' => 'Admin',
            'email' => 'admin@gestock.com',
            'password' => Hash::make('admin123'), // mot de passe par défaut
            'role_id' => 1, // à adapter selon ton Role admin
            'statut' => true
        ]);

        // Génère 5 utilisateurs fictifs pour test
        User::factory(5)->create();
    }
}
