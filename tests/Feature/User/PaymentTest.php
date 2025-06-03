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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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
        Category::factory()->create([
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
            'name' => 'Test Menu',
            'price' => 25000,
            'discount' => 0,
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
    public function it_can_display_checkout_page()
    {
        //Data test for checkout
        $cart = [
            $this->menu->id => [
                'name' => $this->menu->name,
                'price' => $this->menu->price,
                'quantity' => 2,
                'discount' => 0,
                'subtotal' => 50000,
            ]
        ];
        $orderId = 'ORDER-' . time();
        $response = $this->actingAs($this->user)
            ->withSession([
                'cart' => $cart,
                'order_id' => $orderId,
                'table_id' => $this->table->id,
            ])
            ->get('/user/payment');
        $response->assertStatus(200);
        $response->assertViewIs('user.checkout_user');
        $response->assertViewHas('cart', $cart);
        $response->assertViewHas('order_id', $orderId);
        $response->assertViewHas('table');
    }
}
