@extends('layouts.app')

@section('title', 'Home')

@section('content')

<style>

:root{
--primary:#c19a6b;
--light-bg:#f9f9f9;
}

.container-home{
max-width:1200px;
margin:auto;
}

/* HERO */

.hero{
display:grid;
grid-template-columns:1fr 1fr;
align-items:center;
gap:3rem;
padding:3rem;
background:white;
border-radius:14px;
box-shadow:0 6px 18px rgba(0,0,0,0.06);
margin-bottom:3rem;
}

.hero-text h1{
font-size:2.4rem;
font-weight:700;
margin-bottom:1rem;
}

.hero-text p{
opacity:0.8;
margin-bottom:1.5rem;
}

.btn-primary-modern{
background:var(--primary);
color:white;
border:none;
padding:10px 22px;
border-radius:30px;
font-weight:600;
text-decoration:none;
transition:0.2s;
}

.btn-primary-modern:hover{
opacity:0.9;
}


/* HERO IMAGE */

.hero-image{
display:flex;
justify-content:center;
}

.hero-image img{
width:300px;
height:300px;
border-radius:50%;
object-fit:cover;
box-shadow:0 10px 25px rgba(0,0,0,0.1);
}


/* PRODUCT GRID */

.products-grid{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
gap:1.8rem;
}


/* CARD */

.product-card{
background:white;
border-radius:12px;
padding:1rem;
display:flex;
flex-direction:column;
box-shadow:0 3px 10px rgba(0,0,0,0.05);
transition:0.25s;
}

.product-card:hover{
transform:translateY(-6px);
box-shadow:0 10px 20px rgba(0,0,0,0.08);
}


/* IMAGE */

.product-image{
width:100%;
height:170px;
border-radius:10px;
overflow:hidden;
margin-bottom:10px;
background:#eee;
display:flex;
align-items:center;
justify-content:center;
}

.product-image img{
width:100%;
height:100%;
object-fit:cover;
}


/* TITLE */

.product-title{
font-size:1.1rem;
font-weight:600;
margin-bottom:3px;
}


/* PRICE */

.product-price{
font-weight:700;
color:var(--primary);
margin-bottom:3px;
}


/* STOCK */

.product-stock{
font-size:13px;
color:#777;
margin-bottom:12px;
}


/* BUTTON GROUP */

.btn-group{
display:flex;
flex-direction:column;
gap:10px;
margin-top:auto;
}


/* DETAIL BUTTON */

.btn-outline-primary{
border:1.5px solid var(--primary);
color:var(--primary);
padding:9px;
border-radius:10px;
text-align:center;
text-decoration:none;
font-size:14px;
font-weight:500;
transition:all 0.2s ease;
}

.btn-outline-primary:hover{
background:var(--primary);
color:white;
}


/* ADD TO CART BUTTON */

.btn-add-cart{
background:var(--primary);
color:white;
border:none;
padding:9px;
border-radius:10px;
font-size:14px;
font-weight:600;
transition:all 0.2s ease;
}

.btn-add-cart:hover{
transform:translateY(-1px);
box-shadow:0 4px 10px rgba(0,0,0,0.12);
}


/* LOGIN BUTTON */

.btn-login-required{
background:#fff3cd;
color:#856404;
border:none;
padding:9px;
border-radius:10px;
font-weight:600;
font-size:14px;
text-align:center;
text-decoration:none;
}

.btn-login-required:hover{
background:#ffe69c;
}


/* SOLD OUT */

.btn-sold-out{
background:#f1f1f1;
color:#888;
border:none;
padding:9px;
border-radius:10px;
font-size:14px;
cursor:not-allowed;
}


/* EMPTY */

.empty-product{
grid-column:1/-1;
text-align:center;
padding:4rem;
color:#777;
font-size:18px;
}

</style>



<div class="container-home">

@if(session('success'))
<div class="alert alert-success mb-4">
{{ session('success') }}
</div>
@endif



{{-- HERO --}}
<section class="hero">

<div class="hero-text">

<h1>Produk Terbaik Kami</h1>

<p>
Temukan berbagai pilihan donut dan produk terbaik
dengan kualitas premium dan harga terbaik.
</p>

<a href="#produk" class="btn-primary-modern">
Lihat Semua Produk
</a>



</div>



<!-- <div class="hero-image">

@if($popularProduct && $popularProduct->image_url)

<img
src="{{ asset('storage/'.$popularProduct->image_url) }}"
alt="{{ $popularProduct->name }}"
>

@else

<div style="
width:300px;
height:300px;
border-radius:50%;
background:#eee;
display:flex;
align-items:center;
justify-content:center;
color:#777;
">
Belum ada produk
</div>

@endif

</div> -->


{{-- HERO IMAGE BARU - Menggunakan Logo Web --}}
<div class="hero-image text-center">
    <img src="{{ asset('storage/logo.png') }}" 
         alt="Fiava Donut Shop"
         style="max-width: 420px; height: auto;">
</div>

</section>




{{-- PRODUCTS --}}
<section id="produk" class="products-grid">

@forelse($products as $product)

<div class="product-card">

<div class="product-image">

@if($product->image_url)

<img src="{{ asset('storage/'.$product->image_url) }}">

@else

No Image

@endif

</div>

<h3 class="product-title">
{{ $product->name }}
</h3>

<p class="product-price">
Rp {{ number_format($product->price,0,',','.') }}
</p>

<p class="product-stock">
Stok: {{ $product->stock }}
</p>



<div class="btn-group">

<a href="{{ route('product.show',$product) }}"
class="btn-outline-primary">
Lihat Detail
</a>


@auth

@if($product->stock > 0)

<form action="{{ route('cart.add',$product) }}" method="POST">
@csrf
<button class="btn-add-cart">
+ Keranjang
</button>
</form>

@else

<button class="btn-sold-out" disabled>
Stok Habis
</button>

@endif


@else

<a href="{{ route('login.show') }}"
class="btn-login-required">
Login untuk membeli
</a>

@endauth

</div>

</div>

@empty

<div class="empty-product">
Produk belum tersedia
</div>

@endforelse

</section>

</div>

@endsection