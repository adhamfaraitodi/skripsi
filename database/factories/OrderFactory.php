<?php

namespace Database\Factories;
use App\Models\Order;
use App\Models\User;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'table_id' => Table::factory(),
            'order_code' => 'order-' . $this->faker->unique()->randomLetter() . $this->faker->unique()->bothify('########'),
            'order_status' => $this->faker->randomElement(['pending','paid']),
            'gross_amount' => $this->faker->numberBetween(10000, 500000),
            'note' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    /**
     * State for pending orders
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'pending',
        ]);
    }

    /**
     * State for completed orders
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'completed',
        ]);
    }

    /**
     * State for paid orders
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'paid',
        ]);
    }

    /**
     * State for cancelled orders
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'cancelled',
        ]);
    }

    /**
     * State for orders with specific table
     */
    public function forTable(int $tableId): static
    {
        return $this->state(fn (array $attributes) => [
            'table_id' => $tableId,
        ]);
    }

    /**
     * State for orders with specific user
     */
    public function forUser(int $userId): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $userId,
        ]);
    }

    /**
     * State for high value orders
     */
    public function highValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'gross_amount' => $this->faker->numberBetween(1000000, 5000000),
        ]);
    }
}
