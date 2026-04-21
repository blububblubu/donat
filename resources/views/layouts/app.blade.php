<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>@yield('title','Donut Shop')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link rel="icon" type="image/png" href="{{ asset('storage/ahay.png') }}">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:#f9f9f9;
}

/* NAVBAR */

.navbar-custom{
background:#fff;
box-shadow:0 2px 10px rgba(0,0,0,0.05);
position:sticky;
top:0;
z-index:1000;
background: #ebe8dd;
}

.navbar-container{
max-width:1200px;
margin:auto;
padding:15px 20px;
display:flex;
align-items:center;
justify-content:space-between;
}

/* LOGO */

.navbar-logo{
display:flex;
align-items:center;
gap:10px;
text-decoration:none;
}

.navbar-logo img{
height:40px;
}

.navbar-logo span{
font-size:22px;
font-weight:700;
color:#222;
}

.navbar-logo span b{
color:#c19a6b;
}

/* MENU */

.nav-menu{
display:flex;
list-style:none;
gap:30px;
margin:0;
}

.nav-menu a{
text-decoration:none;
color:#444;
font-weight:500;
font-size:15px;
}

.nav-menu a:hover{
color:#c19a6b;
}

/* RIGHT SIDE */

.nav-right{
display:flex;
align-items:center;
gap:20px;
}

.search-box{
position:relative;
}

.search-box input{
padding:8px 15px;
border:1px solid #ddd;
border-radius:5px;
width:180px;
}

.search-box i{
position:absolute;
right:10px;
top:50%;
transform:translateY(-50%);
color:#777;
}

/* ICON */

.nav-icon{
color:#333;
font-size:18px;
text-decoration:none;
}

.nav-icon:hover{
color:#c19a6b;
}

/* MOBILE */

@media(max-width:992px){

.nav-menu{
display:none;
}

.search-box{
display:none;
}

}


.search-result{
position:absolute;
top:40px;
left:0;
width:100%;
background:#fff;
border:1px solid #eee;
border-radius:6px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
max-height:300px;
overflow-y:auto;
display:none;
z-index:999;
}

.search-item{
padding:10px;
border-bottom:1px solid #f1f1f1;
display:flex;
align-items:center;
gap:10px;
cursor:pointer;
}

.search-item:hover{
background:#f9f9f9;
}

.search-item img{
width:40px;
height:40px;
object-fit:cover;
border-radius:5px;
}

.no-result{
padding:10px;
text-align:center;
color:#888;
}
</style>

@yield('css')

</head>

<body>

<nav class="navbar-custom">

<div class="navbar-container">

{{-- LOGO --}}
<a href="{{ route('home') }}" class="navbar-logo">

<img src="{{ asset('storage/logo.png') }}" alt="Fiava Logo">

<!-- <span>Fiava<b>.co</b></span> -->

</a>


{{-- MENU UMUM --}}
<ul class="nav-menu">

<li>
<a href="{{ route('home') }}">Home</a>
</li>

<li>
<a href="{{ route('home') }}#produk">Produk</a>
</li>

@auth

@if(in_array(auth()->user()->role, ['buyer','admin']))

<li>
<a href="{{ route('cart.index') }}">Keranjang</a>
</li>

<li>
<a href="{{ route('orders.my') }}">Pesanan</a>
</li>

@endif


@if(auth()->user()->role === 'admin')

<li>
<a href="{{ route('admin.dashboard') }}">Admin Panel</a>
</li>

@endif

@endauth

</ul>



{{-- RIGHT SIDE --}}
<div class="nav-right">


{{-- SEARCH --}}
<!-- <div class="search-box">
<input type="text" placeholder="Search...">
<i class="fas fa-search"></i>
</div> -->

<!-- SEARCH BOX - Modern Version -->
<div class="search-box position-relative d-none d-md-block" style="width: 280px;">
    
    <div class="input-group">
        <span class="input-group-text bg-white border-end-0" style="border-radius: 50px 0 0 50px;">
            <i class="fas fa-search text-muted"></i>
        </span>
        <input 
            type="text" 
            id="searchInput"
            class="form-control border-start-0 ps-0"
            placeholder="Cari jajan favoritmu..."
            autocomplete="off"
            style="border-radius: 0 50px 50px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    </div>

    <!-- Search Result Dropdown -->
    <div id="searchResult" class="search-result shadow-sm" 
         style="display:none; border-radius:12px; margin-top:8px; max-height:380px; overflow-y:auto;">
    </div>
</div>


{{-- ACCOUNT --}}
@guest

<a href="{{ route('login.show') }}" class="nav-icon">
<i class="far fa-user"></i>
</a>

@else

<form action="{{ route('logout') }}" method="POST">
@csrf

<button class="btn btn-sm btn-dark">
<i class="fas fa-sign-out-alt"></i>
</button>

</form>

@endguest



{{-- CART --}}
@auth

<!-- @if(auth()->user()->role === 'buyer')

<a href="{{ route('cart.index') }}" class="nav-icon">
<i class="fas fa-shopping-bag"></i>
</a>

@endif -->

@endauth


</div>

</div>

</nav>


<div class="container py-4">

@yield('content')

</div>

