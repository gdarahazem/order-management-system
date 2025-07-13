<?php

namespace Tests\Feature\Feature;


use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    /**
     * Test GET /api/orders
     */
    public function test_can_get_all_orders()
    {
        $products = Product::factory()->count(2)->create(['price' => 50.00]);

        Order::factory()->create([
            'products' => $products->pluck('id')->toArray(),
            'total_price' => 100.00
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'products', 'total_price', 'products_details']
                ],
                'message'
            ]);
    }

    /**
     * Test POST /api/orders
     */
    public function test_can_create_order()
    {
        $products = Product::factory()->count(2)->create(['price' => 50.00]);

        $orderData = [
            'products' => $products->pluck('id')->toArray()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/orders', $orderData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'products' => $products->pluck('id')->toArray(),
                    'total_price' => 100.00
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'products' => json_encode($products->pluck('id')->toArray()),
            'total_price' => 100.00
        ]);
    }

    /**
     * Test order calculates correct total price
     */
    public function test_order_calculates_total_price()
    {
        $product1 = Product::factory()->create(['price' => 25.99]);
        $product2 = Product::factory()->create(['price' => 74.01]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/orders', [
            'products' => [$product1->id, $product2->id]
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => ['total_price' => 100.00]
            ]);
    }

    /**
     * Test orders require authentication
     */
    public function test_orders_require_authentication()
    {
        $response = $this->getJson('/api/orders');
        $response->assertStatus(401);

        $response = $this->postJson('/api/orders', ['products' => [1, 2]]);
        $response->assertStatus(401);
    }

    /**
     * Test order validation
     */
    public function test_order_validation()
    {
        // Empty products array
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/orders', ['products' => []]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products']);

        // Non-existent product IDs
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/orders', ['products' => [999, 1000]]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products.0', 'products.1']);
    }
}
