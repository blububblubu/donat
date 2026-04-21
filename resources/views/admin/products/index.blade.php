@extends('layouts.admin')

@section('title', 'Produk')

@section('css')
<style>
    .product-table-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .product-table-card .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #eaeaea;
        padding: 1rem 1.25rem;
    }

    .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .btn-action {
        padding: 0.25rem 0.6rem;
        font-size: 0.85rem;
        border-radius: 6px;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .product-img {
            width: 50px;
            height: 50px;
        }

        .btn-action {
            padding: 0.2rem 0.5rem;
            font-size: 0.8rem;
        }

        .table-responsive {
            font-size: 0.95rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold" style="color: #222;">Daftar Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Produk
        </a>
    </div>

    <div class="product-table-card">
        <div class="card-header">
            <h5 class="mb-0">Semua Produk ({{ $products->total() }})</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Foto</th>
                        <th>Nama Produk</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Stok</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td>
                                @if($p->image_url)
                                    <img src="{{ asset('storage/' . $p->image_url) }}" 
                                         alt="{{ $p->name }}" 
                                         class="product-img">
                                @else
                                    <div class="bg-light border d-flex align-items-center justify-content-center" 
                                         style="width:60px; height:60px; border-radius:8px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">{{ $p->name }}</div>
                                @if(strlen($p->description) > 50)
                                    <small class="text-muted d-block mt-1">
                                        {{ Str::limit($p->description, 50) }}
                                    </small>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-success">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($p->stock <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($p->stock <= 5)
                                    <span class="badge bg-warning text-dark">Sisa {{ $p->stock }}</span>
                                @else
                                    <span class="badge bg-success">Tersedia</span>
                                @endif
                            </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Hapus produk \"{{ $p->name }}\"?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-box-open me-2"></i>Belum ada produk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection