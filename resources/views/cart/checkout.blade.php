@extends('layouts.app')

@section('title', 'Checkout')

@section('css')
<style>
    :root {
        --primary: #c19a6b;
    }

    .checkout-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .checkout-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        overflow: hidden;
    }

    .section-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 1.5rem;
    }

    .form-section {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #444;
        margin-bottom: 0.6rem;
        font-size: 0.95rem;
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        border: 1.5px solid #e0e0e0;
        font-size: 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(193, 154, 107, 0.15);
    }

    /* Alamat Card */
    .address-card {
        border: 2px solid #eee;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .address-card:hover {
        border-color: var(--primary);
    }

    .address-card.selected {
        border-color: var(--primary);
        background: #fdf8f0;
    }

    .address-card input[type="radio"] {
        display: none;
    }

    .address-info {
        margin-left: 10px;
    }

    .summary-box {
        background: #faf9f5;
        border-radius: 16px;
        padding: 1.8rem;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.65rem 0;
        font-size: 1.02rem;
    }

    .summary-total {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--primary);
        border-top: 2px solid #e5e0d4;
        padding-top: 1rem;
        margin-top: 1rem;
    }

    .btn-checkout {
        background: var(--primary);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        width: 100%;
        margin-top: 1.5rem;
        box-shadow: 0 4px 15px rgba(193, 154, 107, 0.3);
    }

    .btn-checkout:hover {
        background: #b38a5c;
        transform: translateY(-2px);
    }

    .qris-box {
        background: white;
        border: 2px dashed #ddd;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="checkout-container">

        <h1 class="text-center mb-5 fw-bold" style="color: #222;">Checkout</h1>

        <div class="checkout-card">
            <div class="row g-0">

                <!-- Form Kiri -->
                <div class="col-lg-7">
                    <div class="form-section">
                        <h4 class="section-title">Informasi Pengiriman</h4>

                        <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">Nama Penerima</label>
                                <input type="text" name="customer_name" 
                                       class="form-control" 
                                       value="{{ old('customer_name', auth()->user()->name ?? '') }}" 
                                       required>
                            </div>

                            <!-- Daftar Alamat (Lebih Friendly) -->
                            <div class="mb-4">
                                <label class="form-label">Pilih Alamat Pengiriman</label>
                                
                                @foreach($addresses as $address)
                                <label class="address-card d-flex align-items-start {{ old('address_id') == $address->id ? 'selected' : '' }}" 
                                       onclick="selectAddress(this)">
                                    <input type="radio" 
                                           name="address_id" 
                                           value="{{ $address->id }}" 
                                           {{ old('address_id') == $address->id ? 'checked' : '' }} 
                                           required style="margin-top: 4px;">
                                    <div class="address-info">
                                        <strong>{{ $address->label }}</strong><br>
                                        <span style="color:#555;">{{ $address->address }}, {{ $address->city }}</span><br>
                                        <small style="color:#777;">{{ $address->phone ?? 'No. HP tidak diisi' }}</small>
                                    </div>
                                </label>
                                @endforeach

                                <a href="{{ route('addresses.create') }}" class="text-decoration-none" style="color: var(--primary); font-weight:600;">
                                    + Tambah Alamat Pengiriman Baru
                                </a>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Nomor Telepon (WhatsApp)</label>
                                <input type="tel" name="phone" 
                                       class="form-control" 
                                       value="{{ old('phone') }}" 
                                       placeholder="0812-3456-7890"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Metode Pembayaran</label>
                                <select name="payment_method" class="form-select" id="paymentMethod" required>
                                    <option value="cod">Cash on Delivery (COD) - Bayar di Tempat</option>
                                    <option value="qris">QRIS (E-Wallet / Mobile Banking)</option>
                                </select>

                                <!-- QRIS Preview -->
                                <div id="qrisBox" class="qris-box" style="display: none;">
                                    <p class="mb-2 fw-medium">Scan QRIS berikut untuk pembayaran</p>
                                    <img src="{{ asset('storage/qris.png') }}" 
                                         alt="QRIS Fiava" 
                                         style="max-width: 240px; width:100%; border-radius: 8px;">
                                    <p class="mt-3 small text-muted">
                                        Gunakan aplikasi GoPay, OVO, Dana, ShopeePay, atau Mobile Banking
                                    </p>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Ringkasan Pesanan Kanan -->
                <div class="col-lg-5" style="background: #fbfaf7;">
                    <div class="form-section summary-box">
                        <h4 class="section-title mb-4">Ringkasan Pesanan</h4>

                        @php $total = 0; @endphp
                        @foreach($cartItems as $item)
                            @php $subtotal = $item->product->price * $item->quantity; $total += $subtotal; @endphp
                            <div class="summary-item">
                                <span>{{ $item->product->name }} <small class="text-muted">× {{ $item->quantity }}</small></span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach

                        @php
                            $shipping = 5000;
                            $grandTotal = $total + $shipping;
                        @endphp

                        <div class="summary-item mt-3">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>

                        <div class="summary-total">
                            <span>Total Pembayaran</span>
                            <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" form="checkoutForm" class="btn-checkout">
                            <i class="fas "></i> Bayar Sekarang
                        </button>

                        <p class="text-center mt-3 mb-0 small text-muted">
                            Pesananmu akan segera diproses setelah pembayaran
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {

    const paymentSelect = document.getElementById("paymentMethod");
    const qrisBox = document.getElementById("qrisBox");

    function toggleQRIS() {
        if (paymentSelect.value === "qris") {
            qrisBox.style.display = "block";
        } else {
            qrisBox.style.display = "none";
        }
    }

    paymentSelect.addEventListener("change", toggleQRIS);
    toggleQRIS();

    // Highlight alamat yang dipilih
    window.selectAddress = function(el) {
        document.querySelectorAll('.address-card').forEach(card => {
            card.classList.remove('selected');
        });
        el.classList.add('selected');
    };
});
</script>