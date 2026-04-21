@extends('layouts.app')

@section('title','Invoice Pesanan')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold mb-0">Invoice #{{ $order->id }}</h4>
        <span class="badge bg-info text-dark px-3 py-2">
            {{ $order->status }}
        </span>
    </div>

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- DATA PRODUK --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Data Produk</h6>

                    <div class="row mb-2">
                        <div class="col-6 text-muted">Nama Produk</div>
                        <div class="col-6 text-end fw-semibold">
                            {{ $order->product->name }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-6 text-muted">Harga</div>
                        <div class="col-6 text-end">
                            Rp {{ number_format($order->product->price,0,',','.') }}
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-6 text-muted">Jumlah</div>
                        <div class="col-6 text-end">
                            {{ $order->quantity }}
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-6 fw-semibold">Total</div>
                        <div class="col-6 text-end fw-bold">
                            Rp {{ number_format($order->total,0,',','.') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- DATA PEMBELI --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Data Pembeli</h6>

                    <p class="mb-1"><span class="text-muted">Nama</span><br>{{ $order->customer_name }}</p>
                    <p class="mb-1"><span class="text-muted">Alamat</span><br>{{ $order->address }}</p>
                    <p class="mb-0"><span class="text-muted">Telepon</span><br>{{ $order->phone }}</p>
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- PEMBAYARAN --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Pembayaran</h6>

                    <p class="mb-2">
                        <span class="text-muted">Metode</span><br>
                        <span class="fw-semibold">{{ strtoupper($order->payment_method) }}</span>
                    </p>

                    <p class="mb-0">
                        <span class="text-muted">Status</span><br>

                        @if($order->is_paid)
                            <span class="badge bg-success">Sudah Dibayar</span>
                        @elseif($order->payment_proof)
                            <span class="badge bg-info text-dark">
                                Menunggu Verifikasi
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                Belum Dibayar
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- QRIS --}}
            @if($order->payment_method == 'qris' && !$order->payment_proof)
            <div class="card border-0 shadow-sm mb-4 text-center">
                <div class="card-body">

                    <h6 class="text-uppercase text-muted mb-3">QRIS</h6>

                    <img src="{{ asset('storage/qris.png') }}" class="img-fluid rounded mb-3" style="max-width:200px;">

                    <p class="small text-muted">
                        Upload bukti pembayaran setelah transfer
                    </p>

                    <form action="{{ route('orders.uploadProof',$order) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="payment_proof" class="form-control mb-2" accept="image/*" required>

                        <button class="btn btn-primary w-100">
                            Upload Bukti
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- BUKTI --}}
            @if($order->payment_proof)
            <div class="card border-0 shadow-sm mb-4 text-center">
                <div class="card-body">

                    <h6 class="text-uppercase text-muted mb-3">Bukti Pembayaran</h6>

                    <img src="{{ asset('storage/'.$order->payment_proof) }}" class="img-fluid rounded mb-2">

                    <p class="small text-warning mb-0">
                        Menunggu verifikasi admin
                    </p>

                </div>
            </div>
            @endif

            <a href="{{ route('orders.my') }}" class="btn btn-secondary w-100">
                Kembali ke Pesanan
            </a>

        </div>

    </div>

</div>

@endsection