---
name: food-qa-testing
description: Automated test cases and QA testing patterns for Laravel food ordering systems using PHPUnit and Pest. Covers checkout tests, empty cart prevention, stock decrement and cancellation rollback assertions, role permissions (customer, owner, admin), and order access security. Use this skill whenever writing tests, validating business logic, running test suites, or debugging failing order scenarios.
---

# Food Ordering QA & Automated Testing Guide

Panduan dan suite pengujian fitur otomatis (Feature Tests) untuk memastikan seluruh proses bisnis pemesanan makanan berjalan aman, akurat, dan tanpa celah keamanan.

---

## 1. Skenario Uji Kritis (Critical Test Scenarios)

1. **Autentikasi & Hak Akses (RBAC)**:
   - Customer tidak bisa mengakses dashboard owner / admin.
   - Customer tidak bisa melihat atau membatalkan pesanan milik customer lain (`403 Forbidden`).
   - Owner hanya bisa mengelola produk dan pesanan dari restorannya sendiri.

2. **Keranjang & Checkout**:
   - Customer tidak bisa checkout jika keranjang belanja kosong.
   - Stok produk wajib berkurang sesuai kuantitas yang dipesan.
   - Restoran yang sedang tutup (`is_open = false`) tidak boleh menerima pesanan.

3. **Pembatalan & Rollback Stok**:
   - Customer hanya bisa membatalkan pesanan yang berstatus `pending`.
   - Pesanan yang berstatus `diproses`, `dikirim`, atau `selesai` tidak boleh dibatalkan oleh customer.
   - Saat pesanan dibatalkan, **stok produk harus kembali bertambah** sebesar jumlah yang dipesan.

---

## 2. Test Suite Lengkap (`tests/Feature/FoodOrderingTest.php`)

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodOrderingTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $owner;
    protected Restaurant $restaurant;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Siapkan Akun & Data Restoran
        $this->owner = User::factory()->create(['role' => 'owner']);
        $this->customer = User::factory()->create(['role' => 'customer']);

        $this->restaurant = Restaurant::create([
            'user_id' => $this->owner->id,
            'name'    => 'Warung Sate Barokah',
            'slug'    => 'warung-sate-barokah',
            'address' => 'Jl. Merdeka No. 10',
            'is_open' => true,
        ]);

        $this->product = Product::create([
            'restaurant_id' => $this->restaurant->id,
            'name'          => 'Sate Ayam Madura 10 Tusuk',
            'price'         => 25000,
            'stock'         => 20,
            'is_available'  => true,
        ]);
    }

    /** @test */
    public function customer_cannot_checkout_with_empty_cart()
    {
        $response = $this->actingAs($this->customer)
            ->post(route('customer.orders.store'), [
                'address'        => 'Jl. Thamrin No. 1',
                'payment_method' => 'cod',
            ]);

        $response->assertRedirect(route('customer.cart.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
    }

    /** @test */
    public function order_reduces_product_stock_correctly()
    {
        // 1. Masukkan item ke keranjang
        $cart = Cart::create([
            'user_id'       => $this->customer->id,
            'restaurant_id' => $this->restaurant->id,
        ]);

        CartItem::create([
            'cart_id'    => $cart->id,
            'product_id' => $this->product->id,
            'quantity'   => 3,
            'price'      => $this->product->price,
        ]);

        // 2. Lakukan checkout
        $response = $this->actingAs($this->customer)
            ->post(route('customer.orders.store'), [
                'address'        => 'Jl. Sudirman Kav 5',
                'payment_method' => 'transfer',
            ]);

        // 3. Verifikasi
        $response->assertRedirect(route('customer.cart.index'));
        $this->assertDatabaseHas('orders', [
            'user_id'       => $this->customer->id,
            'restaurant_id' => $this->restaurant->id,
            'total_price'   => 75000,
            'status'        => 'pending',
        ]);

        // Stok awal 20 dikurangi 3 harus menjadi 17
        $this->assertEquals(17, $this->product->fresh()->stock);

        // Keranjang belanja harus bersih
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    /** @test */
    public function cancelling_order_restores_product_stock()
    {
        // Simulasikan stok berkurang karena pesanan
        $this->product->update(['stock' => 17]);

        $order = Order::create([
            'user_id'        => $this->customer->id,
            'restaurant_id'  => $this->restaurant->id,
            'total_price'    => 75000,
            'status'         => 'pending',
            'payment_method' => 'transfer',
            'address'        => 'Jl. Sudirman Kav 5',
        ]);

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $this->product->id,
            'quantity'   => 3,
            'price'      => $this->product->price,
        ]);

        // Customer membatalkan pesanan
        $response = $this->actingAs($this->customer)
            ->post(route('customer.orders.cancel', $order));

        $this->assertEquals('dibatalkan', $order->fresh()->status);
        // Stok 17 harus kembali menjadi 20
        $this->assertEquals(20, $this->product->fresh()->stock);
    }

    /** @test */
    public function customer_cannot_view_another_customers_order()
    {
        $otherCustomer = User::factory()->create(['role' => 'customer']);

        $order = Order::create([
            'user_id'        => $otherCustomer->id,
            'restaurant_id'  => $this->restaurant->id,
            'total_price'    => 50000,
            'status'         => 'pending',
            'payment_method' => 'cod',
            'address'        => 'Jl. Kenanga No. 2',
        ]);

        // Customer pertama mencoba melihat pesanan customer kedua
        $response = $this->actingAs($this->customer)
            ->get(route('customer.orders.show', $order));

        $response->assertStatus(403);
    }
}
```

---

## 3. Cara Menjalankan Tes
Jalankan di terminal:
```bash
php artisan test
# Atau spesifik ke fitur pemesanan makanan:
php artisan test --filter FoodOrderingTest
```
