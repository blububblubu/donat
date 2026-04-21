@extends('layouts.app')

@section('title','Checkout')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">Checkout</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('cart.processCheckout') }}" method="POST">
        @csrf
        {{-- Daftar Keranjang --}}
        <h4>Keranjang Anda</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($cartItems as $item)
                    @php $subtotal = $item->product->price * $item->quantity; @endphp
                    @php $grandTotal += $subtotal; @endphp
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>Rp {{ number_format($item->product->price,0,',','.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($subtotal,0,',','.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total</td>
                    <td class="fw-bold">Rp {{ number_format($grandTotal,0,',','.') }}</td>
                </tr>
            </tbody>
        </table>

        {{-- 2️⃣ Pilih Alamat --}}
        <h4 class="mt-4">Pilih Alamat</h4>

        @if($addresses->isNotEmpty())
            <div class="mb-3">
                <label for="address_id" class="form-label">Alamat Tersimpan</label>
                <select name="address_id" id="address_id" class="form-select">
                    <option value="">-- Pilih alamat tersimpan --</option>
                    @foreach($addresses as $addr)
                        <option value="{{ $addr->id }}">{{ $addr->label }} - {{ $addr->address }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <h5>Atau Tambah Alamat Baru</h5>
        <div class="mb-3">
            <label for="customer_name" class="form-label">Nama Penerima</label>
            <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
        </div>
        <div class="mb-3">
            <label for="new_address" class="form-label">Alamat Lengkap</label>
            <textarea name="new_address" id="new_address" class="form-control" rows="3">{{ old('new_address') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">No. HP</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>

        {{-- 3️⃣ Pilih Metode Pembayaran --}}
        <h4 class="mt-4">Metode Pembayaran</h4>
        <div class="mb-3">
            <select name="payment_method" class="form-select" required>
                <option value="">-- Pilih metode pembayaran --</option>
                <option value="cod">COD (Bayar di tempat)</option>
                <option value="bank_transfer">Transfer Bank</option>
                <option value="e_wallet">E-Wallet</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success btn-lg">Checkout Sekarang</button>
    </form>
</div>
@endsection
