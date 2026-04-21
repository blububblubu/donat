@extends('layouts.admin')


@section('title','Detail Pesanan')

@section('content')
<h1>Detail Pesanan #{{ $order->id }}</h1>
<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">Produk: {{ $order->product->name }}</h5>
    <p>Harga: Rp {{ number_format($order->product->price,0,',','.') }}</p>
    <p>Jumlah: {{ $order->quantity }}</p>
    <p>Total: Rp {{ number_format($order->total,0,',','.') }}</p>
    <p>Status: {{ $order->status }}</p>
    <hr>
    <h5>Data Pembeli</h5>
    <p>Nama: {{ $order->user->name }}</p>
    <p>Email: {{ $order->user->email }}</p>
    <p>Alamat: {{ $order->address }}</p>
    <p>Telepon: {{ $order->phone }}</p>
  </div>
</div>
<a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Kembali</a>
@endsection
