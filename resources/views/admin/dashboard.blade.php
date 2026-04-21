@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('css')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #ebe8dd, #f5f2e9);
        border-radius: 16px;
        padding: 1.8rem 2rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 16px;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(193, 154, 107, 0.15);
    }

    .stats-value {
        font-size: 2.1rem;
        font-weight: 700;
        margin: 0.5rem 0 0.2rem;
    }

    .stats-label {
        font-size: 0.95rem;
        color: #666;
        margin-bottom: 0;
    }

    .order-table-card {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .status-badge {
        padding: 0.45em 0.85em;
        font-weight: 600;
        border-radius: 8px;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <!-- Header dengan Logo -->
    <div class="dashboard-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('storage/logo.png') }}" alt="Fiava Logo" style="height: 65px;">
            <div>
                <h1 class="fw-bold mb-0" style="color: #222; font-size: 28px;">Selamat Datang, Admin</h1>
                <p class="text-muted mb-0">Fiava Donut Shop — Dashboard Manajemen</p>
            </div>
        </div>
        <div class="text-end">
            <small class="text-muted">Hari ini: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</small>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-box fa-2x mb-3" style="color: #c19a6b;"></i>
                    <p class="stats-label">Total Produk</p>
                    <p class="stats-value text-dark">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-bag fa-2x mb-3" style="color: #0dcaf0;"></i>
                    <p class="stats-label">Total Pesanan</p>
                    <p class="stats-value text-dark">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x mb-3" style="color: #ffc107;"></i>
                    <p class="stats-label">Pesanan Pending</p>
                    <p class="stats-value text-warning">
                        {{ \App\Models\Order::where('status', 'pending')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-3" style="color: #198754;"></i>
                    <p class="stats-label">Pesanan Selesai</p>
                    <p class="stats-value text-success">
                        {{ \App\Models\Order::where('status', 'completed')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Pendapatan -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="card stats-card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-wallet fa-3x" style="color: #c19a6b;"></i>
                        <div>
                            <p class="text-muted mb-1">Total Pendapatan Bulan Ini</p>
                            <h2 class="fw-bold text-success mb-0">
                                Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                            </h2>
                            <small class="text-success">↑ Naik dari bulan lalu</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="order-table-card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Pesanan Terbaru</h5>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-dark">
                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th class="text-end">Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestOrders as $o)
                            <tr>
                                <td><strong>#{{ $o->id }}</strong></td>
                                <td>{{ $o->user->name ?? 'Guest' }}</td>
                                <td>{{ $o->product->name ?? 'Multiple Items' }}</td>
                                <td class="text-end fw-medium">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'pending'    => ['label' => 'Pending', 'class' => 'warning'],
                                            'processing' => ['label' => 'Diproses', 'class' => 'info'],
                                            'shipped'    => ['label' => 'Dikirim', 'class' => 'primary'],
                                            'completed'  => ['label' => 'Selesai', 'class' => 'success'],
                                            'cancelled'  => ['label' => 'Dibatalkan', 'class' => 'danger'],
                                        ];
                                        $status = $statusMap[$o->status] ?? ['label' => ucfirst($o->status), 'class' => 'secondary'];
                                    @endphp
                                    <span class="badge bg-{{ $status['class'] }} status-badge">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $o) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Belum ada pesanan terbaru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection