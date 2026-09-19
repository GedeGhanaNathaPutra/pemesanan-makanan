---
name: food-seeder-generator
description: Indonesian culinary database seeder generator with realistic restaurants (Padang Sederhana, Bebek Sinjay, Ayam Geprek, Kopi Kenangan), categories, appetizing menus with realistic IDR prices, Unsplash food images, and preconfigured test users (admin, owner, customer). Use this skill whenever populating demo data, generating database seeders, or setting up development testing data.
---

# Indonesian Culinary Database Seeder Generator

Skill ini menyediakan template data seeder siap pakai dengan kuliner nusantara yang realistis, lengkap dengan akun demo untuk setiap role (`admin`, `owner`, `customer`).

---

## 1. Akun Pengguna Demo (Demo Users)

| Role | Nama | Email | Password | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | Administrator Kuliner | `admin@food.test` | `password` | Mengelola platform, restoran, dan kategori |
| **Owner** | Budi Santoso (Mitra Restoran) | `owner@food.test` | `password` | Pemilik "Restoran Padang Salero Bundo" |
| **Owner 2** | Siti Rahma (Kedai Kopi) | `kopi@food.test` | `password` | Pemilik "Kopi Senja Bahagia" |
| **Customer** | Andi Pratama | `customer@food.test` | `password` | Pembeli aktif dengan saldo dan keranjang |

---

## 2. Kode Seeder Siap Pakai (`database/seeders/FoodOrderingSeeder.php`)

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FoodOrderingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pengguna
        $admin = User::firstOrCreate(['email' => 'admin@food.test'], [
            'name'     => 'Administrator',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '081122334455',
            'address'  => 'Kantor Pusat FoodApp, Jakarta',
        ]);

        $ownerPadang = User::firstOrCreate(['email' => 'owner@food.test'], [
            'name'     => 'Budi Salero',
            'password' => Hash::make('password'),
            'role'     => 'owner',
            'phone'    => '081234567890',
            'address'  => 'Jl. Sudirman No. 45, Jakarta Selatan',
        ]);

        $ownerKopi = User::firstOrCreate(['email' => 'kopi@food.test'], [
            'name'     => 'Siti Rahmawati',
            'password' => Hash::make('password'),
            'role'     => 'owner',
            'phone'    => '081398765432',
            'address'  => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
        ]);

        $customer = User::firstOrCreate(['email' => 'customer@food.test'], [
            'name'     => 'Andi Pratama',
            'password' => Hash::make('password'),
            'role'     => 'customer',
            'phone'    => '085711223344',
            'address'  => 'Apartemen Casablanca Tower B No. 102, Jakarta',
        ]);

        // 2. Kategori Kuliner
        $categories = [
            ['name' => 'Makanan Berat', 'slug' => 'makanan-berat'],
            ['name' => 'Ayam & Bebek',   'slug' => 'ayam-bebek'],
            ['name' => 'Minuman & Kopi', 'slug' => 'minuman-kopi'],
            ['name' => 'Camilan & Snack','slug' => 'camilan-snack'],
            ['name' => 'Aneka Sambal',   'slug' => 'aneka-sambal'],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['slug']] = Category::firstOrCreate(['slug' => $cat['slug']], [
                'name' => $cat['name']
            ]);
        }

        // 3. Restoran 1: Padang Salero Bundo
        $padang = Restaurant::firstOrCreate(['slug' => 'padang-salero-bundo'], [
            'user_id'     => $ownerPadang->id,
            'name'        => 'Restoran Padang Salero Bundo',
            'description' => 'Masakan Padang asli Bukittinggi dengan bumbu rempah warisan leluhur. Rendang juara, gulai tunjang, dan ayam pop khas.',
            'address'     => 'Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan',
            'phone'       => '021-7201928',
            'is_open'     => true,
            'open_time'   => '08:00',
            'close_time'  => '22:00',
        ]);

        $menuPadang = [
            [
                'name'         => 'Rendang Daging Sapi Spesial',
                'description'  => 'Daging sapi pilihan dimasak perlahan 8 jam dengan santan kental dan rempah Minang authentic.',
                'price'        => 28000,
                'stock'        => 50,
                'is_available' => true,
                'category_id'  => $categoryModels['makanan-berat']->id,
            ],
            [
                'name'         => 'Ayam Pop Gurih Sambal Merah',
                'description'  => 'Ayam pejantan empuk dengan cita rasa gurih khas Padang disajikan dengan sambal lado merah.',
                'price'        => 24000,
                'stock'        => 40,
                'is_available' => true,
                'category_id'  => $categoryModels['ayam-bebek']->id,
            ],
            [
                'name'         => 'Gulai Tunjang Kikil Empuk',
                'description'  => 'Kikil sapi empuk kenyal berpadu kuah santan kuning kental kaya rempah.',
                'price'        => 30000,
                'stock'        => 25,
                'is_available' => true,
                'category_id'  => $categoryModels['makanan-berat']->id,
            ],
            [
                'name'         => 'Es Teh Manis Segar',
                'description'  => 'Teh seduh wangi melati dengan gula tebu asli dan es batu dingin.',
                'price'        => 6000,
                'stock'        => 100,
                'is_available' => true,
                'category_id'  => $categoryModels['minuman-kopi']->id,
            ],
        ];

        foreach ($menuPadang as $menu) {
            Product::firstOrCreate(
                ['restaurant_id' => $padang->id, 'name' => $menu['name']],
                array_merge($menu, ['slug' => Str::slug($menu['name'])])
            );
        }

        // 4. Restoran 2: Kopi Senja Bahagia
        $kopi = Restaurant::firstOrCreate(['slug' => 'kopi-senja-bahagia'], [
            'user_id'     => $ownerKopi->id,
            'name'        => 'Kopi Senja Bahagia',
            'description' => 'Spesialis Kopi Susu Gula Aren, Croissant renyah, dan aneka artisan toast.',
            'address'     => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
            'phone'       => '021-7192834',
            'is_open'     => true,
            'open_time'   => '07:00',
            'close_time'  => '23:00',
        ]);

        $menuKopi = [
            [
                'name'         => 'Kopi Susu Gula Aren Signature',
                'description'  => 'Espresso double shot blend Arabica-Robusta dengan susu segar dan sirup aren organik.',
                'price'        => 22000,
                'stock'        => 80,
                'is_available' => true,
                'category_id'  => $categoryModels['minuman-kopi']->id,
            ],
            [
                'name'         => 'Butter Croissant Crispy',
                'description'  => 'Croissant flaky berlapis mentega Prancis asli, wangi dan renyah luar dalam.',
                'price'        => 26000,
                'stock'        => 30,
                'is_available' => true,
                'category_id'  => $categoryModels['camilan-snack']->id,
            ],
        ];

        foreach ($menuKopi as $menu) {
            Product::firstOrCreate(
                ['restaurant_id' => $kopi->id, 'name' => $menu['name']],
                array_merge($menu, ['slug' => Str::slug($menu['name'])])
            );
        }
    }
}
```

---

## 3. Cara Menjalankan Seeder
Tambahkan pemanggilan seeder pada `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call([
        FoodOrderingSeeder::class,
    ]);
}
```

Lalu jalankan di terminal:
```bash
php artisan db:seed
# Atau jika ingin refresh database total:
php artisan migrate:fresh --seed
```
