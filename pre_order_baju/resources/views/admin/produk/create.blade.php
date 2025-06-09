<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h3>Tambah Produk</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Produk</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" required>{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga') }}" required>
        </div>

        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select name="kategori" id="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Shirt">Shirt</option>
                <option value="Blouse">Blouse</option>
                <option value="Tunic">Tunic</option>
                <option value="Outerwear">Outerwear</option>
                <option value="Dress">Dress</option>
                <option value="Skirt">Skirt</option>
                <option value="Pants">Pants</option>
                <option value="One Set">One Set</option>
                <option value="Prayer Set">Prayer Set</option>
                <option value="Hijab">Muslim Shirt</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Upload Gambar</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
        </div>

        <h5>Ukuran & Stok</h5>
        <div class="mb-3">
            <label>Stok untuk S</label>
            <input type="number" name="stock[S]" class="form-control" value="{{ old('stock.S') }}">
        </div>
        <div class="mb-3">
            <label>Stok untuk M</label>
            <input type="number" name="stock[M]" class="form-control" value="{{ old('stock.M') }}">
        </div>
        <div class="mb-3">
            <label>Stok untuk L</label>
            <input type="number" name="stock[L]" class="form-control" value="{{ old('stock.L') }}">
        </div>
        <div class="mb-3">
            <label>Stok untuk XL</label>
            <input type="number" name="stock[XL]" class="form-control" value="{{ old('stock.XL') }}">
        </div>
        <div class="mb-3">
            <label>Stok All Size</label>
            <input type="number" name="stock[ALL]" class="form-control" value="{{ old('stock.ALL') }}">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>