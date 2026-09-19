---
name: food-ui-components
description: Tailwind CSS v4 and Blade component patterns for food ordering and delivery applications. Covers restaurant cards, appetizing food item cards, floating cart bar, order status timeline steppers, rating stars, and restaurant owner metrics dashboard. Use this skill whenever building or refining views, Blade templates, styling menu lists, checkout screens, or order tracking interfaces.
---

# Food Ordering UI & Blade Components (Tailwind CSS v4)

Koleksi pola desain antarmuka dan komponen Blade ramah mobile untuk aplikasi pemesanan makanan, terinspirasi dari standar aplikasi pengiriman makanan modern (GoFood, GrabFood, ShopeeFood).

---

## 1. Kartu Restoran (Restaurant Card Component)

Menampilkan identitas restoran, foto cover, rating bintang, status buka/tutup, dan estimasi waktu.

```blade
{{-- resources/views/components/restaurant-card.blade.php --}}
@props(['restaurant'])

<div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-gray-100 flex flex-col h-full">
    <!-- Cover Image & Status Badge -->
    <div class="relative h-44 w-full overflow-hidden bg-gray-100">
        @if($restaurant->image)
            <img src="{{ asset('storage/' . $restaurant->image) }}" 
                 alt="{{ $restaurant->name }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center bg-orange-50 text-orange-400 font-medium">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        @endif

        <!-- Status Open / Closed Badge -->
        <span class="absolute top-3 right-3 px-2.5 py-1 text-xs font-semibold rounded-full shadow-sm {{ $restaurant->is_open ? 'bg-emerald-500 text-white' : 'bg-gray-800/80 text-white backdrop-blur-xs' }}">
            {{ $restaurant->is_open ? 'Buka' : 'Tutup' }}
        </span>

        <!-- Rating Pill -->
        <div class="absolute bottom-3 left-3 bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-full text-xs font-bold text-gray-800 flex items-center gap-1 shadow-sm">
            <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span>{{ number_format($restaurant->average_rating, 1) }}</span>
        </div>
    </div>

    <!-- Body Information -->
    <div class="p-4 flex flex-col flex-1">
        <h3 class="font-bold text-gray-900 text-lg leading-snug group-hover:text-orange-600 transition-colors">
            {{ $restaurant->name }}
        </h3>
        <p class="text-xs text-gray-500 mt-1 line-clamp-1">
            {{ $restaurant->address }}
        </p>

        <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                20-35 Menit
            </span>
            <a href="{{ route('restaurants.show', $restaurant) }}" class="text-orange-600 font-semibold hover:underline">
                Lihat Menu &rarr;
            </a>
        </div>
    </div>
</div>
```

---

## 2. Kartu Menu Makanan (Food Item Card)

Dirancang agar menu terlihat menggugah selera dengan harga yang jelas, status stok, dan tombol tambah ke keranjang.

```blade
{{-- resources/views/components/food-card.blade.php --}}
@props(['product'])

<div class="bg-white rounded-xl border border-gray-100 p-3.5 flex gap-4 hover:border-orange-200 transition-all shadow-xs">
    <!-- Menu Photo -->
    <div class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-xl overflow-hidden bg-gray-50 shrink-0">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center bg-orange-50 text-orange-400 font-bold text-xs">
                Food Image
            </div>
        @endif

        @if(!$product->is_available || $product->stock <= 0)
            <div class="absolute inset-0 bg-black/60 backdrop-blur-2xs flex items-center justify-center text-white text-xs font-bold uppercase tracking-wider">
                Habis
            </div>
        @endif
    </div>

    <!-- Menu Details & Action -->
    <div class="flex flex-col justify-between flex-1 min-w-0">
        <div>
            <h4 class="font-bold text-gray-900 text-base leading-tight truncate">
                {{ $product->name }}
            </h4>
            <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                {{ $product->description ?? 'Dibuat dengan bahan segar dan bumbu pilihan.' }}
            </p>
        </div>

        <div class="flex items-center justify-between mt-2 pt-2">
            <span class="font-bold text-orange-600 text-base">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </span>

            @if($product->is_available && $product->stock > 0)
                <form action="{{ route('customer.cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" 
                            class="px-3 py-1.5 bg-orange-600 hover:bg-orange-700 active:scale-95 text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah
                    </button>
                </form>
            @else
                <span class="text-xs font-medium text-gray-400">Tidak Tersedia</span>
            @endif
        </div>
    </div>
</div>
```

---

## 3. Floating Bottom Cart Bar (Keranjang Melayang Mobile)

Komponen penting pada food ordering agar customer selalu mengetahui jumlah item dan total belanja tanpa perlu scroll kembali ke atas.

```blade
{{-- resources/views/components/floating-cart.blade.php --}}
@if($cart && $cart->items->count() > 0)
<div class="fixed bottom-4 inset-x-4 max-w-xl mx-auto z-40">
    <div class="bg-gray-900 text-white rounded-2xl p-3.5 shadow-xl flex items-center justify-between border border-gray-800 backdrop-blur-md">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                {{ $cart->items->sum('quantity') }}
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Pesanan</p>
                <p class="text-base font-bold text-white">
                    Rp {{ number_format($cart->total, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <a href="{{ route('customer.cart.index') }}" 
           class="px-5 py-2.5 bg-orange-600 hover:bg-orange-500 text-white font-bold text-sm rounded-xl transition-colors shadow-sm flex items-center gap-1.5">
            <span>Lihat Keranjang</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</div>
@endif
```

---

## 4. Order Tracking Stepper (Pelacak Status Pesanan Interaktif)

Visualisasi vertikal/horizontal langkah pesanan untuk halaman detail pesanan pelanggan:

```blade
{{-- resources/views/components/order-stepper.blade.php --}}
@props(['status'])

@php
    $steps = [
        'pending'    => ['title' => 'Menunggu Pembayaran / Konfirmasi', 'desc' => 'Pesanan telah dibuat dan menunggu pembayaran'],
        'diproses'   => ['title' => 'Sedang Dimasak', 'desc' => 'Restoran sedang menyiapkan hidangan lezat Anda'],
        'dikirim'    => ['title' => 'Dalam Pengantaran', 'desc' => 'Makanan sudah di jalan menuju alamat Anda'],
        'selesai'    => ['title' => 'Pesanan Tiba', 'desc' => 'Pesanan telah diterima. Selamat menikmati!'],
    ];

    $orderStatusFlow = ['pending', 'diproses', 'dikirim', 'selesai'];
    $currentIndex = array_search($status, $orderStatusFlow);
    $isCancelled = $status === 'dibatalkan';
@endphp

@if($isCancelled)
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl flex items-center gap-3">
        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <h4 class="font-bold">Pesanan Dibatalkan</h4>
            <p class="text-xs">Pesanan ini telah dibatalkan dan stok produk telah dikembalikan.</p>
        </div>
    </div>
@else
    <div class="py-4">
        <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
            @foreach($steps as $key => $step)
                @php
                    $stepIndex = array_search($key, $orderStatusFlow);
                    $isPassed = $stepIndex <= $currentIndex;
                    $isCurrent = $stepIndex === $currentIndex;
                @endphp
                <div class="relative flex items-start gap-3.5">
                    <span class="absolute -left-6 top-1 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors 
                        {{ $isPassed ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-300 text-transparent' }}
                        {{ $isCurrent ? 'ring-4 ring-orange-100' : '' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <div>
                        <h5 class="text-sm font-bold {{ $isPassed ? 'text-gray-900' : 'text-gray-400' }}">
                            {{ $step['title'] }}
                        </h5>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
```
