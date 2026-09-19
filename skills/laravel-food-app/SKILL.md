---
name: laravel-food-app
description: Laravel 12 development guide, database migration schemas, Eloquent relationship patterns, controllers, authorization middleware, and bug audit checklist for this food ordering project. Use this skill whenever creating or editing models, controllers, migrations, seeders, or fixing authentication and authorization issues in the food ordering app.
---

# Laravel 12 Food Ordering Application Guide

Panduan arsitektur, skema database, relasi Eloquent, dan best practices untuk aplikasi pemesanan makanan berbasis Laravel 12.

---

## 1. Skema Database Lengkap (Database Schema Architecture)

Aplikasi memiliki 10 tabel utama dengan relasi terintegrasi:

```
                  ┌──────────────┐
                  │    users     │ (admin, owner, customer)
                  └──────┬───────┘
          ┌──────────────┼──────────────┐
          │ (owns)       │ (orders)     │ (reviews)
          ▼              ▼              ▼
   ┌─────────────┐ ┌───────────┐  ┌───────────┐
   │ restaurants │ │  orders   │  │  reviews  │
   └──────┬──────┘ └─────┬─────┘  └─────▲─────┘
          │              │              │
          │ (has many)   │ (has many)   │
          ▼              ▼              │
    ┌───────────┐  ┌───────────┐        │
    │ products  │◄─┤order_items│        │
    └─────▲─────┘  └───────────┘        │
          │                             │
          │ (categorized by)            │
    ┌─────┴─────┐                       │
    │categories │                       │
    └───────────┘                       │
                                        │
    ┌───────────┐  ┌───────────┐        │
    │   carts   ├──►cart_items ├────────┘
    └───────────┘  └───────────┘
```

### Template Migration Siap Pakai

#### 1. Modifikasi Tabel Users (`database/migrations/..._add_food_fields_to_users_table.php`)
```php
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['admin', 'owner', 'customer'])->default('customer')->after('password');
    $table->string('phone')->nullable()->after('role');
    $table->text('address')->nullable()->after('phone');
    $table->string('avatar')->nullable()->after('address');
});
```

#### 2. Tabel Categories (`database/migrations/..._create_categories_table.php`)
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('icon')->nullable();
    $table->string('image')->nullable();
    $table->timestamps();
});
```

#### 3. Tabel Restaurants (`database/migrations/..._create_restaurants_table.php`)
```php
Schema::create('restaurants', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->text('address');
    $table->string('phone')->nullable();
    $table->string('image')->nullable();
    $table->boolean('is_open')->default(true);
    $table->time('open_time')->nullable();
    $table->time('close_time')->nullable();
    $table->timestamps();
});
```

#### 4. Tabel Products (`database/migrations/..._create_products_table.php`)
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
    $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
    $table->string('name');
    $table->string('slug')->nullable();
    $table->text('description')->nullable();
    $table->decimal('price', 12, 2);
    $table->integer('stock')->default(0);
    $table->string('image')->nullable();
    $table->boolean('is_available')->default(true);
    $table->timestamps();
});
```

#### 5. Tabel Carts & Cart Items (`database/migrations/..._create_carts_tables.php`)
```php
Schema::create('carts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
    $table->timestamps();
});

Schema::create('cart_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
    $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
    $table->integer('quantity')->default(1);
    $table->decimal('price', 12, 2);
    $table->string('notes')->nullable();
});
```

#### 6. Tabel Orders, Order Items & Payments (`database/migrations/..._create_orders_tables.php`)
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
    $table->decimal('total_price', 12, 2);
    $table->decimal('delivery_fee', 12, 2)->default(0);
    $table->decimal('service_fee', 12, 2)->default(0);
    $table->enum('status', ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('pending');
    $table->enum('payment_method', ['transfer', 'cod', 'ewallet'])->default('transfer');
    $table->text('address');
    $table->text('notes')->nullable();
    $table->timestamps();
});

Schema::create('order_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
    $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
    $table->integer('quantity')->default(1);
    $table->decimal('price', 12, 2);
    $table->string('notes')->nullable();
    $table->timestamps();
});

Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
    $table->string('payment_proof')->nullable();
    $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();
});
```

#### 7. Tabel Reviews (`database/migrations/..._create_reviews_table.php`)
```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
    $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
    $table->tinyInteger('rating')->unsigned(); // 1 to 5
    $table->text('comment')->nullable();
    $table->timestamps();
});
```

---

## 2. Standar Relasi Eloquent (Eloquent Relationships)

Pastikan Model menggunakan penamaan PascalCase standar dan relasi lengkap:

- **`User`**:
  - `hasOne(Restaurant::class)`
  - `hasMany(Order::class)`
  - `hasMany(Cart::class)`
  - `hasMany(Review::class)`
  - Helper methods: `isAdmin()`, `isOwner()`, `isCustomer()`

- **`Restaurant`**:
  - `belongsTo(User::class)`
  - `hasMany(Product::class)`
  - `hasMany(Order::class)`
  - `hasMany(Review::class)`
  - Attribute: `average_rating` -> `$this->reviews()->avg('rating') ?? 0;`

- **`Product`**:
  - `belongsTo(Restaurant::class)`
  - `belongsTo(Category::class)`
  - `hasMany(OrderItem::class)`
  - `hasMany(Review::class)`

- **`Order`**:
  - `belongsTo(User::class)`
  - `belongsTo(Restaurant::class)`
  - `hasMany(OrderItem::class)`
  - `hasOne(Payment::class)`

---

## 3. Pencegahan N+1 Query Problem (Eager Loading)

Dalam aplikasi pemesanan makanan, query daftar restoran dan riwayat pesanan rawan menyebabkan N+1 queries. Selalu gunakan Eager Loading:

```php
// BAIK: Mencegah puluhan query tambahan
$orders = Order::where('user_id', Auth::id())
    ->with(['restaurant', 'items.product', 'payment'])
    ->latest()
    ->paginate(10);

$restaurants = Restaurant::with(['products' => function($q) {
        $q->where('is_available', true)->take(6);
    }])
    ->withAvg('reviews', 'rating')
    ->withCount('reviews')
    ->where('is_open', true)
    ->paginate(12);
```

---

## 4. Checklist Audit Bug Umum pada Proyek Ini
1. **Periksa Otorisasi User pada Order**:
   - ❌ Salah: `if ($order->id != Auth::id()) abort(403);`
   - ✅ Benar: `if ($order->user_id !== Auth::id()) abort(403);`
2. **Kesesuaian Nama Method Controller & Route**:
   - Cek route `orders.payment`: memanggil `uploadPayment`, pastikan method di `OrderController` dieja `uploadPayment` (bukan `uplaodPayment`).
3. **Storage Symlink untuk Bukti Pembayaran & Foto Menu**:
   - Jalankan `php artisan storage:link` agar file bukti transfer di `storage/app/public` dapat diakses via `asset('storage/...')`.
4. **Validasi Role Middleware**:
   - Pastikan middleware `role` terdaftar di `bootstrap/app.php` pada Laravel 12.
