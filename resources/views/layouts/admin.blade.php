<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>@yield('title','Admin Panel')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="icon" type="image/png" href="{{ asset('storage/ahay.png') }}">


<style>

:root{
--primary:#c19a6b;
--dark:#2b1d13;
--light:#f8f5f1;
}

body{
background:var(--light);
font-family:'Segoe UI',sans-serif;
}


/* SIDEBAR */

.admin-sidebar{
width:250px;
min-height:100vh;
background:var(--dark);
position:fixed;
top:0;
left:0;
display:flex;
flex-direction:column;
background: #ebe8dd;
}


/* BRAND */

.admin-brand{
display:flex;
align-items:center;
gap:12px;
padding:20px;
border-bottom:1px solid rgba(255,255,255,0.1);
}

.admin-brand img{
height:45px;
}

.admin-brand span{
color:white;
font-weight:600;
font-size:18px;
letter-spacing:0.5px;
}


/* MENU */

.admin-menu{
flex:1;
margin-top:10px;
}

.admin-menu a{
color:#222;
display:block;
padding:12px 20px;
text-decoration:none;
font-size:15px;
transition:0.2s;
}

.admin-menu a:hover,
.admin-menu a.active{
background:var(--primary);
color:white;
}


/* LOGOUT */

.admin-logout{
padding:20px;
border-top:1px solid rgba(255,255,255,0.1);
}

.admin-logout button{
background:#dc3545;
border:none;
}


/* TOPBAR */

.admin-topbar{
margin-left:250px;
height:60px;
background:white;
display:flex;
align-items:center;
justify-content:space-between;
padding:0 25px;
box-shadow:0 2px 8px rgba(0,0,0,0.05);
position:sticky;
top:0;
z-index:100;
}

.admin-topbar .title{
font-size:20px;
font-weight:600;
color:#333;
}


/* CONTENT */

.admin-content{
margin-left:250px;
padding:30px;
}

</style>

@yield('css')

</head>

<body>


{{-- SIDEBAR --}}
<div class="admin-sidebar">

<div class="admin-brand">

<img src="{{ asset('storage/logo.png') }}">

<!-- <span>Fiava Admin</span> -->

</div>


<div class="admin-menu">

<a href="{{ route('admin.dashboard') }}"
class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
Dashboard
</a>

<a href="{{ route('admin.products.index') }}"
class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
Produk
</a>

<a href="{{ route('admin.orders.index') }}"
class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
Pesanan
</a>

<a href="{{ route('home') }}" target="_blank">
Lihat Website
</a>

</div>


<div class="admin-logout">

<form action="{{ route('logout') }}" method="POST">
@csrf
<button class="btn btn-danger w-100">
Logout
</button>
</form>

</div>

</div>



{{-- TOPBAR --}}
<div class="admin-topbar">

<div class="title">
@yield('title','Admin Panel')
</div>

<div>
<small class="text-muted">
{{ auth()->user()->name ?? 'Admin' }}
</small>
</div>

</div>



{{-- CONTENT --}}
<div class="admin-content">

@yield('content')

</div>


</body>
</html>