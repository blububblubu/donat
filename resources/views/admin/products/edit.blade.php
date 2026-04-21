@extends('layouts.admin')

@section('title','Edit Produk')

@section('content')
<h1>Edit Produk</h1>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="mb-3">
  <label>Nama</label>
  <input type="text" name="name" class="form-control" value="{{ $product->name }}">
</div>

<div class="mb-3">
  <label>Deskripsi</label>
  <textarea name="description" class="form-control">{{ $product->description }}</textarea>
</div>

<div class="mb-3">
  <label>Harga</label>
  <input type="number" name="price" class="form-control" value="{{ $product->price }}">
</div>

<div class="mb-3">
  <label>Stok</label>
  <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">
</div>

<div class="mb-3">
  <label>Foto Sekarang</label><br>
  <img src="{{ asset('storage/'.$product->image_url) }}" width="120">
</div>

<div class="mb-3">
  <label>Ganti Foto</label>
  <input type="file" name="image" class="form-control">
</div>

<button class="btn btn-primary">Update</button>
</form>
@endsection
