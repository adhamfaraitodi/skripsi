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
        config(['app.env' => 'testing']);
        putenv('MIDTRANS_SERVER_KEY=' . $this->serverKey);
    }

    public function test_successful_payment_notification_with_settlement_status()
    {
        $order = Order::factory()->create(['order_code' => 'order-nFUIrFTB']);
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
        
        // Assert response must return 200 OK
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Payment recorded successfully', $responseData['message']);
        
        // Assert payment was created with correct details
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
        $order = Order::factory()->create(['order_code' => 'order-test123']);
        
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
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('payments', [
            'order_code' => $orderCode,
            'transaction_status' => 'pending',
        ]);
        
        $this->assertDatabaseHas('orders', [
            'order_code' => $orderCode,
            'order_status' => $order->order_status
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
        $this->assertEquals(403, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Invalid signature', $responseData['message']);
        $this->assertDatabaseMissing('payments', [
            'order_code' => 'order-invalid'
        ]);
    }

    public function test_order_not_found_throws_exception()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        
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
        //test with missing required fields
        $requestData = [
            'order_id' => 'order-test',
        ];

        $request = Request::create('/api/webhook', 'POST', $requestData);
        
        $this->controller->index($request);
    }

    public function test_payment_update_or_create_updates_existing_payment()
    {
        $order = Order::factory()->create(['order_code' => 'order-update']);
        $existingPayment = Payment::factory()->create([
            'order_code' => 'order-update',
            'transaction_status' => 'pending'
        ]);
        
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
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('payments', [
            'id' => $existingPayment->id,
            'order_code' => $orderCode,
            'transaction_status' => 'settlement',
            'transaction_id' => 'updated-transaction-id'
        ]);
        $this->assertDatabaseCount('payments', 1);
    }

    public function test_handles_va_numbers_gracefully_when_not_provided()
    {
        $order = Order::factory()->create(['order_code' => 'order-no-va']);
        $orderCode = 'order-no-va';
        $statusCode = '200';
        $grossAmount = '25000.00';
        $validSignature = hash('sha512', $orderCode . $statusCode . $grossAmount . $this->serverKey);
        //Data test without va_numbers
        $requestData = [
            'order_id' => $orderCode,
            'transaction_id' => 'test-transaction-id',
            'transaction_status' => 'settlement',
            'transaction_time' => '2025-05-20 19:46:33',
            'payment_type' => 'credit_card',
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'status_code' => $statusCode,
        ];

        $request = Request::create('/api/webhook', 'POST', $requestData);
        
        $response = $this->controller->index($request);
        $this->assertEquals(200, $response->getStatusCode());
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
