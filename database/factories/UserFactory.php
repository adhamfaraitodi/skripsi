<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            // 'role_id' => Role::first()->id ?? Role::factory()->create()->id,
            'role_id' => $this->getOrCreateRole(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    
    /**
     * Get or create a default role
     */
    private function getOrCreateRole(): int
    {
        $role = Role::where('name', 'user')->first();
        
        if (!$role) {
            $role = Role::factory()->user()->create();
        }
        
        return $role->id;
    }

    /**
     * State for admin users
     */
    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            $adminRole = Role::where('name', 'admin')->first() 
                ?? Role::factory()->admin()->create();
            
            return [
                'role_id' => $adminRole->id,
            ];
        });
    }

    /**
     * State for manager users
     */
    public function manager(): static
    {
        return $this->state(function (array $attributes) {
            $managerRole = Role::where('name', 'manager')->first() 
                ?? Role::factory()->manager()->create();
            
            return [
                'role_id' => $managerRole->id,
            ];
        });
    }

    /**
     * State for regular users
     */
    public function user(): static
    {
        return $this->state(function (array $attributes) {
            $userRole = Role::where('name', 'user')->first() 
                ?? Role::factory()->user()->create();
            
            return [
                'role_id' => $userRole->id,
            ];
        });
    }

    /**
     * State for users with specific role
     */
    public function withRole(int $roleId): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => $roleId,
        ]);
    }
}
