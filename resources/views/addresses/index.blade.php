@extends('layouts.app')

@section('title', 'Alamat Saya')

@section('css')
<style>
    :root {
        --primary: #c19a6b;
    }
    .address-card {
        background: white;
        border-radius: 12px;
        padding: 1.2rem;
        margin-bottom: 1.2rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }
    .address-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.8rem;
    }
    .address-label {
        font-weight: 600;
        font-size: 1.1rem;
        color: #222;
    }
    .address-text {
        color: #555;
        line-height: 1.6;
    }
    .btn-sm-modern {
        padding: 0.25rem 0.6rem;
        font-size: 0.85rem;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold">Alamat Pengiriman</h1>
        <a href="{{ route('addresses.create') }}" class="btn" style="background:var(--primary); color:white;">
            <i class="fas fa-plus me-1"></i> Tambah Alamat
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($addresses->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-map-marker-alt" style="font-size:3rem; margin-bottom:1rem; color:#ccc;"></i>
            <h5>Belum ada alamat</h5>
            <p class="mt-2">Tambahkan alamat pengiriman pertama kamu.</p>
        </div>
    @else
        @foreach($addresses as $address)
            <div class="address-card">
                <div class="address-header">
                    <div>
                        <div class="address-label">{{ $address->label }}</div>
                        <div class="text-muted">{{ $address->phone }}</div>
                    </div>
                    <div>
                        <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-outline-warning btn-sm-modern me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-sm-modern">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="address-text">
                    {{ $address->address }}<br>
                    @if($address->city){{ $address->city }}, @endif
                    @if($address->zip){{ $address->zip }}@endif
                </div>
            </div>
        @endforeach
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">
        ← Kembali
    </a>
</div>
@endsection