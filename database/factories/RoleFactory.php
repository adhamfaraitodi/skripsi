<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    private static $roleId = 1;
    public function definition(): array
    {
        return [
            'id' => self::$roleId++,
            'name' => fake()->jobTitle(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'id' => 1,
            'name' => 'Admin',
        ]);
    }

    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'id' => 2,
            'name' => 'Manager',
        ]);
    }

    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'id' => 3,
            'name' => 'User',
        ]);
    }
}
