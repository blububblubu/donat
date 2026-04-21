@extends('layouts.admin')


@section('title','Beri Ulasan')

@section('content')
<h1>Beri Ulasan untuk {{ $product->name }}</h1>

<form action="{{ route('reviews.store', $product) }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Rating (1-5)</label>
        <input type="number" name="rating" class="form-control" value="5" min="1" max="5">
        @error('rating') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label>Komentar</label>
        <textarea name="comment" class="form-control">{{ old('comment') }}</textarea>
        @error('comment') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
    <button class="btn btn-primary">Kirim Ulasan</button>
</form>
@endsection
