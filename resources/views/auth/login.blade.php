@extends('layouts.app')

@section('title','Login')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">

  <div class="col-md-5">
    <div class="card border-0 shadow-lg p-4" style="border-radius: 16px;">

      <div class="text-center mb-4">
        <h3 class="fw-bold">Selamat Datang</h3>
        <p class="text-muted">Masuk untuk melanjutkan pesanan favoritmu</p>
      </div>

      <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label small text-muted">Email</label>
          <input type="email" name="email"
            class="form-control rounded-pill px-3 py-2"
            placeholder="Masukkan email kamu"
            value="{{ old('email') }}" required>
          @error('email')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label small text-muted">Password</label>
          <input type="password" name="password"
            class="form-control rounded-pill px-3 py-2"
            placeholder="Masukkan password"
            required>
          @error('password')
            <small class="text-danger">{{ $message }}</small>
          @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label small" for="remember">Ingat saya</label>
          </div>
        </div>

        <button type="submit"
          class="btn w-100 py-2 fw-semibold"
          style="background:#c19a6b; color:white; border-radius:50px;">
          Login
        </button>

      </form>

      <div class="text-center mt-4">
        <small class="text-muted">
          Belum punya akun?
          <a href="{{ route('register.show') }}" style="color:#c19a6b; text-decoration:none;">
            Daftar sekarang
          </a>
        </small>
      </div>

    </div>
  </div>

</div>
@endsection