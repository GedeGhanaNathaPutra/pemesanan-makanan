@extends('layouts.app')
@section('title', 'Detail Pesanan #' . $order->id . ' – FoodOrder')
@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
    {{-- Header & Back button --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('customer.orders.index') }}" class="btn btn-light btn-sm p-2 d-inline-flex align-items-center rounded-circle" title="Kembali ke Daftar Pesanan" style="width: 42px; height: 42px; justify-content: center;">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 1px;">Status Pesanan</span>
                <h4 class="fw-extrabold mb-0 text-dark">Pesanan #{{ $order->id }}</h4>
                <small class="text-muted">{{ $order->created_at?->format('d M Y, H:i') }} WIB</small>
            </div>
        </div>
        <span class="badge badge-{{ $order->status }} px-3.5 py-2 rounded-pill fs-6">
            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>{{ $order->status_label }}
        </span>
    </div>

    {{-- ORDER STATUS TRACKING STEPPER (Liquid Glass Stepper) --}}
    <div class="card border-0 rounded-4 p-4 mb-4">
        <h6 class="fw-extrabold text-dark mb-3">
            <i class="bi bi-clock-history me-1.5 text-primary"></i>Status Pelacakan Pesanan
        </h6>

        @if($order->status === 'dibatalkan')
            <div class="p-3.5 rounded-4 d-flex align-items-center gap-3 border-0 mb-0" style="background: rgba(254, 226, 226, 0.85); backdrop-filter: blur(14px); color: #991b1b; border: 1px solid rgba(255, 255, 255, 0.85);">
                <i class="bi bi-x-circle-fill fs-2 text-danger"></i>
                <div>
                    <h6 class="fw-bold mb-0">Pesanan Telah Dibatalkan</h6>
                    <p class="small mb-0">Pesanan ini telah dibatalkan dan stok produk telah dipulihkan kembali ke restoran mitra.</p>
                </div>
            </div>
        @else
            @php
                $orderSteps = [
                    'pending'  => ['title' => 'Menunggu Pembayaran', 'desc' => 'Pesanan dibuat & menunggu konfirmasi pembayaran'],
                    'diproses' => ['title' => 'Sedang Dimasak', 'desc' => 'Restoran sedang menyiapkan hidangan segar Anda'],
                    'dikirim'  => ['title' => 'Dalam Pengantaran', 'desc' => 'Kurir sedang mengantar pesanan ke alamat Anda'],
                    'selesai'  => ['title' => 'Pesanan Selesai', 'desc' => 'Pesanan telah diterima, selamat menikmati!'],
                ];
                $stepsKeys = ['pending', 'diproses', 'dikirim', 'selesai'];
                $currentIdx = array_search($order->status, $stepsKeys);
            @endphp

            <div class="row g-3 text-center text-md-start">
                @foreach($orderSteps as $k => $step)
                    @php
                        $stepIdx = array_search($k, $stepsKeys);
                        $isPassed = $stepIdx <= $currentIdx;
                        $isCurrent = $stepIdx === $currentIdx;
                    @endphp
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-4 h-100 position-relative {{ $isCurrent ? 'bg-white' : '' }}" style="{{ $isCurrent ? 'background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 2px solid var(--liquid-primary); box-shadow: 0 10px 25px -5px rgba(234,88,12,0.3);' : ($isPassed ? 'background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.85);' : 'background: rgba(255, 255, 255, 0.35); opacity: 0.6; border: 1px solid rgba(255, 255, 255, 0.5);') }}">
                            <div class="d-flex align-items-center gap-2 mb-1.5 justify-content-center justify-content-md-start">
                                <span class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold position-relative flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem; background: {{ $isPassed ? 'linear-gradient(135deg, #ff5722, #ea580c)' : '#94a3b8' }}; box-shadow: {{ $isPassed ? '0 3px 8px rgba(234,88,12,0.35)' : 'none' }};">
                                    @if($isPassed && !$isCurrent)
                                        <i class="bi bi-check-lg"></i>
                                    @elseif($isCurrent)
                                        <span class="spinner-grow spinner-grow-sm position-absolute text-white" style="width: 28px; height: 28px; opacity: 0.35;" role="status"></span>
                                        <i class="bi bi-circle-fill" style="font-size: 0.55rem;"></i>
                                    @else
                                        {{ $stepIdx + 1 }}
                                    @endif
                                </span>
                                <h6 class="fw-bold mb-0 small text-dark">{{ $step['title'] }}</h6>
                            </div>
                            <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $step['desc'] }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="row g-4">
        {{-- Order Items Section --}}
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 overflow-hidden mb-4">
                <div class="py-3 px-4 border-bottom d-flex align-items-center justify-content-between" style="background: rgba(255, 255, 255, 0.5);">
                    <div>
                        <h6 class="fw-extrabold mb-0 text-dark">{{ $order->restaurant->name ?? 'Restoran' }}</h6>
                        <small class="text-muted">{{ $order->items->count() }} item dipesan</small>
                    </div>
                    @if($order->restaurant)
                        <a href="{{ route('restaurants.show', $order->restaurant) }}" class="btn btn-light btn-sm rounded-pill px-3.5 fw-bold">
                            Kunjungi Restoran <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @endif
                </div>

                <div class="p-0">
                    @foreach($order->items as $item)
                    <div class="p-3.5 px-4 border-bottom d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3 min-width-0">
                            <div class="rounded-4 overflow-hidden flex-shrink-0" style="width: 58px; height: 58px; border: 1px solid rgba(255, 255, 255, 0.85); box-shadow: 0 4px 10px rgba(15, 23, 42, 0.04);">
                                @if($item->product?->image)
                                    <img src="{{ asset('storage/'.$item->product->image) }}" class="w-100 h-100 object-fit-cover" alt="">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(255, 242, 237, 0.8), rgba(255, 224, 211, 0.8));">
                                        <i class="bi bi-egg-fried text-primary fs-5"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-width-0">
                                <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $item->product->name ?? 'Menu Dihapus' }}</h6>
                                <small class="text-muted">Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }} porsi</small>
                            </div>
                        </div>
                        <div class="fw-extrabold text-dark text-end flex-shrink-0">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach

                    <div class="p-4 d-flex justify-content-between align-items-center" style="background: rgba(255, 255, 255, 0.4);">
                        <span class="fw-bold text-dark">Total Pembayaran</span>
                        <span class="fw-extrabold fs-4" style="color: var(--liquid-primary);">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- PAYMENT PROOF SECTION (For Transfer or E-Wallet) --}}
            @if(in_array($order->status, ['pending', 'diproses']) && $order->payment_method !== 'cod')
            <div class="card border-0 rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-credit-card text-primary fs-5"></i>
                    <h5 class="fw-extrabold text-dark mb-0">Petunjuk Pembayaran &amp; Bukti Transfer</h5>
                </div>
                <p class="text-muted small mb-3">
                    Silakan selesaikan pembayaran sesuai rincian berikut, lalu unggah foto struk agar restoran segera memproses pesanan Anda.
                </p>

                {{-- Payment Account Details (Liquid Glass Box) --}}
                @if($order->payment_method === 'transfer')
                <div class="p-3.5 rounded-4 mb-3" style="background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.85);">
                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                        <span class="small text-muted">Bank Tujuan:</span>
                        <strong class="text-dark">BCA (Bank Central Asia)</strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                        <span class="small text-muted">Nomor Rekening:</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="font-monospace fw-bold fs-6 text-dark">8210987654</span>
                            <button type="button" class="btn btn-light btn-sm py-0.5 px-2.5 rounded-pill fw-bold" onclick="navigator.clipboard.writeText('8210987654'); this.innerHTML='Disalin!'; setTimeout(() => this.innerHTML='Salin', 1500)" style="font-size: 0.75rem;">
                                Salin
                            </button>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                        <span class="small text-muted">Atas Nama:</span>
                        <span class="fw-semibold text-dark">PT FoodOrder Indonesia</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="small text-muted">Total Transfer:</span>
                        <span class="fw-extrabold fs-5" style="color: var(--liquid-primary);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
                @elseif($order->payment_method === 'ewallet')
                <div class="p-4 rounded-4 mb-3 text-center" style="background: rgba(255, 255, 255, 0.65); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.85);">
                    <div class="small text-muted mb-1 fw-semibold">Scan QRIS untuk Pembayaran Instant</div>
                    <div class="fw-extrabold fs-4 mb-3" style="color: var(--liquid-primary);">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </div>
                    <div class="rounded-4 p-3.5 d-inline-block mx-auto mb-2" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.95); box-shadow: 0 10px 24px rgba(0,0,0,0.08);">
                        <i class="bi bi-qr-code text-dark" style="font-size: 6rem;"></i>
                    </div>
                    <p class="small text-muted mb-0" style="font-size: 0.75rem;">
                        Scan via BCA Mobile, Livin Mandiri, GoPay, OVO, DANA, ShopeePay, atau LinkAja
                    </p>
                </div>
                @endif

                @if($order->payment?->payment_proof)
                <div class="d-flex align-items-center gap-2.5 rounded-4 mb-3 p-3 small" style="background: rgba(220, 252, 231, 0.85); backdrop-filter: blur(12px); color: #166534; border: 1px solid rgba(255, 255, 255, 0.85);">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div>
                        <strong>Bukti pembayaran sudah berhasil diunggah!</strong>
                        <div>Status verifikasi pembayaran: <strong>{{ ucfirst($order->payment->payment_status) }}</strong></div>
                    </div>
                </div>
                <div class="mb-3">
                    <img src="{{ asset('storage/'.$order->payment->payment_proof) }}" alt="Bukti Pembayaran" class="img-thumbnail rounded-4" style="max-height: 200px; border: 1px solid rgba(255, 255, 255, 0.85); box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);">
                </div>
                @endif

                <form action="{{ route('customer.orders.payment', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Pilih Foto Struk / Screenshot Bukti Bayar</label>
                        <input type="file" name="payment_proof" class="form-control" accept="image/*" required onchange="previewProof(this)">
                        <small class="text-muted" style="font-size: 0.72rem;">Format: JPG, PNG. Maksimal 2MB.</small>
                        <div id="proofPreviewBox" class="mt-2.5 d-none">
                            <span class="small text-muted d-block mb-1">Pratinjau Struk:</span>
                            <img id="proofPreviewImg" src="" class="img-thumbnail rounded-4 shadow-sm" style="max-height: 180px; object-fit: contain;" alt="Preview Bukti">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm px-4 rounded-pill fw-bold">
                        <i class="bi bi-upload me-1"></i>{{ $order->payment?->payment_proof ? 'Ganti Bukti Bayar' : 'Upload Bukti Bayar' }}
                    </button>
                </form>
            </div>
            @endif

            {{-- REVIEW SECTION (Available if status is 'selesai') --}}
            @if($order->status === 'selesai' && $order->restaurant)
            <div class="card border-0 rounded-4 p-4">
                <h5 class="fw-extrabold text-dark mb-2">
                    <i class="bi bi-star-fill text-warning me-2"></i>Beri Penilaian Hidangan
                </h5>
                <p class="text-muted small mb-3">
                    Bagaimana pengalaman Anda menikmati hidangan dari <strong>{{ $order->restaurant->name }}</strong>? Ulasan Anda sangat berharga bagi mitra restoran.
                </p>

                <form action="{{ route('customer.reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="restaurant_id" value="{{ $order->restaurant_id }}">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">Rating Kepuasan</label>
                        <div class="d-flex align-items-center gap-1.5 mb-1" id="starRatingGroup">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="btn p-0 border-0 fs-3 text-warning star-select-btn" data-value="{{ $i }}" title="{{ $i }} Bintang" onclick="setRating({{ $i }})">
                                    <i class="bi bi-star-fill" id="starIcon{{ $i }}"></i>
                                </button>
                            @endfor
                            <span id="starRatingLabel" class="small fw-bold text-dark ms-2.5">5 Bintang (Luar Biasa &amp; Sangat Lezat)</span>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Ulasan &amp; Komentar</label>
                        <textarea name="comment" class="form-control form-control-sm" rows="3" placeholder="Ceritakan rasa masakan, kemasan, atau kecepatan pelayanan..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold rounded-pill">
                        <i class="bi bi-send me-1"></i>Kirim Penilaian
                    </button>
                </form>
            </div>
            @endif
        </div>

        {{-- Order Info Sidebar --}}
        <div class="col-lg-4">
            {{-- Details Card --}}
            <div class="card border-0 rounded-4 p-4 mb-3">
                <h6 class="fw-extrabold text-dark mb-3">Informasi Transaksi</h6>
                <table class="table table-sm table-borderless small mb-0">
                    <tr>
                        <td class="text-muted">Nomor Pesanan</td>
                        <td class="fw-bold text-end font-monospace">#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Transaksi</td>
                        <td class="text-end">{{ $order->created_at?->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Metode Pembayaran</td>
                        <td class="text-end fw-semibold">
                            @if($order->payment_method === 'transfer')
                                Transfer Bank Manual
                            @elseif($order->payment_method === 'ewallet')
                                E-Wallet / QRIS
                            @else
                                Bayar di Tempat (COD)
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status Pembayaran</td>
                        <td class="text-end">
                            @php $ps = $order->payment?->payment_status ?? 'pending'; @endphp
                            <span class="badge {{ $ps==='paid' ? 'badge-selesai' : ($ps==='failed' ? 'badge-dibatalkan' : 'badge-pending') }} px-2.5 py-1 rounded-pill">
                                {{ ucfirst($ps) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Address Card --}}
            <div class="card border-0 rounded-4 p-4 mb-3">
                <h6 class="fw-extrabold text-dark mb-2">
                    <i class="bi bi-geo-alt me-1 text-danger"></i>Alamat Pengantaran
                </h6>
                <p class="small text-secondary mb-0">{{ $order->address }}</p>
            </div>

            {{-- Cancel Order Action --}}
            @if($order->status === 'pending')
            <div class="card border-0 rounded-4 p-4" style="background: rgba(254, 242, 242, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.85);">
                <h6 class="fw-bold text-danger mb-1">Batalkan Pesanan?</h6>
                <p class="small text-muted mb-3">Pesanan dapat dibatalkan selama status masih menunggu konfirmasi dari pihak restoran.</p>
                <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok menu akan segera dipulihkan.')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-bold rounded-pill py-2">
                        <i class="bi bi-x-circle me-1"></i>Batalkan Pesanan
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewProof(input) {
    const box = document.getElementById('proofPreviewBox');
    const img = document.getElementById('proofPreviewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            box.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        box.classList.add('d-none');
    }
}

const ratingLabels = {
    1: '1 Bintang (Kurang Memuaskan)',
    2: '2 Bintang (Perlu Peningkatan)',
    3: '3 Bintang (Cukup Baik)',
    4: '4 Bintang (Enak & Memuaskan)',
    5: '5 Bintang (Luar Biasa & Sangat Lezat)'
};

function setRating(val) {
    const input = document.getElementById('ratingInput');
    const label = document.getElementById('starRatingLabel');
    if (input) input.value = val;
    if (label && ratingLabels[val]) label.textContent = ratingLabels[val];

    for (let i = 1; i <= 5; i++) {
        const icon = document.getElementById('starIcon' + i);
        if (icon) {
            if (i <= val) {
                icon.className = 'bi bi-star-fill';
            } else {
                icon.className = 'bi bi-star';
            }
        }
    }
}
</script>
@endpush
@endsection
