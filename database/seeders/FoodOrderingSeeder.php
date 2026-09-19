<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FoodOrderingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -------------------------------------------------------------
        // 1. PENGGUNA DEMO (ADMIN, RESTAURANT OWNERS, CUSTOMERS)
        // -------------------------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@food.test'],
            [
                'name'     => 'Administrator Kuliner',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '081122334455',
                'address'  => 'Kantor Pusat FoodApp, Gedung Cyber 2 Lt. 15, Jakarta Selatan',
            ]
        );

        $ownerPadang = User::firstOrCreate(
            ['email' => 'owner@food.test'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role'     => 'owner',
                'phone'    => '081234567890',
                'address'  => 'Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan',
            ]
        );

        $ownerKopi = User::firstOrCreate(
            ['email' => 'kopi@food.test'],
            [
                'name'     => 'Siti Rahmawati',
                'password' => Hash::make('password'),
                'role'     => 'owner',
                'phone'    => '081398765432',
                'address'  => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
            ]
        );

        $ownerBebek = User::firstOrCreate(
            ['email' => 'bebek@food.test'],
            [
                'name'     => 'H. Achmad Madura',
                'password' => Hash::make('password'),
                'role'     => 'owner',
                'phone'    => '081288990011',
                'address'  => 'Jl. Margonda Raya No. 150, Depok',
            ]
        );

        $customerAndi = User::firstOrCreate(
            ['email' => 'customer@food.test'],
            [
                'name'     => 'Andi Pratama',
                'password' => Hash::make('password'),
                'role'     => 'customer',
                'phone'    => '085711223344',
                'address'  => 'Apartemen Casablanca Tower B No. 102, Jakarta Selatan',
            ]
        );

        $customerBudi = User::firstOrCreate(
            ['email' => 'budi@food.test'],
            [
                'name'     => 'Budi Wijaya',
                'password' => Hash::make('password'),
                'role'     => 'customer',
                'phone'    => '085877665544',
                'address'  => 'Jl. Tebet Barat Dalam VII No. 24, Jakarta Selatan',
            ]
        );

        $customerDewi = User::firstOrCreate(
            ['email' => 'dewi@food.test'],
            [
                'name'     => 'Dewi Lestari',
                'password' => Hash::make('password'),
                'role'     => 'customer',
                'phone'    => '085933445566',
                'address'  => 'Cluster Harmoni Indah Blok C3, Bintaro Jaya',
            ]
        );

        // -------------------------------------------------------------
        // 2. KATEGORI KULINER NUSANTARA
        // -------------------------------------------------------------
        $categoriesData = [
            [
                'name'        => 'Makanan Berat',
                'slug'        => 'makanan-berat',
                'description' => 'Aneka hidangan nasi lezat, lauk pauk komplit, dan sajian utama mengenyangkan.',
            ],
            [
                'name'        => 'Ayam & Bebek',
                'slug'        => 'ayam-bebek',
                'description' => 'Ayam bakar, goreng, geprek, bebek kremes rempah gurih khas daerah.',
            ],
            [
                'name'        => 'Minuman & Kopi',
                'slug'        => 'minuman-kopi',
                'description' => 'Kopi kekinian, espresso blend, teh melati segar, dan jus buah murni.',
            ],
            [
                'name'        => 'Camilan & Snack',
                'slug'        => 'camilan-snack',
                'description' => 'Pastry, roti panggang, pisang goreng madu, dan kudapan ringan.',
            ],
            [
                'name'        => 'Aneka Sambal',
                'slug'        => 'aneka-sambal',
                'description' => 'Sambal terasi, sambal ijo Padang, sambal matah, dan sambal pencit mangga.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // -------------------------------------------------------------
        // 3. RESTORAN 1: PADANG SALERO BUNDO
        // -------------------------------------------------------------
        $padang = Restaurant::firstOrCreate(
            ['slug' => 'padang-salero-bundo'],
            [
                'user_id'     => $ownerPadang->id,
                'name'        => 'Restoran Padang Salero Bundo',
                'description' => 'Masakan Padang asli Bukittinggi dengan racikan bumbu rempah warisan leluhur. Rendang juara terempuk, gulai tunjang gurih, dan ayam pop khas Minang.',
                'address'     => 'Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan',
                'phone'       => '021-7201928',
                'is_open'     => true,
            ]
        );

        $menuPadang = [
            [
                'name'         => 'Rendang Daging Sapi Spesial',
                'slug'         => 'rendang-daging-sapi-spesial',
                'description'  => 'Daging sapi gandik pilihan dimasak perlahan 8 jam dengan santan kental kelapa parut dan 16 rempah Minang authentic.',
                'price'        => 28000,
                'stock'        => 60,
                'is_available' => true,
                'category_id'  => $categories['makanan-berat']->id,
            ],
            [
                'name'         => 'Ayam Pop Gurih Sambal Merah',
                'slug'         => 'ayam-pop-gurih-sambal-merah',
                'description'  => 'Ayam pejantan empuk dengan cita rasa gurih air kelapa khas Padang, disajikan hangat dengan sambal lado merah harum.',
                'price'        => 24000,
                'stock'        => 45,
                'is_available' => true,
                'category_id'  => $categories['ayam-bebek']->id,
            ],
            [
                'name'         => 'Gulai Tunjang Kikil Empuk',
                'slug'         => 'gulai-tunjang-kikil-empuk',
                'description'  => 'Kikil tunjang sapi empuk kenyal berpadu kuah santan kuning kental kaya kunyit, serai, dan asam kandis.',
                'price'        => 30000,
                'stock'        => 30,
                'is_available' => true,
                'category_id'  => $categories['makanan-berat']->id,
            ],
            [
                'name'         => 'Telur Dadar Barendo Crispy',
                'slug'         => 'telur-dadar-barendo-crispy',
                'description'  => 'Telur dadar bebek tebal dengan renda krispi keemasan, kaya irisan daun bawang dan cabai rawit.',
                'price'        => 12000,
                'stock'        => 80,
                'is_available' => true,
                'category_id'  => $categories['makanan-berat']->id,
            ],
            [
                'name'         => 'Es Teh Manis Melati Segar',
                'slug'         => 'es-teh-manis-melati-segar',
                'description'  => 'Teh seduh wangi melati alami dengan sirup gula tebu asli dan es batu dingin menyegarkan.',
                'price'        => 6000,
                'stock'        => 150,
                'is_available' => true,
                'category_id'  => $categories['minuman-kopi']->id,
            ],
            [
                'name'         => 'Es Jeruk Peras Murni',
                'slug'         => 'es-jeruk-peras-murni',
                'description'  => 'Perasan jeruk Pontianak segar alami, manis asam pas, kaya vitamin C.',
                'price'        => 9000,
                'stock'        => 100,
                'is_available' => true,
                'category_id'  => $categories['minuman-kopi']->id,
            ],
        ];

        $padangProducts = [];
        foreach ($menuPadang as $menu) {
            $padangProducts[$menu['slug']] = Product::firstOrCreate(
                ['restaurant_id' => $padang->id, 'name' => $menu['name']],
                $menu
            );
        }

        // -------------------------------------------------------------
        // 4. RESTORAN 2: KOPI SENJA BAHAGIA
        // -------------------------------------------------------------
        $kopi = Restaurant::firstOrCreate(
            ['slug' => 'kopi-senja-bahagia'],
            [
                'user_id'     => $ownerKopi->id,
                'name'        => 'Kopi Senja Bahagia',
                'description' => 'Kedai kopi modern spesialis Kopi Susu Gula Aren organik, pastry flaky mentega Prancis, dan camilan teman ngobrol santai.',
                'address'     => 'Jl. Kemang Raya No. 12, Jakarta Selatan',
                'phone'       => '021-7192834',
                'is_open'     => true,
            ]
        );

        $menuKopi = [
            [
                'name'         => 'Kopi Susu Gula Aren Signature',
                'slug'         => 'kopi-susu-gula-aren-signature',
                'description'  => 'Double shot espresso blend Arabica Aceh Gayo dan Robusta Dampit, susu segar creamy, dan sirup aren nira murni.',
                'price'        => 22000,
                'stock'        => 100,
                'is_available' => true,
                'category_id'  => $categories['minuman-kopi']->id,
            ],
            [
                'name'         => 'Americano Iced Double Shot',
                'slug'         => 'americano-iced-double-shot',
                'description'  => 'Espresso dingin murni tanpa gula dengan notes cokelat hitam dan karamel yang tegas.',
                'price'        => 18000,
                'stock'        => 80,
                'is_available' => true,
                'category_id'  => $categories['minuman-kopi']->id,
            ],
            [
                'name'         => 'Matcha Latte Creamy Uji Kyoto',
                'slug'         => 'matcha-latte-creamy-uji-kyoto',
                'description'  => 'Bubuk matcha murni dari Uji Kyoto dipadukan susu segar lembut, harum dan manis seimbang.',
                'price'        => 25000,
                'stock'        => 50,
                'is_available' => true,
                'category_id'  => $categories['minuman-kopi']->id,
            ],
            [
                'name'         => 'Butter Croissant Crispy',
                'slug'         => 'butter-croissant-crispy',
                'description'  => 'Croissant lapis renyah mentega Elle & Vire Prancis, garing di luar dan sangat lembut di dalam.',
                'price'        => 26000,
                'stock'        => 35,
                'is_available' => true,
                'category_id'  => $categories['camilan-snack']->id,
            ],
            [
                'name'         => 'Korean Garlic Cream Cheese Bread',
                'slug'         => 'korean-garlic-cream-cheese-bread',
                'description'  => 'Roti brioche empuk dibelah bintang dengan isian cream cheese gurih dan celupan butter bawang putih wangi.',
                'price'        => 28000,
                'stock'        => 25,
                'is_available' => true,
                'category_id'  => $categories['camilan-snack']->id,
            ],
        ];

        $kopiProducts = [];
        foreach ($menuKopi as $menu) {
            $kopiProducts[$menu['slug']] = Product::firstOrCreate(
                ['restaurant_id' => $kopi->id, 'name' => $menu['name']],
                $menu
            );
        }

        // -------------------------------------------------------------
        // 5. RESTORAN 3: BEBEK SINJAY MADURA ASLI
        // -------------------------------------------------------------
        $bebek = Restaurant::firstOrCreate(
            ['slug' => 'bebek-sinjay-madura-asli'],
            [
                'user_id'     => $ownerBebek->id,
                'name'        => 'Bebek Sinjay Madura Asli',
                'description' => 'Pelopor bebek goreng bumbu hitam kremes Madura disajikan dengan sambal pencit mangga muda pedas segar menggugah selera.',
                'address'     => 'Jl. Margonda Raya No. 150, Pondok Cina, Depok',
                'phone'       => '021-7721890',
                'is_open'     => true,
            ]
        );

        $menuBebek = [
            [
                'name'         => 'Paket Bebek Sinjay Sambal Pencit',
                'slug'         => 'paket-bebek-sinjay-sambal-pencit',
                'description'  => 'Nasi putih pulen, 1 porsi bebek goreng kremes empuk tidak amis, taburan kremes bumbu gurih, dan sambal mangga muda.',
                'price'        => 38000,
                'stock'        => 50,
                'is_available' => true,
                'category_id'  => $categories['ayam-bebek']->id,
            ],
            [
                'name'         => 'Bebek Goreng Paha Rempah',
                'slug'         => 'bebek-goreng-paha-rempah',
                'description'  => 'Potongan paha bebek gemuk diungkep bumbu ketumbar jinten lalu digoreng garing renyah.',
                'price'        => 32000,
                'stock'        => 40,
                'is_available' => true,
                'category_id'  => $categories['ayam-bebek']->id,
            ],
            [
                'name'         => 'Ayam Kampung Goreng Kremes Sinjay',
                'slug'         => 'ayam-kampung-goreng-kremes-sinjay',
                'description'  => 'Ayam kampung asli gurih manis dengan remah kremesan khas Bangkalan Madura.',
                'price'        => 29000,
                'stock'        => 35,
                'is_available' => true,
                'category_id'  => $categories['ayam-bebek']->id,
            ],
            [
                'name'         => 'Sambal Mangga Pencit Ekstra',
                'slug'         => 'sambal-mangga-pencit-ekstra',
                'description'  => 'Sambal cabai rawit pedas nampol dipadu serutan mangga muda asam segar.',
                'price'        => 7000,
                'stock'        => 100,
                'is_available' => true,
                'category_id'  => $categories['aneka-sambal']->id,
            ],
            [
                'name'         => 'Es Kelapa Muda Gula Aren',
                'slug'         => 'es-kelapa-muda-gula-aren',
                'description'  => 'Daging kelapa muda murni dengan air kelapa segar dan lelehan gula aren wangi pandan.',
                'price'        => 12000,
                'stock'        => 60,
                'is_available' => true,
                'category_id'  => $categories['minuman-kopi']->id,
            ],
        ];

        $bebekProducts = [];
        foreach ($menuBebek as $menu) {
            $bebekProducts[$menu['slug']] = Product::firstOrCreate(
                ['restaurant_id' => $bebek->id, 'name' => $menu['name']],
                $menu
            );
        }

        // -------------------------------------------------------------
        // 6. SAMPLE TRANSAKSI / PESANAN REALISTIS (ORDERS & PAYMENTS)
        // -------------------------------------------------------------
        // Pesanan 1: Selesai (Customer: Andi Pratama, Restoran: Padang Salero Bundo)
        $order1 = Order::firstOrCreate(
            ['user_id' => $customerAndi->id, 'restaurant_id' => $padang->id, 'status' => 'selesai'],
            [
                'total_price'    => 68000,
                'payment_method' => 'transfer',
                'address'        => 'Apartemen Casablanca Tower B No. 102, Jakarta Selatan',
                'notes'          => 'Rendang tolong dibungkus daun pisang, kuah gulai dipisah ya.',
                'created_at'     => now()->subDays(2),
                'updated_at'     => now()->subDays(2)->addMinutes(45),
            ]
        );

        if ($order1->wasRecentlyCreated) {
            OrderItem::create([
                'order_id'   => $order1->id,
                'product_id' => $padangProducts['rendang-daging-sapi-spesial']->id,
                'quantity'   => 2,
                'price'      => 28000,
            ]);
            OrderItem::create([
                'order_id'   => $order1->id,
                'product_id' => $padangProducts['telur-dadar-barendo-crispy']->id,
                'quantity'   => 1,
                'price'      => 12000,
            ]);
            Payment::create([
                'order_id'       => $order1->id,
                'payment_proof'  => 'payment_proof/demo_transfer_andi.jpg',
                'payment_status' => 'paid',
                'paid_at'        => now()->subDays(2)->addMinutes(10),
            ]);
        }

        // Pesanan 2: Sedang Dikirim (Customer: Budi Wijaya, Restoran: Kopi Senja Bahagia)
        $order2 = Order::firstOrCreate(
            ['user_id' => $customerBudi->id, 'restaurant_id' => $kopi->id, 'status' => 'dikirim'],
            [
                'total_price'    => 70000,
                'payment_method' => 'ewallet',
                'address'        => 'Jl. Tebet Barat Dalam VII No. 24, Jakarta Selatan',
                'notes'          => 'Kopi gula aren less sweet (50%), es batu normal.',
                'created_at'     => now()->subHours(1),
                'updated_at'     => now()->subMinutes(15),
            ]
        );

        if ($order2->wasRecentlyCreated) {
            OrderItem::create([
                'order_id'   => $order2->id,
                'product_id' => $kopiProducts['kopi-susu-gula-aren-signature']->id,
                'quantity'   => 2,
                'price'      => 22000,
            ]);
            OrderItem::create([
                'order_id'   => $order2->id,
                'product_id' => $kopiProducts['butter-croissant-crispy']->id,
                'quantity'   => 1,
                'price'      => 26000,
            ]);
            Payment::create([
                'order_id'       => $order2->id,
                'payment_proof'  => 'payment_proof/demo_qris_budi.png',
                'payment_status' => 'paid',
                'paid_at'        => now()->subHours(1)->addMinutes(5),
            ]);
        }

        // Pesanan 3: Menunggu Konfirmasi (Customer: Dewi Lestari, Restoran: Bebek Sinjay)
        $order3 = Order::firstOrCreate(
            ['user_id' => $customerDewi->id, 'restaurant_id' => $bebek->id, 'status' => 'pending'],
            [
                'total_price'    => 45000,
                'payment_method' => 'cod',
                'address'        => 'Cluster Harmoni Indah Blok C3, Bintaro Jaya',
                'notes'          => 'Tolong sambal pencitnya yang banyak ya mas.',
                'created_at'     => now()->subMinutes(20),
                'updated_at'     => now()->subMinutes(20),
            ]
        );

        if ($order3->wasRecentlyCreated) {
            OrderItem::create([
                'order_id'   => $order3->id,
                'product_id' => $bebekProducts['paket-bebek-sinjay-sambal-pencit']->id,
                'quantity'   => 1,
                'price'      => 38000,
            ]);
            OrderItem::create([
                'order_id'   => $order3->id,
                'product_id' => $bebekProducts['sambal-mangga-pencit-ekstra']->id,
                'quantity'   => 1,
                'price'      => 7000,
            ]);
            Payment::create([
                'order_id'       => $order3->id,
                'payment_proof'  => null,
                'payment_status' => 'pending',
                'paid_at'        => null,
            ]);
        }

        // -------------------------------------------------------------
        // 7. SAMPLE ULASAN / RATING PELANGGAN (REVIEWS)
        // -------------------------------------------------------------
        $reviewsData = [
            [
                'user_id'       => $customerAndi->id,
                'restaurant_id' => $padang->id,
                'product_id'    => $padangProducts['rendang-daging-sapi-spesial']->id,
                'rating'        => 5,
                'comment'       => 'Rendangnya luar biasa lembut! Bumbunya meresap sampai ke serat terdalam. Sangat authentic rasa Padang Bukittinggi.',
            ],
            [
                'user_id'       => $customerAndi->id,
                'restaurant_id' => $padang->id,
                'product_id'    => $padangProducts['telur-dadar-barendo-crispy']->id,
                'rating'        => 5,
                'comment'       => 'Telur barendo-nya renyah banget pinggirannya dan tebal gurih. Porsi mantap!',
            ],
            [
                'user_id'       => $customerBudi->id,
                'restaurant_id' => $kopi->id,
                'product_id'    => $kopiProducts['kopi-susu-gula-aren-signature']->id,
                'rating'        => 5,
                'comment'       => 'Kopi susu terenak di Kemang. Paduan espresso dan arennya pas, tidak terlalu manis. Wajib coba!',
            ],
            [
                'user_id'       => $customerBudi->id,
                'restaurant_id' => $kopi->id,
                'product_id'    => $kopiProducts['butter-croissant-crispy']->id,
                'rating'        => 4,
                'comment'       => 'Croissant flaky dan buttery. Pas banget dicelup ke kopi susu hangat.',
            ],
            [
                'user_id'       => $customerDewi->id,
                'restaurant_id' => $bebek->id,
                'product_id'    => $bebekProducts['paket-bebek-sinjay-sambal-pencit']->id,
                'rating'        => 5,
                'comment'       => 'Bebeknya garing dan tidak ada bau amis sama sekali. Sambal pencit mangganya juara bikin nagih!',
            ],
        ];

        foreach ($reviewsData as $rev) {
            Review::firstOrCreate(
                [
                    'user_id'       => $rev['user_id'],
                    'restaurant_id' => $rev['restaurant_id'],
                    'product_id'    => $rev['product_id'],
                ],
                $rev
            );
        }
    }
}
