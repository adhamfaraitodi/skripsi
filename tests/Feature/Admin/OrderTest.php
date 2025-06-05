<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;
use App\Models\Category;
use App\Models\Table;
use App\Models\User;
use App\Models\Menu;
use App\Models\Payment;
use App\Models\Order;
use Mockery;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $table;
    protected $menu;
    protected $category;
    protected function setUp(): void
    {
        parent::setUp();
        Role::factory()->admin()->create();
        Role::factory()->manager()->create();
        Role::factory()->user()->create();
        $this->category = Category::factory()->create([
        'name' => 'makanan',
        ]);
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id'=>1,
            'password' => bcrypt('password'),
        ]);
        $this->table = Table::factory()->create([
            'number' => '01',
            'table_code'=> 'T0100',
        ]);
    }
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    public function test_display_order_in (): void
    {
        $order = Order::factory()->create([
            'order_status' => 'pending'
        ]);

        $response = $this->actingAs($this->user)->get(route('order.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.order');
        $response->assertViewHas('datas');
    }
    public function test_display_order_history(): void
    {
        $order = Order::factory()->create([
            'order_status' => 'success'
        ]);

        $response = $this->actingAs($this->user)->get(route('order.history.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.history');
        $response->assertViewHas('datas');
    }
    public function test_admin_successfully_processed_order(): void
    {
        $order = Order::factory()->create([
            'order_status' => 'paid'
        ]);

        $response = $this->actingAs($this->user)->post(route('order.update', $order->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'success'
        ]);
    }
    public function test_download_payment_detail_json(): void
    {
        $payment = Payment::factory()->create([
            'response_json' => json_encode(['status' => 'paid', 'amount' => 50000]),
        ]);

        $response = $this->actingAs($this->user)->get(route('download.response', $payment->id));
        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('Content-Type'), 'text/plain')
        );
        $this->assertEquals(
            'attachment; filename=payment_response_' . $payment->id . '.txt',
            $response->headers->get('Content-Disposition')
        );
        $response->assertSee('paid');
    }

}
