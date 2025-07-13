<?php

namespace Tests\Feature\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
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
     * Test GET /api/products
     */
    public function test_can_get_all_products()
    {
        Product::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['*' => ['id', 'name', 'price']],
                'message'
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    /**
     * Test POST /api/products
     */
    public function test_can_create_product()
    {
        $productData = [
            'name' => 'Gaming Laptop',
            'price' => 1299.99
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/products', $productData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Gaming Laptop',
                    'price' => 1299.99
                ]
            ]);

        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test products require authentication
     */
    public function test_products_require_authentication()
    {
        $response = $this->getJson('/api/products');
        $response->assertStatus(401);

        $response = $this->postJson('/api/products', ['name' => 'Test', 'price' => 99.99]);
        $response->assertStatus(401);
    }

    /**
     * Test product validation
     */
    public function test_product_validation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/products', [
            'name' => '',
            'price' => 0
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price']);
    }
}
