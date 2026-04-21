@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold" style="color: #222;">Detail Pesanan #{{ $order->id }}</h1>
            <p class="text-muted mb-0">Informasi lengkap pesanan pelanggan</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pesanan
        </a>
    </div>

    <div class="row g-4">

        <!-- Informasi Utama -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-header bg-white py-3" style="border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0 fw-semibold">Informasi Pesanan</h5>
                </div>
                <div class="card-body p-4">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong style="color:#666;">Customer</strong><br>
                            <span style="font-size:1.1rem;">{{ $order->user->name ?? 'Guest' }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong style="color:#666;">Total Pembayaran</strong><br>
                            <span style="font-size:1.35rem; font-weight:700; color:#c19a6b;">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong style="color:#666;">Status Pesanan</strong><br>
                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}" 
                                  style="padding: 8px 14px; font-size: 1rem;">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong style="color:#666;">Pembayaran</strong><br>
                            @if($order->is_paid)
                                <span class="text-success fw-bold" style="font-size:1.1rem;">✅ Sudah Dibayar</span>
                            @else
                                <span class="text-danger fw-bold" style="font-size:1.1rem;">⏳ Belum Dibayar</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Bukti Pembayaran -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0" style="border-radius: 16px; height: 100%;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">Bukti Pembayaran</h5>
                </div>
                <div class="card-body p-4 text-center">

                    @if($order->payment_proof)
                        <img 
                            src="{{ asset('storage/' . $order->payment_proof) }}"
                            alt="Bukti Pembayaran"
                            class="img-fluid rounded"
                            style="max-height: 320px; cursor: pointer; border: 2px solid #eee;"
                            onclick="openImage(this.src)"
                        >
                        <p class="mt-3 text-muted small">Klik gambar untuk memperbesar</p>

                        @if(!$order->is_paid)
                            <form action="{{ route('admin.orders.approvePayment', $order->id) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100" 
                                        style="border-radius: 12px; padding: 12px;">
                                    <i class="fas fa-check-circle me-2"></i> Approve Pembayaran
                                </button>
                            </form>
                        @endif

                    @else
                        <div class="py-5 text-center">
                            <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada bukti pembayaran</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal Image Viewer -->
<div id="imageModal" 
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
            background:rgba(0,0,0,0.9); justify-content:center; align-items:center; z-index:9999;">
    
    <img id="modalImage" 
         style="max-width:92%; max-height:92%; border-radius:12px; box-shadow:0 15px 50px rgba(0,0,0,0.6);">
    
    <button onclick="closeModal()" 
            style="position:absolute; top:20px; right:30px; background:none; border:none; color:white; font-size:2rem; cursor:pointer;">
        &times;
    </button>
</div>
@endsection

@section('js')
<script>
function openImage(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').style.display = "flex";
}

function closeModal() {
    document.getElementById('imageModal').style.display = "none";
}

// Tutup modal jika klik area gelap
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target.id === 'imageModal') {
        closeModal();
    }
});
</script>
@endsection