<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodOrderingTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $owner;
    protected User $admin;
    protected Restaurant $restaurant;
    protected Product $product;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Siapkan Akun Pengguna
        $this->admin = User::factory()->admin()->create();
        $this->owner = User::factory()->owner()->create();
        $this->customer = User::factory()->customer()->create();

        // 2. Siapkan Kategori
        $this->category = Category::create([
            'name' => 'Makanan Utama',
            'slug' => 'makanan-utama',
        ]);

        // 3. Siapkan Restoran
        $this->restaurant = Restaurant::create([
            'user_id'     => $this->owner->id,
            'name'        => 'Warung Sate Barokah',
            'slug'        => 'warung-sate-barokah',
            'description' => 'Sate kambing dan ayam khas Madura',
            'address'     => 'Jl. Merdeka No. 10',
            'is_open'     => true,
        ]);

        // 4. Siapkan Produk Menu
        $this->product = Product::create([
            'restaurant_id' => $this->restaurant->id,
            'category_id'   => $this->category->id,
            'name'          => 'Sate Ayam Madura 10 Tusuk',
            'slug'          => 'sate-ayam-madura-10-tusuk',
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
        // Masukkan item ke keranjang
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

        // Lakukan checkout
        $response = $this->actingAs($this->customer)
            ->post(route('customer.orders.store'), [
                'address'        => 'Jl. Sudirman Kav 5',
                'payment_method' => 'transfer',
            ]);

        // Verifikasi hasil pesanan
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
        // Stok 17 harus kembali dipulihkan menjadi 20
        $this->assertEquals(20, $this->product->fresh()->stock);
    }

    /** @test */
    public function customer_cannot_view_another_customers_order()
    {
        $otherCustomer = User::factory()->customer()->create();

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

    /** @test */
    public function customer_cannot_cancel_order_that_is_already_processing()
    {
        $order = Order::create([
            'user_id'        => $this->customer->id,
            'restaurant_id'  => $this->restaurant->id,
            'total_price'    => 50000,
            'status'         => 'diproses', // Status sudah diproses/dimasak
            'payment_method' => 'cod',
            'address'        => 'Jl. Thamrin No. 10',
        ]);

        $response = $this->actingAs($this->customer)
            ->post(route('customer.orders.cancel', $order));

        $response->assertSessionHas('error');
        $this->assertEquals('diproses', $order->fresh()->status);
    }

    /** @test */
    public function cannot_add_items_from_different_restaurants_to_same_cart()
    {
        // Restoran kedua
        $owner2 = User::factory()->owner()->create();
        $restaurant2 = Restaurant::create([
            'user_id' => $owner2->id,
            'name'    => 'Kedai Kopi Nusantara',
            'slug'    => 'kedai-kopi-nusantara',
            'address' => 'Jl. Tebet Raya No. 5',
            'is_open' => true,
        ]);
        $product2 = Product::create([
            'restaurant_id' => $restaurant2->id,
            'name'          => 'Kopi Susu Gula Aren',
            'price'         => 18000,
            'stock'         => 50,
            'is_available'  => true,
        ]);

        // Masukkan item restoran 1 ke keranjang
        $this->actingAs($this->customer)
            ->post(route('customer.cart.add'), [
                'product_id' => $this->product->id,
                'quantity'   => 1,
            ]);

        // Coba masukkan item dari restoran 2 ke keranjang yang sama
        $response = $this->actingAs($this->customer)
            ->post(route('customer.cart.add'), [
                'product_id' => $product2->id,
                'quantity'   => 1,
            ]);

        $response->assertSessionHas('error');
        $cart = Cart::where('user_id', $this->customer->id)->first();
        $this->assertEquals($this->restaurant->id, $cart->restaurant_id);
    }

    /** @test */
    public function cannot_add_product_to_cart_if_stock_is_insufficient()
    {
        $response = $this->actingAs($this->customer)
            ->post(route('customer.cart.add'), [
                'product_id' => $this->product->id,
                'quantity'   => 999, // Melebihi stok yang tersedia (20)
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('carts', ['user_id' => $this->customer->id]);
    }

    /** @test */
    public function customer_cannot_access_owner_or_admin_dashboard()
    {
        $responseOwner = $this->actingAs($this->customer)->get(route('owner.dashboard'));
        $responseOwner->assertStatus(403);

        $responseAdmin = $this->actingAs($this->customer)->get(route('admin.dashboard'));
        $responseAdmin->assertStatus(403);
    }

    /** @test */
    public function owner_can_verify_payment_and_order_transitions_to_processing()
    {
        $order = Order::create([
            'user_id'        => $this->customer->id,
            'restaurant_id'  => $this->restaurant->id,
            'total_price'    => 50000,
            'status'         => 'pending',
            'payment_method' => 'transfer',
            'address'        => 'Jl. Mangga No. 7',
        ]);

        Payment::create([
            'order_id'       => $order->id,
            'payment_status' => 'pending',
        ]);

        $response = $this->actingAs($this->owner)
            ->post(route('owner.orders.verifyPayment', $order), [
                'status' => 'paid',
            ]);

        $response->assertSessionHas('success');
        $this->assertEquals('paid', $order->payment->fresh()->payment_status);
        $this->assertEquals('diproses', $order->fresh()->status);
    }

    /** @test */
    public function customer_cannot_review_restaurant_without_completed_order()
    {
        $response = $this->actingAs($this->customer)
            ->post(route('customer.reviews.store'), [
                'restaurant_id' => $this->restaurant->id,
                'product_id'    => $this->product->id,
                'rating'        => 5,
                'comment'       => 'Makanannya enak sekali!',
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('reviews', 0);
    }

    /** @test */
    public function customer_can_review_restaurant_after_completed_order()
    {
        // Buat pesanan dengan status 'selesai'
        Order::create([
            'user_id'        => $this->customer->id,
            'restaurant_id'  => $this->restaurant->id,
            'total_price'    => 50000,
            'status'         => 'selesai',
            'payment_method' => 'transfer',
            'address'        => 'Jl. Mangga No. 7',
        ]);

        $response = $this->actingAs($this->customer)
            ->post(route('customer.reviews.store'), [
                'restaurant_id' => $this->restaurant->id,
                'product_id'    => $this->product->id,
                'rating'        => 5,
                'comment'       => 'Sate ayamnya sangat empuk dan bumbu kacangnya juara!',
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'user_id'       => $this->customer->id,
            'restaurant_id' => $this->restaurant->id,
            'product_id'    => $this->product->id,
            'rating'        => 5,
        ]);
    }
}
