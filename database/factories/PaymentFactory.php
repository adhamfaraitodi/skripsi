<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $transactionTime = $this->faker->dateTimeBetween('-1 month', 'now');
        $settlementTime = $this->faker->optional(0.7)->dateTimeBetween($transactionTime, 'now'); // 70% chance of settlement

        return [
            'order_id' => Order::factory(),
            'order_code' => 'order-' . $this->faker->unique()->randomLetter() . $this->faker->unique()->bothify('########'),
            'transaction_id' => $this->faker->uuid(),
            'transaction_status' => $this->faker->randomElement(['pending', 'settlement', 'capture', 'deny', 'cancel', 'expire', 'failure']),
            'payment_type' => $this->faker->randomElement(['qris', 'bank_transfer', 'credit_card', 'gopay', 'shopeepay', 'other_va']),
            'gross_amount' => $this->faker->numberBetween(10000, 500000),
            'transaction_time' => $transactionTime,
            'settlement_time' => $settlementTime,
            'va_number' => $this->faker->optional(0.4)->numerify('############'), // 40% chance of VA number
            'bank' => $this->faker->optional(0.4)->randomElement(['bca', 'bni', 'bri', 'mandiri', 'permata', 'cimb']),
            'response_json' => json_encode([
                'status_code' => '200',
                'status_message' => 'Success, transaction is found',
                'transaction_id' => $this->faker->uuid(),
                'order_id' => 'order-' . $this->faker->bothify('########'),
                'gross_amount' => $this->faker->numberBetween(10000, 500000) . '.00',
                'payment_type' => $this->faker->randomElement(['qris', 'bank_transfer', 'credit_card']),
                'transaction_time' => $transactionTime->format('Y-m-d H:i:s'),
                'transaction_status' => 'settlement',
                'fraud_status' => 'accept',
                'merchant_id' => 'G' . $this->faker->numerify('#########'),
            ])
        ];
    }

    /**
     * State for settled payments
     */
    public function settled(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_status' => 'settlement',
            'settlement_time' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * State for pending payments
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_status' => 'pending',
            'settlement_time' => null,
        ]);
    }

    /**
     * State for failed payments
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_status' => 'failure',
            'settlement_time' => null,
        ]);
    }

    /**
     * State for cancelled payments
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_status' => 'cancel',
            'settlement_time' => null,
        ]);
    }

    /**
     * State for QRIS payments
     */
    public function qris(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_type' => 'qris',
            'va_number' => null,
            'bank' => null,
        ]);
    }

    /**
     * State for bank transfer payments with VA
     */
    public function bankTransfer(string $bank = 'bca'): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_type' => 'bank_transfer',
            'va_number' => $this->faker->numerify('############'),
            'bank' => $bank,
        ]);
    }

    /**
     * State for credit card payments
     */
    public function creditCard(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_type' => 'credit_card',
            'va_number' => null,
            'bank' => null,
        ]);
    }

    /**
     * State for payments with specific order
     */
    public function forOrder(Order $order): static
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => $order->id,
            'order_code' => $order->order_code,
            'gross_amount' => $order->gross_amount,
        ]);
    }

    /**
     * State for payments with specific order code
     */
    public function withOrderCode(string $orderCode): static
    {
        return $this->state(fn (array $attributes) => [
            'order_code' => $orderCode,
        ]);
    }

    /**
     * State for high value payments
     */
    public function highValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'gross_amount' => $this->faker->numberBetween(1000000, 5000000), // 1M - 5M
        ]);
    }
}