<!-- FOOTER -->
<footer style="background: #ebe8dd; font-family: 'Segoe UI', sans-serif;">
    <div class="container py-5">
        <div class="row gy-5">

            <!-- Logo & Deskripsi -->
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none mb-3">
                    <img src="{{ asset('storage/logo.png') }}" alt="Fiava Logo" style="height: 48px;">
                </a>
                <p style="max-width: 320px; color: #444; line-height: 1.7;">
                    Donat fresh premium dengan tekstur crunchy di luar dan moist di dalam. 
                    Dibuat setiap hari dengan bahan terbaik untuk Anda.
                </p>
                <p class="fw-medium" style="color: #c19a6b;">
                    Crunchy outside. Moist inside.
                </p>
            </div>

            <!-- Navigasi -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-semibold mb-3" style="color: #222;">Navigasi</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none" style="color: #444;">Home</a></li>
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none" style="color: #444;">Produk</a></li>
                    @auth
                    @if(in_array(auth()->user()->role, ['buyer','admin']))
                    <li class="mb-2"><a href="{{ route('cart.index') }}" class="text-decoration-none" style="color: #444;">Keranjang</a></li>
                    <li class="mb-2"><a href="{{ route('orders.my') }}" class="text-decoration-none" style="color: #444;">Pesanan Saya</a></li>
                    @endif
                    @endauth
                </ul>
            </div>

            <!-- Kontak -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-semibold mb-3" style="color: #222;">Hubungi Kami</h6>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <a href="https://wa.me/6282211088070" target="_blank" 
                           class="text-decoration-none d-flex align-items-center gap-2" 
                           style="color: #444;">
                            <i class="fab fa-whatsapp" style="color: #25D366;"></i>
                            <span>+62 822-1108-8070</span>
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="https://instagram.com/crunchymocaf" target="_blank" 
                           class="text-decoration-none d-flex align-items-center gap-2" 
                           style="color: #444;">
                            <i class="fab fa-instagram" style="color: #E4405F;"></i>
                            <span>@crunchymocaf</span>
                        </a>
                    </li>
                </ul>
                <p style="color: #555; font-size: 0.95rem;">
                    Genteng, Banyuwangi<br>
                    Jawa Timur
                </p>
            </div>

            <!-- Jam Operasional -->
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-semibold mb-3" style="color: #222;">Jam Operasional</h6>
                <p style="color: #555; font-size: 0.95rem;">
                    Senin – Minggu<br>
                    07.00 – 20.00 WIB
                </p>
            </div>

        </div>

        <hr style="border-color: #d4d0c0; margin: 40px 0 20px;">

        <!-- Bottom Copyright -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start" style="color: #666; font-size: 0.9rem;">
                &copy; {{ date('Y') }} Fiava Donut Shop. All Rights Reserved.
            </div>
            <div class="col-md-6 text-center text-md-end" style="color: #666; font-size: 0.9rem;">
                Freshly made with passion every day
            </div>
        </div>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@yield('js')

<script>
// Real-time Search dengan rekomendasi saat klik
const searchInput = document.getElementById('searchInput');
const searchResult = document.getElementById('searchResult');
let timeout = null;

// Fungsi untuk fetch data
function fetchProducts(query = '') {
    let url = query ? `/search?q=${encodeURIComponent(query)}` : '/search?recommend=1';
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            searchResult.innerHTML = '';

            if (data.length === 0) {
                searchResult.innerHTML = `
                    <div class="no-result p-4 text-center">
                        <i class="fas fa-search fa-2x text-muted mb-3 d-block"></i>
                        <strong>Tidak ada produk yang ditemukan</strong><br>
                        <small class="text-muted">Coba kata kunci lain</small>
                    </div>`;
            } else {
                data.forEach(product => {
                    let image = product.image_url 
                        ? `/storage/${product.image_url}` 
                        : '/images/no-image.png';

                    let priceFormatted = new Intl.NumberFormat('id-ID').format(product.price);

                    searchResult.innerHTML += `
                        <a href="/product/${product.id}" class="search-item text-decoration-none">
                            <img src="${image}" alt="${product.name}">
                            <div class="flex-grow-1">
                                <strong style="font-size:15px;">${product.name}</strong><br>
                                <small class="text-success fw-medium">Rp ${priceFormatted}</small>
                            </div>
                        </a>`;
                });
            }

            searchResult.style.display = "block";
        })
        .catch(err => {
            console.error(err);
            searchResult.innerHTML = `<div class="no-result p-3">Terjadi kesalahan saat mencari.</div>`;
            searchResult.style.display = "block";
        });
}

// Event: Ketika user klik input (tampilkan rekomendasi)
searchInput.addEventListener('focus', function() {
    if (this.value.trim() === '') {
        clearTimeout(timeout);
        fetchProducts('');   // ambil rekomendasi / produk populer
    }
});

// Event: Saat mengetik (real-time search)
searchInput.addEventListener('keyup', function() {
    clearTimeout(timeout);
    
    const query = this.value.trim();
    
    if (query.length < 1) {
        // Jika kosong, tampilkan rekomendasi lagi
        timeout = setTimeout(() => fetchProducts(''), 150);
        return;
    }

    // Debounce 300ms agar tidak terlalu sering request
    timeout = setTimeout(() => {
        fetchProducts(query);
    }, 300);
});

// Tutup hasil jika klik di luar search box
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResult.contains(e.target)) {
        searchResult.style.display = "none";
    }
});

// Style tambahan untuk hasil search yang lebih modern
const style = document.createElement('style');
style.innerHTML = `
    .search-item {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f1f1;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #333;
        transition: background 0.2s;
    }
    .search-item:hover {
        background: #fdf8f0;
    }
    .search-item img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 8px;
    }
    .no-result {
        padding: 30px 20px;
        text-align: center;
        color: #888;
    }
`;
document.head.appendChild(style);
</script>
</body>
</html>