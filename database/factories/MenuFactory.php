<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'status' => '1',
            'image_path' => $this->faker->imageUrl(640, 480, 'food', true),
            'favorite' => $this->faker->numberBetween(0,10),
            'price' => $this->faker->numberBetween(10, 30000),
            'discount' => $this->faker->numberBetween(10, 30000),
        ];
    }
}
