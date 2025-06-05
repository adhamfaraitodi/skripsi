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

class MenuTest extends TestCase
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
    public function test_display_all_menu(): void
    {
        $session = [
            'table_id' => $this->table->id,
            'order_id' => 1234,
        ];

        $response = $this->withSession($session)
            ->get('/dine-in');

        $response->assertStatus(200);
        $response->assertViewIs('user.menu_user');
        $response->assertViewHasAll(['tableId', 'orderId', 'datas']);

        $viewData = $response->viewData('datas');
        $this->assertTrue($viewData->contains($this->menu));
        $this->assertEquals(10, $viewData->first()->stock);
    }
    public function test_search_menu_by_name(): void
    {
        $session = [
            'table_id' => $this->table->id,
            'order_id' => 1234,
        ];

        $response = $this->withSession($session)
            ->get('/dine-in?query=Test Menu');

        $response->assertStatus(200);
        $response->assertViewIs('user.menu_user');
        $response->assertViewHasAll(['tableId', 'orderId', 'datas']);

        $viewData = $response->viewData('datas');
        $this->assertTrue($viewData->contains($this->menu));
    }
    public function test_search_menu_by_category(): void
    {
        $session = [
            'table_id' => $this->table->id,
            'order_id' => 1234,
        ];

        $response = $this->withSession($session)
            ->get('/dine-in?query=makanan');

        $response->assertStatus(200);
        $response->assertViewIs('user.menu_user');
        $response->assertViewHasAll(['tableId', 'orderId', 'datas']);

        $viewData = $response->viewData('datas');
        $this->assertTrue($viewData->contains($this->menu));
    }
    public function test_search_menu_by_description(): void
    {
        $session = [
            'table_id' => $this->table->id,
            'order_id' => 1234,
        ];

        $response = $this->withSession($session)
            ->get('/dine-in?query=test menu');

        $response->assertStatus(200);
        $response->assertViewIs('user.menu_user');
        $response->assertViewHasAll(['tableId', 'orderId', 'datas']);

        $viewData = $response->viewData('datas');
        $this->assertTrue($viewData->contains($this->menu));
    }
    public function test_search_menu_with_no_results(): void
    {
        $session = [
            'table_id' => $this->table->id,
            'order_id' => 1234,
        ];

        $response = $this->withSession($session)
            ->get('/dine-in?query=Nonexistent Menu');

        $response->assertStatus(200);
        $response->assertViewIs('user.menu_user');
        $response->assertViewHasAll(['tableId', 'orderId', 'datas']);

        $viewData = $response->viewData('datas');
        $this->assertTrue($viewData->isEmpty());
    }
    public function test_session_expired_redirect(): void
    {
        $response = $this->get('/dine-in');

        $response->assertRedirect(route('user.table'));
        $response->assertSessionHas('error', 'Session expired. Please scan again.');
    }
}
