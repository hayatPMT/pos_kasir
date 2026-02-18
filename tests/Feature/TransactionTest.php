<?php

use App\Models\Branch;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed basic data
    $this->branch = Branch::create([
        'name' => 'Test Branch',
        'code' => 'TEST',
        'is_active' => true,
    ]);

    $this->user = User::factory()->create([
        'branch_id' => $this->branch->id,
        'role' => 'cashier',
    ]);

    $this->category = Category::create([
        'name' => 'Test Category',
        'slug' => 'test-category',
    ]);

    $this->product = Product::create([
        'branch_id' => $this->branch->id,
        'category_id' => $this->category->id,
        'name' => 'Test Product',
        'slug' => 'test-product',
        'sku' => 'TEST-001',
        'buy_price' => 1000,
        'sell_price' => 2000,
        'stock' => 100,
        'min_stock' => 10,
        'is_active' => true,
    ]);
});

test('cashier can create transaction via web pos', function () {
    $this->actingAs($this->user);

    $response = $this->post(route('pos.store'), [
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 2,
            ],
        ],
        'pay_amount' => 5000,
        'payment_method' => 'cash',
    ]);

    $response->assertRedirect(route('pos.index'));
    $response->assertSessionHas('success');

    // Assert Transaction Created
    $this->assertDatabaseHas('transactions', [
        'branch_id' => $this->branch->id,
        'subtotal' => 4000, // 2 * 2000
        'total' => 4000,
        'pay_amount' => 5000,
        'change_amount' => 1000,
        'status' => 'completed',
    ]);

    // Assert Stock Reduced
    $this->assertDatabaseHas('products', [
        'id' => $this->product->id,
        'stock' => 98, // 100 - 2
    ]);

    // Assert Payment Recorded
    $this->assertDatabaseHas('payments', [
        'amount' => 5000,
        'method' => 'cash',
    ]);

    // Assert Stock Movement
    $this->assertDatabaseHas('stock_movements', [
        'product_id' => $this->product->id,
        'quantity' => 2,
        'type' => 'out',
    ]);
});

test('api can create transaction', function () {
    $this->actingAs($this->user); // Sanctum auth via actingAs works for tests if configured or mocked, but for API routes we might need check guard

    // Note: actingAs usually works for default guard. For API, we might need sanctum acting as.
    // Laravel Pest actingAs handles it if we use Sanctum::actingAs($user) or just actingAs($user, 'sanctum')?
    // Let's try simple actingAs first, as Sanctum hooks into it.

    $response = $this->postJson('/api/transactions', [
        'branch_id' => $this->branch->id,
        'user_id' => $this->user->id,
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 5,
            ],
        ],
        'pay_amount' => 10000,
        'payment_method' => 'cash',
    ]);

    // If we get 401, we know it's auth.
    if ($response->status() === 401) {
        \Laravel\Sanctum\Sanctum::actingAs($this->user, ['*']);
        $response = $this->postJson('/api/transactions', [
            'branch_id' => $this->branch->id,
            'user_id' => $this->user->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 5,
                ],
            ],
            'pay_amount' => 10000,
            'payment_method' => 'cash',
        ]);
    }

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'invoice_number',
                'total',
            ],
        ]);

    $this->assertDatabaseHas('transactions', [
        'total' => 10000, // 5 * 2000
        'change_amount' => 0,
    ]);
});
