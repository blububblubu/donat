@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('css')
<style>
    :root {
        --primary: #c19a6b;
        --light-bg: #f9f9f9;
        --text: #333;
    }

    .cart-item {
        background: white;
        border-radius: 12px;
        padding: 1.2rem;
        margin-bottom: 1.2rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        display: flex;
        gap: 1.2rem;
        align-items: center;
    }

    .cart-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }

    .cart-item-info {
        flex: 1;
        text-align: left;
    }

    .cart-item-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.3rem;
        color: #222;
    }

    .cart-item-price {
        color: var(--primary);
        font-weight: 700;
        font-size: 1.05rem;
        margin: 0.2rem 0;
    }

    .cart-item-stock {
        font-size: 0.85rem;
        color: #777;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0.5rem 0;
    }

    .quantity-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #eee;
        border: none;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #444;
    }

    .quantity-input {
        width: 50px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 4px;
        font-weight: 600;
    }

    .btn-remove {
        background: #f8d7da;
        color: #721c24;
        border: none;
        padding: 0.35rem 0.7rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.4rem;
        cursor: pointer;
    }

    .btn-remove:hover {
        background: #f1b0b7;
    }

    .cart-total-section {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        margin-top: 1.5rem;
    }

    .cart-total-row {
        display: flex;
        justify-content: space-between;
        font-size: 1.15rem;
        margin-bottom: 1rem;
    }

    .cart-grand-total {
        font-weight: 700;
        color: var(--primary);
        font-size: 1.3rem;
    }

    .cart-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-modern {
        padding: 0.65rem 1.4rem;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
    }

    .btn-secondary-modern {
        background: #eee;
        color: #333;
    }

    .btn-secondary-modern:hover {
        background: #ddd;
    }

    .btn-success-modern {
        background: var(--primary);
        color: white;
    }

    .btn-success-modern:hover {
        opacity: 0.9;
    }

    .empty-cart {
        text-align: center;
        padding: 3rem 1rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    @media (max-width: 768px) {
        .cart-item {
            flex-direction: column;
            text-align: center;
        }

        .cart-item img {
            margin-bottom: 0.8rem;
        }

        .cart-actions {
            flex-direction: column;
        }

        .btn-modern {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')

<h1 class="mb-4" style="color:#222; font-weight:600;">Keranjang Belanja</h1>

@if(session('success'))
    <div class="alert alert-success" style="border-radius:8px;">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="border-radius:8px;">{{ session('error') }}</div>
@endif

@if($cartItems->isEmpty())

    <div class="empty-cart">
        <h4 class="mb-3">Keranjang masih kosong.</h4>
        <p class="text-muted mb-4">Tambahkan produk favoritmu sekarang!</p>
        <a href="{{ route('home') }}" class="btn-modern btn-secondary-modern">Kembali Belanja</a>
    </div>

@else

    @php $grandTotal = 0; @endphp

    @foreach($cartItems as $item)
        @php
            $total = $item->quantity * $item->product->price;
            $grandTotal += $total;
        @endphp

        <div class="cart-item">
            <img 
                src="{{ $item->product->image_url ? asset('storage/' . $item->product->image_url) : asset('no-image.png') }}" 
                alt="{{ $item->product->name }}"
                onerror="this.src='{{ asset('no-image.png') }}'"
            >
            <div class="cart-item-info">
                <div class="cart-item-title">{{ $item->product->name }}</div>
                <div class="cart-item-price">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                <div class="cart-item-stock">Stok: {{ $item->product->stock }}</div>

                {{-- Kontrol Jumlah --}}
                <div class="quantity-control">
                    <form method="POST" action="{{ route('cart.update', $item) }}" style="display:inline;">
                        @csrf @method('PUT')
                        <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                        <button type="submit" class="quantity-btn" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                    </form>

                    <span class="quantity-input">{{ $item->quantity }}</span>

                    <form method="POST" action="{{ route('cart.update', $item) }}" style="display:inline;">
                        @csrf @method('PUT')
                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                        <button type="submit" class="quantity-btn" {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>+</button>
                    </form>
                </div>

                {{-- Hapus --}}
                <form method="POST" action="{{ route('cart.remove', $item) }}" style="display:inline;" onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-remove">Hapus</button>
                </form>
            </div>
        </div>

    @endforeach

    {{-- Total & Aksi --}}
    <div class="cart-total-section">
        <div class="cart-total-row">
            <span>Total Belanja:</span>
            <span class="cart-grand-total">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
        </div>

        <div class="cart-actions">
            <a href="{{ route('home') }}" class="btn-modern btn-secondary-modern">Lanjut Belanja</a>
            <a href="{{ route('cart.checkout') }}" class="btn-modern btn-success-modern">Checkout</a>
        </div>
    </div>

@endif

@endsection