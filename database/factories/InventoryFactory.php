<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Menu;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'current_quantity' => $this->faker->numberBetween(0, 200),
            'transaction_type' => $this->faker->randomElement(['in', 'out']),
            'reason' => $this->faker->sentence(),
        ];
    }
}
