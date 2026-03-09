<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = \App\Models\Role::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->unique()->jobTitle(), // Génère un nom de rôle unique
            'description' => $this->faker->sentence(6),  // Petite description du rôle
            'statut' => true
        ];
    }
}
