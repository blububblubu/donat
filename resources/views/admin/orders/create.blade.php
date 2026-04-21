@extends('layouts.admin')


@section('title','Buat Pesanan Baru')

@section('content')
<h1>Buat Pesanan Baru</h1>

<form action="{{ route('admin.orders.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Pilih User</label>
        <select name="user_id" class="form-select">
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
        @error('user_id') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Pilih Produk</label>
        <select name="product_id" class="form-select">
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} - Rp {{ number_format($product->price,0,',','.') }}</option>
            @endforeach
        </select>
        @error('product_id') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" name="quantity" class="form-control" value="1" min="1">
        @error('quantity') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Nama Customer</label>
        <input type="text" name="customer_name" class="form-control">
        @error('customer_name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="address" class="form-control"></textarea>
        @error('address') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Telepon</label>
        <input type="text" name="phone" class="form-control">
        @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Metode Pembayaran</label>
        <select name="payment_method" class="form-select">
            <option value="cod">COD</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="e_wallet">E-Wallet</option>
        </select>
        @error('payment_method') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Buat Pesanan</button>
</form>
@endsection
