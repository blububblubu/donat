@extends('layouts.admin')

@section('title', 'Data Pesanan')

@section('css')
<style>
    .orders-header {
        margin-bottom: 2.5rem;
    }

    .orders-card {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .table thead th {
        background: #222;
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 1.25rem 1.1rem;
        letter-spacing: 0.6px;
        vertical-align: middle;
    }

    .table td {
        padding: 1.25rem 1.1rem;
        vertical-align: middle;
        color: #333;
    }

    .order-id {
        font-family: monospace;
        background: #f8f9fa;
        color: #c19a6b;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 700;
    }

    .status-badge {
        padding: 9px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.92rem;
    }

    .btn-detail {
        border-radius: 50px;
        padding: 8px 20px;
        font-size: 0.95rem;
    }

    .status-select {
        border-radius: 50px;
        padding: 8px 14px;
        border: 2px solid #e9e9e9;
        font-size: 0.95rem;
    }

    .status-select:focus {
        border-color: #c19a6b;
        box-shadow: 0 0 0 3px rgba(193, 154, 107, 0.15);
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="orders-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="fw-bold mb-1" style="color: #222; font-size: 28px;">Data Pesanan</h1>
            <p class="text-muted mb-0">Kelola semua pesanan pelanggan Fiava Donut Shop</p>
        </div>
        
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-4 py-2">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabel Pesanan -->
    <div class="card orders-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th width="110">ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th class="text-end">Total Harga</th>
                            <th width="160">Status Pesanan</th>
                            <th width="230" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <span class="order-id">#{{ $order->id }}</span>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $order->user->name ?? 'Guest' }}</div>
                                <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                            </td>
                            <td class="fw-medium">
                                {{ $order->product->name ?? 'Multiple Items' }}
                            </td>
                            <td class="text-end fw-bold text-dark">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'pending'    => 'bg-secondary',
                                        'processing' => 'bg-warning text-dark',
                                        'shipped'    => 'bg-info',
                                        'completed'  => 'bg-success',
                                        'cancelled'  => 'bg-danger',
                                        default      => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} status-badge">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="btn btn-sm btn-primary btn-detail me-2">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>

                                <form action="{{ route('admin.orders.updateStatus', $order) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <select name="status" 
                                            class="status-select form-select form-select-sm"
                                            onchange="this.form.submit()">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block opacity-75"></i>
                                <p class="mb-0">Belum ada pesanan saat ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->


</div>
@endsection