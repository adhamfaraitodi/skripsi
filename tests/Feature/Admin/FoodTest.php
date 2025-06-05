<?php

namespace Tests\Feature\Admin;

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Role;
use App\Models\Category;
use App\Models\Table;
use App\Models\User;
use Mockery;

class FoodTest extends TestCase
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

    public function test_store_food_data(): void
    {
         Storage::fake('public');

        $file = UploadedFile::fake()->image('food.jpg');

        $data = [
            'foodName' => 'Nasi Goreng',
            'foodDesc' => 'Delicious fried rice',
            'foodImg' => $file,
            'foodStock' => 20,
            'category_id' => $this->category->id,
            'foodPrice' => 30000,
            'foodDisc' => 5000,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('food.store'), $data);

        $response->assertRedirect(route('food.index'));
        $response->assertSessionHas('success');
        $this->assertTrue(Storage::disk('public')->exists('menu_images/' . $file->hashName()));
        $this->assertDatabaseHas('menus', [
            'name' => 'Nasi Goreng',
            'description' => 'Delicious fried rice',
            'category_id' => $this->category->id,
            'price' => 30000,
            'discount' => 5000,
            'user_id' => $this->user->id,
        ]);
        $menu = Menu::where('name', 'Nasi Goreng')->first();
        $this->assertDatabaseHas('inventories', [
            'menu_id' => $menu->id,
            'quantity' => 20,
            'transaction_type' => 'in',
            'reason' => 'initial quantity',
        ]);
    }
    public function test_update_food_data(): void
    {
        $menu = Menu::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'name' => 'Test Menu',
            'description' => 'This is a test menu',
            'image_path' => 'test-menu.jpg',
            'favorite' => 0,
            'price' => 25000,
            'discount' => 0,
            'status' => 1,
        ]);
        Storage::fake('public');
        $file = UploadedFile::fake()->image('food_updated.jpg');
        $data = [
            'foodName' => 'Updated Nasi Goreng',
            'foodDesc' => 'Updated delicious fried rice',
            'foodImg' => $file,
            'category_id' => $this->category->id,
            'foodPrice' => 35000,
            'foodDisc' => 1000,
        ];
        $response = $this->actingAs($this->user)
            ->post(route('food.update', $menu->id), $data);

        $response->assertRedirect(route('food.index'));
        $response->assertSessionHas('success');
        $this->assertTrue(Storage::disk('public')->exists('menu_images/' . $file->hashName()));
        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'name' => 'Updated Nasi Goreng',
            'description' => 'Updated delicious fried rice',
            'category_id' => $this->category->id,
            'price' => 35000,
            'discount' => 1000,
        ]);
    }
    public function test_delete_food_data(): void
    {
        $menu = Menu::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'name' => 'Test Menu',
            'description' => 'This is a test menu',
            'image_path' => 'test-menu.jpg',
            'favorite' => 0,
            'price' => 25000,
            'discount' => 0,
            'status' => 1,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('food.remove', $menu->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');
        //soft delete
        $this->assertSoftDeleted('menus', [
            'id' => $menu->id,
        ]);
    }
    public function test_food_index_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('food.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.food');
        $response->assertViewHas('datas');
    }
}
