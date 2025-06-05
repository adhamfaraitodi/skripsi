<?php

namespace Tests\Feature\User;

use App\Models\Inventory;
use App\Models\Menu;
use App\Models\MenuOrder;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Facade;
use Midtrans\Snap;
use Mockery;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $table;
    protected $menu;
    protected function setUp(): void
    {
        parent::setUp();
        Role::factory()->admin()->create();
        Role::factory()->manager()->create();
        Role::factory()->user()->create();
        $category = Category::factory()->create([
        'name' => 'makanan',
        ]);
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id'=>3,
            'password' => bcrypt('password'),
        ]);
        $this->table = Table::factory()->create([
            'number' => '01',
            'table_code'=> 'T0100',
        ]);
        $this->menu = Menu::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'name' => 'Test Menu',
            'description' => 'This is a test menu',
            'image_path' => 'test-menu.jpg',
            'favorite' => 0,
            'price' => 25000,
            'discount' => 0,
            'status' => 1,
        ]);
        Inventory::factory()->create([
            'menu_id' => $this->menu->id,
            'quantity' => 10,
            'transaction_type' => 'in',
            'reason' => 'initial_stock',
            'current_quantity' => 10,
        ]);
    }
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    /** @test */
    public function test_display_checkout_page()
    {
        //Data test for checkout
        $cart = [
            $this->menu->id => [
                'name' => $this->menu->name,
                'price' => $this->menu->price,
                'original_price' => $this->menu->price,
                'image_path' => $this->menu->image_path,
                'quantity' => 2,
                'discount' => 0,
                'subtotal' => 50000,
            ]
        ];
        $orderId = 'RY-' . time();
        $response = $this->actingAs($this->user)
            ->withSession([
                'cart' => $cart,
                'order_id' => $orderId,
                'table_id' => $this->table->id,
            ])
            ->get('/dine-in/checkout');
        $response->assertStatus(200);
        $response->assertViewIs('user.checkout_user');
        $response->assertViewHas('cart', $cart);
        $response->assertViewHas('order_id', $orderId);
        $response->assertViewHas('table');
    }
    /** @test */
    public function test_create_payment_and_return_snap_token(){
        Facade::clearResolvedInstances();
        AliasLoader::getInstance()->alias('Snap', Snap::class);
        $mock = Mockery::mock('alias:Midtrans\Snap');
        $mock->shouldReceive('getSnapToken')->once()->andReturn('fake-snap-token');
        $cart = [
            $this->menu->id => [
                'name' => $this->menu->name,
                'original_price' => $this->menu->price,
                'image_path' => $this->menu->image_path,
                'price' => $this->menu->price,
                'quantity' => 2,
                'discount' => 0,
                'subtotal' => $this->menu->price * 2,
            ]
        ];

        $orderId = 'RY-' . time();

        $response = $this->actingAs($this->user)
            ->withSession([
                'cart' => $cart,
                'order_id' => $orderId,
                'table_id' => $this->table->id,
            ])
            ->postJson(route('user.checkout.create'), [
                'order_note' => 'Please cook fast!',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'snap_token' => 'fake-snap-token',
        ]);

        $this->assertDatabaseHas('orders', [
            'order_code' => $orderId,
            'user_id' => $this->user->id,
            'order_status' => 'pending',
            'gross_amount' => $this->menu->price * 2,
            'note' => 'Please cook fast!',
        ]);

        $this->assertDatabaseHas('menu_orders', [
            'menu_id' => $this->menu->id,
            'quantity' => 2,
            'subtotal' => $this->menu->price * 2,
        ]);
    }
    /** @test */
    public function test_continue_pending_payment_in_history_page(){
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'table_id' => $this->table->id,
            'order_code' => 'RY-123',
            'order_status' => 'pending',
            'gross_amount' => 50000,
        ]);
        AliasLoader::getInstance()->alias('Snap', Snap::class);
        $mock = Mockery::mock('alias:Midtrans\Snap');
        $mock->shouldReceive('getSnapToken')->once()->andReturn('mocked-snap-token');
        $response = $this->post('/continue-payment', [
            'order_code' => 'RY-123',
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'snap_token' => 'mocked-snap-token',
        ]);
    }

    /** @test */
    public function test_display_appreciate_page(){
        $response = $this->actingAs($this->user)
            ->get('/thank-you');
        $response->assertStatus(200);
        $response->assertViewIs('user.thank_you');
    }
}
