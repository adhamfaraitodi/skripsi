<?php

namespace Tests\Feature\User;

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Role;
use App\Models\Category;
use App\Models\Table;
use App\Models\Inventory;
use App\Models\User;
use Mockery;

class CartTest extends TestCase
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
    public function test_display_cart_page(): void
    {
        $this->actingAs($this->user);
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
        $response = $this->withSession(['cart' => $cart])
            ->get('/dine-in/cart');

        $response->assertStatus(200);
        $response->assertViewIs('user.cart_user');
        $response->assertViewHas('total', 50000);
        $response->assertViewHas('cart', $cart);
    }
    public function test_adds_item_to_cart()
    {
        $menu = Menu::factory()->create(['price' => 10000, 'discount' => 1000]);

        $response = $this->postJson('/cart/add', [
            'id' => $menu->id,
            'table_id' => 1,
        ]);

        $response->assertJson([
            'status' => 'success',
            'message' => 'Item added to cart successfully',
            'cart_count' => 1,
        ]);

        $this->assertEquals(1, session('cart')[$menu->id]['quantity']);
    }
    public function test_updates_item_quantity_in_cart()
    {
        $this->actingAs($this->user);
        $menu = Menu::factory()->create(['price' => 10000, 'discount' => 1000]);
        $cart = [
            $menu->id => [ 
                'name' => $menu->name, 
                'price' => $menu->price, 
                'original_price' => $menu->price,
                'image_path' => $menu->image_path,
                'quantity' => 2,
                'discount' => 0,
                'subtotal' => 50000,
            ]
        ];
        $this->withSession(['cart' => $cart]);

        $response = $this->postJson('/cart/update', [
            'menu_id' => $menu->id,
            'quantity' => 3,
        ]);

        $response->assertJson([
            'status' => 'success',
            'subtotal' => 30000,
            'total' => 30000
        ]);
    }
    public function test_removes_item_from_cart()
    {
        $this->actingAs($this->user);
        $menu = Menu::factory()->create(['price' => 10000, 'discount' => 1000]);
        $cart = [
            $menu->id => [
                'name' => $menu->name, 
                'price' => $menu->price,
                'original_price' => $menu->price,
                'image_path' => $menu->image_path, 
                'quantity' => 2,
                'discount' => 0,
                'subtotal' => 50000,
            ]
        ];
        $this->withSession(['cart' => $cart]);

        $response = $this->postJson('/cart/remove', [
            'menu_id' => $menu->id
        ]);

        $response->assertJson([
            'status' => 'success',
            'message' => 'Item removed from cart',
            'total' => 0
        ]);

        $this->assertEmpty(session('cart'));
    }
    public function test_increments_favorite_count()
    {
        $menu = Menu::factory()->create(['favorite' => 0]);

        $this->postJson('/favorite', [
            'menu_id' => $menu->id,
            'is_favorite' => true
        ])->assertJson([
            'status' => 'added',
            'favorite_count' => 1
        ]);
    }
}
