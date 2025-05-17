<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h3>Edit Produk</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Produk</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $produk->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" required>{{ old('deskripsi', $produk->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga', $produk->harga) }}" required>
        </div>

        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select name="kategori" id="kategori" class="form-control" required>
                @php $kategoriList = ['Shirt', 'Blouse', 'Tunic', 'Outerwear', 'Dress', 'Skirt', 'Pants', 'One Set', 'Prayer Set', 'Muslim Shirt']; @endphp
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoriList as $kategori)
                    <option value="{{ $kategori }}" {{ old('kategori', $produk->kategori) == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Upload Gambar Baru (Opsional)</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
            @if ($produk->gambar)
                <small class="text-muted">Gambar sekarang: <a href="{{ asset('storage/' . $produk->gambar) }}" target="_blank">Lihat</a></small>
            @endif
        </div>

        @php $stok = json_decode($produk->stock, true) ?? []; @endphp

        <h5>Ukuran & Stok</h5>
        @foreach (['S', 'M', 'L', 'XL', 'ALL'] as $ukuran)
            <div class="mb-3">
                <label>Stok untuk {{ $ukuran }}</label>
                <input type="number" name="stock[{{ $ukuran }}]" class="form-control" value="{{ old("stock.$ukuran", $stok[$ukuran] ?? '') }}">
            </div>
        @endforeach

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>