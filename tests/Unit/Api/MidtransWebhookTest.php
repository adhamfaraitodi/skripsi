<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use App\Http\Controllers\Api\MidtransController;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;

class MidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $serverKey;

    protected function setUp(): void
    {
        parent::setUp();
        Role::factory()->admin()->create();
        Role::factory()->manager()->create();
        Role::factory()->user()->create();
        $this->controller = new MidtransController();
        $this->serverKey = env('MIDTRANS_SERVER_KEY');
        
        // Mock the environment variable
        config(['app.env' => 'testing']);
        putenv('MIDTRANS_SERVER_KEY=' . $this->serverKey);
    }

    public function test_successful_payment_notification_with_settlement_status()
    {
        // Create test order
        $order = Order::factory()->create(['order_code' => 'order-nFUIrFTB']);
        
        // Generate valid signature
        $orderCode = 'order-nFUIrFTB';
        $statusCode = '200';
        $grossAmount = '2987710.00';
        $validSignature = hash('sha512', $orderCode . $statusCode . $grossAmount . $this->serverKey);
        
        $requestData = [
            'order_id' => $orderCode,
            'transaction_id' => '64075489-a529-496f-a810-f2679ae833f6',
            'transaction_status' => 'settlement',
            'transaction_time' => '2025-05-20 19:46:33',
            'settlement_time' => '2025-05-20 19:46:46',
            'payment_type' => 'qris',
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'status_code' => $statusCode,
            'va_numbers' => [
                [
                    'va_number' => '123456789',
                    'bank' => 'bca'
                ]
            ]
        ];

        $request = Request::create('/api/webhook', 'POST', $requestData);
        
        $response = $this->controller->index($request);
        
        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Payment recorded successfully', $responseData['message']);
        
        // Assert payment was created
        $this->assertDatabaseHas('payments', [
            'order_code' => $orderCode,
            'transaction_id' => '64075489-a529-496f-a810-f2679ae833f6',
            'transaction_status' => 'settlement',
            'payment_type' => 'qris',
            'gross_amount' => $grossAmount,
            'va_number' => '123456789',
            'bank' => 'bca'
        ]);
        
        // Assert order status was updated to paid
        $this->assertDatabaseHas('orders', [
            'order_code' => $orderCode,
            'order_status' => 'paid'
        ]);
    }

    public function test_successful_payment_notification_without_settlement_status()
    {
        // Create test order
        $order = Order::factory()->create(['order_code' => 'order-test123']);
        
        // Generate valid signature
        $orderCode = 'order-test123';
        $statusCode = '200';
        $grossAmount = '50000.00';
        $validSignature = hash('sha512', $orderCode . $statusCode . $grossAmount . $this->serverKey);
        
        $requestData = [
            'order_id' => $orderCode,
            'transaction_id' => 'test-transaction-id',
            'transaction_status' => 'pending',
            'transaction_time' => '2025-05-20 19:46:33',
            'payment_type' => 'bank_transfer',
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'status_code' => $statusCode,
        ];

        $request = Request::create('/api/webhook', 'POST', $requestData);
        
        $response = $this->controller->index($request);
        
        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        // Assert payment was created
        $this->assertDatabaseHas('payments', [
            'order_code' => $orderCode,
            'transaction_status' => 'pending',
        ]);
        
        // Assert order status was NOT updated to paid (since status is not settlement)
        $this->assertDatabaseHas('orders', [
            'order_code' => $orderCode,
            'order_status' => $order->order_status // Original status unchanged
        ]);
    }

    public function test_invalid_signature_returns_403()
    {
        Log::shouldReceive('warning')->once();
        
        $order = Order::factory()->create(['order_code' => 'order-invalid']);
        
        $requestData = [
            'order_id' => 'order-invalid',
            'transaction_id' => 'test-transaction-id',
            'transaction_status' => 'settlement',
            'transaction_time' => '2025-05-20 19:46:33',
            'payment_type' => 'qris',
            'gross_amount' => '50000.00',
            'signature_key' => 'invalid-signature-key',
            'status_code' => '200',
        ];

        $request = Request::create('/api/webhook', 'POST', $requestData);
        
        $response = $this->controller->index($request);
        
        // Assert 403 response
        $this->assertEquals(403, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Invalid signature', $responseData['message']);
        
        // Assert no payment was created
        $this->assertDatabaseMissing('payments', [
            'order_code' => 'order-invalid'
        ]);
    }

    public function test_order_not_found_throws_exception()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        
        // Generate valid signature for non-existent order
        $orderCode = 'non-existent-order';
        $statusCode = '200';
        $grossAmount = '50000.00';
        $validSignature = hash('sha512', $orderCode . $statusCode . $grossAmount . $this->serverKey);
        
        $requestData = [
            'order_id' => $orderCode,
            'transaction_id' => 'test-transaction-id',
            'transaction_status' => 'settlement',
            'transaction_time' => '2025-05-20 19:46:33',
            'payment_type' => 'qris',
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'status_code' => $statusCode,
        ];

        $request = Request::create('/api/webhook', 'POST', $requestData);
        
        $this->controller->index($request);
    }

    public function test_validation_fails_with_missing_required_fields()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        
        $requestData = [
            'order_id' => 'order-test',
            // Missing required fields
        ];

        $request = Request::create('/api/webhook', 'POST', $requestData);
        
        $this->controller->index($request);
    }

    public function test_payment_update_or_create_updates_existing_payment()
    {
        // Create test order and existing payment
        $order = Order::factory()->create(['order_code' => 'order-update']);
        $existingPayment = Payment::factory()->create([
            'order_code' => 'order-update',
            'transaction_status' => 'pending'
        ]);
        
        // Generate valid signature
        $orderCode = 'order-update';
        $statusCode = '200';
        $grossAmount = '75000.00';
        $validSignature = hash('sha512', $orderCode . $statusCode . $grossAmount . $this->serverKey);
        
        $requestData = [
            'order_id' => $orderCode,
            'transaction_id' => 'updated-transaction-id',
            'transaction_status' => 'settlement',
            'transaction_time' => '2025-05-20 19:46:33',
            'settlement_time' => '2025-05-20 19:46:46',
            'payment_type' => 'credit_card',
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'status_code' => $statusCode,
        ];

        $request = Request::create('/api/webhook', 'POST', $requestData);
        
        $response = $this->controller->index($request);
        
        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        // Assert payment was updated (not created new)
        $this->assertDatabaseHas('payments', [
            'id' => $existingPayment->id, // Same ID
            'order_code' => $orderCode,
            'transaction_status' => 'settlement', // Updated status
            'transaction_id' => 'updated-transaction-id' // Updated transaction ID
        ]);
        
        // Assert only one payment record exists for this order
        $this->assertDatabaseCount('payments', 1);
    }

    public function test_handles_va_numbers_gracefully_when_not_provided()
    {
        $order = Order::factory()->create(['order_code' => 'order-no-va']);
        
        // Generate valid signature
        $orderCode = 'order-no-va';
        $statusCode = '200';
        $grossAmount = '25000.00';
        $validSignature = hash('sha512', $orderCode . $statusCode . $grossAmount . $this->serverKey);
        
        $requestData = [
            'order_id' => $orderCode,
            'transaction_id' => 'test-transaction-id',
            'transaction_status' => 'settlement',
            'transaction_time' => '2025-05-20 19:46:33',
            'payment_type' => 'credit_card',
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'status_code' => $statusCode,
            // No va_numbers provided
        ];

        $request = Request::create('/api/webhook', 'POST', $requestData);
        
        $response = $this->controller->index($request);
        
        // Assert response is successful
        $this->assertEquals(200, $response->getStatusCode());
        
        // Assert payment was created with null va_number and bank
        $this->assertDatabaseHas('payments', [
            'order_code' => $orderCode,
            'va_number' => null,
            'bank' => null
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
