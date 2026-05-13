@extends('layouts.app')

@section('title', 'Edit Alamat')

@section('css')
<style>
    :root {
        --primary: #c19a6b;
    }

    .address-box {
        background: white;
        border-radius: 14px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-control {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #ddd;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(193,154,107,0.2);
    }

    .btn-primary-modern {
        background: var(--primary);
        border: none;
        color: white;
        padding: 0.7rem 1.4rem;
        border-radius: 10px;
        font-weight: 600;
    }

    .btn-primary-modern:hover {
        opacity: 0.9;
    }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h1 class="h3 fw-bold">Edit Alamat</h1>
        <p class="text-muted">Perbarui alamat pengiriman kamu.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="address-box">
        <form action="{{ route('addresses.update', $address) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Label Alamat</label>
                <input type="text"
                       name="label"
                       class="form-control"
                       value="{{ old('label', $address->label) }}"
                       placeholder="Rumah / Kantor"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="address"
                          class="form-control"
                          rows="4"
                          required>{{ old('address', $address->address) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kota</label>
                    <input type="text"
                           name="city"
                           class="form-control"
                           value="{{ old('city', $address->city) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode Pos</label>
                    <input type="text"
                           name="zip"
                           class="form-control"
                           value="{{ old('zip', $address->zip) }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Nomor HP</label>
                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ old('phone', $address->phone) }}"
                       required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-modern">
                    Simpan Perubahan
                </button>

                <a href="{{ route('addresses.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection