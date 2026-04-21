@extends('layouts.app')

@section('title','Register')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">

  <div class="col-md-5">
    <div class="card border-0 shadow-lg p-4" style="border-radius: 16px;">

      <div class="text-center mb-4">
        <h3 class="fw-bold">Buat Akun Baru</h3>
        <p class="text-muted">Daftar untuk mulai pesanan  favoritmu</p>
      </div>

      <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label small text-muted">Nama</label>
          <input type="text" name="name"
            class="form-control rounded-pill px-3 py-2"
            placeholder="Nama lengkap kamu"
            value="{{ old('name') }}" required>
          @error('name')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label small text-muted">Email</label>
          <input type="email" name="email"
            class="form-control rounded-pill px-3 py-2"
            placeholder="Masukkan email"
            value="{{ old('email') }}" required>
          @error('email')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label small text-muted">Password</label>
          <input type="password" name="password"
            class="form-control rounded-pill px-3 py-2"
            placeholder="Minimal 6 karakter"
            required>
          @error('password')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-4">
          <label class="form-label small text-muted">Konfirmasi Password</label>
          <input type="password" name="password_confirmation"
            class="form-control rounded-pill px-3 py-2"
            placeholder="Ulangi password"
            required>
        </div>

        <button type="submit"
          class="btn w-100 py-2 fw-semibold"
          style="background:#c19a6b; color:white; border-radius:50px;">
          Daftar Sekarang
        </button>

      </form>

      <div class="text-center mt-4">
        <small class="text-muted">
          Sudah punya akun?
          <a href="{{ route('login.show') }}" style="color:#c19a6b; text-decoration:none;">
            Login di sini
          </a>
        </small>
      </div>

    </div>
  </div>

</div>
@endsection