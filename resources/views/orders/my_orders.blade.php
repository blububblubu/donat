@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Pesanan Saya</h2>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="alert alert-info text-center py-5 rounded-4">
            <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
            <h5>Belum ada pesanan</h5>
            <p class="text-muted">Kamu belum melakukan pembelian apapun.</p>
            <a href="{{ route('home') }}" class="btn btn-primary px-4">Mulai Belanja</a>
        </div>
    @else
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="80">ID</th>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Status</th>
                            <th width="150">Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="fw-medium">#{{ $order->id }}</td>
                            <td><strong>{{ $order->product->name }}</strong></td>
                            <td class="text-center">{{ $order->quantity }}x</td>
                            <td class="text-end fw-semibold">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @php
                                    $statusClass = match($order->status) {
                                        'pending' => 'warning',
                                        'paid' => 'info',
                                        'completed', 'selesai' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }} px-3 py-2 fs-6">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $order->created_at->format('d M Y') }}<br>
                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                            </td>
                            <td class="text-center">
                                <!-- Tombol Detail -->
                                <a href="{{ route('orders.invoice', $order->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-invoice"></i> Detail
                                </a>

                                @if(in_array($order->status, ['completed', 'selesai']))
                                    @php
                                        $userReview = $order->product->reviews()
                                            ->where('user_id', auth()->id())
                                            ->first();
                                    @endphp

                                    @if(!$userReview)
                                        <!-- Belum Ulas -->
                                        <a href="{{ route('product.show', $order->product->id) }}" 
                                           class="btn btn-sm btn-success ms-2">
                                            <i class="fas fa-star"></i> Beri Ulasan
                                        </a>
                                    @else
                                        <!-- Sudah Ulas - Bisa Lihat / Edit -->
                                        <a href="{{ route('product.show', $order->product->id) }}" 
                                           class="btn btn-sm btn-primary ms-2">
                                            <i class="fas fa-eye"></i> Lihat Ulasan Saya
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection