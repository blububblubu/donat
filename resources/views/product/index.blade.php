@extends('layouts.app')

@section('title', 'Semua Produk')

@section('content')

<style>
:root{
    --primary:#c19a6b;
}

.container-products{
    max-width:1200px;
    margin:auto;
    padding:20px;
}

/* HEADER */
.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:15px;
}

.page-header h2{
    font-size:1.8rem;
    font-weight:700;
    margin:0;
}

/* SEARCH */
.search-box input{
    padding:10px 14px;
    border-radius:25px;
    border:1px solid #ddd;
    outline:none;
    width:240px;
}

/* GRID */
.products-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(240px,1fr));
    gap:1.5rem;
}

/* CARD */
.product-card{
    background:white;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 4px 15px rgba(0,0,0,0.05);
    transition:0.25s;
    display:flex;
    flex-direction:column;
}

.product-card:hover{
    transform:translateY(-6px);
    box-shadow:0 12px 25px rgba(0,0,0,0.08);
}

/* IMAGE */
.product-image{
    height:190px;
    background:#eee;
    overflow:hidden;
}

.product-image img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:0.3s;
}

.product-card:hover img{
    transform:scale(1.05);
}

/* CONTENT */
.product-content{
    padding:14px;
    display:flex;
    flex-direction:column;
    flex-grow:1;
}

.product-title{
    font-size:1rem;
    font-weight:600;
    margin-bottom:6px;
}

.product-price{
    color:var(--primary);
    font-weight:700;
    margin-bottom:10px;
}

/* BUTTON */
.btn-detail{
    margin-top:auto;
    padding:10px;
    border-radius:10px;
    text-align:center;
    font-size:14px;
    font-weight:600;
    text-decoration:none;
    background:var(--primary);
    color:white;
    transition:0.2s;
}

.btn-detail:hover{
    opacity:0.9;
}

/* EMPTY */
.empty{
    grid-column:1/-1;
    text-align:center;
    padding:40px;
    color:#777;
}
</style>

<div class="container-products">

    {{-- HEADER --}}
    <div class="page-header">

        <h2>Semua Produk</h2>

        <!-- <form method="GET" action="{{ route('products.index') }}" class="search-box">
            <input type="text" name="q" placeholder="Cari produk..."
                value="{{ request('q') }}">
        </form> -->

    </div>

    {{-- GRID --}}
    <div class="products-grid">

        @forelse($products as $product)

        <div class="product-card">

            {{-- IMAGE --}}
            <div class="product-image">
                @if($product->image_url)
                    <img src="{{ asset('storage/'.$product->image_url) }}">
                @else
                    <div style="display:flex;align-items:center;justify-content:center;height:100%;">
                        No Image
                    </div>
                @endif
            </div>

            {{-- CONTENT --}}
            <div class="product-content">

                <div class="product-title">
                    {{ $product->name }}
                </div>

                <div class="product-price">
                    Rp {{ number_format($product->price,0,',','.') }}
                </div>

                <a href="{{ route('product.show',$product) }}" class="btn-detail">
                    Lihat Detail
                </a>

            </div>

        </div>

        @empty

        <div class="empty">
            Produk tidak ditemukan
        </div>

        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div style="margin-top:30px;">
        {{ $products->links() }}
    </div>

</div>

@endsection