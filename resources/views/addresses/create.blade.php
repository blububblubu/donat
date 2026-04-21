@extends('layouts.app')

@section('title', 'Tambah Alamat')

@section('css')
<style>
    :root {
        --primary: #c19a6b;
    }
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        padding: 1.8rem;
        margin-bottom: 1.5rem;
    }
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .form-control {
        border-radius: 8px;
        padding: 0.6rem;
        border: 1px solid #ddd;
    }
    .btn-modern {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.7rem 1.4rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 1rem;
    }
    .btn-modern:hover {
        opacity: 0.9;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="form-card">
        <h2 class="mb-4 fw-bold">Tambah Alamat Baru</h2>

        <form action="{{ route('addresses.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Alamat (contoh: Rumah, Kantor)</label>
                <input type="text" name="label" class="form-control" value="{{ old('label') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kota</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="zip" class="form-control" value="{{ old('zip') }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-modern">Simpan Alamat</button>
                <a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection