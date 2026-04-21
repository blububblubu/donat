@extends('layouts.admin')

@section('title','Tambah Produk')

@section('content')

<style>
.page-title {
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 25px;
    color: #222;
}

.product-form-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    max-width: 900px;
}

.form-label {
    font-weight: 600;
    margin-bottom: 6px;
    color: #333;
}

.form-control {
    border-radius: 8px;
    padding: 10px 14px;
    border: 1px solid #ddd;
}

.form-control:focus {
    border-color: #c19a6b;
    box-shadow: 0 0 0 3px rgba(193, 154, 107, 0.15);
}

.image-upload {
    border: 2px dashed #ccc;
    border-radius: 10px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fafafa;
}

.image-upload:hover {
    border-color: #c19a6b;
    background: #fff;
}

.image-upload.dragover {
    border-color: #c19a6b;
    background: #fdf8f0;
}

.preview-image {
    margin-top: 15px;
    max-width: 220px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.btn-save {
    background: #c19a6b;
    border: none;
    padding: 12px 28px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
}

.btn-save:hover {
    background: #b38a5c;
    transform: translateY(-1px);
}
</style>

<div class="page-title">Tambah Produk</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

    <div class="product-form-card">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" 
                       name="name" 
                       class="form-control" 
                       placeholder="Contoh: CHOCOLATE (100 gr)"
                       required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" 
                       name="price" 
                       class="form-control" 
                       placeholder="45000"
                       min="0"
                       required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" 
                       name="stock" 
                       class="form-control" 
                       placeholder="100"
                       min="0"
                       required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Deskripsi Produk <span class="text-danger">*</span></label>
            <textarea name="description" 
                      rows="5" 
                      class="form-control" 
                      placeholder="Tuliskan deskripsi produk secara lengkap..."
                      required></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label">Foto Produk <span class="text-danger">*</span></label>
            
            <div id="dropZone" class="image-upload">
                <p class="mb-2 text-muted">Seret dan lepas gambar di sini</p>
                <p class="mb-3 text-muted">atau</p>
                <button type="button" class="btn btn-outline-secondary btn-sm" 
                        onclick="document.getElementById('image').click()">
                    Pilih dari Komputer
                </button>
                
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*" 
                       hidden 
                       required>
                
                <div id="previewContainer" class="text-center mt-3">
                    <img id="preview" class="preview-image" style="display: none;">
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save">
            Simpan Produk
        </button>

    </div>
</form>

<script>
// Preview Image + Drag and Drop
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('image');
const preview = document.getElementById('preview');

function previewImage(file) {
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        }
        reader.readAsDataURL(file);
    }
}

// Click to upload
fileInput.addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        previewImage(e.target.files[0]);
    }
});

// Drag and Drop
dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    dropZone.classList.add('dragover');
});

dropZone.addEventListener('dragleave', function() {
    dropZone.classList.remove('dragover');
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        fileInput.files = e.dataTransfer.files;  // Assign file ke input
        previewImage(file);
    } else {
        alert('Mohon upload file gambar saja (jpg, png, webp)');
    }
});

// Form validation sebelum submit
document.getElementById('productForm').addEventListener('submit', function(e) {
    const name = document.querySelector('input[name="name"]').value.trim();
    const price = document.querySelector('input[name="price"]').value;
    const stock = document.querySelector('input[name="stock"]').value;
    const description = document.querySelector('textarea[name="description"]').value.trim();
    const image = document.querySelector('input[name="image"]').files.length;

    if (!name || !price || !stock || !description || image === 0) {
        e.preventDefault();
        alert('Mohon isi semua field yang wajib (*)');
    }
});
</script>

@endsection