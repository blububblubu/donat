@extends('layouts.app')

@section('title', $product->name)

@section('css')
<style>
    :root {
        --primary: #c19a6b;
        --light-bg: #f9f9f9;
        --text: #333;
    }

    .product-detail-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .product-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 16px 0 0 16px;
    }

    @media (max-width: 768px) {
        .product-image {
            height: 300px;
            border-radius: 16px 16px 0 0 !important;
        }
    }

    .product-info {
        padding: 2rem;
    }

    .product-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 1rem;
    }

    .product-description {
        color: #555;
        line-height: 1.6;
        margin-bottom: 1.2rem;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        margin: 1rem 0;
    }

    .product-stock {
        font-size: 0.95rem;
        color: #777;
        margin-bottom: 1.5rem;
    }

    .btn-modern {
        padding: 0.7rem 1.4rem;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        width: 100%;
        text-align: center;
    }

    .btn-add-cart {
        background: var(--primary);
        color: white;
    }

    .btn-add-cart:hover {
        opacity: 0.9;
    }

    .btn-view-cart {
        background: #f0f0f0;
        color: #333;
        margin-top: 0.6rem;
    }

    .btn-view-cart:hover {
        background: #e0e0e0;
    }

    .btn-login-required {
        background: #ffc107;
        color: #212529;
    }

    .btn-login-required:hover {
        opacity: 0.9;
    }

    .review-card {
        background: white;
        border-radius: 12px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.6rem;
    }

    .review-name {
        font-weight: 600;
        color: #222;
    }

    .review-rating {
        color: var(--primary);
        font-weight: 600;
    }

    .review-comment {
        color: #444;
        line-height: 1.5;
    }

    .review-form-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        margin-top: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 0.6rem;
    }

    .alert-info-custom {
        background: #f8f9fa;
        border-left: 4px solid var(--primary);
        padding: 1rem;
        border-radius: 8px;
        color: #555;
    }

    /* ★ STAR RATING ★ */
    .star-rating {
        font-size: 1.8rem;
        color: #ddd;
        display: inline-flex;
        gap: 2px;
    }

    .star-rating .star {
        cursor: pointer;
        transition: color 0.2s;
    }

    .star-rating .star.filled {
        color: var(--primary) !important;
    }

    .star-rating .star:hover {
        color: var(--primary) !important;
    }
</style>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success" style="border-radius:8px;">{{ session('success') }}</div>
@endif

<div class="product-detail-card">
    <div class="row g-0">
        <!-- Gambar -->
        <div class="col-md-5">
            <img 
                src="{{ $product->image_url ? asset('storage/'.$product->image_url) : asset('no-image.png') }}" 
                class="product-image"
                alt="{{ $product->name }}"
                onerror="this.src='{{ asset('no-image.png') }}'"
            >
        </div>

        <!-- Detail -->
        <div class="col-md-7">
            <div class="product-info">
                <h1 class="product-title">{{ $product->name }}</h1>

                <p class="product-description">
                    {{ $product->description ?: 'Deskripsi tidak tersedia.' }}
                </p>

                <div class="product-price">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                <p class="product-stock">
                    Stok tersedia: <strong>{{ $product->stock }}</strong>
                </p>

                <hr style="margin: 1.5rem 0;">

                <!-- Aksi -->
                @auth
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn-modern btn-add-cart">
                                + Tambah ke Keranjang
                            </button>
                        </form>
                        <a href="{{ route('cart.index') }}" class="btn-modern btn-view-cart">
                            Lihat Keranjang
                        </a>
                    @else
                        <div class="alert alert-warning text-center" style="border-radius:30px;">
                            Stok Habis
                        </div>
                    @endif
                @else
                    <a href="{{ route('login.show') }}" class="btn-modern btn-login-required">
                        Login untuk Membeli
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<hr class="my-4">

<!-- Ulasan -->
<h4 class="mb-3" style="font-weight:600; color:#222;">Ulasan Pembeli</h4>

@if($product->reviews->isEmpty())
    <div class="alert-info-custom">
        Belum ada ulasan untuk produk ini.
    </div>
@else
    @foreach($product->reviews as $review)
        <div class="review-card">
            <div class="review-header">
                <span class="review-name">{{ $review->user->name }}</span>
                <span class="review-rating">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <i class="fas fa-star" style="color:var(--primary);"></i>
                        @else
                            <i class="far fa-star" style="color:#ccc;"></i>
                        @endif
                    @endfor
                </span>
            </div>
            <p class="review-comment">{{ $review->comment }}</p>
        </div>
    @endforeach
@endif

<!-- Form Ulasan / Edit Ulasan -->
@auth
    @php
        $userReview = $product->reviews()->where('user_id', auth()->id())->first();
    @endphp

    <div class="review-form-card">
        <h5 class="mb-3 fw-bold">
            @if($userReview)
                Edit Ulasan Anda
            @else
                Tulis Ulasan Anda
            @endif
        </h5>

        <form action="{{ route('product.review', $product) }}" method="POST" id="review-form">
            @csrf

            <!-- Rating -->
            <div class="mb-3">
                <label class="form-label">Rating Anda</label>
                <div class="d-flex align-items-center">
                    <input type="hidden" name="rating" id="rating-input" 
                           value="{{ old('rating', $userReview?->rating ?? 5) }}" required>

                    <div class="star-rating">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>

                    <span class="ms-2 fw-bold" id="rating-text" style="color:var(--primary);">
                        {{ old('rating', $userReview?->rating ?? 5) }} bintang
                    </span>
                </div>
            </div>

            <!-- Komentar -->
            <div class="mb-3">
                <label class="form-label">Komentar</label>
                <textarea name="comment" class="form-control" rows="4" required
                    placeholder="Bagikan pengalamanmu...">{{ old('comment', $userReview?->comment ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn-modern btn-add-cart" style="width:auto; padding:0.7rem 1.5rem;">
                @if($userReview)
                    Simpan Perubahan
                @else
                    Kirim Ulasan
                @endif
            </button>
        </form>
    </div>
@else
    <div class="alert alert-warning mt-4" style="border-radius:8px;">
        <i class="fas fa-lock me-2"></i> Login untuk memberikan atau mengedit ulasan.
    </div>
@endauth

@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star-rating .star');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');

    function updateStars(rating) {
        stars.forEach(star => {
            const value = parseInt(star.getAttribute('data-value'));
            if (value <= rating) {
                star.classList.add('filled');
            } else {
                star.classList.remove('filled');
            }
        });
    }

    // Set nilai awal dari input hidden
    let currentRating = parseInt(ratingInput.value) || 5;
    updateStars(currentRating);
    ratingText.textContent = currentRating + ' bintang';

    stars.forEach(star => {
        const value = parseInt(star.getAttribute('data-value'));

        star.addEventListener('mouseover', () => updateStars(value));

        star.addEventListener('click', () => {
            ratingInput.value = value;
            ratingText.textContent = value + ' bintang';
            currentRating = value;
        });

        star.addEventListener('mouseout', () => {
            updateStars(currentRating);
        });
    });
});
</script>
@endsection