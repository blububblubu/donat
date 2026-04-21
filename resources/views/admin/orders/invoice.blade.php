@extends('layouts.admin')

@section('title','Invoice #'.$order->id)

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold mb-0">Invoice #{{ $order->id }}</h4>

        <span class="badge {{ $order->is_paid ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">
            {{ $order->is_paid ? 'Sudah Dibayar' : 'Belum Dibayar' }}
        </span>
    </div>

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-7">

            {{-- DATA PEMBELI --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Data Pembeli</h6>

                    <div class="mb-2">
                        <div class="text-muted small">Nama</div>
                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                    </div>

                    <div class="mb-2">
                        <div class="text-muted small">Alamat</div>
                        <div>{{ $order->address }}</div>
                    </div>

                    <div>
                        <div class="text-muted small">Telepon</div>
                        <div>{{ $order->phone }}</div>
                    </div>
                </div>
            </div>

            {{-- DATA PRODUK --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Detail Produk</h6>

                    <div class="row mb-2">
                        <div class="col-6 text-muted">Produk</div>
                        <div class="col-6 text-end fw-semibold">
                            {{ $order->product->name }}
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

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-5">

            {{-- PEMBAYARAN --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-3">Pembayaran</h6>

                    <div class="mb-3">
                        <div class="text-muted small">Metode</div>
                        <div class="fw-semibold">
                            {{ strtoupper(str_replace('_',' ',$order->payment_method)) }}
                        </div>
                    </div>

                    <div>
                        <div class="text-muted small">Status</div>
                        <div>
                            @if($order->is_paid)
                                <span class="badge bg-success">Sudah Dibayar</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Dibayar</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- ACTION --}}
            @if(!$order->is_paid && $order->payment_method !== 'cod')
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">

                    <form action="{{ route('orders.pay', $order) }}" method="POST">
                        @csrf
                        <button class="btn btn-success w-100">
                            Bayar Sekarang
                        </button>
                    </form>

                </div>
            </div>
            @endif

        </div>

    </div>

</div>

@endsection